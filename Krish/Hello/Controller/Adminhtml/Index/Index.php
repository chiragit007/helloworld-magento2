<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Krish\Hello\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
   

    protected $resultPageFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
 
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Krish_Hello::hello');
        $resultPage->addBreadcrumb(__('KrishINC'), __('KrishINC'));
        $resultPage->addBreadcrumb(__('Manage Questions'), __('Manage Questions'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Questions and Answers'));
 
        return $resultPage;
    }
}
