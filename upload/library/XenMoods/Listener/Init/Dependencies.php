<?php
/**
 * XenMoods listener for init_dependencies code event.
 *
 * @package XenMoods
 */

class XenMoods_Listener_Init_Dependencies
{
	/**
	 * Initialise the code event
	 *
	 * @return void
	 */
	public static function init(XenForo_Dependencies_Abstract $dependencies, array $data)
	{
		new self($dependencies, $data);
	}

	/**
	 * Construct and execute code event.
	 *
	 * @param XenForo_Dependencies_Abstract Current dependencies object
	 * @param array Already pre-loaded data
	 * @return void
	 */
	protected function __construct(XenForo_Dependencies_Abstract $dependencies, array $data)
	{
		// only execute if we are a public-facing view
		if ($dependencies instanceof XenForo_Dependencies_Public)
		{
			$moods = $this->_loadMoodDataRegistry();

			if (!is_array($moods))
			{
				$moods = $this->_rebuildMoodCache();
			}
			XenForo_Application::set('moods', $moods);
		}
	}

	/**
	 * Load moods from XenForo data registry.
	 *
	 * @return array|null List of moods or null if no such entry exists
	 */
	protected function _loadMoodDataRegistry()
	{
		return XenForo_Model::create('XenForo_Model_DataRegistry')->get('moods');
	}

	/**
	 * Helper method to get the mood model.
	 *
	 * @return XenMoods_Model_Mood
	 */
	protected function _getMoodModel()
	{
		return XenForo_Model::create('XenMoods_Model_Mood');
	}

	/**
	 * Rebuilds mood cache and inserts it into the database.
	 *
	 * @return array Mood cache data
	 */
	protected function _rebuildMoodCache()
	{
		return $this->_getMoodModel()->rebuildMoodCache();
	}
}
