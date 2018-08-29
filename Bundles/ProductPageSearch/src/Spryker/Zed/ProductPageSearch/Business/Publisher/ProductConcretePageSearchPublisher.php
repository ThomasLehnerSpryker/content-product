<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Publisher;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductPageSearch\Business\Exception\ProductConcretePageSearchNotFoundException;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface;
use Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapperInterface;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface;

class ProductConcretePageSearchPublisher implements ProductConcretePageSearchPublisherInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface
     */
    protected $mapper;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @var \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface[]
     */
    protected $pageDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface $productPageSearchRepository
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface $productPageSearchEntityManager
     * @param \Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapperInterface $productPageSearchMapper
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface $utilEncoding
     * @param array $pageDataExpanderPlugins
     */
    public function __construct(
        ProductPageSearchRepositoryInterface $productPageSearchRepository,
        ProductPageSearchEntityManagerInterface $productPageSearchEntityManager,
        ProductPageSearchMapperInterface $productPageSearchMapper,
        ProductPageSearchToUtilEncodingInterface $utilEncoding,
        array $pageDataExpanderPlugins
    ) {
        $this->repository = $productPageSearchRepository;
        $this->entityManager = $productPageSearchEntityManager;
        $this->mapper = $productPageSearchMapper;
        $this->pageDataExpanderPlugins = $pageDataExpanderPlugins;
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param array $ids
     *
     * @return void
     */
    public function publish(array $ids): void
    {
        $productConcreteTransfers = $this->repository->findConcreteProductsByIds($ids);
        $productConcretePageSearchTransfers = $this->repository->findProductConcretePageSearchByProductConcreteIds($ids);

        $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteTransfers, $productConcretePageSearchTransfers) {
            $this->executePublishTransaction($productConcreteTransfers, $productConcretePageSearchTransfers);
        });
    }

    /**
     * @param int[] $ids
     *
     * @return void
     */
    public function unpublish(array $ids): void
    {
        $productConcretePageSearchTransfers = $this->repository->findProductConcretePageSearchByProductConcreteIds($ids);

        $this->getTransactionHandler()->handleTransaction(function () use ($productConcretePageSearchTransfers) {
            $this->executeUnpublishTransaction($productConcretePageSearchTransfers);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $productConcretePageSearchTransfers
     *
     * @return void
     */
    protected function executePublishTransaction(array $productConcreteTransfers, array $productConcretePageSearchTransfers): void
    {
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            foreach ($productConcreteTransfer->getStores() as $storeTransfer) {
                $this->syncProductConcretePageSearchPerStore(
                    $productConcreteTransfer,
                    $storeTransfer,
                    $productConcretePageSearchTransfers[$productConcreteTransfer->getIdProductConcrete()][$storeTransfer->getName()] ?? []
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $productConcretePageSearchTransfers
     *
     * @return void
     */
    protected function executeUnpublishTransaction(array $productConcretePageSearchTransfers): void
    {
        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $localizedProductConcretePageSearchTransfers
     *
     * @return void
     */
    protected function syncProductConcretePageSearchPerStore(
        ProductConcreteTransfer $productConcreteTransfer,
        StoreTransfer $storeTransfer,
        array $localizedProductConcretePageSearchTransfers
    ): void {
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $productConcretePageSearchTransfer = $localizedProductConcretePageSearchTransfers[$localizedAttribute->getLocale()->getLocaleName()] ?? new ProductConcretePageSearchTransfer();

            if ($productConcreteTransfer->getIsActive() !== true && $productConcretePageSearchTransfer->getIdProductConcretePageSearch() !== null) {
                $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
            }

            $productConcretePageSearchTransfer = $this->mapper->mapProductConcreteTransferToProductConcretePageSearchTransfer(
                $productConcreteTransfer,
                $productConcretePageSearchTransfer,
                $storeTransfer,
                $localizedAttribute
            );

            $productConcretePageSearchTransfer->setData(
                $this->mapper->mapToSearchData($productConcretePageSearchTransfer)
            );

            $productConcretePageSearchTransfer->setStructuredData(
                $this->utilEncoding->encodeJson($productConcretePageSearchTransfer->toArray())
            );

            $this->expandProductConcretePageSearchTransferWithPlugins($productConcreteTransfer, $productConcretePageSearchTransfer);

            $this->entityManager->saveProductConcretePageSearch($productConcretePageSearchTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @throws \Spryker\Zed\ProductPageSearch\Business\Exception\ProductConcretePageSearchNotFoundException
     *
     * @return void
     */
    protected function deleteProductConcretePageSearch(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): void
    {
        if ($this->entityManager->deleteProductConcretePageSearch($productConcretePageSearchTransfer) === false) {
            throw new ProductConcretePageSearchNotFoundException(sprintf('Target storage entry for product with id %s not found', $productConcretePageSearchTransfer->getIdProduct()));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return void
     */
    protected function expandProductConcretePageSearchTransferWithPlugins(ProductConcreteTransfer $productConcreteTransfer, ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): void
    {
        foreach ($this->pageDataExpanderPlugins as $plugin) {
            $plugin->expand($productConcreteTransfer, $productConcretePageSearchTransfer);
        }
    }
}
