<?php
namespace Ather\OrderLogger\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class OrderSaveAfter implements ObserverInterface
{
    protected $_logger;
    protected $_scopeConfig;
    protected $_directoryList;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->_logger = $logger;
        $this->_scopeConfig = $scopeConfig;
        $this->_directoryList = $directoryList;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $logSubtotal = $this->_scopeConfig->getValue('order_logging/logging_settings/log_subtotal');
        $logTotal = $this->_scopeConfig->getValue('order_logging/logging_settings/log_total');
        $logOrderId = $this->_scopeConfig->getValue('order_logging/logging_settings/log_order_id');
        $logFileName = $this->_scopeConfig->getValue('order_logging/logging_settings/log_file_name');

        if ($order->isObjectNew()) {
            $logData = ['order_id' => $order->getId()];
            if ($logSubtotal) {
                $subtotal = $order->getSubtotal();
                $logData['subtotal'] = $subtotal;
            }
            if ($logTotal) {
                $total = $order->getGrandTotal();
                $logData['total'] = $total;
            }
            if ($logOrderId) {
                $logData['order_id'] = $order->getId();
            }
            $logFile = $this->_directoryList->getPath('log') . '/' . $logFileName . '.log';
            $this->_logger->info('Order Placed', $logData, $logFile);
        }
    }
}
