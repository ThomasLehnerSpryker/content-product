<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Model;

use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use Generated\Shared\Transfer\CalculableContainerTransfer;
use Generated\Shared\Transfer\CartTransfer;

class CalculableContainer implements CalculableInterface
{

    /**
     * @var CartTransfer
     */
    private $cart;

    /**
     * @param CartTransfer $cart
     */
    public function __construct(CartTransfer $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return CalculableContainerTransfer
     */
    public function getCalculableObject()
    {
        return $this->cart;
    }

}
