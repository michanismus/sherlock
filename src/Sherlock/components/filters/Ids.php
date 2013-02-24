<?php
/**
 * User: Zachary Tong
 * Date: 2013-02-19
 * Time: 08:26 PM
 * Auto-generated by "generate.filters.php"
 */
namespace Sherlock\components\filters;

use Sherlock\components;

/**
 * @method \Sherlock\components\filters\Ids type() type(\string $value)
 */
class Ids extends \Sherlock\components\BaseComponent implements \Sherlock\components\FilterInterface
{
    public function __construct($hashMap = null)
    {

        parent::__construct($hashMap);
    }

    /**
     * @param  \string | array $values
     * @return Ids
     */
    public function values($ids)
    {

        $args = func_get_args();
        \Analog\Analog::log("Ids->Values(".print_r($args, true).")", \Analog\Analog::DEBUG);

        //single param, array of ids
        if (count($args) == 1 && is_array($args[0]))
            $args = $args[0];

        foreach ($args as $arg) {
            if (is_string($arg))
                $this->params['values'][] = $arg;
        }

        return $this;
    }

    public function toArray()
    {
        $ret = array (
  'ids' =>
  array (
    'type' => $this->params["type"],
    'values' => $this->params["values"],
  ),
);

        return $ret;
    }

}
