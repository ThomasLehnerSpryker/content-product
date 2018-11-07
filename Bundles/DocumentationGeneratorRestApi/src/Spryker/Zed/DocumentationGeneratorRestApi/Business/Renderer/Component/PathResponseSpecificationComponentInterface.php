<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\OpenApiSpecificationPathResponseComponentTransfer;

interface PathResponseSpecificationComponentInterface extends SpecificationComponentInterface
{
    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathResponseComponentTransfer $pathResponseComponentTransfer
     *
     * @return void
     */
    public function setPathResponseComponentTransfer(OpenApiSpecificationPathResponseComponentTransfer $pathResponseComponentTransfer): void;
}
