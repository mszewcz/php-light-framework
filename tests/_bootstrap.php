<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

ob_start();

$_ENV['CONFIG_FILE_FRAMEWORK'] = \realpath(\dirname(__DIR__)).'/src/_config_framework.json';

require_once \realpath(\dirname(__FILE__).'/../').'/vendor/autoload.php';
