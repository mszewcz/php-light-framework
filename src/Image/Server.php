<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Image;

use MS\LightFramework\Base;
use MS\LightFramework\Filesystem\File;


/**
 * Class Server
 *
 * @package MS\LightFramework\Image
 */
final class Server
{
    private $baseClass;
    private $uriParams = [];
    private $serverProtocol = 'HTTP/1.1';
    private $allowedResizeParams = ['w', 'h', 'gs'];

    /**
     * Server constructor.
     */
    public function __construct()
    {
        $this->baseClass = Base::getInstance();
        $this->serverProtocol = $this->baseClass->getServerProtocol();

        $this->parseRequestUri();
        if ($this->baseClass->Image->Server->forceGrayScale === true) {
            $this->uriParams['resize-params']['gs'] = true;
        }
        $this->serveImage();
    }

    /**
     * Parses request uri
     */
    private function parseRequestUri(): void
    {
        $requestUri = $this->baseClass->getRequestUri();
        $path = \explode('/', $requestUri);
        $pathCnt = \count($path);
        if ($pathCnt > 0) {
            $file = \array_pop($path);
            $path = \sprintf('%%DOCUMENT_ROOT%%%s%s', \implode(DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
            $path = $this->baseClass->parsePath($path);

            $file = \explode('.', $file);
            $ext = '';

            if (\count($file) > 1) {
                $ext = \array_pop($file);
            }

            $file = \explode('__', \implode('.', $file));
            $resizeParams = [];

            if (\count($file) > 1) {
                $resizeParams = $this->parseResizeParams(\explode('_', $file[1]));
            }

            $file = $file[0];

            $this->uriParams['path'] = $path;
            $this->uriParams['file'] = $file;
            $this->uriParams['extension'] = $ext;
            $this->uriParams['resize-params'] = $resizeParams;
        }
    }

    /**
     * Parses resize params
     *
     * @param array $params
     * @return array
     */
    private function parseResizeParams(array $params = []): array
    {
        $resizeParams = [];
        foreach ($params as $param) {
            \preg_match('/^('.\implode('|', $this->allowedResizeParams).')([0-9]+)$/', $param, $matches);
            if (isset($matches[1]) && isset($matches[2])) {
                $resizeParams[$matches[1]] = (int)$matches[2];

                if ($matches[1] == 'gs') {
                    $resizeParams[$matches[1]] = ((int)$matches[2] === 1);
                }
            }
        }
        return $resizeParams;
    }

    /**
     * Returns resize params string
     *
     * @return string
     */
    private function getResizeParamsString(): string
    {
        $ret = '';
        foreach ($this->uriParams['resize-params'] as $param => $value) {
            $ret .= \sprintf('_%s%s', $param, $value);
        }
        return $ret !== '' ? \sprintf('_%s', $ret) : '';
    }

    /**
     * Returns proper content type form image
     *
     * @return string
     */
    private function getImageContentType(): string
    {
        switch ($this->uriParams['extension']) {
            case 'jpg':
            case 'jpeg':
                $contentType = 'image/jpeg';
                break;
            case 'gif':
                $contentType = 'image/gif';
                break;
            case 'png':
            default:
                $contentType = 'image/png';
                break;
        }

        return $contentType;
    }

    /**
     * Sends caching headers
     *
     * @param string $imagePath
     */
    private function sendCachingHeaders(string $imagePath = ''): void
    {
        $ifModifiedSince = '';
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $ifModifiedSince = \preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
        }

        $mdate = \gmdate('D, d M Y H:i:s', \filemtime($imagePath)).' GMT';
        if ($ifModifiedSince == $mdate) {
            \header($this->serverProtocol.' 304 Not Modified', true, 304);
            die();
        }

        \header($this->serverProtocol.' 200 OK', true, 200);
        \header('Last-Modified: '.$mdate);

        $maxAge = $this->baseClass->Image->Server->cacheTime;
        $maxAgeHeader = 'Cache-Control: no-transform,public,max-age='.$maxAge;
        if ($maxAge == 0) {
            $maxAgeHeader = 'Cache-Control: max-age=0, no-cache, no-store';
        }

        \header($maxAgeHeader);
        \header('Expires: '.\gmdate('D, d M Y H:i:s', \time() + $maxAge).' GMT');
    }

    /**
     * Reads image from disk and outputs it
     *
     * @param string $imagePath
     */
    private function outputImage(string $imagePath = ''): void
    {
        $fPointer = \fopen($imagePath, 'rb');
        $this->sendCachingHeaders($imagePath);
        \header('Content-Type: '.$this->getImageContentType());
        \header('Content-Length: '.\filesize($imagePath));
        \fpassthru($fPointer);
        \fclose($fPointer);
        die();
    }

    /**
     * Serves image.
     * If no REQUEST_URI found sends 500 Internal Server Error header. If no image is found sends 404 Not found header.
     * If image needs resizing or grayscale converting - it applies transformations and returns it,
     * otherwise returns original one.
     *
     * @return void
     */
    private function serveImage(): void
    {
        if (empty($this->uriParams)) {
            \header($this->serverProtocol.' 500 Internal Server Error', true, 500);
            die();
        }

        $requestFilePath = \sprintf(
            '%s%s%s.%s',
            $this->uriParams['path'],
            $this->uriParams['file'],
            $this->getResizeParamsString(),
            $this->uriParams['extension']
        );
        $originalFilePath = \sprintf(
            '%s%s.%s',
            $this->uriParams['path'],
            $this->uriParams['file'],
            $this->uriParams['extension']
        );

        if (File::exists($requestFilePath)) {
            $this->outputImage($requestFilePath);
        }
        if (File::exists($originalFilePath)) {
            if ($this->baseClass->Image->Server->checkRefererBeforeResize === true) {
                if (!\preg_match('|^'.$this->baseClass->getServerName().'|i', $this->baseClass->getReferer())) {
                    \header($this->serverProtocol.' 403 Forbidden', true, 403);
                    die();
                }
            }

            $options = $this->uriParams['resize-params'];
            $options['quality'] = $this->baseClass->Image->Resizer->defaultQuality;

            try {
                Resizer::resize($originalFilePath, $requestFilePath, $options);
            } catch (\Exception $e) {
                \header($this->serverProtocol.' 500 Internal Server Error', true, 500);
                die();
            }
            $this->outputImage($requestFilePath);
        }

        \header($this->serverProtocol.' 404 Not Found', true, 404);
        die();
    }
}
