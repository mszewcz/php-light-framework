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
 * Class StringLength
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class StringLength extends AbstractSpecific
{
    const VALIDATOR_ERROR_STRING_LENGTH_NOT_MATCH = 1401;
    const VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_HIGHER_THAN_MIN = 1402;
    const VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_HIGHER_OR_EQUAL_TO_MIN = 1403;
    const VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_LOWER_THAN_MAX = 1404;
    const VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_LOWER_OR_EQUAL_TO_MAX = 1405;

    protected $defaultOptions = [
        'type'      => 'equals',
        'length'    => 10,
        'inclusive' => false,
    ];

    /**
     * StringLength constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Checks whether string length is equal to specified number
     *
     * @return bool
     */
    private function validateEquals(): bool
    {
        $isValid = \mb_strlen((string)$this->value, 'UTF-8') == $this->options['length'] ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_STRING_LENGTH_NOT_MATCH);
        }
        return $isValid;
    }

    /**
     * Checks whether string length is greater than specified number
     *
     * @return bool
     */
    private function validateGreaterThan(): bool
    {
        if ($this->options['inclusive'] === true) {
            $isValid = \mb_strlen($this->value, 'UTF-8') >= $this->options['length'] ? true : false;
            if (!$isValid) {
                $this->setError(self::VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_HIGHER_OR_EQUAL_TO_MIN);
            }
            return $isValid;
        }

        $isValid = \mb_strlen($this->value, 'UTF-8') > $this->options['length'] ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_HIGHER_THAN_MIN);
        }
        return $isValid;
    }

    /**
     * Checks whether string length is lower than specified number
     *
     * @return bool
     */
    private function validateLowerThan(): bool
    {
        if ($this->options['inclusive'] === true) {
            $isValid = \mb_strlen($this->value, 'UTF-8') <= $this->options['length'] ? true : false;
            if (!$isValid) {
                $this->setError(self::VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_LOWER_OR_EQUAL_TO_MAX);
            }
            return $isValid;
        }

        $isValid = \mb_strlen($this->value, 'UTF-8') < $this->options['length'] ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_LOWER_THAN_MAX);
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

        switch ($this->options['type']) {
            case 'equals':
                $isValid = $this->validateEquals();
                break;
            case 'lower-than':
                $isValid = $this->validateLowerThan();
                break;
            case 'greater-than':
                $isValid = $this->validateGreaterThan();
                break;
            default:
                $isValid = $this->validateEquals();
                break;
        }

        return $isValid;
    }
}
