<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
class Tweetist_Model_Classifier
{
	/**
	 * @var Tweetist_Model_FunctionExtractors
	 */
	private $extractors;

	/**
	 * @var Tweetist_Model_Probabilities
	 */
	private $training;

	/**
	 * @var Tweetist_Model_Scorer
	 */
	private $scorer;

	/**
	 * @param Tweetist_Model_FunctionExtractors $extractors
	 * @param Tweetist_Model_Probabilities $training
	 * @param Tweetist_Model_Scorer $scorer
	 */
	public function __construct(
		Tweetist_Model_FunctionExtractors $extractors,
		Tweetist_Model_Probabilities $training,
		Tweetist_Model_Scorer $scorer
	)
	{
		$this->extractors = $extractors;
		$this->training = $training;
		$this->scorer = $scorer;
	}

	/**
	 * @param SimpleXMLElement $tweet
	 * @return int
	 */
	public function score(SimpleXMLElement $tweet)
	{
		$functionSignatures = array();
		foreach ($this->extractors as $extractor)
		{
			$functionSignatures = $extractor->getFunctionSignatures($tweet, $functionSignatures);
		}

		$likelihoodFunctions = $this->training->getLikelihoodFunctions($functionSignatures);
		$score = $this->scorer->getScore($likelihoodFunctions);

		return $score;
	}
}
