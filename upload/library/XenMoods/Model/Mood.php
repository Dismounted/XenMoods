<?php
/**
 * Model for moods.
 *
 * @package XenMoods
 */

class XenMoods_Model_Mood extends XenForo_Model
{
	/**
	 * Gets the named mood by ID.
	 *
	 * @param integer $moodId
	 *
	 * @return array|false
	 */
	public function getMoodById($moodId)
	{
		return $this->_getDb()->fetchRow('
			SELECT *
			FROM xf_mood
			WHERE mood_id = ?
		', $moodId);
	}

	/**
	 * Gets the named mood by image URL.
	 *
	 * @param string $moodImageUrl
	 *
	 * @return array|false
	 */
	public function getMoodByUrl($moodImageUrl)
	{
		return $this->_getDb()->fetchRow('
			SELECT *
			FROM xf_mood
			WHERE image_url = ?
		', $moodImageUrl);
	}

	/**
	 * Gets all moods ordered by their title.
	 *
	 * @return array Format: [mood id] => info
	 */
	public function getAllMoods()
	{
		return $this->fetchAllKeyed('
			SELECT *
			FROM xf_mood
			ORDER BY title
		', 'mood_id');
	}

	/**
	 * Get the mood data needed for the mood cache.
	 *
	 * @return array Format: [mood id] => info
	 */
	public function getAllMoodsForCache()
	{
		return $this->getAllMoods();
	}

	/**
	 * Rebuilds the mood cache.
	 *
	 * @return array Mood cache
	 */
	public function rebuildMoodCache()
	{
		$moods = $this->getAllMoodsForCache();
		$this->_getDataRegistryModel()->set('moods', $moods);

		return $moods;
	}

	/**
	 * Deletes the mood cache. Mainly used for uninstallation.
	 *
	 * @return void
	 */
	public function deleteMoodCache()
	{
		$this->_getDataRegistryModel()->delete('moods');
	}

	/**
	 * Fetches the default mood's ID from a list of moods supplied. If a list is
	 * not supplied, the moods will be fetched from the database directly.
	 *
	 * @param array List of moods, should contain as much data as getAllMoodsForCache()
	 *
	 * @return integer|false Mood ID on success, false on failure
	 */
	public function getDefaultMoodId($moods = null)
	{
		if ($moods === null)
		{
			$moods = $this->getAllMoodsForCache();
		}

		foreach ($moods AS $mood)
		{
			if ($mood['is_default'])
			{
				return $mood['mood_id'];
			}
		}

		return false;
	}

	/**
	 * Fetches the default mood's URL from a list of moods supplied. If a list is
	 * not supplied, the moods will be fetched from the database directly.
	 *
	 * @param array List of moods, should contain as much data as getAllMoodsForCache()
	 *
	 * @return integer|false Mood ID on success, false on failure
	 */
	public function getDefaultMoodUrl($moods = null)
	{
		if ($moods === null)
		{
			$moods = $this->getAllMoodsForCache();
		}

		$moodId = $this->getDefaultMoodId($moods);

		if (isset($moods[$moodId]['image_url']))
		{
			return $moods[$moodId]['image_url'];
		}

		return false;
	}

	/**
	 * Checks and sets to make sure there is only one default mood.
	 *
	 * @param integer The new default mood's ID
	 *
	 * @return void
	 */
	public function checkDefaultIsLone($moodId)
	{
		$this->_getDb()->query('
			UPDATE xf_mood
			SET is_default = 0
			WHERE mood_id <> ?
		', $moodId);
	}

	/**
	 * Determines if moods can be viewed with the given permissions. If no
	 * permissions are specified, permissions are retrieved from the currently
	 * visiting user.
	 *
	 * @param array Information about the inquiring user
	 *
	 * @return boolean
	 */
	public function canViewMoods(array $viewingUser = null)
	{
		$this->standardizeViewingUserReference($viewingUser);

		if (XenForo_Permission::hasPermission($viewingUser['permissions'], 'mood', 'view'))
		{
			return true;
		}

		return false;
	}

	/**
	 * Determines if a user can have a mood with the given permissions. If no
	 * permissions are specified, permissions are retrieved from the currently
	 * visiting user.
	 *
	 * @param array Information about the inquiring user
	 *
	 * @return boolean
	 */
	public function canHaveMood(array $viewingUser = null)
	{
		$this->standardizeViewingUserReference($viewingUser);

		if (XenForo_Permission::hasPermission($viewingUser['permissions'], 'mood', 'have'))
		{
			return true;
		}

		return false;
	}
}