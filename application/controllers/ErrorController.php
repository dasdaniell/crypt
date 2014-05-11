<?php

class ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {        
        $this->view->headTitle('Not Found');
        $this->view->headTitle()->setSeparator(' - ');
        
        $this->utilsModel = new Model_Utils();
        $this->session = $this->utilsModel->initSession();
        
        $baseUrl = $this->view->myBaseUrl('/');
        $this->view->headScript()->appendFile($baseUrl . 'js/jquery-1.11.0.min.js');

        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->headerMessage = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->headerMessage = 'Application error';
//                $this->view->headerMessage = 'Application error: ' . $errors->exception->getMessage(); // 
                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->crit($this->view->headerMessage, $errors->exception);
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}
