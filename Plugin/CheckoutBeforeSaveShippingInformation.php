<?php

namespace SendCloud\SendCloud\Plugin;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\PaymentDetails;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
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
     * @throws NoSuchEntityException
     */
    public function afterSaveAddressInformation(
        ShippingInformationManagement $subject,
        PaymentDetails $paymentDetails,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $extensionAttributes = $addressInformation->getExtensionAttributes();
        if ($extensionAttributes === null) {
            return $paymentDetails;
        }

        $sendCloudCheckoutData = $extensionAttributes->getSendcloudCheckoutData();
        if (!$this->isCheckoutDataValid($sendCloudCheckoutData)) {
            return $paymentDetails;
        }

        $quote = $this->quoteRepository->getActive($cartId);
        $sendCloudCheckoutData = $this->removeAccessTokenFromCheckoutData($extensionAttributes->getSendcloudCheckoutData());
        $quote->setSendcloudCheckoutData($sendCloudCheckoutData);
        $this->quoteRepository->save($quote);

        return $paymentDetails;
    }

    /**
     * Unset access_token and api_key in order to skip saving it into the database
     *
     * @param string $jsonData
     * @return string
     */
    private function removeAccessTokenFromCheckoutData(string $jsonData): string
    {
        $data = json_decode($jsonData, true);
        unset($data['access_token']);
        if (array_key_exists('service_point_data', $data)) {
            unset($data['service_point_data']['api_key']);
        }

        return json_encode($data);
    }

    /**
     * Checks whether checkout data is in valid json format.
     *
     * @param $jsonData
     *
     * @return bool
     */
    private function isCheckoutDataValid($jsonData): bool
    {
        return !($jsonData === null || $jsonData === "NULL" || json_decode($jsonData, true) === null);
    }
}
