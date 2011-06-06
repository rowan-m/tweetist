<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
class Tweetist_Model_AuthorExtractor
	implements Tweetist_Model_FunctionExtractor
{
	/**
	 * @param SimpleXMLElement $tweet
	 * @param array $functionSignatures
	 * @return array
	 */
	public function getFunctionSignatures(SimpleXMLElement $tweet, array $functionSignatures)
	{
		$functionSignatures[] = 'author:'.(string)$tweet->user->id;
		return $functionSignatures;
	}
}
