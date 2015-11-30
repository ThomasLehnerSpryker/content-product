<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Dependency;

use Generated\Shared\Transfer\ChangeTransfer;

interface ItemExpanderPluginInterface
{

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change);

}
