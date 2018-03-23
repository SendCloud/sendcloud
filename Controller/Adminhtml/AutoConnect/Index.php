<?php
namespace CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect;

use Magento\Framework\Controller\ResultFactory;
use \Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 *
 * @package CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect
 */
class Index extends \Magento\Backend\App\Action
{
    private $resultPageFactory;
    private $autoGenerateApiUser;

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param PageFactory                         $resultPageFactory
     * @param AutoGenerateApiUser                 $autoGenerateApiUser
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        PageFactory $resultPageFactory,
        AutoGenerateApiUser $autoGenerateApiUser
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->autoGenerateApiUser = $autoGenerateApiUser;
        parent::__construct($context);

    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($this->autoGenerateApiUser->getApiUser()) {
            $apiUser = $this->autoGenerateApiUser->getApiUser();
        } else {
            $apiUser = $this->autoGenerateApiUser->createApiUser();
        }
        
        $url = $this->generateUrl($apiUser);

        $responseData = array(
            "url" => $url
        );

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($responseData);

        return $resultJson;
    }

    /**
     * Base url ophalen in plaats van hardcoded
     * @param $apiUser
     *
     * @return string
     */
    private function generateUrl($apiUser)
    {
        $url = sprintf(
            '%s/shops/magento_v2/connect/?shop_url=%s&username=%s&password=%s',
            "https://panel.sendcloud.sc",
            urlencode("http://192.168.70.70/"),
            $apiUser->getUserName(),
            $apiUser->getPassword()
        );

        return $url;
    }
}
