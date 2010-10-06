<?php
/**
 * Performs the uninstallation actions for XenMoods.
 *
 * Uninstall methods are designated in the format _uninstallVersionX, where X is
 * the version ID of which that install code applies.
 *
 * @package XenMoods
 * @author Hanson Wong
 * @copyright (c) 2010 Hanson Wong
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

class XenMoods_Uninstall
{
	/**
	* Instance manager.
	*
	* @var XenMoods_Uninstall
	*/
	private static $_instance;

	/**
	* Database object
	*
	* @var Zend_Db_Adapter_Abstract
	*/
	protected $_db;

	/**
	* Gets the uninstaller instance.
	*
	* @return XenMoods_Uninstall
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
	 * Begins the uninstallation process and runs uninstall routines.
	 *
	 * @param array Information about the (now uninstalled) add-on
	 * @return void
	 */
	public static function uninstall($addOnData)
	{
		// opposite of install!
		$startVersionId = $addOnData['version_id'];
		$endVersionId = 1;

		// create our uninstall object
		$uninstall = self::getInstance();

		for ($i = $startVersionId; $i >= $endVersionId; $i--)
		{
			$method = '_uninstallVersion' . $i;
			if (method_exists($uninstall, $method) === false)
			{
				continue;
			}

			$uninstall->$method();
		}
	}

	/**
	 * Uninstall routine for version ID 1.
	 *
	 * @return void
	 */
	protected function _uninstallVersion1()
	{
		$db = $this->_getDb();

		$queries = XenMoods_Uninstall_Data_MySql::getQueries(1);
		foreach ($queries AS $query)
		{
			$db->query($query);
		}
	}
}