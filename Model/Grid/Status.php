<?php

namespace SendCloud\SendCloud\Model\Grid;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class RecoveryEmailOptions
 *
 * @package CleverReach\CleverReachIntegration\Model\AbandonedCartRecords
 */
class Status implements OptionSourceInterface
{

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 1,
                'label' => __('Yes'),
            ],
            [
                'value' => 0,
                'label' => __('No'),
            ]
        ];
    }
}
