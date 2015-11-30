<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Transfer\InstalledPackagesTransfer;

interface InstalledPackageCollectorInterface
{

    /**
     * @return InstalledPackagesTransfer
     */
    public function getInstalledPackages();

}
