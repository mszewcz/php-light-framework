<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Session\Backend;

use MS\LightFramework\Encryption\Mcrypt;
use MS\LightFramework\Filesystem\Directory;


/**
 * Class Filesystem
 *
 * @package MS\LightFramework\Session\Backend
 */
final class Filesystem implements \SessionHandlerInterface
{
    private $savePath;

    /**
     * Opens session
     *
     * @param string $savePath
     * @param string $sessionName
     * @return bool
     */
    public function open($savePath, $sessionName): bool
    {
        $this->savePath = $savePath;
        Directory::create($this->savePath);
        return true;
    }

    /**
     * Closes session
     *
     * @return bool
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Reads session data
     *
     * @param string $sessionID
     * @return string
     */
    public function read($sessionID): string
    {
        $file = $this->savePath.DIRECTORY_SEPARATOR.'sess_'.$sessionID;
        if (\file_exists($file)) {
            $handle = @\fopen($file, 'rb');
            if ($handle) {
                @\flock($handle, LOCK_SH);
                $fileSize = @\filesize($file);
                $sessionData = $fileSize == 0 ? '' : @\fread($handle, $fileSize);
                @\flock($handle, LOCK_UN);
                @\fclose($handle);
                return Mcrypt::decrypt($sessionData);
            }
        }
        return '';
    }

    /**
     * Writes session data
     *
     * @param string $sessionID
     * @param string $sessionData
     * @return bool
     */
    public function write($sessionID, $sessionData): bool
    {
        $result = false;
        $sessionData = Mcrypt::encrypt($sessionData);
        $file = $this->savePath.DIRECTORY_SEPARATOR.'sess_'.$sessionID;
        $handle = @\fopen($file, 'wb');
        if ($handle) {
            @\flock($handle, LOCK_EX);
            $result = @\fwrite($handle, $sessionData) !== false ? true : false;
            @\flock($handle, LOCK_UN);
            @\fclose($handle);
        }
        @\chmod($file, 0600);
        return $result;
    }

    /**
     * Destroys session
     *
     * @param string $sessionID
     * @return bool
     */
    public function destroy($sessionID): bool
    {
        $file = $this->savePath.DIRECTORY_SEPARATOR.'sess_'.$sessionID;
        $ret = \file_exists($file) ? @\unlink($file) : true;
        return $ret;
    }

    /**
     * Garbages expired sessions
     *
     * @param int $maxLifeTime
     * @return bool
     */
    public function gc($maxLifeTime = 1440): bool
    {
        foreach (\glob($this->savePath.DIRECTORY_SEPARATOR.'sess_*') as $file) {
            if (\file_exists($file) && \filemtime($file) + $maxLifeTime < \time()) {
                @\unlink($file);
            }
        }
        return true;
    }
}
