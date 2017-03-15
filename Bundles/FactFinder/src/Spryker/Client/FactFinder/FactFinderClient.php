<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

use Generated\Shared\Transfer\FactFinderRecommendationRequestTransfer;
use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;
use Generated\Shared\Transfer\FactFinderSuggestRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Zed\Collector\CollectorConfig;

/**
 * @method \Spryker\Client\FactFinder\FactFinderFactory getFactory()
 */
class FactFinderClient extends AbstractClient implements FactFinderClientInterface
{

    /**
     * @api
     *
     * @param string $locale
     * @param string $number
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getProductCsv($locale, $number = '')
    {
        return $this
            ->getFactory()
            ->createZedFactFinderStub()
            ->getExportedCsv($locale, CollectorConfig::COLLECTOR_TYPE_PRODUCT_ABSTRACT, $number);
    }

    /**
     * @api
     *
     * @param string $locale
     * @param string $number
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getCategoryCsv($locale, $number = '')
    {
        return $this
            ->getFactory()
            ->createZedFactFinderStub()
            ->getExportedCsv($locale, CollectorConfig::COLLECTOR_TYPE_CATEGORYNODE, $number);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FactFinderSearchRequestTransfer $factFinderSearchRequestTransfer
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function search(FactFinderSearchRequestTransfer $factFinderSearchRequestTransfer)
    {
        $quoteTransfer = $this->getQuote();
        $quoteTransfer->setFactFinderSearchRequest($factFinderSearchRequestTransfer);

        $ffSearchResponseTransfer = $this
            ->getFactory()
            ->createSearchRequest()
            ->request($quoteTransfer);

        return $ffSearchResponseTransfer;
    }

    /**
     * @param FactFinderSuggestRequestTransfer $factFinderSuggestRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSuggestResponseTransfer
     */
    public function getSuggestions(FactFinderSuggestRequestTransfer $factFinderSuggestRequestTransfer)
    {
        $quoteTransfer = $this->getQuote();
        $quoteTransfer->setFactFinderSuggestRequest($factFinderSuggestRequestTransfer);

        $ffSuggestResponseTransfer = $this
            ->getFactory()
            ->createSuggestRequest()
            ->request($quoteTransfer);

        return $ffSuggestResponseTransfer;
    }

    /**
     * @param FactFinderRecommendationRequestTransfer $factFinderRecommendationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderRecommendationResponseTransfer
     */
    public function getRecommendations(FactFinderRecommendationRequestTransfer $factFinderRecommendationRequestTransfer)
    {
        $quoteTransfer = $this->getQuote();
        $quoteTransfer->setFactFinderRecommendationRequest($factFinderRecommendationRequestTransfer);

        $factFinderRecommendationResponseTransfer = $this
            ->getFactory()
            ->createRecommendationsRequest()
            ->request($quoteTransfer);

        return $factFinderRecommendationResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuote()
    {
        return $this->getSession()->getQuote();
    }

    /**
     * @return \Spryker\Client\Cart\Session\QuoteSessionInterface
     */
    protected function getSession()
    {
        return $this->getFactory()->createSession();
    }

}
