<?php

namespace SendCloud\SendCloud\Plugin;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\PaymentDetails;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Framework\App\RequestInterface;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Model\QuoteRepository;
use SendCloud\SendCloud\Helper\Checkout;
use SendCloud\SendCloud\Logger\SendCloudLogger;

class BeforeSaveShippingInformation
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
     * SendCloudLogger
     */
    private $sendcloudLogger;

    /**
     * BeforeSaveShippingInformation constructor.
     *
     * @param RequestInterface $request
     * @param QuoteRepository $quoteRepository
     * @param Checkout $helper
     * @param SendCloudLogger $sendCloudLogger
     */
    public function __construct(
        RequestInterface $request,
        QuoteRepository $quoteRepository,
        Checkout $helper,
        SendCloudLogger $sendCloudLogger
    ) {
        $this->request = $request;
        $this->quoteRepository = $quoteRepository;
        $this->helper = $helper;
        $this->sendcloudLogger = $sendCloudLogger;
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
        $this->sendcloudLogger->info(
            "SendCloud\SendCloud\Plugin\BeforeSaveShippingInformation::afterSaveAddressInformation(): payment details:" . json_encode($paymentDetails->toArray()) .
            ", cart id: " . $cartId
        );

        $extensionAttributes = $addressInformation->getExtensionAttributes();

        if (!empty($extensionAttributes) && $this->helper->checkIfModuleIsActive()) {
            $quote = $this->quoteRepository->getActive($cartId);

            $spId = $extensionAttributes->getSendcloudServicePointId();
            $spName = $extensionAttributes->getSendcloudServicePointName();
            $spStreet = $extensionAttributes->getSendcloudServicePointStreet();
            $spHouseNumber = $extensionAttributes->getSendcloudServicePointHouseNumber();
            $spZipCode = $extensionAttributes->getSendcloudServicePointZipCode();
            $spCity = $extensionAttributes->getSendcloudServicePointCity();
            $spCountry = $extensionAttributes->getSendcloudServicePointCountry();
            $spPostnumber = $extensionAttributes->getSendcloudServicePointPostnumber();

            $quote->setSendcloudServicePointId($spId);
            $quote->setSendcloudServicePointName($spName);
            $quote->setSendcloudServicePointStreet($spStreet);
            $quote->setSendcloudServicePointHouseNumber($spHouseNumber);
            $quote->setSendcloudServicePointZipCode($spZipCode);
            $quote->setSendcloudServicePointCity($spCity);
            $quote->setSendcloudServicePointCountry($spCountry);
            $quote->setSendcloudServicePointPostnumber($spPostnumber);

            $this->quoteRepository->save($quote);

            $this->sendcloudLogger->info(
                "SendCloud\SendCloud\Plugin\BeforeSaveShippingInformation::afterSaveAddressInformation(): quote: " .
                json_encode($quote->toArray())
            );
        }

        return $paymentDetails;
    }
}
