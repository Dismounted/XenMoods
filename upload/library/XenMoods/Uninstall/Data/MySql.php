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
		$args = array_slice($args, 1);

		if (!is_array($args))
		{
			$args = array();
		}

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

	/**
	 * Uninstall queries for version 6.
	 *
	 * @return array List of queries to run
	 */
	protected static function _getQueriesVersion6()
	{
		$queries = array();

$queries[] = "
	DELETE FROM xf_content_type
	WHERE content_type = 'mood'
";

$queries[] = "
	DELETE FROM xf_content_type_field
	WHERE content_type = 'mood'
";

$queries[] = "
	DELETE FROM xf_news_feed
	WHERE content_type = 'mood'
";

		return $queries;
	}
}
