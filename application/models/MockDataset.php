<?php
error_log('*** ['.__FILE__.'@'.__LINE__.'] This class is all wrong! ***');
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
class Tweetist_Model_MockDataset
	implements Tweetist_Model_Dataset
{

	/**
	 * In the form:
	 * tweet ID, true = read | false = not read, function signature
	 * @var array
	 */
	private $data = array(
		array(1,  true,  'topic:lazyweb'),
		array(2,  true,  'topic:lazyweb'),
		array(2,  true,  'word:the'),
		array(3,  true,  'word:the'),
		array(4,  false, 'word:the'),
		array(4,  false, 'author:bobbyllew'),
		array(5,  false, 'author:bobbyllew'),
		array(6,  false, 'author:bobbyllew'),
		array(7,  false, 'author:bobbyllew'),
		array(8,  true,  'author:aral'),
		array(9,  true,  'author:aral'),
		array(10, true,  'author:aral'),
		array(11, false, 'author:aral'),
		array(11, false, 'word:is'),
		array(12, false, 'word:is'),
		array(13, false, 'word:is'),
		array(14, false, 'word:is'),
		array(15, true,  'word:is'),
	);
	/**
	 * Get the probability of reading a tweet
	 * @return float
	 */
	public function getPRead()
	{
		$read = 0;

		foreach ($this->data as $row) {
			if ($row[1] === true) $read++;
		}

		return bcdiv($read, count($this->data), 9);
	}

	/**
	 * Get the probability of not reading a tweet
	 * @return float
	 */
	public function getPNoRead()
	{
		$noRead = 0;

		foreach ($this->data as $row) {
			if ($row[1] === false) $noRead++;
		}

		return bcdiv($noRead, count($this->data), 9);
	}

	/**
	 * Get the probability of the specified function signature being extracted
	 * from a tweet given that tweet is read.
	 * @param string $functionSignature
	 * @return float
	 */
	public function getPSigGRead($functionSignature)
	{
		$read = 0;
		$sig = 0;

		foreach ($this->data as $row) {
			if ($row[1] === true) {
				$read++;
				if ($row[2] == $functionSignature) $sig++;
			}
		}

		return bcdiv($sig, $read, 9);
	}

	/**
	 * Get the probability of the specified function signature being extracted
	 * from a tweet given that tweet is not read.
	 * @param string $functionSignature
	 * @return float
	 */
	public function getPSigNoRead($functionSignature)
	{
		$noRead = 0;
		$sig = 0;

		foreach ($this->data as $row) {
			if ($row[1] === false) {
				$noRead++;
				if ($row[2] == $functionSignature) $sig++;
			}
		}

		return bcdiv($sig, $noRead, 9);
	}

	/**
	 * @param string $functionSignature
	 * @return int
	 */
	public function countFunctionSignature($functionSignature)
	{
		$count = 0;

		foreach ($this->data as $row) {
			if ($row[2] == $functionSignature) $count++;
		}

		return $count;
	}

	/**
	 * Add a set of function signatures to the dataset for a tweet which is
	 * read.
	 * @param int $id
	 * @param array $functionSignatures
	 */
	public function addRead($id, array $functionSignatures)
	{
		return;
	}

	/**
	 * Add a set of function signatures to the dataset for a tweet which is
	 * not read
	 * @param int $id
	 * @param array $functionSignatures
	 */
	public function addNoRead($id, array $functionSignatures)
	{
		return;
	}

	/**
	 * Remove any data associated with a tweet.
	 * @param int $id
	 */
	public function reset($id)
	{
		return;
	}
}
