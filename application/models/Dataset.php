<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
interface Tweetist_Model_Dataset
{

	/**
	 * Count the number of tweets
	 * @return int
	 */
	public function countTweets();

	/**
	 * Count the number of read tweets
	 * @return int
	 */
	public function countReadTweets();

	/**
	 * Count the number of not read tweets
	 * @return int
	 */
	public function countNoReadTweets();

	/**
	 * Count the number of read tweets for this function signature
	 * @param string $functionSignature
	 * @return int
	 */
	public function countReadTweetsForFuncSig($functionSignature);

	/**
	 * Count the number of not read tweets for this function signature
	 * @param string $functionSignature
	 * @return int
	 */
	public function countNoReadTweetsForFuncSig($functionSignature);

	/**
	 * Get the probability of reading a tweet
	 * @return float
	 */
	public function getPRead();

	/**
	 * Get the probability of not reading a tweet
	 * @return float
	 */
	public function getPNoRead();

	/**
	 * Get the probability of the specified function signature being extracted
	 * from a tweet given that tweet is read.
	 * @param string $functionSignature
	 * @return float
	 */
	public function getPSigGRead($functionSignature);

	/**
	 * Get the probability of the specified function signature being extracted
	 * from a tweet given that tweet is not read.
	 * @param string $functionSignature
	 * @return float
	 */
	public function getPSigNoRead($functionSignature);

	/**
	 * @param string $functionSignature
	 * @return int
	 */
	public function countFunctionSignature($functionSignature);

	/**
	 * Add a set of function signatures to the dataset for a tweet which is
	 * read.
	 * @param int $id
	 * @param array $functionSignatures
	 */
	public function addRead($id, array $functionSignatures);

	/**
	 * Add a set of function signatures to the dataset for a tweet which is
	 * not read
	 * @param int $id
	 * @param array $functionSignatures
	 */
	public function addNoRead($id, array $functionSignatures);

	/**
	 * Remove any data associated with a tweet.
	 * @param int $id
	 */
	public function reset($id);
}
