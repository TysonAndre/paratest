<?php
namespace ParaTest\Logging;

/**
 * Class MetaProvider
 *
 * Adds __call behavior to a logging object
 * for aggregating totals and messages
 *
 * @package ParaTest\Logging
 */
abstract class MetaProvider
{
    /**
     * This pattern is used to see whether a missing
     * method is a "total" method or not
     *
     * @var string
     */
    protected static $totalMethod = '/^getTotal([\w]+)$/';

    /**
     * This pattern is used to add message retrieval for a given
     * type - i.e getFailures() or getErrors()
     *
     * @var string
     */
    protected static $messageMethod = '/^get((Failure|Error)s)$/';

    /**
     * Simplify aggregation of totals or messages
     */
    public function __call($method, $args)
    {
        if (preg_match(self::$totalMethod, $method, $matches) && $property = strtolower($matches[1])) {
            return $this->getNumericValue($property);
        }
        if (preg_match(self::$messageMethod, $method, $matches) && $type = strtolower($matches[1])) {
            return $this->getMessages($type);
        }
    }

    /**
     * Return a value as a float or integer
     *
     * @param $property
     * @return float|int
     */
    protected abstract function getNumericValue($property);

    /**
     * Return messages for a given type
     *
     * @param $type
     * @return array
     */
    protected abstract function getMessages($type);
}
