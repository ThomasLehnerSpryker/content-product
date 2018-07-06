<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCatalogClientInterface;
use Spryker\Glue\SearchRestApi\Processor\Catalog\CatalogReader;
use Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapper;
use Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface;

class SearchRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCatalogClientInterface
     */
    protected function getCatalogClient(): SearchRestApiToCatalogClientInterface
    {
        return $this->getProvidedDependency(SearchRestApiDependencyProvider::CLIENT_CATALOG_CLIENT);
    }

    /**
     * @return \Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface
     */
    protected function createSearchResourceMapper(): SearchResourceMapperInterface
    {
        return new SearchResourceMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\SearchRestApi\Processor\Catalog\CatalogReader
     */
    public function createCatalogReader(): CatalogReader
    {
        return new CatalogReader(
            $this->getCatalogClient(),
            $this->getResourceBuilder(),
            $this->createSearchResourceMapper()
        );
    }
}
