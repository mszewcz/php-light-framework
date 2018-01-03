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
 * Class Regon
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class Regon extends AbstractSpecific
{
    const VALIDATOR_ERROR_REGON_INCORRECT_FORMAT = 1301;
    const VALIDATOR_ERROR_REGON_INVALID_NUMBER = 1302;

    /**
     * Regon constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Validates Regon
     *
     * @return bool
     */
    private function validateRegon(): bool
    {
        if (!\preg_match('/^[0-9]{9}$/', (string)$this->value)) {
            $this->setError(self::VALIDATOR_ERROR_REGON_INCORRECT_FORMAT);
            return false;
        }

        $checksum = 0;
        $weightArray = [8, 9, 2, 3, 4, 5, 6, 7];

        for ($i = 0; $i < 8; $i++) {
            $checksum += $weightArray[$i] * $this->value[$i];
        }
        $checksum = $checksum % 11 == 10 ? 0 : $checksum % 11;
        $isValid = $checksum == $this->value[8] ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_REGON_INVALID_NUMBER);
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

        return $this->validateRegon();
    }
}
