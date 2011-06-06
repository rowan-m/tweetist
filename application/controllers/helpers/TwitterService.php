<?php
class Zend_Controller_Action_Helper_TwitterService extends Zend_Controller_Action_Helper_Abstract
{
	public function twitterService()
	{
		$twitter = null;
		$consumer = $this->getActionController()->getInvokeArg('bootstrap')->getResource('oauth');
		$savedTokens = new Zend_Session_Namespace('tokens');

		if (!$savedTokens->accessToken) {
			Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')->gotoSimple('index', 'auth');
		}

		$access = unserialize($savedTokens->accessToken);
		$twitter = new Zend_Service_Twitter(array(
			'username' => $access->getParam('screen_name'),
			'accessToken' => $access
		));
		return $twitter;
	}

	public function direct()
	{
		return $this->twitterService();
	}
}
