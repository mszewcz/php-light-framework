<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Variables\Specific;

use MS\LightFramework\Exceptions\BadMethodCallException;
use MS\LightFramework\Exceptions\RuntimeException;
use MS\LightFramework\Variables\Variables;


/**
 * Class Files
 *
 * @package MS\LightFramework\Variables\Specific
 */
final class Files extends AbstractReadOnly
{
    private static $instance;
    private $variables = [];

    /**
     * This method returns class instance.
     *
     * @return Files
     */
    public static function getInstance(): Files
    {
        if (!isset(static::$instance)) {
            $class = __CLASS__;
            static::$instance = new $class;
        }
        return static::$instance;
    }

    /**
     * Files constructor.
     */
    private function __construct()
    {
        $tmp = $_FILES;
        foreach ($tmp as $file => $data) {
            foreach ($data as $varName => $varVal) {
                if (\is_array($varVal)) {
                    foreach ($varVal as $multiFile => $multiVal) {
                        $this->variables[$file][$multiFile][$varName] = $multiVal;
                    }
                }
                if (!\is_array($varVal)) {
                    $this->variables[$file][$varName] = $varVal;
                }
            }
        }
        if (\count($this->variables) > 0 && \extension_loaded('fileinfo')) {
            $this->addMimeInfo();
        }
    }

    /**
     * Class destructor
     */
    public function __destruct()
    {
        $this->variables = [];
    }

    /**
     * __clone overload
     */
    public function __clone()
    {
        throw new RuntimeException('Clone of '.__CLASS__.' is not allowed');
    }

    /**
     * Adds MIME information to files
     */
    private function addMimeInfo(): void
    {
        $finfoMimeType = new \finfo(FILEINFO_MIME_TYPE);
        $finfoMimeEncoding = new \finfo(FILEINFO_MIME_ENCODING);

        foreach ($this->variables as $file => $data) {
            foreach ($data as $varName => $varVal) {
                if (\is_array($varVal)) {
                    $this->variables[$file][$varName]['encoding'] = '';

                    if (isset($this->variables[$file][$varName]['error']) &&
                        $this->variables[$file][$varName]['error'] === 0) {

                        $finfoFile = $this->variables[$file][$varName]['tmp_name'];
                        $this->variables[$file][$varName]['type'] = $finfoMimeType->file($finfoFile);
                        $this->variables[$file][$varName]['encoding'] = $finfoMimeEncoding->file($finfoFile);
                    }
                }
                if (!\is_array($varVal)) {
                    $this->variables[$file]['encoding'] = '';

                    if (isset($this->variables[$file]['error']) && $this->variables[$file]['error'] === 0) {
                        $finfoFile = $this->variables[$file]['tmp_name'];
                        $this->variables[$file]['type'] = $finfoMimeType->file($finfoFile);
                        $this->variables[$file]['encoding'] = $finfoMimeEncoding->file($finfoFile);
                    }
                }
            }
        }
    }

    /**
     * Returns FILES variable's value. If variable doesn't exist method returns default value for specified type.
     *
     * @param string|null $variableName
     * @param int         $type
     * @return array|mixed
     */
    public function get(string $variableName = null, int $type = Variables::TYPE_ARRAY)
    {
        if ($variableName === null) {
            throw new BadMethodCallException('Variable name must be specified');
        }
        if (isset($this->variables['MFVARS'][$variableName])) {
            return $this->variables['MFVARS'][$variableName];
        }
        if (isset($this->variables[$variableName])) {
            return $this->variables[$variableName];
        }
        return [];
    }

    /**
     * Returns all FILES variables
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->variables;
    }
}
