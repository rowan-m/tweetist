<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
class Tweetist_Model_TopicExtractor
	implements Tweetist_Model_FunctionExtractor
{
	/**
	 * @param SimpleXMLElement $tweet
	 * @param array $functionSignatures
	 * @return array
	 */
	public function getFunctionSignatures(SimpleXMLElement $tweet, array $functionSignatures)
	{
		$matches = preg_match_all('/#([a-z0-9_]+)/', strtolower((string)$tweet->text), $topics);

		if ($matches > 0) {
			$topics = array_unique($topics[1]);

			foreach ($topics as $topic) {
				$functionSignatures[] = 'topic:'.$topic;
			}
		}

		return $functionSignatures;
	}
}
