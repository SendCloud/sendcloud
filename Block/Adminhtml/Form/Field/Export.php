<?php
namespace SendCloud\SendCloud\Block\Adminhtml\Form\Field;

use \Magento\Backend\Block\Widget\Button;
use \Magento\Backend\Model\UrlInterface;
use \Magento\Framework\Data\Form\Element\AbstractElement;
use \Magento\Framework\Data\Form\Element\Factory;
use \Magento\Framework\Data\Form\Element\CollectionFactory;
use \Magento\Framework\Escaper;

class Export extends AbstractElement
{
    /**
     * @var UrlInterface
     */
    protected $_backendUrl;

    /**
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param UrlInterface $backendUrl
     * @param array $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        UrlInterface $backendUrl,
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->_backendUrl = $backendUrl;
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        /** @var Button $buttonBlock  */
        $buttonBlock = $this->getForm()->getParent()->getLayout()->createBlock(Button::class);

        $params = ['website' => $buttonBlock->getRequest()->getParam('website')];

        $url = $this->_backendUrl->getUrl("sendcloud_exportrates/exportrates/exportrates", $params);
        $data = [
            'label' => __('Export SendCloud CSV'),
            'onclick' => "setLocation('" .
            $url .
            "conditionName/' + $('carriers_sendcloud_condition_name').value + '/sendcloud_servicepointrates.csv' )",
            'class' => '',
        ];

        return $buttonBlock->setData($data)->toHtml();
    }
}
