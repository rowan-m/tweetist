<?php
/**
 * @filesource
 */

/**
 * @package Tweetist
 * @subpackage Model
 */
class Tweetist_Model_WordExtractor
	implements Tweetist_Model_FunctionExtractor
{
	/**
	 * @param SimpleXMLElement $tweet
	 * @param array $functionSignatures
	 * @return array
	 */
	public function getFunctionSignatures(SimpleXMLElement $tweet, array $functionSignatures)
	{
		$text = $this->normalize((string)$tweet->text);
		$matches = preg_match_all('/[a-z0-9]+/', $text, $words);

		if ($matches > 0) {
			$words = array_unique($words[0]);

			foreach ($words as $word) {
				$functionSignatures[] = 'word:'.$word;
			}
		}

		return $functionSignatures;
	}

	/**
	 * @param string $text
	 * @return string
	 */
	private function normalize($text)
	{
		$text = strtolower($text);
		$filter = array(
			'\'' => '', '_' => ''
		);
		$text = strtr($text, $filter);
		return $text;
	}
}
