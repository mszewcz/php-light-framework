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

use MS\LightFramework\Exceptions\InvalidArgumentException;


/**
 * Class DateTime
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class DateTime extends AbstractSpecific
{
    const VALIDATOR_ERROR_DATETIME_NOT_VALID = 301;

    protected $defaultOptions = [
        'format' => null,
    ];

    /**
     * DateTime constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Validates datetime
     *
     * @return bool
     */
    private function validateDateTime(): bool
    {
        if ($this->value instanceof \DateTime) {
            return true;
        }

        if (\is_string($this->value) && $this->options['format'] === null) {
            throw new InvalidArgumentException('"format" option has to be provided');
        }

        \is_float($this->value) || \is_int($this->value)
            ? new \DateTime("@$this->value")
            : \DateTime::createFromFormat($this->options['format'], $this->value);

        $errors = \DateTime::getLastErrors();
        if ($errors['warning_count'] === 0 && $errors['error_count'] === 0) {
            return true;
        }

        $this->setError(self::VALIDATOR_ERROR_DATETIME_NOT_VALID);
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

        return $this->validateDateTime();
    }
}
