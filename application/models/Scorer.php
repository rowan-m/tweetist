<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
interface Tweetist_Model_Scorer
{
	/**
	 * @param array $likelihoodFunctions
	 * @return int
	 */
	public function getScore(array $likelihoodFunctions);
}
