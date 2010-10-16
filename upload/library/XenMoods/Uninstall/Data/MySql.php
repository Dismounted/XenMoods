<?php
/**
 * MySQL uninstall queries for XenMoods.
 *
 * @package XenMoods
 */

class XenMoods_Uninstall_Data_MySql
{
	/**
	 * Fetches the appropriate queries. This method can take a variable number
	 * of arguments, which will be passed on to the specific method.
	 *
	 * @param integer Version ID of queries to fetch
	 *
	 * @return array List of queries to run
	 * @return void Nothing if called method doesn't exist
	 */
	public static function getQueries($version)
	{
		$method = '_getQueriesVersion' . (int)$version;
		if (method_exists(__CLASS__, $method) === false)
		{
			return array();
		}

		$args = func_get_args();
		$args = array_shift($args);

		return call_user_func_array(array(__CLASS__, $method), $args);
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
