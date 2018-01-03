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

use MS\LightFramework\Encryption\Password as PasswordEnctyption;


/**
 * Class Password
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class Password extends AbstractSpecific
{
    const VALIDATOR_ERROR_PASSWORD_INCORRECT_LENGTH = 1001;
    const VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_LETTER = 1002;
    const VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_LOWERCASE_LETTER = 1003;
    const VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_UPPERCASE_LETTER = 1004;
    const VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_NUMBER = 1005;
    const VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_SPECIAL_CHARACTER = 1006;
    const VALIDATOR_ERROR_PASSWORD_MUST_BE_DIFFERENT_THAN_PREVIOUS_ONE = 1007;
    const VALIDATOR_ERROR_PASSWORD_MUST_BE_DIFFERENT_THAN_PREVIOUS_ALL = 1008;

    protected $defaultOptions = [
        'min-length'                          => 10,
        'require-letter'                      => true,
        'require-lowercase-letter'            => true,
        'require-uppercase-letter'            => true,
        'require-number'                      => true,
        'require-special-character'           => true,
        'require-different-than-previous-one' => true,
        'require-different-than-previous-all' => false,
        'special-character-pattern'           => '[\`\!\@\#\$\%\^\&\*\(\)\_\+\-\=\{\}\[\]\\\:\"\;\'\,\.\/\<\>\?]',
        'stored-passwords'                    => [],
        'stored-salt'                         => '',
    ];

    /**
     * Password constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Validates password length
     *
     * @return bool
     */
    private function validateLength(): bool
    {
        if ($this->options['min-length'] > 0) {
            if (\mb_strlen((string)$this->value) <= $this->options['min-length']) {
                $this->setError(self::VALIDATOR_ERROR_PASSWORD_INCORRECT_LENGTH);
                return false;
            }
        }
        return true;
    }

    /**
     * Validates that password contains a letter
     *
     * @return bool
     */
    private function validateLetter(): bool
    {
        if ($this->options['require-letter'] === true) {
            if (!\preg_match('/[a-z]/iu', (string)$this->value)) {
                $this->setError(self::VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_LETTER);
                return false;
            }
        }
        return true;
    }

    /**
     * Validates that password contains a lowercase letter
     *
     * @return bool
     */
    private function validateLowercaseLetter(): bool
    {
        if ($this->options['require-lowercase-letter'] === true) {
            if (!\preg_match('/[a-z]/u', (string)$this->value)) {
                $this->setError(self::VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_LOWERCASE_LETTER);
                return false;
            }
        }
        return true;
    }

    /**
     * Validates that password contains an uppercase letter
     *
     * @return bool
     */
    private function validateUppercaseLetter(): bool
    {
        if ($this->options['require-uppercase-letter'] === true) {
            if (!\preg_match('/[A-Z]/u', (string)$this->value)) {
                $this->setError(self::VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_UPPERCASE_LETTER);
                return false;
            }
        }
        return true;
    }

    /**
     * Validates that password contains a number
     *
     * @return bool
     */
    private function validateNumber(): bool
    {
        if ($this->options['require-number'] === true) {
            if (!\preg_match('/[0-9]/', (string)$this->value)) {
                $this->setError(self::VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_NUMBER);
                return false;
            }
        }
        return true;
    }

    /**
     * Validates that password contains a special character
     *
     * @return bool
     */
    private function validateSpecialCharacter(): bool
    {
        if ($this->options['require-special-character'] === true) {
            if (!\preg_match('/'.$this->options['special-character-pattern'].'/', (string)$this->value)) {
                $this->setError(self::VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_SPECIAL_CHARACTER);
                return false;
            }
        }
        return true;
    }

    /**
     * Validates that password is different than previous one
     *
     * @return bool
     */
    private function validateDifferentPreviousOne(): bool
    {
        if ($this->options['require-different-than-previous-one'] === true) {
            if (isset($this->options['stored-passwords'][0])) {
                $passwordHash = PasswordEnctyption::hash((string)$this->value, $this->options['stored-salt']);
                if ($passwordHash['password'] === $this->options['stored-passwords'][0]) {
                    $this->setError(self::VALIDATOR_ERROR_PASSWORD_MUST_BE_DIFFERENT_THAN_PREVIOUS_ONE);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Validates that password is different than all previous passwords
     *
     * @return bool
     */
    private function validateDifferentPreviousAll(): bool
    {
        if ($this->options['require-different-than-previous-all'] === true) {
            $passwordHash = PasswordEnctyption::hash((string)$this->value, $this->options['stored-salt']);
            foreach ($this->options['stored-passwords'] as $storedPassword) {
                if ($passwordHash['password'] === $storedPassword) {
                    $this->setError(self::VALIDATOR_ERROR_PASSWORD_MUST_BE_DIFFERENT_THAN_PREVIOUS_ALL);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Validates value
     *
     * @param mixed $value
     * @param array $options
     * @return bool
     */
    public function isValid($value = null, array $options = []): bool
    {
        $this->setOptions($options);
        $this->setValue($value);
        $this->errors = [];

        $this->options['stored-passwords'] = array_reverse($this->options['stored-passwords']);

        $lengthValid = $this->validateLength();
        $letterValid = $this->validateLetter();
        $lowercaseLetterValid = $this->validateLowercaseLetter();
        $uppercaseLetterValid = $this->validateUppercaseLetter();
        $numberValid = $this->validateNumber();
        $specialCharacterValid = $this->validateSpecialCharacter();
        $differentPrevOneValid = $this->validateDifferentPreviousOne();
        $differentPrevAllValid = $this->validateDifferentPreviousAll();

        return $lengthValid
            && $letterValid
            && $lowercaseLetterValid
            && $uppercaseLetterValid
            && $numberValid
            && $specialCharacterValid
            && $differentPrevOneValid
            && $differentPrevAllValid;
    }
}
