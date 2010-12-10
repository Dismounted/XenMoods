<?php
/**
 * XenMoods listener for template_hook code event.
 *
 * @package XenMoods
 */

class XenMoods_Listener_TemplateHook
{
	/**
	 * Initialise the code event
	 *
	 * @param string Name of the template hook
	 * @param string Contents of the hook block
	 * @param array Parameters passed to this code event
	 * @param XenForo_Template_Abstract
	 *
	 * @return void
	 */
	public static function init($name, &$contents, $params, XenForo_Template_Abstract $template)
	{
		new self($name, $contents, $params, $template);
	}

	/**
	 * Construct and execute code event.
	 *
	 * @param string Name of the template hook
	 * @param string Contents of the hook block
	 * @param array Parameters passed to this code event
	 * @param XenForo_Template_Abstract
	 *
	 * @return void
	 */
	protected function __construct($name, &$contents, $params, XenForo_Template_Abstract $template)
	{
		// mood display on quick reply and message view
		if ($name == 'message_user_info_avatar')
		{
			// check style property
			if (XenForo_Template_Helper_Core::styleProperty('threadShowMood') == FALSE)
			{
				return;
			}

			// generate the mood template
			$moodDisplay = $this->_getMoodTemplate($template, $params['user']);

			// add it to the master template
			$needle = '<!-- slot: message_user_info_avatar -->';
			$contents = str_replace($needle, $needle . $moodDisplay, $contents);
		}
	}

	/**
	 * Helper function to generate mood display.
	 *
	 * @param XenForo_Template_Abstract
	 * @param array Users details of the mood to be displayed
	 *
	 * @return string Compiled mood_display template
	 */
	protected function _getMoodTemplate(XenForo_Template_Abstract $template, $user)
	{
		$globalParams = $template->getParams();
		$model = $this->_getMoodModel();

		$params = array(
			'user' => $user,
			'visitor' => $globalParams['visitor'],
			'requestPaths' => $globalParams['requestPaths'],
			'moods' => $this->_getMoodData(),
			'defaultMoodId' => $model->getDefaultMoodId($this->_getMoodData()),
			'canViewMoods' => $model->canViewMoods(),
			'canHaveMood' => $model->canHaveMood()
		);

		return $template->create('mood_display', $params)->render();
	}

	/**
	 * Helper function to get moods from data registry.
	 *
	 * @return array List of moods
	 */
	protected function _getMoodData()
	{
		return XenForo_Application::get('moods');
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
}
