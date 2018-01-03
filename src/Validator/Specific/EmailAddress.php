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
 * Class EmailAddress
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class EmailAddress extends AbstractSpecific
{
    const VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT = 401;
    const VALIDATOR_ERROR_EMAIL_INVALID_DOMAIN = 402;
    const VALIDATOR_ERROR_EMAIL_INVALID_MX = 403;

    protected $defaultOptions = [
        'domain-check'  => true,
        'mx-check'      => false,
        'deep-mx-check' => false,
    ];

    /**
     * EmailAddress constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Checks whether host IP is private or reserved
     *
     * @param string $host
     * @return bool
     */
    private function isReserved(string $host): bool
    {
        $hosts = [$host];
        if (!\preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $host)) {
            $tmp = \gethostbynamel($host);
            $hosts = $tmp !== false ? $tmp : [];
        }

        $res = true;
        $flags = FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
        foreach ($hosts as $host) {
            if (\filter_var($host, FILTER_VALIDATE_IP, ['flags' => $flags]) !== false) {
                $res = false;
                break;
            }
        }

        return $res;
    }

    /**
     * Performs MX check
     *
     * @param string $value
     * @return bool
     */
    private function mxCheck(string $value = ''): bool
    {
        $mxHosts = [];
        $weight = [];

        if (@\getmxrr($value, $mxHosts, $weight) !== false) {
            if (!empty($mxHosts) && !empty($weight)) {
                $mxHosts = \array_combine($mxHosts, $weight);
            }
            \arsort($mxHosts);
        } elseif (($result = @\gethostbynamel($value)) !== false) {
            $mxHosts = \array_flip($result);
        }

        $isValid = true;
        if (empty($mxHosts)) {
            $isValid = false;
        } elseif ($this->options['deep-mx-check'] === true) {
            $isValid = $this->deepMxCheck($mxHosts);
        }
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_EMAIL_INVALID_MX);
        }

        return $isValid;
    }

    /**
     * Performs deep MX check
     *
     * @param array $value
     * @return bool
     */
    private function deepMxCheck(array $value = []): bool
    {
        $isValid = false;
        foreach ($value as $host => $weight) {
            $res = $this->isReserved($host);
            if (!$res && (\checkdnsrr($host, 'A') || \checkdnsrr($host, 'AAAA') || \checkdnsrr($host, 'A6'))) {
                $isValid = true;
                break;
            }
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

        if (\strpos($this->value, '..') !== false || !\preg_match('/^(.+)@([^@]+)$/', $this->value, $matches)) {
            $this->setError(self::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT);
            return false;
        }
        if (!\filter_var($this->value, FILTER_VALIDATE_EMAIL) !== false) {
            $this->setError(self::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT);
            return false;
        }
        $host = $matches[2];
        if ($this->options['domain-check'] === true && @\gethostbyname($host) === $host) {
            $this->setError(self::VALIDATOR_ERROR_EMAIL_INVALID_DOMAIN);
            return false;
        }
        if ($this->options['mx-check'] === true) {
            return $this->mxCheck($host);
        }

        return true;
    }
}
