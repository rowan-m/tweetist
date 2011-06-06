<?php
/**
 * @filesource
 */

/**
 * Manage the various extractor classes.
 *
 * @package Tweetist
 * @subpackage Model
 */
class Tweetist_Model_FunctionExtractors
	extends Tweetist_Model_ValidatedArray
{
	/**
	 * Instantiate the requested extractor
	 * @param string $extractorName
	 */
	public function validateValue($extractorName)
	{
		$classname = 'Tweetist_Model_' . ucfirst((string)$extractorName) . 'Extractor';

		if (class_exists($classname)) {
			return new $classname();
		}

		throw new InvalidArgumentException('Invalid extractor: ' . $extractorName);
	}
}
