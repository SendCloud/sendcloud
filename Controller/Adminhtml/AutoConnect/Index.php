<?php
namespace CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use \Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Math\Random;

/**
 * Class Index
 *
 * @package CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect
 */
class Index extends Action
{
    /** @var PageFactory  */
    private $resultPageFactory;

    /** @var AutoGenerateApiUser  */
    private $autoGenerateApiUser;

    /** @var Random  */
    private $mathRandom;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param AutoGenerateApiUser $autoGenerateApiUser
     * @param Random $mathRandom
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        AutoGenerateApiUser $autoGenerateApiUser,
        Random $mathRandom
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->autoGenerateApiUser = $autoGenerateApiUser;
        $this->mathRandom = $mathRandom;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $password = $this->generatePassword();
        } catch (\Exception $ex) {
            $this->logger->debug($ex->getMessage());
        }

        $apiUserInfo = $this->autoGenerateApiUser->getApiUser($password);

        if (!isset($apiUserInfo['userId'])) {
            $apiUserInfo = $this->autoGenerateApiUser->createApiUser($password);
        }

        $url = $this->generateUrl($apiUserInfo);

        $responseData = array(
            "url" => $url
        );

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($responseData);

        return $resultJson;
    }

    /**
     * Base url ophalen in plaats van hardcoded
     * @param $apiUserInfo
     *
     * @return string
     */
    private function generateUrl($apiUserInfo)
    {
        $baseUrl = $this->_backendUrl->getBaseUrl();

        $url = sprintf(
            '%s/shops/magento_v2/connect/?shop_url=%s&username=%s&password=%s',
            "https://panel.sendcloud.sc",
            urlencode($baseUrl),
            $apiUserInfo['username'],
            $apiUserInfo['password']
        );

        return $url;
    }

    /**
     * Generate random password
     *
     * @param int $length
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function generatePassword($length = 10)
    {
        $chars = Random::CHARS_LOWERS . Random::CHARS_UPPERS . Random::CHARS_DIGITS;

        $password = $this->mathRandom->getRandomString($length, $chars);

        return $password;
    }
}
