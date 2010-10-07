<?php
/**
 * MySQL uninstall queries for XenMoods.
 *
 * @package XenMoods
 */

class XenMoods_Uninstall_Data_MySql
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
		$method = '_getQueriesVersion' . (int) $version;
		if (method_exists(__CLASS__, $method))
		{
			return;
		}

		return self::$method();
	}

	/**
	 * Uninstall queries for version 1.
	 *
	 * @return array List of queries to run
	 */
	protected static function _getQueriesVersion1()
	{
		$queries = array();
$queries[] = "
	DROP TABLE xf_mood
";

$queries[] = "
	ALTER TABLE xf_user
	DROP mood_id
";

		return $queries;
	}
}
