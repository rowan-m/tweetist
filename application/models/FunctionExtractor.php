<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
interface Tweetist_Model_FunctionExtractor
{
	/**
	 * @param SimpleXMLElement $tweet
	 * @param array $functionSignatures
	 * @return array
	 */
	public function getFunctionSignatures(SimpleXMLElement $tweet, array $functionSignatures);
}
