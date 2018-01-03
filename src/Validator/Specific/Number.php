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
 * Class Number
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class Number extends AbstractSpecific
{
    const VALIDATOR_ERROR_NUMBER_INCORRECT_TYPE = 901;
    const VALIDATOR_ERROR_NUMBER_MUST_BE_HIGHER_THAN_MIN = 902;
    const VALIDATOR_ERROR_NUMBER_MUST_BE_HIGHER_OR_EQUAL_TO_MIN = 903;
    const VALIDATOR_ERROR_NUMBER_MUST_BE_LOWER_THAN_MAX = 904;
    const VALIDATOR_ERROR_NUMBER_MUST_BE_LOWER_OR_EQUAL_TO_MAX = 905;

    protected $defaultOptions = [
        'type'      => 'integer',
        'min'       => null,
        'max'       => null,
        'inclusive' => true,
    ];

    /**
     * Number constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Checks whether number is of required type. Integers are treated as valid floats.
     *
     * @return bool
     */
    private function validateType(): bool
    {
        switch ($this->options['type']) {
            case 'integer':
                $isValid = \is_int($this->value) ? true : false;
                break;
            case 'float':
                $isValid = \is_int($this->value) || \is_float($this->value) ? true : false;
                break;
            default:
                $isValid = \is_int($this->value) || \is_float($this->value) ? true : false;
                break;
        }
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_NUMBER_INCORRECT_TYPE);
        }
        return $isValid;
    }

    /**
     * Checks whether number is greater than specified min number
     *
     * @return bool
     */
    private function validateGreaterThan(): bool
    {
        if ($this->options['min'] !== null) {
            if ($this->options['inclusive'] === false && $this->value <= $this->options['min']) {
                $this->setError(self::VALIDATOR_ERROR_NUMBER_MUST_BE_HIGHER_THAN_MIN);
                return false;
            }

            if ($this->value < $this->options['min']) {
                $this->setError(self::VALIDATOR_ERROR_NUMBER_MUST_BE_HIGHER_OR_EQUAL_TO_MIN);
                return false;
            }
        }
        return true;
    }

    /**
     * Checks whether number is lower than specified max number
     *
     * @return bool
     */
    private function validateLowerThan(): bool
    {
        if ($this->options['max'] !== null) {
            if ($this->options['inclusive'] === false && $this->value >= $this->options['max']) {
                $this->setError(self::VALIDATOR_ERROR_NUMBER_MUST_BE_LOWER_THAN_MAX);
                return false;
            }

            if ($this->value > $this->options['max']) {
                $this->setError(self::VALIDATOR_ERROR_NUMBER_MUST_BE_LOWER_OR_EQUAL_TO_MAX);
                return false;
            }
        }
        return true;
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

        $validType = $this->validateType();
        $validGT = $this->validateGreaterThan();
        $validLT = $this->validateLowerThan();

        return $validType && $validGT && $validLT;
    }
}
