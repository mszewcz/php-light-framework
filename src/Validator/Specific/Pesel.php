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
 * Class Pesel
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class Pesel extends AbstractSpecific
{
    const VALIDATOR_ERROR_PESEL_INCORRECT_FORMAT = 1101;
    const VALIDATOR_ERROR_PESEL_INVALID_NUMBER = 1102;

    /**
     * Pesel constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Validates Pesel
     *
     * @return bool
     */
    private function validatePesel(): bool
    {
        if (!\preg_match('/^[0-9]{11}$/', (string)$this->value)) {
            $this->setError(self::VALIDATOR_ERROR_PESEL_INCORRECT_FORMAT);
            return false;
        }

        $checksum = 0;
        $weightArray = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];

        for ($i = 0; $i < 10; $i++) {
            $checksum += $weightArray[$i] * $this->value[$i];
        }
        $checksum = 10 - ($checksum % 10) == 10 ? 0 : 10 - ($checksum % 10);
        $isValid = $checksum == $this->value[10] ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_PESEL_INVALID_NUMBER);
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
        $this->setOptions($options);
        $this->setValue($value);
        $this->errors = [];

        $isValid = $this->validatePesel();

        return $isValid;
    }
}
