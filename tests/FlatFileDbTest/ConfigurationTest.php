<?php

namespace FlatFileDbTest;

use FlatFileDb\Configuration;
use FlatFileDb\Exception\ConfigurationException;
use PHPUnit_Framework_TestCase;

class ConfigurationTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException ConfigurationException
     */
    public function testMissionDirectoryOption()
    {
        new Configuration(array());
    }

    /**
     * @expectedException FlatFileDbException
     */
    public function testIsNotDirectory()
    {
        new Configuration(array(
            'directory' => 'skyrim'
        ));
    }

}
