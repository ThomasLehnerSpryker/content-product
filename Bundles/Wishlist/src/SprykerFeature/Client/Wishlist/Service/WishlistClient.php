<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
class WishlistClient extends AbstractClient implements WishlistClientInterface
{

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return WishlistTransfer
     */
    public function addItem(ItemTransfer $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->addItem($wishlistChange);
        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return WishlistTransfer
     */
    public function increaseItemQuantity(ItemTransfer $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->increaseQuantity($wishlistChange);

        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return WishlistTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->descreaseQuantity($wishlistChange);

        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return WishlistTransfer
     */
    public function removeItem(ItemTransfer $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->removeItem($wishlistChange);
        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @return WishlistTransfer
     */
    public function getWishlist()
    {
        $wishlistItems = $this->getSession()->getWishlist();
        $this->getStorage()->expandProductDetails($wishlistItems);

        return $wishlistItems;
    }

    /**
     * @return WishlistTransfer
     */
    public function synchronizeSession()
    {
        $wishlistItems = $this->getSession()->getWishlist();

        $wishlistChange = new WishlistChangeTransfer();
        $customerTransfer = $this->getCustomerTransfer();
        $wishlistChange->setCustomer($customerTransfer);

        foreach ($wishlistItems->getItems() as $item) {
            $wishlistChange->addItem($item);
        }

        $wishlist = $this->getZedStub()->addItem($wishlistChange);
        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemTransfer $wishlistItemTransfer
     *
     * @return WishlistChangeTransfer
     */
    protected function createChangeTransfer(ItemTransfer $wishlistItemTransfer)
    {
        $wishlistTransfer = $this->getSession()->getWishlist();

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistChange->setWishlist($wishlistTransfer);
        $wishlistChange->addItem($wishlistItemTransfer);
        $customerTransfer = $this->getCustomerTransfer();

        if ($customerTransfer !== null) {
            $wishlistChange->setCustomer($customerTransfer);
        }

        return $wishlistChange;
    }

    /**
     * @return CustomerTransfer
     */
    protected function getCustomerTransfer()
    {
        $customerClient = $this->getDependencyContainer()->createCustomerClient();
        $customerTransfer = $customerClient->getCustomer();

        return $customerTransfer;
    }

    /**
     * @return Session\WishlistSessionInterface
     */
    protected function getSession()
    {
        return $this->getDependencyContainer()->createSession();
    }

    /**
     * @return Zed\WishlistStubInterface
     */
    protected function getZedStub()
    {
        return $this->getDependencyContainer()->createZedStub();
    }

    /**
     * @return Storage\WishlistStorageInterface
     */
    protected function getStorage()
    {
        return $this->getDependencyContainer()->createStorage();
    }

}
