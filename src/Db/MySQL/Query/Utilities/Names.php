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
 * Class Names
 *
 * @package MS\LightFramework\Db\MySQL\Query\Utilities
 */
final class Names
{
    private $aliases = [];

    /**
     * Parses names to array of strings.
     *
     * @param mixed|null $names
     * @param bool       $isTable
     * @return array
     */
    public function parse($names = null, bool $isTable = false): array
    {
        $ret = [];
        if ($names === null || $names == '*') {
            $ret[] = '*';
        } elseif (\is_string($names)) {
            $ret[] = $names;
            $this->aliases[] = $names.($isTable === true ? '\\.' : '');
        } elseif (\is_array($names)) {
            foreach ($names as $name => $alias) {
                if (\is_string($name)) {
                    if (\in_array($alias, [-1, 1])) {
                        $ret[] = \sprintf('%s %s', $name, \str_replace([-1, 1], ['DESC', 'ASC'], $alias));
                    } elseif (\is_string($alias)) {
                        $ret[] = \sprintf('%s %s', $name, $alias);
                        $this->aliases[] = \str_replace(
                            ['(', ')', '[', ']', '{', '}', '*', '+', '.', '?', '/', '\\'],
                            ['\\(', '\\)', '\\[', '\\]', '\\{', '\\}', '\\*', '\\+', '\\.', '\\?', '\\/', '\\\\'],
                            $name
                        );
                        $this->aliases[] = $alias.($isTable === true ? '\\.' : '');
                    }
                } elseif (\is_string($alias)) {
                    $ret[] = $alias;
                    $this->aliases[] = $alias.($isTable === true ? '\\.' : '');
                }
            }
        }
        return $ret;
    }

    /**
     * Adds aliases
     *
     * @param array $aliases
     */
    public function addAliases(array $aliases): void
    {
        foreach ($aliases as $alias) {
            $this->aliases[] = '\\('.$alias;
        }
        $this->aliases = \array_unique($this->aliases);
    }


    /**
     * Returns found aliases
     *
     * @return array
     */
    public function getAliases(): array
    {
        return \array_unique($this->aliases);
    }
}
