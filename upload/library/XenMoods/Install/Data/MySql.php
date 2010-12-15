<?php
/**
 * MySQL Schema for XenMoods installation.
 *
 * @package XenMoods
 */

class XenMoods_Install_Data_MySql
{
	/**
	 * Fetches the appropriate queries. This method can take a variable number
	 * of arguments, which will be passed on to the specific method.
	 *
	 * @param integer Version ID of queries to fetch
	 *
	 * @return array List of queries to run
	 * @return array Empty array if method does not exist
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
		is_default INT UNSIGNED NOT NULL DEFAULT 0,
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

	/**
	 * Schema definitions for version 2.
	 *
	 * @param string Mood images processed from XenMoods_Install::_getMoodImages()
	 * @param string Location of mood images to add into the system
	 *
	 * @return array List of queries to run
	 */
	protected static function _getQueriesVersion2($moodImages, $moodImageDir)
	{
		$queries = array();
		$insertSql = '';

		foreach ($moodImages AS $name => $path)
		{
			$insertSql .= "\n('{$name}', '{$moodImageDir}{$path}'),";
		}

		if (!empty($insertSql))
		{
			// removes last comma that would generate SQL exception
			$insertSql = substr($insertSql, 0, -1);

			$queries[] = "
	INSERT INTO xf_mood
		(title, image_url)
	VALUES
" . $insertSql;
		}

		return $queries;
	}

	/**
	 * Schema definitions for version 6.
	 *
	 * @return array List of queries to run
	 */
	protected static function _getQueriesVersion6()
	{
		$queries = array();

$queries[] = "
	INSERT INTO xf_content_type
		(content_type, addon_id, fields)
	VALUES
		('mood', 'xenmoods', '')
";

$queries[] = "
	INSERT INTO xf_content_type_field
		(content_type, field_name, field_value)
	VALUES
		('mood', 'news_feed_handler_class', 'XenMoods_NewsFeedHandler_Mood')
";

		return $queries;
	}
}
