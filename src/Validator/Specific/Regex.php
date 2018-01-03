<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Validator\Specific;


/**
 * Class Regex
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class Regex extends AbstractSpecific
{
    const VALIDATOR_ERROR_REGEX_PATTERN_NOT_MATCH = 1201;

    protected $defaultOptions = [
        'pattern' => '/.*/',
    ];

    /**
     * Regex constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Checks whether string is valid against regex pattern
     *
     * @return bool
     */
    private function validateRegex(): bool
    {
        $isValid = \preg_match($this->options['pattern'], $this->value) ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_REGEX_PATTERN_NOT_MATCH);
        }
        return $isValid;
    }

    /**
     * Validates value
     *
     * @param null  $value
     * @param array $options
     * @return bool
     */
    public function isValid($value = null, array $options = []): bool
    {
        if (\count($options) > 0) {
            $this->setOptions($options);
        }
        $this->setValue($value);
        $this->errors = [];

        return $this->validateRegex();
    }
}
