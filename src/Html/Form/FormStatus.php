<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Html\Form;

use MS\LightFramework\Exceptions\InvalidArgumentException;
use MS\LightFramework\Html\Tags;


/**
 * Class FormStatus
 *
 * @package MS\LightFramework\Html\Form
 */
class FormStatus extends Element
{
    const STATUS_OK = 'status-ok';
    const STATUS_ERROR = 'status-error';

    private $statusData = [];

    /**
     * FormStatus constructor.
     *
     * @param array $statusData
     */
    public function __construct(array $statusData = [])
    {
        if (!\array_key_exists('status-type', $statusData)) {
            throw new InvalidArgumentException('FormStatus requires "status-type" in $statusData');
        }
        if (!\array_key_exists('status-text', $statusData)) {
            throw new InvalidArgumentException('FormStatus requires "status-text" in $statusData');
        }
        $this->statusData = $statusData;
    }

    /**
     * Generates FormStatus and returns it
     *
     * @return string
     */
    public function generate(): string
    {
        $status = Tags::NBSP;
        if ($this->statusData['status-type'] !== '' && $this->statusData['status-text'] !== '') {
            $status = [
                Tags::div(
                    $this->getConstant($this->statusData['status-text']),
                    ['class' => $this->getConstant($this->statusData['status-type'])]
                )
            ];
        }
        return Tags::div($status, ['class' => 'status']);
    }
}
