<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected $_view;
    protected $_frontController;

    protected function _initSettings() {
        date_default_timezone_set('Africa/Cairo');
        foreach ($this->_options['settings'] as $key => $value) {
            defined($key)
                || define($key, (string)$value);
        }
    }
    protected function _initController()
    {
        $this->bootstrap('frontController');
        $this->_frontController = $this->getResource('frontController');
    }
    protected function _initLocale()
    {
        $locale = new Zend_Locale('en_GB');
        Zend_Registry::set('Zend_Locale', $locale);
    }
    
    protected function _initViewSettings()
    {
        
        $this->bootstrap('view');
        $this->_view = $this->getResource('view');
        // set encoding and doctype
        $this->_view->setEncoding('UTF-8');
        $this->_view->doctype('XHTML1_TRANSITIONAL');
        // set the content type and language
        $this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $this->_view->headMeta()->appendHttpEquiv('Content-Language', 'en-US');
        
        $this->_view->headTitle(APP_NAME);
        $this->_view->headTitle()->setSeparator(' - ');
        
    }
    protected function _initRouting()
    {
        $router = $this->_frontController->getRouter();
        $this->_frontController->setRouter($router);
    }

    protected function _initDB() {
        try {
            $this->db = new Zend_Db_Adapter_Pdo_Mysql(array(
                'host'     => $this->_options["db"]["params"]['host'],
                'username' => $this->_options["db"]["params"]['username'],
                'password' => $this->_options["db"]["params"]['password'],
                'dbname'   => $this->_options["db"]["params"]['dbname']
            ));
            $this->db->getConnection()->exec("SET CHARACTER SET " . $this->_options["db"]["params"]['charset']);
            $this->db->getConnection()->exec("SET COLLATION_CONNECTION = '{$this->_options["db"]["params"]['collation']}'");
            Zend_Registry::set('db', $this->db);

            Zend_Db_Table_Abstract::setDefaultAdapter($this->db);

            // store the information required to connect to external database in registry
//            Zend_Registry::set('_options_db2', $this->_options["db2"]["params"]);
        }
        catch (PDOException $e) {
            die('pdo exception = '. $e->getMessage());
        }
        catch (Exception $e) {
            die('DB general exception = '. $e->getMessage());
        }
    }

    /**
     * append services and models to autoloader so that the resources can autoload properly -
     * result: you can instantiate a class named differently than without including the file that contains it
     */
    protected function _initAutoLoading()
    {
        // without this we cant load Model_Utils class
        $loader = new Zend_Application_Module_Autoloader(array(
                'namespace' => '',
                'basePath' => APPLICATION_PATH)
        );
        return $loader;        
    }
    
}