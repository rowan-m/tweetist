<?php

class TweetController extends Zend_Controller_Action
{
	public function init()
	{
		/* Initialize action controller here */
	}

	/**
	 * @param int $twinkyId
	 */
	private function parseTweet($tweetId)
	{
		$functionSignatures = array();

		if ($tweetId) {
			// twitter
			$twitter = $this->_helper->twitterService();

			if ($twitter) {
				$response = $twitter->status->show($tweetId);

				$extractors = new Tweetist_Model_FunctionExtractors(
					array('author', 'topic', 'user', 'word')
				);

				foreach ($extractors as $extractor)
				{
					$functionSignatures = $extractor->getFunctionSignatures(
						reset($response), $functionSignatures
					);
				}
			}
		}
		return $functionSignatures;
	}


	public function indexAction()
	{
		// action body
	}

	public function loveAction()
	{
		$tweetId = $this->getRequest()->getParam('id');
		$functionSignatures = $this->parseTweet($tweetId);

		if (!empty($functionSignatures)) {
			$savedTokens = new Zend_Session_Namespace('tokens');
			$access = unserialize($savedTokens->accessToken);
			$dataset = new Tweetist_Model_ZendDbDataset($access->getParam('user_id'));
			$dataset->addRead($tweetId, $functionSignatures);
		}
	}

	public function hateAction()
	{
		$tweetId = $this->getRequest()->getParam('id');
		$functionSignatures = $this->parseTweet($tweetId);

		if (!empty($functionSignatures)) {
			$savedTokens = new Zend_Session_Namespace('tokens');
			$access = unserialize($savedTokens->accessToken);
			$dataset = new Tweetist_Model_ZendDbDataset($access->getParam('user_id'));
			$dataset->addNoRead($tweetId, $functionSignatures);
		}
	}

	public function resetAction()
	{
		$tweetId = $this->getRequest()->getParam('id');

		if ($tweetId) {
			$savedTokens = new Zend_Session_Namespace('tokens');
			$access = unserialize($savedTokens->accessToken);
			$dataset = new Tweetist_Model_ZendDbDataset($access->getParam('user_id'));
			$dataset->reset($tweetId);
		}
	}
}
