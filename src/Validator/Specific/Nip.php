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
 * Class Nip
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class Nip extends AbstractSpecific
{
    const VALIDATOR_ERROR_NIP_INCORRECT_FORMAT = 801;
    const VALIDATOR_ERROR_NIP_INVALID_NUMBER = 802;

    /**
     * Nip constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Validates Nip
     *
     * @return bool
     */
    private function validateNip(): bool
    {
        if (!\preg_match('/^[0-9]{10}$/', $this->value)) {
            $this->setError(self::VALIDATOR_ERROR_NIP_INCORRECT_FORMAT);
            return false;
        }

        $checksum = 0;
        $weightArray = [6, 5, 7, 2, 3, 4, 5, 6, 7];

        for ($i = 0; $i < 9; $i++) {
            $checksum += $weightArray[$i] * $this->value[$i];
        }
        $isValid = $checksum % 11 == $this->value[9] ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_NIP_INVALID_NUMBER);
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
        $this->setValue(\str_replace(['-', ' '], '', $value));
        $this->errors = [];

        return $this->validateNip();
    }
}
