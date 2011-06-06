<?php

class AuthController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		// action body
	}

	public function redirectAction()
	{
		$consumer = $this->getInvokeArg('bootstrap')->getResource('oauth');
		$token = $consumer->getRequestToken();
		$savedTokens = new Zend_Session_Namespace('tokens');
		$savedTokens->requestToken = serialize($token);
		$consumer->redirect();
	}

	public function callbackAction()
	{
		$consumer = $this->getInvokeArg('bootstrap')->getResource('oauth');
		$savedTokens = new Zend_Session_Namespace('tokens');

		if (!empty($_GET) && isset($savedTokens->requestToken)) {
			$token = $consumer->getAccessToken(
				$_GET,
				unserialize($savedTokens->requestToken)
			);
			$savedTokens->accessToken = serialize($token);

			// Now that we have an Access Token, we can discard the Request Token
			$savedTokens->requestToken = null;
		} else {
			// Mistaken request? Some malfeasant trying something?
			throw new Zend_Controller_Action_Exception('', 404);
		}
	}
}
