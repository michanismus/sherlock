<?php
/**
 * User: Zachary Tong
 * Date: 2/7/13
 * Time: 8:30 AM
 */

namespace sherlock\components\queries;
use sherlock\components\QueryInterface;
use sherlock\common\exceptions;

/**
 * @method \sherlock\components\queries\MultiMatch field() field($name)  Field to search
 * @method \sherlock\components\queries\MultiMatch query() query($term)    query to search
 *
 * @method \sherlock\components\queries\MultiMatch use_dis_max() use_dis_max($value) Default 1
 * @method \sherlock\components\queries\MultiMatch tie_breaker() tie_breaker($value) Default 0.7
 * @method \sherlock\components\queries\MultiMatch boost() boost($value) Optional boosting for term value. Default = 1
 * @method \sherlock\components\queries\MultiMatch operator() operator($operator) Optional operator for match query. Default = 'and'
 * @method \sherlock\components\queries\MultiMatch analyzer() analyzer($analyzer) Optional analyzer for match query. Default to 'default'
 * @method \sherlock\components\queries\MultiMatch fuzziness() fuzziness($value) Optional amount of fuzziness. Default to 0.5
 * @method \sherlock\components\queries\MultiMatch fuzzy_rewrite() fuzzy_rewrite($value) Default to 'constant_score_default'
 * @method \sherlock\components\queries\MultiMatch lenient() lenient($value) Default to 1
 * @method \sherlock\components\queries\MultiMatch max_expansions() max_expansions($value) Default to 100
 * @method \sherlock\components\queries\MultiMatch minimum_should_match() minimum_should_match($value) Default to 2
 * @method \sherlock\components\queries\MultiMatch prefix_length() prefix_length($value) Default to 2
 */

class MultiMatch implements QueryInterface
{
	protected $params = array();

	public function __construct()
	{
		$this->params['boost'] = 1;
		$this->params['operator'] = 'and';
		$this->params['analyzer'] = 'default';
		$this->params['fuzziness'] = 0.5;
		$this->params['fuzzy_rewrite'] = 'constant_score_default';
		$this->params['lenient'] = 1;
		$this->params['max_expansions'] = 100;
		$this->params['minimum_should_match'] = 2;
		$this->params['prefix_length'] = 2;
		$this->params['use_dis_max'] = 1;
		$this->params['tie_breaker'] = 0.7;
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return MultiMatch
	 */
	public function __call($name, $arguments)
	{
		$this->params[$name] = $arguments[0];
		return $this;
	}

	/**
	 * @return array
	 * @throws \sherlock\common\exceptions\RuntimeException
	 */
	public function build()
	{
		$data = $this->params;

		if (!isset($data['field']))
			throw new exceptions\RuntimeException("Field must be set for a Multi_Match Query");

		if (!isset($data['query']))
			throw new exceptions\RuntimeException("Query must be set for a Multi_Match Query");

		$ret = 	array("match" =>
					array($data['field'] =>
						array("query" => $data['query'],
							"boost" => $data['boost'],
							"operator" => $data['operator'],
							"analyzer" => $data['analyzer'],
							"fuzziness" => $data['fuzziness'],
							"fuzzy_rewrite" => $data['fuzzy_rewrite'],
							"lenient" => $data['lenient'],
							"max_expansions" => $data['max_expansions'],
							"minimum_should_match" => $data['minimum_should_match'],
							"prefix_length" => $data['prefix_length'],
							"use_dis_max" => $data['use_dis_max'],
							"tie_breaker" => $data['tie_breaker']
						)
					)
				);

		return $ret;
	}
}




