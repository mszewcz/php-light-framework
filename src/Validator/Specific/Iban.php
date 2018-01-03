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

use MS\LightFramework\Exceptions\DomainException;


/**
 * Class Iban
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class Iban extends AbstractSpecific
{
    const VALIDATOR_ERROR_IBAN_INCORRECT_FORMAT = 501;
    const VALIDATOR_ERROR_IBAN_INVALID_NUMBER = 502;

    protected $defaultOptions = [
        'allow-non-sepa' => true,
        'country-code'   => null,
    ];
    private $sepaCountries = [
        'AT', 'BE', 'BG', 'CH', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'FO', 'GB', 'GI', 'GL', 'GR', 'HU',
        'IE', 'IS', 'IT', 'LI', 'LT', 'LU', 'LV', 'MC', 'MT', 'NL', 'NO', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK',
    ];
    private $patterns = [
        'AD' => 'AD[0-9]{2}[0-9]{4}[0-9]{4}[A-Z0-9]{12}',
        'AE' => 'AE[0-9]{2}[0-9]{3}[0-9]{16}',
        'AL' => 'AL[0-9]{2}[0-9]{8}[A-Z0-9]{16}',
        'AT' => 'AT[0-9]{2}[0-9]{5}[0-9]{11}',
        'AZ' => 'AZ[0-9]{2}[A-Z]{4}[A-Z0-9]{20}',
        'BA' => 'BA[0-9]{2}[0-9]{3}[0-9]{3}[0-9]{8}[0-9]{2}',
        'BE' => 'BE[0-9]{2}[0-9]{3}[0-9]{7}[0-9]{2}',
        'BG' => 'BG[0-9]{2}[A-Z]{4}[0-9]{4}[0-9]{2}[A-Z0-9]{8}',
        'BH' => 'BH[0-9]{2}[A-Z]{4}[A-Z0-9]{14}',
        'BR' => 'BR[0-9]{2}[0-9]{8}[0-9]{5}[0-9]{10}[A-Z][A-Z0-9]',
        'CH' => 'CH[0-9]{2}[0-9]{5}[A-Z0-9]{12}',
        'CR' => 'CR[0-9]{2}[0-9]{3}[0-9]{14}',
        'CY' => 'CY[0-9]{2}[0-9]{3}[0-9]{5}[A-Z0-9]{16}',
        'CZ' => 'CZ[0-9]{2}[0-9]{20}',
        'DE' => 'DE[0-9]{2}[0-9]{8}[0-9]{10}',
        'DO' => 'DO[0-9]{2}[A-Z0-9]{4}[0-9]{20}',
        'DK' => 'DK[0-9]{2}[0-9]{14}',
        'EE' => 'EE[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{11}[0-9]{1}',
        'ES' => 'ES[0-9]{2}[0-9]{4}[0-9]{4}[0-9]{1}[0-9]{1}[0-9]{10}',
        'FI' => 'FI[0-9]{2}[0-9]{6}[0-9]{7}[0-9]{1}',
        'FO' => 'FO[0-9]{2}[0-9]{4}[0-9]{9}[0-9]{1}',
        'FR' => 'FR[0-9]{2}[0-9]{5}[0-9]{5}[A-Z0-9]{11}[0-9]{2}',
        'GB' => 'GB[0-9]{2}[A-Z]{4}[0-9]{6}[0-9]{8}',
        'GE' => 'GE[0-9]{2}[A-Z]{2}[0-9]{16}',
        'GI' => 'GI[0-9]{2}[A-Z]{4}[A-Z0-9]{15}',
        'GL' => 'GL[0-9]{2}[0-9]{4}[0-9]{9}[0-9]{1}',
        'GR' => 'GR[0-9]{2}[0-9]{3}[0-9]{4}[A-Z0-9]{16}',
        'GT' => 'GT[0-9]{2}[A-Z0-9]{4}[A-Z0-9]{20}',
        'HR' => 'HR[0-9]{2}[0-9]{7}[0-9]{10}',
        'HU' => 'HU[0-9]{2}[0-9]{3}[0-9]{4}[0-9]{1}[0-9]{15}[0-9]{1}',
        'IE' => 'IE[0-9]{2}[A-Z]{4}[0-9]{6}[0-9]{8}',
        'IL' => 'IL[0-9]{2}[0-9]{3}[0-9]{3}[0-9]{13}',
        'IS' => 'IS[0-9]{2}[0-9]{4}[0-9]{2}[0-9]{6}[0-9]{10}',
        'IT' => 'IT[0-9]{2}[A-Z]{1}[0-9]{5}[0-9]{5}[A-Z0-9]{12}',
        'KW' => 'KW[0-9]{2}[A-Z]{4}[0-9]{22}',
        'KZ' => 'KZ[0-9]{2}[0-9]{3}[A-Z0-9]{13}',
        'LB' => 'LB[0-9]{2}[0-9]{4}[A-Z0-9]{20}',
        'LI' => 'LI[0-9]{2}[0-9]{5}[A-Z0-9]{12}',
        'LT' => 'LT[0-9]{2}[0-9]{5}[0-9]{11}',
        'LU' => 'LU[0-9]{2}[0-9]{3}[A-Z0-9]{13}',
        'LV' => 'LV[0-9]{2}[A-Z]{4}[A-Z0-9]{13}',
        'MC' => 'MC[0-9]{2}[0-9]{5}[0-9]{5}[A-Z0-9]{11}[0-9]{2}',
        'MD' => 'MD[0-9]{2}[A-Z0-9]{20}',
        'ME' => 'ME[0-9]{2}[0-9]{3}[0-9]{13}[0-9]{2}',
        'MK' => 'MK[0-9]{2}[0-9]{3}[A-Z0-9]{10}[0-9]{2}',
        'MR' => 'MR13[0-9]{5}[0-9]{5}[0-9]{11}[0-9]{2}',
        'MT' => 'MT[0-9]{2}[A-Z]{4}[0-9]{5}[A-Z0-9]{18}',
        'MU' => 'MU[0-9]{2}[A-Z]{4}[0-9]{2}[0-9]{2}[0-9]{12}[0-9]{3}[A-Z]{3}',
        'NL' => 'NL[0-9]{2}[A-Z]{4}[0-9]{10}',
        'NO' => 'NO[0-9]{2}[0-9]{4}[0-9]{6}[0-9]{1}',
        'PK' => 'PK[0-9]{2}[A-Z]{4}[A-Z0-9]{16}',
        'PL' => 'PL[0-9]{2}[0-9]{8}[0-9]{16}',
        'PS' => 'PS[0-9]{2}[A-Z]{4}[A-Z0-9]{21}',
        'PT' => 'PT[0-9]{2}[0-9]{4}[0-9]{4}[0-9]{11}[0-9]{2}',
        'RO' => 'RO[0-9]{2}[A-Z]{4}[A-Z0-9]{16}',
        'RS' => 'RS[0-9]{2}[0-9]{3}[0-9]{13}[0-9]{2}',
        'SA' => 'SA[0-9]{2}[0-9]{2}[A-Z0-9]{18}',
        'SE' => 'SE[0-9]{2}[0-9]{3}[0-9]{16}[0-9]{1}',
        'SI' => 'SI[0-9]{2}[0-9]{5}[0-9]{8}[0-9]{2}',
        'SK' => 'SK[0-9]{2}[0-9]{4}[0-9]{6}[0-9]{10}',
        'SM' => 'SM[0-9]{2}[A-Z]{1}[0-9]{5}[0-9]{5}[A-Z0-9]{12}',
        'TN' => 'TN59[0-9]{2}[0-9]{3}[0-9]{13}[0-9]{2}',
        'TR' => 'TR[0-9]{2}[0-9]{5}[A-Z0-9]{1}[A-Z0-9]{16}',
        'VG' => 'VG[0-9]{2}[A-Z]{4}[0-9]{16}',
    ];

    /**
     * Iban constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Validates IBAN number
     *
     * @return bool
     */
    private function validateIban(): bool
    {
        $value = \substr($this->value, 4).\substr($this->value, 0, 4);
        $value = \str_replace(
            ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
            ['10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22',
                '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35'],
            $value
        );

        $tmp = \intval(\substr($value, 0, 1));
        $len = \strlen($value);
        for ($i = 1; $i < $len; ++$i) {
            $tmp *= 10;
            $tmp += \intval(\substr($value, $i, 1));
            $tmp %= 97;
        }
        $valid = $tmp == 1 ? true : false;
        if (!$valid) {
            $this->setError(self::VALIDATOR_ERROR_IBAN_INVALID_NUMBER);
        }
        return $valid;
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
        $this->setValue(\str_replace(' ', '', (string)$value));
        $this->errors = [];

        if ($this->options['country-code'] !== null) {
            $newValue = \preg_match('/^[A-Z]{2}/', $this->value)
                ? \preg_replace('/^[A-Z]{2}/', $this->options['country-code'], $this->value)
                : $this->options['country-code'].$this->value;

            $this->setValue($newValue);
        }

        $countryCode = \substr($this->value, 0, 2);
        if (!\array_key_exists($countryCode, $this->patterns)) {
            throw new DomainException('Unsopported country code: '.$countryCode);
        }
        if ($this->options['allow-non-sepa'] === false && !\in_array($countryCode, $this->sepaCountries)) {
            $exMsg = 'Countries outside the Single Euro Payments Area (SEPA) are not supported';
            throw new DomainException($exMsg);
        }

        if (!\preg_match('/^'.$this->patterns[$countryCode].'$/', $this->value)) {
            $this->setError(self::VALIDATOR_ERROR_IBAN_INCORRECT_FORMAT);
            return false;
        }

        return $this->validateIban();
    }
}
