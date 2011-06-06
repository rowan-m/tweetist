<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');
	}

	protected function _initViewHead()
	{
		$view = $this->getResource('view');
		$layout = $this->getResource('layout');
		$view->headTitle($layout['title'])->setSeparator(' - ')->setDefaultAttachOrder('PREPEND');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
	}
	protected function _initOauth()
	{
		$consumer = new Zend_Oauth_Consumer($this->getOption('oauth'));
		return $consumer;
	}

	protected function _initRoutes()
	{
		$this->bootstrap('frontController');

		$router = $this->getResource('frontController')->getRouter();
		$router->addConfig(new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', 'routes'),'routes');
		return $router;
	}

	protected function _initAutoload()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
			'namespace' => 'Tweetist_',
			'basePath'  => APPLICATION_PATH,
		));

		Zend_Controller_Action_HelperBroker::addPrefix('Tweetist_Controllers_Helpers');
		Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/controllers/helpers');

		return $autoloader;
	}
}
