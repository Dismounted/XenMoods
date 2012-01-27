<?php
/**
 * XenMoods listener for init_dependencies code event.
 *
 * @package XenMoods
 */

class XenMoods_Listener_InitDependencies
{
	/**
	 * Initialise the code event.
	 *
	 * @param XenForo_Dependencies_Abstract
	 * @param array Already pre-loaded data
	 *
	 * @return void
	 */
	public static function init(XenForo_Dependencies_Abstract $dependencies, array $data)
	{
		// only execute if we are a public-facing view
		if ($dependencies instanceof XenForo_Dependencies_Public)
		{
			$moods = self::_loadMoodDataRegistry();

			if (!is_array($moods))
			{
				$moods = self::_rebuildMoodCache();
			}
			XenForo_Application::set('moods', $moods);
		}
	}

	/**
	 * Load moods from XenForo data registry.
	 *
	 * @return array|null List of moods or null if no such entry exists
	 */
	protected static function _loadMoodDataRegistry()
	{
		return XenForo_Model::create('XenForo_Model_DataRegistry')->get('moods');
	}

	/**
	 * Helper method to get the mood model.
	 *
	 * @return XenMoods_Model_Mood
	 */
	protected static function _getMoodModel()
	{
		return XenForo_Model::create('XenMoods_Model_Mood');
	}

	/**
	 * Rebuilds mood cache and inserts it into the database.
	 *
	 * @return array Mood cache data
	 */
	protected static function _rebuildMoodCache()
	{
		return self::_getMoodModel()->rebuildMoodCache();
	}
}
