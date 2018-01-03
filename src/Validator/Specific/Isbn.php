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
 * Class Isbn
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class Isbn extends AbstractSpecific
{
    const VALIDATOR_ERROR_ISBN_INCORRECT_FORMAT = 701;
    const VALIDATOR_ERROR_ISBN_INVALID_NUMBER = 702;

    protected $defaultOptions = [
        'type' => 'auto',
    ];
    private $patterns = [
        'isbn10-basic'     => '/^[0-9]{1,9}[0-9X]$/',
        'isbn10-separated' => '/^[0-9]{1,7}[\ \-][0-9]{1,7}[\ \-][0-9]{1,7}[\ \-][0-9X]$/',
        'isbn13-basic'     => '/^[0-9]{13}$/',
        'isbn13-separated' => '/^[0-9]{1,9}[\ \-][0-9]{1,5}[\ \-][0-9]{1,9}[\ \-][0-9]{1,9}[\ \-][0-9]$/',
    ];

    /**
     * Isbn constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Validates ISBN-10 number
     *
     * @return bool
     */
    private function validateIsbn10(): bool
    {
        $value = \str_replace(['-', ' '], '', $this->value);
        if (!\preg_match($this->patterns['isbn10-basic'], $value)) {
            $this->setError(self::VALIDATOR_ERROR_ISBN_INCORRECT_FORMAT);
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (10 - $i) * $value{$i};
        }

        $checksum = 11 - ($sum % 11);
        if ($checksum == 11) {
            $checksum = '0';
        } elseif ($checksum == 10) {
            $checksum = 'X';
        }
        $isValid = \substr($value, -1) == $checksum ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_ISBN_INVALID_NUMBER);
        }
        return $isValid;
    }

    /**
     * Validates ISBN-13 number
     *
     * @return bool
     */
    private function validateIsbn13(): bool
    {
        $value = \str_replace(['-', ' '], '', $this->value);
        if (!\preg_match($this->patterns['isbn13-basic'], $value)) {
            $this->setError(self::VALIDATOR_ERROR_ISBN_INCORRECT_FORMAT);
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += ($i % 2 == 0) ? $value{$i} : (3 * $value{$i});
        }

        $checksum = (10 - ($sum % 10)) % 10;
        $isValid = \substr($value, -1) == $checksum ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_ISBN_INVALID_NUMBER);
        }
        return $isValid;
    }

    /**
     * Validates ISBN number with auto format detection
     *
     * @return bool
     */
    private function validateAuto(): bool
    {
        if (\preg_match($this->patterns['isbn13-basic'], $this->value)) {
            return $this->validateIsbn13();
        }
        if (\preg_match($this->patterns['isbn13-separated'], $this->value) && \strlen($this->value) == 17) {
            return $this->validateIsbn13();
        }
        if (\preg_match($this->patterns['isbn10-basic'], $this->value)) {
            return $this->validateIsbn10();
        }
        if (\preg_match($this->patterns['isbn10-separated'], $this->value) && \strlen($this->value) == 13) {
            return $this->validateIsbn10();
        }

        $this->setError(self::VALIDATOR_ERROR_ISBN_INCORRECT_FORMAT);
        return false;
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
            case 'isbn-10':
                $isValid = $this->validateIsbn10();
                break;
            case 'isbn-13':
                $isValid = $this->validateIsbn13();
                break;
            default:
                $isValid = $this->validateAuto();
                break;
        }
        return $isValid;
    }
}
