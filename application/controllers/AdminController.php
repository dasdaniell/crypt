<?php

class AdminController extends Zend_Controller_Action
{
    private $_redirector;
    private $_messenger;

    /**
     * Sets message for the messenger
     */
    private function _setMessage($type, $message)
    {
        $message = array('type' => $type, 'text' => $message);
        $this->_messenger->addMessage(serialize($message));
    }
    /**
     * Retrieves message from messenger
     */
    private function _getMessage()
    {
        if ($this->_messenger->hasMessages()) {
            $messages = $this->_messenger->getMessages();
            return unserialize($messages[0]);
        }
        return null;
    }

    public function init()
    {
        $this->utilsModel = new Model_Utils();
        $this->session = $this->utilsModel->initSession();

        $this->_messenger = $this->_helper->getHelper('FlashMessenger');// this calls session_start()
        $this->_messenger->setNamespace($this->_request->getParam('controller'));

            $this->layout = Zend_Layout::getMvcInstance();
            $this->myBaseUrl = $this->view->myBaseUrl('/');
            $this->_redirector = $this->_helper->getHelper('Redirector');

            $this->view->headScript()->appendFile($this->myBaseUrl . 'js/jquery-1.11.0.min.js');
            $this->view->headScript()->appendFile($this->myBaseUrl . 'js/jquery-ui-1.10.4.min.js');
            $this->view->headScript()->appendFile($this->myBaseUrl . 'js/jquery.validate.min.js');

            $this->view->headLink()->appendStylesheet($this->myBaseUrl . 'css/jquery-ui-1.10.4.min.css');

            $this->view->message = $this->_getMessage();
    }

    public function indexAction()
    {
        $this->view->headTitle('Administration', 'PREPEND');
    }

}
