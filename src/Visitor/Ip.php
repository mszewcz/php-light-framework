<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);


namespace MS\LightFramework\Visitor;


/**
 * Class Ip
 *
 * @package MS\LightFramework\Visitor
 */
class Ip
{
    private static $ipData = [
        'CLIENT_IP'      => null,
        'CLIENT_HOST'    => null,
        'CLIENT_IP_HOST' => null,
        'PROXY_IP'       => null,
        'PROXY_HOST'     => null,
        'PROXY_IP_HOST'  => null,
        'PROXY_STRING'   => null,
        'FULL_IP_HOST'   => null,
        'HTTP_VIA'       => null,
    ];

    /**
     * Check and returns proxy string
     *
     * @return null|string
     */
    private static function getProxyString(): ?string
    {
        $proxyString = null;
        if (\getenv('HTTP_CLIENT_IP')) {
            $proxyString = \getenv('HTTP_CLIENT_IP');
        } elseif (\getenv('HTTP_X_FORWARDED_FOR')) {
            $proxyString = \getenv('HTTP_X_FORWARDED_FOR');
        } elseif (\getenv('HTTP_X_FORWARDED')) {
            $proxyString = \getenv('HTTP_X_FORWARDED');
        } elseif (\getenv('HTTP_X_COMING_FROM')) {
            $proxyString = \getenv('HTTP_X_COMING_FROM');
        } elseif (\getenv('HTTP_FORWARDED_FOR')) {
            $proxyString = \getenv('HTTP_FORWARDED_FOR');
        } elseif (\getenv('HTTP_FORWARDED')) {
            $proxyString = \getenv('HTTP_FORWARDED');
        } elseif (\getenv('HTTP_COMING_FROM')) {
            $proxyString = \getenv('HTTP_COMING_FROM');
        }
        return $proxyString;
    }

    /**
     * Returns hostname matching provided IP address
     *
     * @param string $ipAddress
     * @return string
     */
    private static function getHost($ipAddress = ''): string
    {
        return ($ipAddress !== '') ? @\gethostbyaddr($ipAddress) : 'unknown';
    }

    /**
     * Checks for first IP in proxy string and returns it
     *
     * @param string $proxyString
     * @return null|string
     */
    private static function getFirstIP($proxyString = ''): ?string
    {
        \preg_match('/^(([0-9]{1,3}\.){3}[0-9]{1,3})/', $proxyString, $matches);
        return (\is_array($matches) && isset($matches[1])) ? $matches[1] : null;
    }

    /**
     * Returns full ip host string
     *
     * @param null $clientIpHost
     * @param null $proxyIpHost
     * @return null|string
     */
    private static function getFullIpHost($clientIpHost = null, $proxyIpHost = null): ?string
    {
        $fullIpHost = [];
        if ($clientIpHost !== null) {
            $fullIpHost[] = \sprintf('client: %s', $clientIpHost);
        }
        if ($proxyIpHost !== null) {
            $fullIpHost[] = \sprintf('proxy: %s', $proxyIpHost);
        }

        return (\count($fullIpHost) > 0) ? \implode(', ', $fullIpHost) : null;
    }

    /**
     * Checks for visitor IP address, proxy address and returns them
     *
     * @return  array
     */
    public static function check(): array
    {
        $remoteAddr = \getenv('REMOTE_ADDR') ?: null;
        $httpVia = \getenv('HTTP_VIA') ?: null;
        $proxyString = static::getProxyString();
        $ipData = static::$ipData;

        if ($remoteAddr !== null) {
            if ($proxyString !== null) {
                $clientIP = static::getFirstIP($proxyString);
                $proxyIP = $remoteAddr;
                $proxyHost = static::getHost($proxyIP);

                if ($clientIP !== null) {
                    $clientHost = static::getHost($clientIP);
                    $ipData['CLIENT_IP'] = $clientIP;
                    $ipData['CLIENT_HOST'] = $clientHost;
                    $ipData['CLIENT_IP_HOST'] = \sprintf('%s (%s)', $clientIP, $clientHost);
                }
                $ipData['PROXY_IP'] = $proxyIP;
                $ipData['PROXY_HOST'] = $proxyHost;
                $ipData['PROXY_IP_HOST'] = \sprintf('%s (%s)', $proxyIP, $proxyHost);
                $ipData['PROXY_STRING'] = $proxyString;
            } elseif ($httpVia !== null) {
                $proxyHost = static::getHost($remoteAddr);

                $ipData['PROXY_IP'] = $remoteAddr;
                $ipData['PROXY_HOST'] = $proxyHost;
                $ipData['PROXY_IP_HOST'] = \sprintf('%s (%s)', $remoteAddr, $proxyHost);
                $ipData['HTTP_VIA'] = $httpVia;
            } else {
                $clientHost = static::getHost($remoteAddr);

                $ipData['CLIENT_IP'] = $remoteAddr;
                $ipData['CLIENT_HOST'] = $clientHost;
                $ipData['CLIENT_IP_HOST'] = \sprintf('%s (%s)', $remoteAddr, $clientHost);
            }

            $ipData['FULL_IP_HOST'] = static::getFullIpHost($ipData['CLIENT_IP_HOST'], $ipData['PROXY_IP_HOST']);
        }

        return $ipData;
    }
}
