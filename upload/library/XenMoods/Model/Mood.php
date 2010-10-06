<?php
/**
 * Model for moods.
 *
 * @package XenMoods
 * @author Hanson Wong
 * @copyright (c) 2010 Hanson Wong
 * @license http://www.opensource.org/licenses/bsd-license.php
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
}