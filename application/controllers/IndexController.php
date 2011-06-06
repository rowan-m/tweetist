<?php

class IndexController extends Zend_Controller_Action
{
	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		$twitter = $this->_helper->twitterService();
		$this->view->tweets = array();

		if ($twitter) {
			$savedTokens = new Zend_Session_Namespace('tokens');
			$access = unserialize($savedTokens->accessToken);

			// classifier
			$extractors = new Tweetist_Model_FunctionExtractors(array('author', 'topic', 'user', 'word'));
			$dataset = new Tweetist_Model_ZendDbDataset($access->getParam('user_id'));
			$probabilities = new Tweetist_Model_Probabilities($dataset);
			$scorer = new Tweetist_Model_NaiveScorer();
			$classifier = new Tweetist_Model_Classifier($extractors, $probabilities, $scorer);

			$response = $twitter->account->verifyCredentials();
			$response = $twitter->status->friendsTimeline();

			$rawTweets = $response->status;
			$this->view->tweets = array();

			if ($response->status) {
				foreach($response->status as $rawTweet)
				{
					$this->view->tweets[] = array(
						'tweet' => $rawTweet,
						'score' => (string)round($classifier->score($rawTweet), 4),
					);
				}
			} else {
				$this->view->tweets[] = array('id' => 'FALEWALE');
			}
		}
	}
}

