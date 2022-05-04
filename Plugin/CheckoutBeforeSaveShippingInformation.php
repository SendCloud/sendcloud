<?php

namespace SendCloud\SendCloud\Plugin;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\PaymentDetails;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Framework\App\RequestInterface;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Model\QuoteRepository;
use SendCloud\SendCloud\Helper\Checkout;

/**
 * Class BeforeSaveShippingInformation
 */
class CheckoutBeforeSaveShippingInformation
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var Checkout
     */
    private $helper;

    /**
     * BeforeSaveShippingInformation constructor.
     *
     * @param RequestInterface $request
     * @param QuoteRepository $quoteRepository
     * @param Checkout $helper
     */
    public function __construct(
        RequestInterface $request,
        QuoteRepository $quoteRepository,
        Checkout $helper
    ) {
        $this->request = $request;
        $this->quoteRepository = $quoteRepository;
        $this->helper = $helper;
    }

    /**
     * @param ShippingInformationManagement $subject
     * @param PaymentDetails $paymentDetails
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     * @return PaymentDetails
     */
    public function afterSaveAddressInformation(
        ShippingInformationManagement $subject,
        PaymentDetails $paymentDetails,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $extensionAttributes = $addressInformation->getExtensionAttributes();

        if (!empty($extensionAttributes) && $extensionAttributes->getSendcloudCheckoutData()) {
            $quote = $this->quoteRepository->getActive($cartId);
            $checkoutData = $extensionAttributes->getSendcloudCheckoutData();
            $quote->setSendcloudCheckoutData($checkoutData);
            $this->quoteRepository->save($quote);
        }

        return $paymentDetails;
    }
}
