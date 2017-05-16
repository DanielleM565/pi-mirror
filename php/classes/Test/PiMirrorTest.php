<?php
namespace Edu\Cnm\PiMirror\Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\Dataset\QueryDataSet;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\Operation\{Composite, Factory, Operation};

// grab the encrypted properties file

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

require_once(dirname(__DIR__) . "/vendor/autoload.php");

/**
* Abstract class containing universal and project specific mySQL parameters
 *
 * This class is designed to lay the foundation of the unit tests per project. It loads all the database parameters about the project so that table specific tests can share the parameters in o place. To use it:
 *
 * 1. Rename the class from PiMirrorTest to a project specific name (e.g., ProjectNameTest)
 * 2. Rename the namespace to be the same as in (1) (e.g., Edu\Cnm\ProjectName\Test)
 * 3. Modify PiMirrorTest::getDataSet() to include all the tables in your project.
 * 4. Modify PiMirrorTest::getConnection() to include the correct mySQL properties file.
 * 5. Have all table specific tests include this class.
 *
 * *NOTE*: Tables must be added in the order they were created in step (2).
 *
 * @authoer Shihlin Lu <slu5@cnm.edu>
 **/

abstract class PiMirrorTest extends TestCase {
	use TestCaseTrait;

	/**
	 * invalid id to use for an INT UNSIGNED field (maximum allowed INT UNSIGNED in mySQL) + 1
	 * @see https://dev.mysql.com/doc/refman/5.6/en/integer-types.html mySQL Integer Types
	 * @var int INVALID_KEY
	 **/
	const INVALID_KEY = 4294967296;

	/**
	 * PHPUnit database connection interface
	 * @var Connection $connection
	 **/
	protected $connection = null;

	/**
	 * assembles the table from the schema and provides it to PHPUnit
	 *
	 * @return QueryDataSet assembled schema for PHPUnit
	 **/
	public final function getDataSet() {
		$dataset = new QueryDataSet($this->getConnection());

		// add all the tables for the project here
		// THESE TABLES *MUST* BE LISTED IN THE SAME ORDER THEY WERE CREATED!
		$dataset->addTable("sensor");
		$dataset->addTable("reading");
		return ($dataset);
	}
	/**
	 * templates the setUp method that runs before each test; this method expunges the database before each run
	 *
	 *
	 **/
}


/**
 * valid location y to use
 *
 * code: protected $VALID_SENSORVALUE = 123456789101112.123456
 */