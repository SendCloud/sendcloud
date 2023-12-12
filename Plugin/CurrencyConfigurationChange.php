<?php

namespace SendCloud\SendCloud\Plugin;

use Magento\Config\Controller\Adminhtml\System\Config\Save;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManager;
use Magento\Backend\Model\View\Result\Redirect;

class CurrencyConfigurationChange
{
    /**
     * @var array
     */
    private $currencyMap;
    /** @var WriterInterface  */
    private $writer;
    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * CurrencyConfigurationChange constructor.
     * @param WriterInterface $writer
     * @param StoreManager $storeManager
     */
    public function __construct(WriterInterface $writer, StoreManager $storeManager)
    {
        $this->writer = $writer;
        $this->storeManager = $storeManager;
    }


    public function beforeExecute(Save $save)
    {
        $section = $save->getRequest()->getParam('section');

        if ($section !== 'currency' && $section !== 'catalog') {
            return;
        }

        $websites = $this->storeManager->getWebsites();
        $map = [];
        foreach ($websites as $website) {
            $map[$website->getId()] = $website->getBaseCurrency()->getCurrencyCode();
        }
        $this->currencyMap = $map;
    }

    public function afterExecute(Save $save, Redirect $result)
    {
        $section = $save->getRequest()->getParam('section');

        if ($section !== 'currency' && $section !== 'catalog') {
            return $result;
        }

        $websites = $this->storeManager->getWebsites();
        foreach ($websites as $website) {
            if (array_key_exists($website->getId(), $this->currencyMap) &&
                $this->currencyMap[$website->getId()] !== $website->getBaseCurrency()->getCurrencyCode()
            ) {
                foreach ($website->getStoreIds() as $storeId) {
                    $this->writer->save(
                        'carriers/sendcloudcheckout/active',
                        0,
                        ScopeInterface::SCOPE_STORE,
                        $storeId
                    );
                }
            }
        }
        return $result;
    }
}
