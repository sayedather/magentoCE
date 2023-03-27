<?php
namespace Ather\Bpay\Model;

use Magento\Payment\Model\Method\AbstractMethod;

class PaymentMethod extends AbstractMethod
{
    /**
     * @var string
     */
    protected $_code = 'ather_bpay';

    /**
     * @var bool
     */
    protected $_isOffline = true;

    /**
     * @param \Magento\Framework\DataObject|mixed $data
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function assignData($data)
    {
        parent::assignData($data);

        // Get the QR code value from the submitted form data
        $qrCodeValue = $data->getData('additional_data')['qr_code_value'];

        // Set the QR code value in the payment object
        $this->getInfoInstance()->setAdditionalInformation('qr_code_value', $qrCodeValue);

        return $this;
    }
}
