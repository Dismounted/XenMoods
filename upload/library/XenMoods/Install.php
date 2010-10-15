<?php
/**
 * Performs the installation actions for XenMoods.
 *
 * Install methods are designated in the format _installVersionX, where X is the
 * version ID of which that install code applies.
 *
 * @package XenMoods
 */

class XenMoods_Install
{
	/**
	 * Instance manager.
	 *
	 * @var XenMoods_Install
	 */
	private static $_instance;

	/**
	 * Database object
	 *
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_db;

	/**
	 * The location of default mood images in the system. Include forward slash
	 * after path only.
	 *
	 * @var string
	 */
	protected $moodImageUrlBase = 'styles/default/xenmoods/';

	/**
	 * Gets the installer instance.
	 *
	 * @return XenMoods_Install
	 */
	public static final function getInstance()
	{
		if (!self::$_instance)
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Helper method to get the database object.
	 *
	 * @return Zend_Db_Adapter_Abstract
	 */
	protected function _getDb()
	{
		if ($this->_db === null)
		{
			$this->_db = XenForo_Application::get('db');
		}

		return $this->_db;
	}

	/**
	 * Begins the installation process and picks the proper install routine.
	 *
	 * See see XenForo_Model_Addon::installAddOnXml() for more details about
	 * the arguments passed to this method.
	 *
	 * @param array Information about the existing version (if upgrading)
	 * @param array Information about the current version being installed
	 *
	 * @return void
	 */
	public static function install($existingAddOn, $addOnData)
	{
		// the version IDs from which we should start/end the install process
		$startVersionId = 1;
		$endVersionId = $addOnData['version_id'];

		if ($existingAddOn)
		{
			// we are upgrading, run every install method since last upgrade
			$startVersionId = $existingAddOn['version_id'] + 1;
		}

		// create our install object
		$install = self::getInstance();

		for ($i = $startVersionId; $i <= $endVersionId; $i++)
		{
			$method = '_installVersion' . $i;
			if (method_exists($install, $method) === false)
			{
				continue;
			}

			$install->$method();
		}

		// rebuild caches
		XenForo_Model::create('XenMoods_Model_Mood')->rebuildMoodCache();
	}

	/**
	 * Install routine for version ID 1 (first version!).
	 *
	 * @return void
	 */
	protected function _installVersion1()
	{
		$db = $this->_getDb();

		$queries = XenMoods_Install_Data_MySql::getQueries(1);
		foreach ($queries AS $query)
		{
			$db->query($query);
		}
	}

	/**
	 * Install routine for version ID 2.
	 *
	 * @return void
	 */
	protected function _installVersion2()
	{
		$db = $this->_getDb();

		// fetch existing moods, result is used later
		$existingMoods = $this->_getMoodModel()->getAllMoods();

		$queries = XenMoods_Install_Data_MySql::getQueries(2, $this->_getMoodImageUrlBase());
		foreach ($queries AS $query)
		{
			$db->query($query);
		}

		// fetch new moods
		$newMoods = $this->_getMoodModel()->getAllMoods();

		// if there are no existing moods, but new, we need to make one default
		if (empty($existingMoods) AND !empty($newMoods))
		{
			$dw = XenForo_DataWriter::create('XenMoods_DataWriter_Mood');
			$dw->setExistingData(1);
			$dw->set('is_default', true);
			$dw->save();
		}
	}

	/**
	 * Install routine for version ID 3.
	 *
	 * @return void
	 */
	protected function _installVersion3()
	{
		$moodNoMoodPath = $this->_getMoodImageUrlBase() . 'No Mood.png';
		$moodNoMoodData = $this->_getMoodModel()->getMoodByUrl($moodNoMoodPath);
		if (empty($moodNoMoodData))
		{
			// this mood was added in this version, if it has been uploaded, add it
			if (file_exists($this->_getRootDir() . '/' . $moodNoMoodPath))
			{
				$dw = $this->_getMoodDataWriter();
				$dw->set('title', 'No Mood');
				$dw->set('image_url', $moodNoMoodPath);
				$dw->set('is_default', true);
				$dw->save();
			}
		}
		else
		{
			// it is already there, probably added by version ID 2 install
			// make it default
			$dw = $this->_getMoodDataWriter();
			$dw->setExistingData($moodNoMoodData['mood_id']);
			$dw->set('is_default', true);
			$dw->save();
		}
	}

	/**
	 * @return XenMoods_Model_Mood
	 */
	protected function _getMoodModel()
	{
		return XenForo_Model::create('XenMoods_Model_Mood');
	}

	/**
	 * @return XenMoods_DataWriter_Mood
	 */
	protected function _getMoodDataWriter()
	{
		return XenForo_DataWriter::create('XenMoods_DataWriter_Mood');
	}

	/**
	 * Fetches the XenForo root directory.
	 *
	 * @return array Root directory path
	 */
	protected function _getRootDir()
	{
		return XenForo_Application::getInstance()->getRootDir();
	}

	/**
	 * Fetches the mood images root directory.
	 *
	 * @return string Mood images directory
	 */
	protected function _getMoodImageUrlBase()
	{
		return $this->moodImageUrlBase;
	}
}
