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
 * Class Alnum
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class Alnum extends AbstractSpecific
{
    const VALIDATOR_ERROR_ALNUM_NOT_VALID = 101;
    const VALIDATOR_ERROR_ALNUM_SPACES_NOT_VALID = 102;
    const VALIDATOR_ERROR_ALNUM_WHITESPACES_NOT_VALID = 103;
    const VALIDATOR_ERROR_ALNUM_SPACES_WHITESPACES_NOT_VALID = 104;

    protected $defaultOptions = [
        'allow-spaces'      => false,
        'allow-whitespaces' => false,
        'utf-8'             => true,
    ];

    /**
     * Alnum constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Checks whether string is valid alnum string
     *
     * @return bool
     */
    private function validateAlnum(): bool
    {
        $charClass = '[:alnum:]';
        if ($this->options['allow-spaces'] === true) {
            $charClass .= '[:space:]';
        }
        if ($this->options['allow-whitespaces'] === true) {
            $charClass .= '[:blank:]';
        }
        $modifier = '';
        if ($this->options['utf-8'] === true) {
            $modifier = 'u';
        }

        $isValid = \preg_match('/^['.$charClass.']+$/'.$modifier, $this->value) ? true : false;
        if (!$isValid) {
            $this->setError(self::VALIDATOR_ERROR_ALNUM_NOT_VALID);

            if ($this->options['allow-whitespaces'] === true && $this->options['allow-spaces'] === true) {
                $this->setError(self::VALIDATOR_ERROR_ALNUM_SPACES_WHITESPACES_NOT_VALID);
            } elseif ($this->options['allow-whitespaces'] === true) {
                $this->setError(self::VALIDATOR_ERROR_ALNUM_WHITESPACES_NOT_VALID);
            } elseif ($this->options['allow-spaces'] === true) {
                $this->setError(self::VALIDATOR_ERROR_ALNUM_SPACES_NOT_VALID);
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

        return $this->validateAlnum();
    }
}
