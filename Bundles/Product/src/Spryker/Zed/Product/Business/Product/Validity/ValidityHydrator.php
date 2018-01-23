<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Validity;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;

class ValidityHydrator implements ValidityHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function hydrateProduct(
        ProductConcreteTransfer $productTransfer,
        SpyProduct $productEntity
    ): ProductConcreteTransfer {
        if ($productEntity->getSpyProductValidities()) {
            /** @var \Orm\Zed\Product\Persistence\SpyProductValidity $validityEntity */
            $validityEntity = $productEntity->getSpyProductValidities()->getFirst();
            $productTransfer->setValidFrom($validityEntity->getValidFrom());
            $productTransfer->setValidTo($validityEntity->getValidTo());
        }

        return $productTransfer;
    }
}
