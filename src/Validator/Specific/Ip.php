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
 * Class Ip
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class Ip extends AbstractSpecific
{
    const VALIDATOR_ERROR_IP_INVALID_NUMBER = 601;
    const VALIDATOR_ERROR_IP_PRIVATE_RANGE_NOT_ALLOWED = 602;
    const VALIDATOR_ERROR_IP_RESERVED_RANGE_NOT_ALLOWED = 603;

    protected $defaultOptions = [
        'allow-ipv4'           => true,
        'allow-ipv6'           => true,
        'allow-private-range'  => true,
        'allow-reserved-range' => true,
    ];

    /**
     * Ip constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Validates IP
     *
     * @return bool
     */
    private function validateIPNumber(): bool
    {
        $value = $this->value;

        if (\preg_match('/^([01]{8}\.){3}[01]{8}$/', $this->value)) {
            // binary format 00000000.00000000.00000000.00000000
            $value = \sprintf(
                '%s.%s.%s.%s',
                \bindec(\substr($this->value, 0, 8)),
                \bindec(\substr($this->value, 9, 8)),
                \bindec(\substr($this->value, 18, 8)),
                \bindec(\substr($this->value, 27, 8))
            );
        } elseif (\preg_match('/^([0-9a-f]{2}\.){3}[0-9a-f]{2}$/i', $this->value)) {
            // hex format ff.ff.ff.ff
            $value = \sprintf(
                '%s.%s.%s.%s',
                \hexdec(\substr($this->value, 0, 2)),
                \hexdec(\substr($this->value, 3, 2)),
                \hexdec(\substr($this->value, 6, 2)),
                \hexdec(\substr($this->value, 9, 2))
            );
        }
        $flags = 0;
        if ($this->options['allow-ipv4'] === true) {
            $flags |= FILTER_FLAG_IPV4;
        }
        if ($this->options['allow-ipv6'] === true) {
            $flags |= FILTER_FLAG_IPV6;
        }
        $isValid = \filter_var($value, FILTER_VALIDATE_IP, ['flags' => $flags]) !== false ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_IP_INVALID_NUMBER);
        }
        return $isValid;
    }

    /**
     * Validates private range
     *
     * @param string $value
     * @return bool
     */
    private function validatePrivateRange(string $value = ''): bool
    {
        $isValid = true;
        if ($this->options['allow-private-range'] === false) {
            $flags = FILTER_FLAG_NO_PRIV_RANGE;
            $isValid = \filter_var($value, FILTER_VALIDATE_IP, ['flags' => $flags]) !== false ? true : false;
        }
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_IP_PRIVATE_RANGE_NOT_ALLOWED);
        }

        return $isValid;
    }

    /**
     * Validates private range
     *
     * @param string $value
     * @return bool
     */
    private function validateReservedRange(string $value = ''): bool
    {
        $isValid = true;
        if ($this->options['allow-reserved-range'] === false) {
            $flags = FILTER_FLAG_NO_RES_RANGE;
            $isValid = \filter_var($value, FILTER_VALIDATE_IP, ['flags' => $flags]) !== false ? true : false;
        }
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_IP_RESERVED_RANGE_NOT_ALLOWED);
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

        $validIP = $this->validateIPNumber();
        $validPrivRange = $this->validatePrivateRange();
        $validResRange = $this->validateReservedRange();

        return $validIP && $validPrivRange && $validResRange;
    }
}
