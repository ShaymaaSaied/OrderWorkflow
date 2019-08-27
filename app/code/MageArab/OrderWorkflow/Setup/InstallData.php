<?php
/**
 * Created By Shaymaa at 18/06/19 12:00.
 */

/**
 * Created by PhpStorm.
 * User: Shaymaa
 * Date: 18/06/2019
 * Time: 12:00
 */

namespace MageArab\OrderWorkflow\Setup;

use Magento\Checkout\Exception;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Status;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\Status as StatusResource;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface{

    protected  $_statusFactory;
    protected  $_statusResourceFactory;
    public function __construct(
        StatusFactory $statusFactory,
        StatusResourceFactory $statusResourceFactory
    ) {
        $this->_statusFactory = $statusFactory;
        $this->_statusResourceFactory = $statusResourceFactory;
    }
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $states=[
            'payment_paid'=>[
                'label'=>'Paid',
                'status'=>'paid'
            ],
            'delivery_done'=>[
                'label'=>'Done',
                'status'=>'done'
            ]
        ];
        foreach ($states as $key=>$state){
            $this->addNewOrderStateAndStatus($key,$state);
        }

    }
    protected function addNewOrderStateAndStatus($key,$state)
    {
        /** @var StatusResource $statusResource */
        $statusResource = $this->_statusResourceFactory->create();
        /** @var Status $status */
        $status = $this->_statusFactory->create();
        $status->setData([
            'status' => $state['status'],
            'label' => $state['label'],
        ]);
        try {
            $statusResource->save($status);
        } catch (Exception $exception) {
            return;
        }
        $status->assignState($key, false, true);
    }

}