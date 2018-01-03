<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Session;

use MS\LightFramework\Base;
use MS\LightFramework\Variables\Variables;


/**
 * Class Integrity
 *
 * @package MS\LightFramework\Session
 */
final class Integrity
{
    private static $initialized = false;
    private static $serverName;
    private static $refererIntegrityEnabled;
    private static $tokenIntegrityEnabled;
    private static $tokenUseLooseIP;
    private static $tokenUseHttpUserAgent;
    private static $tokenUseHttpAccept;
    private static $tokenUseHttpAcceptCharset;
    private static $tokenUseHttpAcceptEncoding;
    private static $tokenUseHttpAcceptLanguage;

    /**
     * Sets static class variables
     */
    private static function init(): void
    {
        if (!static::$initialized) {
            $baseClass = Base::getInstance();
            static::$initialized = true;
            static::$serverName = (string)$baseClass->getServerName();
            static::$refererIntegrityEnabled = (bool)$baseClass->Session->RefererIntegrity->enabled;
            static::$tokenIntegrityEnabled = (bool)$baseClass->Session->TokenIntegrity->enabled;
            static::$tokenUseLooseIP = (bool)$baseClass->Session->TokenIntegrity->useLooseIP;
            static::$tokenUseHttpUserAgent = (bool)$baseClass->Session->TokenIntegrity->useHttpUserAgent;
            static::$tokenUseHttpAccept = (bool)$baseClass->Session->TokenIntegrity->useHttpAccept;
            static::$tokenUseHttpAcceptCharset = (bool)$baseClass->Session->TokenIntegrity->useHttpAcceptCharset;
            static::$tokenUseHttpAcceptEncoding = (bool)$baseClass->Session->TokenIntegrity->useHttpAcceptEncoding;
            static::$tokenUseHttpAcceptLanguage = (bool)$baseClass->Session->TokenIntegrity->useHttpAcceptLanguage;
        }
    }

    /**
     * Returns user's loose IP
     *
     * @return string
     */
    private static function getLooseIP(): string
    {
        return md5(
            static::$tokenUseLooseIP && isset($_SERVER['REMOTE_ADDR'])
                ? \long2ip(\ip2long($_SERVER['REMOTE_ADDR']) & \ip2long('255.255.0.0'))
                : ''
        );
    }

    /**
     * Returns HTTP_USER_AGENT variable according to configuration
     *
     * @return string
     */
    private static function getHttpUserAgent(): string
    {
        return \md5(
            static::$tokenUseHttpUserAgent && isset($_SERVER['HTTP_USER_AGENT'])
                ? $_SERVER['HTTP_USER_AGENT']
                : ''
        );
    }

    /**
     * Returns HTTP_ACCEPT variable according to configuration
     *
     * @return string
     */
    private static function getHttpAccept(): string
    {
        return \md5(
            static::$tokenUseHttpAccept && isset($_SERVER['HTTP_ACCEPT'])
                ? $_SERVER['HTTP_ACCEPT']
                : ''
        );
    }

    /**
     * Returns HTTP_ACCEPT_ENCODING variable according to configuration
     *
     * @return string
     */
    private static function getHttpAcceptEncoding(): string
    {
        return \md5(
            static::$tokenUseHttpAcceptEncoding && isset($_SERVER['HTTP_ACCEPT_ENCODING'])
                ? $_SERVER['HTTP_ACCEPT_ENCODING']
                : ''
        );
    }

    /**
     * Returns HTTP_ACCEPT_LANGUAGE variable according to configuration
     *
     * @return string
     */
    private static function getHttpAcceptLanguage(): string
    {
        return \md5(
            static::$tokenUseHttpAcceptLanguage && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
                ? $_SERVER['HTTP_ACCEPT_LANGUAGE']
                : ''
        );
    }

    /**
     * Returns HTTP_ACCEPT_CHARSET variable according to configuration
     *
     * @return string
     */
    private static function getHttpAcceptCharset(): string
    {
        return \md5(
            static::$tokenUseHttpAcceptCharset && isset($_SERVER['HTTP_ACCEPT_CHARSET'])
                ? $_SERVER['HTTP_ACCEPT_CHARSET']
                : ''
        );
    }

    /**
     * Returns session integrity token based on session configuration
     *
     * @return string
     */
    private static function buildToken(): string
    {
        return \md5(
            \sprintf(
                'MF_SESSION_INTEGRITY_TOKEN:%s%s%s%s%s%s',
                static::getLooseIP(),
                static::getHttpUserAgent(),
                static::getHttpAccept(),
                static::getHttpAcceptEncoding(),
                static::getHttpAcceptLanguage(),
                static::getHttpAcceptCharset()
            )
        );
    }

    /**
     * Sets session integrity token according to session configuration
     *
     * @param string $variableName
     * @return bool
     */
    public static function setToken(string $variableName = '_MF_SESSION_INTEGRITY_TOKEN_'): bool
    {
        static::init();
        $variableName = (string)$variableName;
        $vHandler = Variables::getInstance();
        $vHandler->session->set($variableName, static::buildToken());
        return true;
    }

    /**
     * Checks session integrity according to session configuration
     *
     * @param string $variableName
     * @return bool
     */
    public static function check(string $variableName = '_MF_SESSION_INTEGRITY_TOKEN_'): bool
    {
        static::init();
        $variableName = (string)$variableName;
        $vHandler = Variables::getInstance();
        $userToken = $vHandler->session->get($variableName, $vHandler::TYPE_STRING);
        $httpReferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $rIntegrity = static::$refererIntegrityEnabled ? \stripos($httpReferer, static::$serverName) !== false : true;
        $tIntegrity = static::$tokenIntegrityEnabled ? $userToken === static::buildToken() : true;
        return $rIntegrity && $tIntegrity;
    }
}
