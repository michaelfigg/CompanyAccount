<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class Grid extends  \Magento\Backend\App\Action
{
    /**
     * Queue list Ajax action
     *
     * @return void
     */
    public function execute()
    {
       $this->_view->loadLayout(false);
        $this->_view->getLayout()->getMessagesBlock()->setMessages($this->messageManager->getMessages(true));
        $this->_view->renderLayout();
    }
}