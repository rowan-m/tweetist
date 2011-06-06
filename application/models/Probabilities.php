<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
class Tweetist_Model_Probabilities
{
	/**
	 * @var Tweetist_Model_Dataset
	 */
	private $dataset;
	/**
	 * @param Tweetist_Model_Dataset $dataset
	 */
	public function __construct(Tweetist_Model_Dataset $dataset)
	{
		$this->dataset = $dataset;
	}

	/**
	 * @param array $functionSignatures
	 * @return array
	 */
	public function getLikelihoodFunctions(array $functionSignatures)
	{
		$likelihoodFunctions = array();

		foreach ($functionSignatures as $functionSignature)
		{
			if ($this->useForScoring($functionSignature)) {
				$likelihoodFunctions[$functionSignature] =
					$this->getProbability($functionSignature);
			}
		}

		return $likelihoodFunctions;
	}

	/**
	 * @param string $functionSignature
	 * @return bool
	 */
	public function useForScoring($functionSignature)
	{
		return (bool) $this->dataset->countFunctionSignature($functionSignature);
	}

	/**
	 * From Bayes Theorem:
	 *
	 *                             P(Sig|Read) * P(Read)
	 * P(Read|Sig) = ---------------------------------------------------
	 *                P(Sig|Read) * P(Read) + P(Sig|NoRead) * P(NoRead)
	 *
	 * P(Read|Sig) is the probability of reading this tweet given it has
	 * this property
	 * P(Sig|Read) is the probability of this property appearing in tweets
	 * that are read
	 * P(Read) is the probability of reading tweets
	 * P(Sig|NoRead) is the probability of not reading this tweet given it
	 * has this property
	 * P(NoRead) is the probability of not reading tweet
	 *
	 * @param string $functionSignature
	 * @return float
	 */
	private function getProbability($functionSignature)
	{
		// P(Sig|Read) * P(Read)
		$pSigReadProd =
			bcmul($this->dataset->getPSigGRead($functionSignature), $this->dataset->getPRead(), 9);

		// P(Sig|NoRead) * P(NoRead)
		$pSigNoReadProd =
			bcmul($this->dataset->getPSigNoRead($functionSignature), $this->dataset->getPNoRead(), 9);

		$pReadGSig =
			bcdiv($pSigReadProd, bcadd($pSigReadProd, $pSigNoReadProd, 9), 9);
		return $pReadGSig;
	}
}
