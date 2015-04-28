<?php

namespace SprykerFeature\Zed\Calculation\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CalculationBusiness;
use SprykerFeature\Zed\Calculation\Business\Model\StackExecutor;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ExpensePriceToPayCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ItemPriceToPayCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\OptionPriceToPayCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\RemoveAllExpensesCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalWithoutItemExpensesTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\TaxTotalsCalculator;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;

/**
 * @method CalculationBusiness getFactory()
 */
class CalculationDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @var CalculationSettings
     */
    protected $settings;

    /**
     * @return StackExecutor
     */
    public function getStackExecutor()
    {
        return $this->getFactory()->createModelStackExecutor($this->getLocator());
    }

    /**
     * @return ExpensePriceToPayCalculator
     */
    public function getExpensePriceToPayCalculator()
    {
        return $this->getFactory()->createModelCalculatorExpensePriceToPayCalculator($this->getLocator());
    }

    /**
     * @return ExpenseTotalsCalculator
     */
    public function getExpenseTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorExpenseTotalsCalculator($this->getLocator());
    }

    /**
     * @return GrandTotalTotalsCalculator
     */
    public function getGrandTotalsCalculator()
    {
        $subtotalTotalsCalculator = $this->getFactory()->createModelCalculatorSubtotalTotalsCalculator($this->getLocator());
        $expenseTotalsCalculator = $this->getFactory()->createModelCalculatorExpenseTotalsCalculator($this->getLocator());

        $grandTotalsCalculator = $this->getFactory()
            ->createModelCalculatorGrandTotalTotalsCalculator(
                $this->getLocator(),
                $subtotalTotalsCalculator,
                $expenseTotalsCalculator
            );

        return $grandTotalsCalculator;
    }

    /**
     * @return ItemPriceToPayCalculator
     */
    public function getItemPriceToPayCalculator()
    {
        return $this->getFactory()->createModelCalculatorItemPriceToPayCalculator($this->getLocator());
    }

    /**
     * @return OptionPriceToPayCalculator
     */
    public function getOptionPriceToPayCalculator()
    {
        return $this->getFactory()->createModelCalculatorOptionPriceToPayCalculator($this->getLocator());
    }

    /**
     * @return RemoveAllExpensesCalculator
     */
    public function getRemoveAllExpensesCalculator()
    {
        return $this->getFactory()->createModelCalculatorRemoveAllExpensesCalculator($this->getLocator());
    }

    /**
     * @return RemoveTotalsCalculator
     */
    public function getRemoveTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorRemoveTotalsCalculator($this->getLocator());
    }

    /**
     * @return SubtotalTotalsCalculator
     */
    public function getSubtotalTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorSubtotalTotalsCalculator($this->getLocator());
    }

    /**
     * @return SubtotalWithoutItemExpensesTotalsCalculator
     */
    public function getSubtotalWithoutItemExpensesTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorSubtotalWithoutItemExpensesTotalsCalculator($this->getLocator());
    }

    /**
     * @return TaxTotalsCalculator
     */
    public function getTaxTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorTaxTotalsCalculator(
            $this->getLocator(),
            $this->getFactory()->createModelPriceCalculationHelper()
        );
    }
}
