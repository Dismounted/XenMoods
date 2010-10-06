<?php
/**
 * MySQL Schema for XenMoods. CREATE and ALTER queries cannot be run via the
 * XenForo database object.
 *
 * @package XenMoods
 * @author Hanson Wong
 * @copyright (c) 2010 Hanson Wong
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

class XenMoods_Install_Data_MySql
{
	/**
	 * Fetches the appropriate queries.
	 *
	 * @param integer Version ID of queries to fetch
	 * @return array List of queries to run
	 * @return void Nothing if called method doesn't exist
	 */
	public static function getQueries($version)
	{
		$method = '_getQueriesVersion' . (int)$version;
		if (method_exists(__CLASS__, $method) === false)
		{
			return;
		}

		return self::$method();
	}

	/**
	 * Schema definitions for version 1.
	 *
	 * @return array List of queries to run
	 */
	protected static function _getQueriesVersion1()
	{
		$queries = array();

$queries[] = "
	CREATE TABLE xf_mood (
		mood_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
		title VARCHAR(50) NOT NULL,
		image_url VARCHAR(200) NOT NULL,
		PRIMARY KEY (mood_id)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
";

$queries[] = "
	ALTER TABLE xf_user ADD (
		mood_id INT UNSIGNED NOT NULL DEFAULT 0
	)
";

		return $queries;
	}
}
