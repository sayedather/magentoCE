<?php
namespace Ather\Bpay\Plugin;

use Magento\Checkout\Block\Onepage\Success as CheckoutSuccessBlock;
use Magento\Framework\DataObject;

class AddQrCodeToOrderSuccessPage
{
    /**
     * @param CheckoutSuccessBlock $subject
     * @param DataObject $result
     * @return DataObject
     */
    public function afterToHtml(CheckoutSuccessBlock $subject, $result)
    {
        // Get the order object
        $order = $subject->getOrder();

        // Load the payment object
        $payment = $order->getPayment();

        // Check if the payment method is our custom offline payment method
        if ($payment->getMethod() === 'ather_bpay') {
            // Get the QR code value from the payment object
            $qrCodeValue = $payment->getAdditionalInformation('qr_code_value');

            // Create the QR code HTML
            $qrCodeHtml = '<div style="text-align:center;"><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qrCodeValue) . '" /></div>';

            // Add the QR code HTML to the result
            $result->setLastSuccessQuoteId($qrCodeHtml);
        }

        return $result;
    }
}
