<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 12-4-18
 * Time: 9:30
 */

namespace CreativeICT\SendCloud\Plugin;


use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Framework\App\RequestInterface;
use Magento\Quote\Model\QuoteRepository;

class BeforeSaveShippingInformation
{
    private $request;
    private $quoteRepository;

    public function __construct(
        RequestInterface $request,
        QuoteRepository $quoteRepository
    )
    {
        $this->request = $request;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param ShippingInformationManagement $subject
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(ShippingInformationManagement $subject, $cartId, ShippingInformationInterface $addressInformation)
    {
        $extensionAttributes = $addressInformation->getExtensionAttributes();
        $spId = $extensionAttributes->getSendcloudServicePointId();
        $spName = $extensionAttributes->getSendCloudServicePointName();
        $spStreet = $extensionAttributes->getSendCloudServicePointStreet();
        $spHouseNumber = $extensionAttributes->getSendCloudServiceHouseNumber();
        $spZipCode = $extensionAttributes->getSendCloudServiceZipCode();
        $spCity = $extensionAttributes->getSendCloudServiceCity();
        $spCountry = $extensionAttributes->getSendCloudServiceCountry();

        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setSendcloudServicePointId($spId);
        $quote->setSendCloudServicePointName($spName);
        $quote->setSendCloudServicePointStreet($spStreet);
        $quote->setSendCloudServiceHouseNumber($spHouseNumber);
        $quote->setSendcloudServiceZipCode($spZipCode);
        $quote->setSendcloudServiceCity($spCity);
        $quote->setSendcloudServiceCountry($spCountry);
    }
}