<?php

use FlatFileDb\Exception\Exception;
use FlatFileDb\Exception\ConfigurationException;

ini_set('error_reporting', E_ALL);

chdir(__DIR__);

require_once __DIR__ . '/../vendor/autoload.php';

class_alias(ConfigurationException::class, 'ConfigurationException');
class_alias(Exception::class, 'FlatFileDbException');