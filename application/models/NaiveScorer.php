<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
class Tweetist_Model_NaiveScorer
	implements Tweetist_Model_Scorer
{
	/**
	 * To combine the probabilities:
	 *
	 *                            (P1 * P2 *... Pn)
	 * P(Read) = -----------------------------------------------------
	 *            P1 * P2 *... Pn + (1 - P1) * (1 - P2) * ...(1 - Pn)
	 *
	 * P(Read) is the probability of reading the tweet
	 * P[1-n] is the probability of reading the tweet given feature [1..n]
	 *
	 * @param array $likelihoodFunctions
	 * @return int
	 */
	public function getScore(array $likelihoodFunctions)
	{
		$product = 1;
		$invProduct = 1;

		foreach($likelihoodFunctions as $functionSignature => $probability)
		{
			$product = bcmul($product, $probability, 9);
			$invProduct = bcmul($invProduct, bcsub(1, $probability, 9), 9);
		}

		$pRead = bcdiv($product, bcadd($product, $invProduct, 9), 9);
		return $pRead;
	}
}
