<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager;

/**
 * @method \Spryker\Service\FileManager\FileManagerServiceFactory getFactory()
 */
interface FileManagerServiceInterface
{
    /**
     * @api
     *
     * @param string $filePath
     *
     * @return string
     */
    public function save(string $filePath);

    /**
     * @api
     *
     * @param string $contentId
     *
     * @return mixed
     */
    public function read(string $contentId);

    /**
     * @api
     *
     * @param string $contentId
     *
     * @return bool
     */
    public function delete(string $contentId);
}
