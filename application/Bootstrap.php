<?php


class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initMetadataCache()
    {
        $cache = Zend_Cache::factory('Core', 'File',
            array('automatic_serialization' => true),
            array('cache_dir' => '/tmp'));
        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
    }


    protected function _initTimeZone()
    {
        //TODO get the user time zone conditional from user data if logged
        date_default_timezone_set('Europe/Madrid');
    }

    protected function _initAutoload()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH));

        return $moduleLoader;
    }



    protected function _initZFDebug()
    {
        if (APPLICATION_ENV != 'production') {
            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->registerNamespace('ZFDebug');

            $options = array(
                'plugins' => array('Variables',
                    'File' => array('base_path' => APPLICATION_PATH),
                    'Memory',
                    'Time',
                    'Registry',
                    'Exception',
                    'Xhprof')
            );

            if ($this->hasPluginResource('db')) {
                $this->bootstrap('db');
                $db = $this->getPluginResource('db')->getDbAdapter();
                $options['plugins']['Database']['adapter'] = $db;
            }

            # Setup the cache plugin
            if ($this->hasPluginResource('cache')) {
                $this->bootstrap('cache');
                $cache = $this - getPluginResource('cache')->getDbAdapter();
                $options['plugins']['Cache']['backend'] = $cache->getBackend();
            }

            $debug = new ZFDebug_Controller_Plugin_Debug($options);

            $this->bootstrap('frontController');
            $frontController = $this->getResource('frontController');
            $frontController->registerPlugin($debug);
        }
    }


    protected function _initFront()
    {
        Zend_Controller_Action_HelperBroker::addPath( APPLICATION_PATH . '/controllers/helpers');
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
        //$router->removeDefaultRoutes();


        $routeDef = new Zend_Controller_Router_Route (
            '/:controller/:action/*', array(
            'controller' => 'index',
            'action' => 'index',
        ));

        $routeSearch = new Zend_Controller_Router_Route (
            '/search/*', array(
            'controller' => 'search',
            'action' => 'index',
        ));

        $routeVuln = new Zend_Controller_Router_Route (
            '/vulnerability/*', array(
            'controller' => 'vulnerability',
            'action' => 'vulnerability',
        ));

        $router->addRoute('default', $routeDef); //important, put the default route first!
        $router->addRoute('search', $routeSearch);
        $router->addRoute('vuln', $routeVuln);

        $front->setRouter($router);
        return $front;
    }

}
