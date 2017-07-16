<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Controller\Adminhtml\Connection;

use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\App\Action\Context;
use Faonni\Bitcoin\Controller\Adminhtml\Connection as ConnectionAbstract;
use Faonni\Bitcoin\Model\Transport;

/**
 * Bitcoin test connection controller
 */
class Test extends ConnectionAbstract
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magento_Config::config_system';

    /**
     * Result json factory instance 
     * 
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultFactory;
    
    /**
     * Transport instance
     *
     * @var \Faonni\Bitcoin\Model\Transport
     */
    protected $_transport;    

    /**
     * Initialize Controller
     * 
     * @param Context $context
     * @param JsonFactory $resultFactory
     * @param Transport $transport
     */
    public function __construct(
        Context $context,       
        JsonFactory $resultFactory,
        Transport $transport
    ) {    
        $this->_resultFactory = $resultFactory;
        $this->_transport = $transport;
        
        parent::__construct(
			$context
		);
    }
   
    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */     
    public function execute()
    {
		$result = ['valid' => 0, 'message' => __('Connection Failed')];
		
		$this->_transport->setConfig(
			$this->getRequest()->getParams(),
			true
		);
		
		try {
			$info = $this->_transport->getInfo();
			if ($info && !empty($info->version)) {
				$result = [
					'valid' => 1, 
					'message' => __('Connection Successful')
				];
			}	
		} catch (\Exception $e) {
			$result['message'] = $e->getMessage();
		}	

        $resultJson = $this->_resultFactory->create();			
        return $resultJson->setData($result);
    }
} 
