<?php

namespace SendCloud\SendCloud\Block\Adminhtml\Form\Field;

use \Magento\Framework\Data\Form\Element\AbstractElement;

class Import extends AbstractElement
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setType('file');
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        $html = '';

        $html .= '<input id="time_condition_sendcloud" type="hidden" name="'
            . $this->getName() . '" value="' . time() . '" />';
        $html .= <<<EndHTML
        <script>
        require(['prototype'], function(){
        Event.observe($('carriers_sendcloud_sen_condition_name'), 'change', checkConditionName.bind(this));
        function checkConditionName(event)
        {
            var conditionNameElement = Event.element(event);
            if (conditionNameElement && conditionNameElement.id) {
                $('time_condition_sendcloud').value = '_' + conditionNameElement.value + '/' + Math.random();
            }
        }
        });
        </script>
EndHTML;

        $html .= parent::getElementHtml();

        return $html;
    }
}
