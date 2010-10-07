<?php
/**
 * Code event listeners for XenMoods dependencies.
 *
 * @package XenMoods
 */

class XenMoods_Listener_Dependencies
{
	/**
	 * Listener for init_dependencies code event.
	 *
	 * @param XenForo_Dependencies_Abstract Current dependencies object
	 * @param array Already pre-loaded data
	 * @return void
	 */
	public static function initDependencies(XenForo_Dependencies_Abstract $dependencies, array $data)
	{
		if ($dependencies instanceof XenForo_Dependencies_Public)
		{
			// pre-load moods for use
			$moods = XenForo_Model::create('XenForo_Model_DataRegistry')->get('moods');

			if (!is_array($moods))
			{
				$moods = XenForo_Model::create('XenMoods_Model_Mood')->rebuildMoodCache();
			}
			XenForo_Application::set('moods', $moods);
		}
	}
}
