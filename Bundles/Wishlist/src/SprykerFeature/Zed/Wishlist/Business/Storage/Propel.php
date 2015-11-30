<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Storage;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use SprykerFeature\Zed\Wishlist\Business\Model\Customer;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainerInterface;

class Propel implements StorageInterface
{

    /**
     * @var WishlistQueryContainerInterface
     */
    protected $wishlistQueryContainer;

    /**
     * @var CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var ProductFacade
     */
    protected $facadeProduct;

    /**
     * @param WishlistQueryContainerInterface $wishlistQueryContainer
     * @param Customer $customer
     * @param WishlistTransfer $wishlistTransfer
     * @param CustomerTransfer $customerTransfer
     * @param ProductFacade $facadeProduct
     */
    public function __construct(
        WishlistQueryContainerInterface $wishlistQueryContainer,
        Customer $customer,
        WishlistTransfer $wishlistTransfer,
        CustomerTransfer $customerTransfer,
        ProductFacade $facadeProduct
    ) {
        $this->wishlistQueryContainer = $wishlistQueryContainer;
        $this->customerTransfer = $customerTransfer;
        $this->wishlistTransfer = $wishlistTransfer;
        $this->customer = $customer;
        $this->facadeProduct = $facadeProduct;
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistChangeTransfer
     */
    public function addItems(WishlistChangeTransfer $wishlistChange)
    {
        $idCustomer = $this->customerTransfer->getIdCustomer();
        $wishlistEntity = $this->getWishlistEntity($idCustomer);

        if (empty($wishlistEntity)) {
            $wishlistEntity = $this->createWishlistEntity($idCustomer);
        }

        foreach ($wishlistChange->getItems() as $wishlistItemTransfer) {
            $wishlistItemEntity = $this->getWishlistItemEntity($wishlistItemTransfer, $wishlistEntity->getIdWishlist());

            if (empty($wishlistItemEntity)) {
                $concreteProductTransfer = $this->facadeProduct->getConcreteProduct($wishlistItemTransfer->getSku());

                $this->createNewWishlistItem(
                    $wishlistItemTransfer,
                    $wishlistEntity->getIdWishlist(),
                    $concreteProductTransfer
                );
            } else {
                $this->updateWishlistItem($wishlistItemEntity, $wishlistItemTransfer);
            }
        }

        $wishlistTransfer = $this->customer->getWishlist();

        return $wishlistTransfer;
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function removeItems(WishlistChangeTransfer $wishlistChange)
    {
        $idCustomer = $this->customerTransfer->getIdCustomer();
        $wishlistEntity = $this->getWishlistEntity($idCustomer);

        $wishlistItems = $wishlistChange->getItems();
        foreach ($wishlistItems as $wishlistItemTransfer) {
            $wishlistItemEntity = $this->getWishlistItemEntity($wishlistItemTransfer, $wishlistEntity->getIdWishlist());

            if (empty($wishlistItemEntity)) {
                continue;
            }

            $quantityDifference = $wishlistItemEntity->getQuantity() - $wishlistItemTransfer->getQuantity();
            if ($quantityDifference <= 0) {
                $this->deleteWishlistItem($wishlistItemEntity);
            } else {
                $wishlistItemEntity->setQuantity($quantityDifference);
                $wishlistItemEntity->save();
            }
        }

        $wishlistTransfer = $this->customer->getWishlist();

        return $wishlistTransfer;
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function increaseItems(WishlistChangeTransfer $wishlistChange)
    {
        return $this->addItems($wishlistChange);
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function decreaseItems(WishlistChangeTransfer $wishlistChange)
    {
        return $this->removeItems($wishlistChange);
    }

    /**
     * @param ItemTransfer $wishlistItemTransfer
     * @param int $idWishlist
     * @param ConcreteProductTransfer $concreteProductTransfer
     *
     * @return SpyWishlistItem
     */
    protected function createNewWishlistItem(
        ItemTransfer $wishlistItemTransfer,
        $idWishlist,
        ConcreteProductTransfer $concreteProductTransfer
    ) {
        $wishlistItemEntity = new SpyWishlistItem();
        $wishlistItemEntity->setGroupKey($wishlistItemTransfer->getGroupKey());
        $wishlistItemEntity->setFkProduct($concreteProductTransfer->getIdConcreteProduct());
        $wishlistItemEntity->setFkAbstractProduct($concreteProductTransfer->getIdAbstractProduct());
        $wishlistItemEntity->setFkWishlist($idWishlist);
        $wishlistItemEntity->setQuantity($wishlistItemTransfer->getQuantity());
        $wishlistItemEntity->setAddedAt(new \DateTime());
        $wishlistItemEntity->save();

        return $wishlistItemEntity;
    }

    /**
     * @param ItemTransfer $wishlistItemTransfer
     * @param int $idWishlist
     *
     * @return null|SpyWishlistItem
     */
    protected function getWishlistItemEntity(ItemTransfer $wishlistItemTransfer, $idWishlist)
    {
        $wishlistItemEntity = null;
        if (!empty($wishlistItemTransfer->getGroupKey())) {
            $wishlistItemEntity = $this->wishlistQueryContainer
                ->queryCustomerWishlistByGroupKey($idWishlist, $wishlistItemTransfer->getGroupKey())
                ->findOne();
        }

        if (empty($wishlistItemEntity)) {
            $idConcreteProduct = $this->facadeProduct->getConcreteProductIdBySku($wishlistItemTransfer->getSku());
            $wishlistItemEntity = $this->wishlistQueryContainer
                ->queryCustomerWishlistByProductId($idWishlist, $idConcreteProduct)
                ->findOne();
        }

        return $wishlistItemEntity;
    }

    /**
     * @param int $idCustomer
     *
     * @return SpyWishlist
     */
    protected function createWishlistEntity($idCustomer)
    {
        $wishlistEntity = new SpyWishlist();
        $wishlistEntity->setFkCustomer($idCustomer);
        $wishlistEntity->save();

        return $wishlistEntity;
    }

    /**
     * @param int $idCustomer
     *
     * @return SpyWishlist
     */
    protected function getWishlistEntity($idCustomer)
    {
        $wishlistEntity = $this->wishlistQueryContainer
            ->queryWishlist()
            ->findOneByFkCustomer($idCustomer);

        return $wishlistEntity;
    }

    /**
     * @param SpyWishlistItem $wishlistItemEntity
     */
    protected function deleteWishlistItem(SpyWishlistItem $wishlistItemEntity)
    {
        $wishlistItemEntity->delete();
    }

    /**
     * @param SpyWishlistItem $wishlistItemEntity
     * @param ItemTransfer $wishlistItemTransfer
     */
    protected function updateWishlistItem(SpyWishlistItem $wishlistItemEntity, ItemTransfer $wishlistItemTransfer)
    {
        $wishlistItemEntity->setQuantity($wishlistItemEntity->getQuantity() + $wishlistItemTransfer->getQuantity());
        $wishlistItemEntity->save();
    }

}
