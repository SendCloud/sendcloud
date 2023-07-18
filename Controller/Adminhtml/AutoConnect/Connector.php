<?php

namespace SendCloud\SendCloud\Controller\Adminhtml\AutoConnect;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Controller\ResultFactory;
use \Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Math\Random;
use Magento\Store\Model\ScopeInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;

/**
 * Class Index
 *
 */
class Connector extends Action
{
    /** @var PageFactory  */
    private $resultPageFactory;

    /** @var AutoGenerateApiUser  */
    private $autoGenerateApiUser;

    /** @var Random  */
    private $mathRandom;

    /** @var SendCloudLogger  */
    private $logger;

    /**
     * @var WriterInterface
     */
    private $writer;
    /**
     * @var ReinitableConfigInterface
     */
    private $scopeConfig;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param AutoGenerateApiUser $autoGenerateApiUser
     * @param Random $mathRandom
     * @param SendCloudLogger $logger
     * @param WriterInterface $writer
     */
    public function __construct(
        ReinitableConfigInterface $scopeConfig,
        Context $context,
        PageFactory $resultPageFactory,
        AutoGenerateApiUser $autoGenerateApiUser,
        Random $mathRandom,
        SendCloudLogger $logger,
        WriterInterface $writer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resultPageFactory = $resultPageFactory;
        $this->autoGenerateApiUser = $autoGenerateApiUser;
        $this->mathRandom = $mathRandom;
        $this->logger = $logger;
        $this->writer = $writer;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $password = $this->generatePassword();
        $store = null;
        if ($this->getRequest()->getParam('store')) {
            $store = $this->getRequest()->getParam('store');
        }
        $apiUserInfo = $this->autoGenerateApiUser->getApiUser($password, $store);

        if (!$apiUserInfo) {
            $apiUserInfo = $this->autoGenerateApiUser->createApiUser($password, $store);
        }

        $url = $this->generateUrl($apiUserInfo);

        $responseData = [
            "url" => $url
        ];

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($responseData);

        if (empty($this->getRequest()->getParam('store'))) {
            $this->writer->save('sendcloud/general/enable', 1);

        } else {
            $this->writer->save('sendcloud/general/enable', 1, ScopeInterface::SCOPE_STORES, (int)$this->getRequest()->getParam('store'));
        }

        $this->scopeConfig->reinit();

        return $resultJson;
    }

    /**
     * @param $apiUserInfo
     *
     * @return string
     */
    private function generateUrl($apiUserInfo)
    {
        $baseUrl = $this->_backendUrl->getBaseUrl();
        if (defined('SC_NGROK_URL')) {
            $baseUrl = SC_NGROK_URL;
        }

        $url = sprintf(
            '%s/shops/magento_v2/connect/?shop_url=%s&username=%s&password=%s',
            "https://panel.sendcloud.sc",
            urlencode($baseUrl),
            $apiUserInfo['username'],
            $apiUserInfo['password']
        );

        if (isset($apiUserInfo['store_view_id'])) {
            $url = $url . "&store_view_id={$apiUserInfo['store_view_id']}";
        }

        return $url;
    }

    /**
     * Generate random password
     *
     * @return string
     */
    private function generatePassword()
    {
        $length = 3;

        try {
            $chars = Random::CHARS_UPPERS;
            $firstPart = $this->mathRandom->getRandomString($length, $chars);
            $secondPart = str_shuffle(bin2hex(openssl_random_pseudo_bytes(4)));
            $password = $firstPart . $secondPart;

            return $password;
        } catch (\Exception $ex) {
            $this->logger->debug($ex->getMessage());
        }
    }
}
