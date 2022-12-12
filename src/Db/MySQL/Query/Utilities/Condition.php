<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Db\MySQL\Query\Utilities;


/**
 * Class Condition
 *
 * @package MS\LightFramework\Db\MySQL\Query\Utilities
 */
final class Condition
{
    private $escapeClass;
    private $operatorsFrom = ['$lte', '$lt', '$eq', '$ne', '$gte', '$gt', '$is'];
    private $operatorsTo = ['<=', '<', '=', '!=', '>=', '>', ' IS '];

    /**
     * Condition constructor.
     *
     * @param array $aliases
     */
    public function __construct(array $aliases = [])
    {
        $this->escapeClass = new Escape($aliases);
    }

    /**
     * Parses logical expression
     *
     * @param string $variable
     * @param array  $expression
     * @param string $logicalOp
     * @return string
     */
    private function parseExpression(string $variable = '', array $expression = [], string $logicalOp = ' AND '): string
    {
        $ret = [];
        foreach ($expression as $operator => $value) {
            if (\in_array($operator, $this->operatorsFrom)) {
                $operator = \str_replace($this->operatorsFrom, $this->operatorsTo, $operator);
                $ret[] = \sprintf('%s%s%s', $variable, $operator, $this->escapeClass->escape($value));
            } elseif ($operator == '$in' && \is_array($value)) {
                $value = \array_map([$this->escapeClass, 'escape'], $value);
                $ret[] = \sprintf('%s IN (%s)', $variable, \implode(',', $value));
            } elseif ($operator == '$in' && \is_string($value)) {
                $value = $this->escapeClass->escape($value);
                $ret[] = \sprintf('%s IN (%s)', $variable, $value);
            } elseif ($operator == '$nin' && \is_array($value)) {
                $value = \array_map([$this->escapeClass, 'escape'], $value);
                $ret[] = \sprintf('%s NOT IN (%s)', $variable, \implode(',', $value));
            } elseif ($operator == '$nin' && $value instanceof \MS\LightFramework\Db\MySQL\Query\Select) {
                $ret[] = \sprintf('%s NOT IN (%s)', $variable, $value->___build());
            } elseif ($operator == '$nin' && \is_string($value)) {
                $value = $this->escapeClass->escape($value);
                $ret[] = \sprintf('%s NOT IN (%s)', $variable, $value);
            } elseif ($operator == '$like') {
                $ret[] = \sprintf('%s LIKE %s', $variable, $this->escapeClass->escape($value));
            }
        }
        return \implode($logicalOp, $ret);
    }

    /**
     * Parses condition
     *
     * @param array  $condition
     * @param string $logicalOperator
     * @return string
     */
    public function parse(array $condition = [], string $logicalOperator = ' AND '): string
    {
        $ret = [];
        foreach ($condition as $key => $val) {
            if (\is_int($key) && \is_array($val)) {
                $ret[] = $this->parse($val, $logicalOperator);
            } elseif (\is_string($key) && \is_array($val)) {
                if(\preg_match('/^\$or[0-9]*$/', $key)) {
                    $ret[] = \sprintf('(%s)', $this->parse($val, ' OR '));
                } elseif(\preg_match('/^\$or[0-9]*$/', $key)) {
                    $ret[] = \sprintf('(%s)', $this->parse($val, ' AND '));
                } else $ret[] = $this->parseExpression($key, $val, $logicalOperator);
            } elseif (\is_string($key) && \in_array(gettype($val), ['integer', 'double', 'string'])) {
                $ret[] = \sprintf('%s=%s', $key, $this->escapeClass->escape($val));
            }
        }
        return \implode($logicalOperator, $ret);
    }
}
