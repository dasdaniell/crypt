<?php
class AjaxController extends Zend_Controller_Action
{
    public function init() {
        try {
            if($this->_request->isXmlHttpRequest()) {
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender(true);
                $this->utilsModel = new Model_Utils();
                $this->session = $this->utilsModel->initSession();
            } else {
                die('Not ajax request.');
            }
        } catch(Exception $ex) {
            $json['response'] = 'failure';
            $json['message'] = $ex->getMessage();
            echo json_encode($json);exit;
        }
    }

}

