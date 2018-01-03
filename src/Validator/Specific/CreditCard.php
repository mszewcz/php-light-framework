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
 * Class CreditCard
 *
 * @package MS\LightFramework\Validator\Specific
 */
final class CreditCard extends AbstractSpecific
{
    const VALIDATOR_ERROR_CREDIT_CARD_NUMBER_PREFIX_NOT_MATCH = 201;
    const VALIDATOR_ERROR_CREDIT_CARD_NUMBER_LENGTH_NOT_MATCH = 202;
    const VALIDATOR_ERROR_CREDIT_CARD_NUMBER_NOT_VALID = 203;

    protected $defaultOptions = [
        'type' => 'any',
    ];
    private $validationData = [
        'american-express'          => [
            'length'   => [15],
            'prefixes' => ['34', '37'],
        ],
        'diners-club'               => [
            'length'   => [14],
            'prefixes' => ['300', '301', '302', '303', '304', '305'],
        ],
        'diners-club-international' => [
            'length'   => [14],
            'prefixes' => ['300', '301', '302', '303', '304', '305', '309', '36', '38', '39'],
        ],
        'diners-club-us'            => [
            'length'   => [16],
            'prefixes' => ['54', '55'],
        ],
        'discover'                  => [
            'length'   => [16],
            'prefixes' => [
                '6011', '622126', '622127', '622128', '622129', '62213', '62214', '62215', '62216', '62217', '62218',
                '62219', '6222', '6223', '6224', '6225', '6226', '6227', '6228', '62290', '62291', '622920', '622921',
                '622922', '622923', '622924', '622925', '644', '645', '646', '647', '648', '649', '65',
            ],
        ],
        'jcb'                       => [
            'length'   => [16],
            'prefixes' => ['3528', '3529', '353', '354', '355', '356', '357', '358'],
        ],
        'laser'                     => [
            'length'   => [16, 17, 18, 19],
            'prefixes' => ['6304', '6706', '6771', '6709'],
        ],
        'maestro'                   => [
            'length'   => [12, 13, 14, 15, 16, 17, 18, 19],
            'prefixes' => [
                '0604', '5018', '5020', '5038', '5612', '5893', '6304', '6390', '6759', '6761', '6762', '6763', '6764',
                '6765', '6766', '6767', '6769', '6771', '6773', '6775',
            ],
        ],
        'mastercard'                => [
            'length'   => [16],
            'prefixes' => ['51', '52', '53', '54', '55'],
        ],
        'solo'                      => [
            'length'   => [16, 18, 19],
            'prefixes' => ['6334', '6767'],
        ],
        'unionpay'                  => [
            'length'   => [16, 17, 18, 19],
            'prefixes' => [
                '622126', '622127', '622128', '622129', '62213', '62214', '62215', '62216', '62217', '62218', '62219',
                '6222', '6223', '6224', '6225', '6226', '6227', '6228', '62290', '62291', '622920', '622921', '622922',
                '622923', '622924', '622925',
            ],
        ],
        'visa'                      => [
            'length'   => [13, 16],
            'prefixes' => ['4'],
        ],
        'visa-electron'             => [
            'length'   => [16],
            'prefixes' => ['4026', '417500', '4405', '4508', '4844', '4913', '4917'],
        ],
    ];

    /**
     * CreditCard constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Validates credit card type
     *
     * @param array $types
     * @return bool
     */
    private function validateType(array $types): bool
    {
        $value = $this->value;
        $length = \strlen($value);
        $foundPrefix = false;
        $foundLength = false;
        foreach ($types as $type) {
            foreach ($this->validationData[$type]['prefixes'] as $prefix) {
                if (\substr($value, 0, \strlen($prefix)) == $prefix) {
                    $foundPrefix = true;
                    if (\in_array($length, $this->validationData[$type]['length'])) {
                        $foundLength = true;
                        break 2;
                    }
                }
            }
        }
        if (!$foundPrefix) {
            $this->setError(self::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_PREFIX_NOT_MATCH);
        }
        if (!$foundLength) {
            $this->setError(self::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_LENGTH_NOT_MATCH);
        }
        return $foundPrefix === true && $foundLength === true;
    }

    /**
     * Validates credit card number
     *
     * @return bool
     */
    private function validateNumber(): bool
    {
        $value = $this->value;
        $length = \strlen($value);
        $sum = 0;
        $weight = 2;

        if ($length < 2) {
            $this->setError(self::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_NOT_VALID);
            return false;
        }

        for ($i = $length - 2; $i >= 0; $i--) {
            $digit = $weight * $value[$i];
            $sum += \floor($digit / 10) + $digit % 10;
            $weight = $weight % 2 + 1;
        }

        if ((10 - $sum % 10) % 10 != $value[$length - 1]) {
            $this->setError(self::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_NOT_VALID);
            return false;
        }
        return true;
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

        if ($this->options['type'] == 'any') {
            $types = \array_keys($this->validationData);
        } elseif (\array_key_exists($this->options['type'], $this->validationData)) {
            $types = [$this->options['type']];
        } else {
            throw new DomainException('Unsopported credit card type: '.$this->options['type']);
        }

        $validType = $this->validateType($types);
        $validNumber = $this->validateNumber();

        return $validType && $validNumber;
    }
}
