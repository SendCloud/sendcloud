<?php

namespace SendCloud\SendCloud\Checkout\Services;

use Magento\Store\Model\StoreManagerInterface;
use SendCloud\SendCloud\CheckoutCore\Contracts\Services\CurrencyService as CurrencyServiceInterface;

/**
 * Class CurrencyService
 * @package SendCloud\SendCloud\Checkout\Services
 */
class CurrencyService implements CurrencyServiceInterface
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * CurrencyService constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultCurrencyCode()
    {
        $currentWebsite = $this->storeManager->getWebsite();
        
        return $currentWebsite->getBaseCurrency()->getCurrencyCode();
    }
}
