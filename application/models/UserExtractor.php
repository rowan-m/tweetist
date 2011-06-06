<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
class Tweetist_Model_UserExtractor
	implements Tweetist_Model_FunctionExtractor
{
	/**
	 * @param SimpleXMLElement $tweet
	 * @param array $functionSignatures
	 * @return array
	 */
	public function getFunctionSignatures(SimpleXMLElement $tweet, array $functionSignatures)
	{
		$matches = preg_match_all('/@([a-z0-9_]+)/', strtolower((string)$tweet->text), $users);

		if ($matches > 0) {
			$users = array_unique($users[1]);

			foreach ($users as $user) {
				$functionSignatures[] = 'user:'.$user;
			}
		}

		return $functionSignatures;
	}
}
