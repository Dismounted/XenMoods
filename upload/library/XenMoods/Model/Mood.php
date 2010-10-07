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
		return $this->fetchAllKeyed('
			SELECT mood_id, title, image_url
			FROM xf_mood
			ORDER BY mood_id
		', 'mood_id');
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
	 * Determines if moods can be viewed with the given permissions. If no
	 * permissions are specified, permissions are retrieved from the currently
	 * visiting user.
	 *
	 * @param array Information about the inquiring user
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