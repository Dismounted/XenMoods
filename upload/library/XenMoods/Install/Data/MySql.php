<?php
/**
 * MySQL Schema for XenMoods installation.
 *
 * @package XenMoods
 */

class XenMoods_Install_Data_MySql
{
	/**
	 * The location of default mood images in the system. Include forward slash
	 * before and after path.
	 *
	 * @var string
	 */
	protected static $moodImageUrlBase = '/styles/default/xenmoods/';

	/**
	 * Fetches the appropriate queries.
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
		is_default INT UNSIGNED NOT NULL DEFAULT 0,
		PRIMARY KEY (mood_id)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
";

$queries[] = "
	ALTER TABLE xf_user ADD (
		mood_id INT UNSIGNED NOT NULL DEFAULT 0
	)
";



$queries[] = "
	INSERT INTO xf_mood
		(title, image_url)
	VALUES
" . self::_getMoodImageSql();

$queries[] = "
	UPDATE xf_mood
	SET is_default = 1
	WHERE mood_id = 1
";

		return $queries;
	}

	/**
	 * Creates the SQL insert values for mood images.
	 *
	 * @return string List of queries to run
	 */
	protected static function _getMoodImageSql()
	{
		$insertSql = '';
		$moodImages = self::_getMoodImages();
		$moodImageUrlBase = self::$moodImageUrlBase;

		foreach ($moodImages AS $name => $path)
		{
			$insertSql .= "('{$name}', '{$moodImageUrlBase}{$path}'),\n";
		}

		return $insertSql;
	}

	/**
	 * Fetches all the mood images uploaded into the default directory.
	 *
	 * @return array List of mood images uploaded
	 */
	protected static function _getMoodImages()
	{
		$moodImages = array();

		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(self::_getRootDir() . self::$moodImageUrlBase));
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
	 * @return array List of mood images uploaded
	 */
	protected static function _getRootDir()
	{
		return XenForo_Application::getRootDir();
	}
}
