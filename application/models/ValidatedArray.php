<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
abstract class Tweetist_Model_ValidatedArray
	implements ArrayAccess, IteratorAggregate, Countable
{
	/**
	 * @var array
	 */
	private $values = array();

	/**
	 * @param array $values
	 */
	public function __construct(array $values = array())
	{
		foreach ($values as $key => $value) {
			$this->values[$key] = $this->validateValue($value);
		}
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	abstract protected function validateValue($value);

	/**
	 * @param $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		if (isset($this->values[$offset])) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * @param $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->values[$offset];
	}

	/**
	 * @param $offset
	 * @param $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->values[$offset] = $value;
	}

	/**
	 * @param $offset
	 */
	public function offsetUnset($offset)
	{
		unset($this->values[$offset]);
	}

	/**
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->values);
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->values);
	}
}
