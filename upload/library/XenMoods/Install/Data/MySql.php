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
		$args = array_shift($args);

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
	 * @param string Mood images directory
	 *
	 * @return array List of queries to run
	 */
	protected static function _getQueriesVersion2($moodImageUrlBase)
	{
		$queries = array();
		$moodImages = self::_getMoodImages($moodImageUrlBase);
		$moodImageSql = self::_getMoodImageSql($moodImages, $moodImageUrlBase);

		if (!empty($moodImageSql))
		{
$queries[] = "
	INSERT INTO xf_mood
		(title, image_url)
	VALUES
" . $moodImageSql;
		}

		return $queries;
	}

	/**
	 * Creates the SQL insert values for mood images.
	 *
	 * @param array List of mood images to generate SQL for
	 * @param string Mood images directory, not required
	 *
	 * @return string List of queries to run
	 */
	protected static function _getMoodImageSql($moodImages, $moodImageUrlBase = '')
	{
		$insertSql = '';

		foreach ($moodImages AS $name => $path)
		{
			$insertSql .= "\n('{$name}', '{$moodImageUrlBase}{$path}'),";
		}

		if (!empty($insertSql))
		{
			$insertSql = substr($insertSql, 0, -1);
		}

		return $insertSql;
	}

	/**
	 * Fetches all the mood images uploaded into the default directory.
	 *
	 * @param string Mood images directory
	 *
	 * @return array List of mood images uploaded
	 */
	protected static function _getMoodImages($moodImageUrlBase)
	{
		$moodImages = array();

		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(self::_getRootDir() . '/' . $moodImageUrlBase));
		while ($it->valid())
		{
			if (!$it->isDot())
			{
				$moodName = pathinfo($it->key(), PATHINFO_FILENAME);
				$moodImages[$moodName] = $it->getSubPathName();
			}

			$it->next();
		}

		return $moodImages;
	}

	/**
	 * Fetches the XenForo root directory.
	 *
	 * @return string Root path
	 */
	protected static function _getRootDir()
	{
		return XenForo_Application::getInstance()->getRootDir();
	}
}
