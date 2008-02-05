<?php

/*
+---------------------------------------------------------------------------+
| OpenX v${RELEASE_MAJOR_MINOR}                                                              |
| ======${RELEASE_MAJOR_MINOR_DOUBLE_UNDERLINE}                                                                 |
|                                                                           |
| Copyright (c) 2003-2008 m3 Media Services Ltd                             |
| For contact details, see: http://www.openx.org/                           |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id$
*/

require_once MAX_PATH . '/lib/OA.php';
require_once MAX_PATH . '/lib/OA/DB.php';
require_once MAX_PATH . '/lib/pear/Date.php';

/**
 * A class for testing the OA_DB class.
 *
 * @package    OpenXDB
 * @subpackage TestSuite
 * @author     Andrzej Swedrzynski <andrzej.swedrzynski@openx.org>
 * @author     Andrew Hill <andrew.hill@openx.org>
 */
class Test_OA_DB extends UnitTestCase
{

    /**
     * The constructor method.
     */
    function Test_OA_DB()
    {
        $this->UnitTestCase();
    }

    /**
     * Tests that the database type is setup in the config .ini file.
     */
    function testDbTypeDefined()
    {
        $aConf = $GLOBALS['_MAX']['CONF'];
        $this->assertNotNull($aConf['database']['type']);
        $this->assertNotEqual($aConf['database']['type'], '');
    }

    /**
     * Tests that the database host is setup in the config .ini file.
     */
    function testDbHostDefined()
    {
        $aConf = $GLOBALS['_MAX']['CONF'];
        $this->assertNotNull($aConf['database']['host']);
        $this->assertNotEqual($aConf['database']['host'], '');
    }

    /**
     * Tests that the database port is setup in the config .ini file.
     */
    function testDbPortDefined()
    {
        $aConf = $GLOBALS['_MAX']['CONF'];
        $this->assertNotNull($aConf['database']['port']);
        $this->assertNotEqual($aConf['database']['port'], '');
    }

    /**
     * Tests that the database user is setup in the config .ini file.
     */
    function testDbUserDefined()
    {
        $aConf = $GLOBALS['_MAX']['CONF'];
        $this->assertNotNull($aConf['database']['username']);
        $this->assertNotEqual($aConf['database']['username'], '');
    }

    /**
     * Tests that the database password is setup in the config .ini file.
     */
    function testDbPasswordDefined()
    {
        $aConf = $GLOBALS['_MAX']['CONF'];
        $this->assertNotNull($aConf['database']['password']);
    }

    /**
     * Tests that the database name is setup in the config .ini file.
     */
    function testDbNameDefined()
    {
        $aConf = $GLOBALS['_MAX']['CONF'];
        $this->assertNotNull($aConf['database']['name']);
        $this->assertNotEqual($aConf['database']['name'], '');
    }

    /**
     * Tests that the OpenX table prefix is setup in the config .ini file.
     */
    function testDbPrefixDefined()
    {
        $aConf = $GLOBALS['_MAX']['CONF'];
        $this->assertNotNull($aConf['table']['prefix']);
    }

    /**
     * Tests that the database connection can be made, without using the
     * Dal class - that is, that the details specified above are okay.
     */
    function testDbConnection()
    {
        $aConf = $GLOBALS['_MAX']['CONF'];
        $dbConnection = MDB2::singleton(
            strtolower($aConf['database']['type']) . '://' .
            $aConf['database']['username'] . ':' .  $aConf['database']['password'] .
            '@' . $aConf['database']['host'] . ':' . $aConf['database']['port'] . '/' .
            $aConf['database']['name']
        );
        $this->assertTrue($dbConnection);
    }

    /**
     * Tests that the singleton() method only ever returns one
     * database connection.
     */
    function testSingletonDbConnection()
    {
        $aConf =& $GLOBALS['_MAX']['CONF'];
        $firstConnection  =& OA_DB::singleton();
        $secondConnection =& OA_DB::singleton();
        $this->assertIdentical($firstConnection, $secondConnection);
        $this->assertReference($firstConnection, $secondConnection);
        TestEnv::restoreConfig();
    }

    function testSingleton()
    {
        $oDbh = OA_DB::singleton();
        $this->assertNotNull($oDbh);
        $this->assertFalse(PEAR::isError($oDbh));

        $dsn = "mysql://scott:tiger@non-existent-host:666/non-existent-database";
        OA::disableErrorHandling();
        $oDbh =& OA_DB::singleton($dsn);
        OA::enableErrorHandling();
        $this->assertNotNull($oDbh);
        $this->assertTrue(PEAR::isError($oDbh));
    }

}


?>