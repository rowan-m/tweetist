<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
class Tweetist_Model_ZendDbDataset
	implements Tweetist_Model_Dataset
{
	/**
	 * @var int
	 */
	private $dataset;

	/**
	 * @var Zend_Db_Adapter
	 */
	private $adapter;

	/**
	 * @param int $id
	 */
	public function __construct($dataset)
	{
		$this->dataset = $dataset;
		$this->adapter = Zend_Db::factory(
			'PDO_SQLITE',
			array(
				'dbname' => realpath(APPLICATION_PATH .  '/../data/db/tweetist.db'),
			)
		);
	}

	/**
	 * @return int
	 */
	public function countTweets()
	{
		return $this->adapter->fetchOne(
			'SELECT COUNT(DISTINCT tweet) FROM tweetist WHERE dataset = :dataset',
			array('dataset' => $this->dataset)
		);
	}

	/**
	 * @return int
	 */
	public function countReadTweets()
	{
		return $this->adapter->fetchOne(
			'SELECT COUNT(DISTINCT tweet)
			FROM tweetist
			WHERE dataset = :dataset AND read = 1',
			array('dataset' => $this->dataset)
		);
	}

	/**
	 * @return int
	 */
	public function countNoReadTweets()
	{
		return $this->adapter->fetchOne(
			'SELECT COUNT(DISTINCT tweet)
			FROM tweetist
			WHERE dataset = :dataset AND read = 0',
			array('dataset' => $this->dataset)
		);
	}

	/**
	 * @param string $functionSignature
	 * @return int
	 */
	public function countReadTweetsForFuncSig($functionSignature)
	{
		$split = explode(':', $functionSignature);

		return $this->adapter->fetchOne(
			'SELECT COUNT(DISTINCT tweet)
			FROM tweetist
			WHERE dataset = :dataset AND read = 1
			AND function = :function AND signature = :signature',
			array('dataset' => $this->dataset, 'function' => $split[0], 'signature' => $split[1])
		);
	}

	/**
	 * @param string $functionSignature
	 * @return int
	 */
	public function countNoReadTweetsForFuncSig($functionSignature)
	{
		$split = explode(':', $functionSignature);

		return $this->adapter->fetchOne(
			'SELECT COUNT(DISTINCT tweet)
			FROM tweetist
			WHERE dataset = :dataset AND read = 0
			AND function = :function AND signature = :signature',
			array('dataset' => $this->dataset, 'function' => $split[0], 'signature' => $split[1])
		);
	}

	/**
	 * Get the probability of reading a tweet
	 * @return float
	 */
	public function getPRead()
	{
		return bcdiv($this->countReadTweets(), $this->countTweets(), 9);
	}

	/**
	 * Get the probability of not reading a tweet
	 * @return float
	 */
	public function getPNoRead()
	{
		return bcdiv($this->countNoReadTweets(), $this->countTweets(), 9);
	}

	/**
	 * Get the probability of the specified function signature being extracted
	 * from a tweet given that tweet is read.
	 * @param string $functionSignature
	 * @return float
	 */
	public function getPSigGRead($functionSignature)
	{
		return bcdiv(
			$this->countReadTweetsForFuncSig($functionSignature),
			$this->countReadTweets(),
			9
		);
	}

	/**
	 * Get the probability of the specified function signature being extracted
	 * from a tweet given that tweet is not read.
	 * @param string $functionSignature
	 * @return float
	 */
	public function getPSigNoRead($functionSignature)
	{
		return bcdiv(
			$this->countNoReadTweetsForFuncSig($functionSignature),
			$this->countNoReadTweets(),
			9
		);
	}

	/**
	 * @param string $functionSignature
	 * @return int
	 */
	public function countFunctionSignature($functionSignature)
	{
		$split = explode(':', $functionSignature);

		return $this->adapter->fetchOne(
			'SELECT COUNT(DISTINCT tweet)
			FROM tweetist
			WHERE dataset = :dataset
			AND function = :function AND signature = :signature',
			array('dataset' => $this->dataset, 'function' => $split[0], 'signature' => $split[1])
		);
	}

	/**
	 * Add a set of function signatures to the dataset for a tweet which is
	 * read.
	 * @param int $id
	 * @param array $functionSignatures
	 */
	public function addRead($id, array $functionSignatures)
	{
		$initialise = false;

		foreach ($functionSignatures as $functionSignature) {
			if ($this->countFunctionSignature($functionSignature) == 0) {
				$initialise = true;
			}

			$split = explode(':', $functionSignature);
			$this->adapter->insert('tweetist',
				array(
					'dataset' => $this->dataset, 'tweet' => $id, 'read' => 1,
					'function' => $split[0], 'signature' => $split[1],
				)
			);

			if ($initialise) {
				$this->addNoRead(0, array($functionSignature));
				$initialise = false;
			}
		}
	}

	/**
	 * Add a set of function signatures to the dataset for a tweet which is
	 * not read
	 * @param int $id
	 * @param array $functionSignatures
	 */
	public function addNoRead($id, array $functionSignatures)
	{
		$initialise = false;

		foreach ($functionSignatures as $functionSignature) {
			if ($this->countFunctionSignature($functionSignature) == 0) {
				$initialise = true;
			}

			$split = explode(':', $functionSignature);
			$this->adapter->insert('tweetist',
				array(
					'dataset' => $this->dataset, 'tweet' => $id, 'read' => 0,
					'function' => $split[0], 'signature' => $split[1],
				)
			);

			if ($initialise) {
				$this->addRead(0, array($functionSignature));
				$initialise = false;
			}
		}
	}

	/**
	 * Remove any data associated with a tweet.
	 * @param int $id
	 */
	public function reset($id)
	{
		$this->adapter->delete('tweetist', array('dataset = '.$this->dataset, 'tweet = '.$id));
	}
}
