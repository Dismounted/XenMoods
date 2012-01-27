<?php
/**
 * XenMoods listener for template_hook code event.
 *
 * @package XenMoods
 */

class XenMoods_Listener_TemplateHook
{
	/**
	 * Temporary cache of mood displays.
	 *
	 * @var array
	 */
	protected static $_cache;

	/**
	 * Initialise the code event.
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
		// add mood displays around the place
		switch ($name)
		{
			case 'sidebar_visitor_panel_stats':
				self::_addMoodDisplay(
					$template,
					'',
					$contents,
					$template->getParam('visitor'),
					'sidebarShowMood',
					FALSE
				);
				break;

			case 'member_card_links':
				self::_addMoodDisplay(
					$template,
					'',
					$contents,
					$template->getParam('user'),
					'memberCardShowMood'
				);
				break;

			case 'member_view_info_block':
				self::_addMoodDisplay(
					$template,
					'',
					$contents,
					$template->getParam('user'),
					'profileShowMood',
					TRUE,
					'mood_display_member_view'
				);
				break;

			case 'message_user_info_avatar':
				self::_addMoodDisplay(
					$template,
					'<!-- slot: message_user_info_avatar -->',
					$contents,
					$params['user'],
					'threadShowMood',
					FALSE
				);
				break;
		}
	}

	/**
	 * Helper function to add mood display into hook.
	 *
	 * @param XenForo_Template_Abstract
	 * @param string Needle to hook on to (empty for pure pre/append)
	 * @param string Contents of the hook block
	 * @param array User of the mood to be displayed
	 * @param string Style property to check (null to disable check)
	 * @param boolean Set to true to prepend, false to append
	 * @param string Override mood display template
	 *
	 * @return void
	 */
	protected static function _addMoodDisplay(XenForo_Template_Abstract $template, $needle, &$contents, $user, $styleProperty = NULL, $prepend = TRUE, $templateName = NULL)
	{
		// check style property
		if (isset($styleProperty) AND XenForo_Template_Helper_Core::styleProperty($styleProperty) == FALSE)
		{
			return;
		}

		// generate the mood template
		$moodDisplay = self::_getMoodTemplate($template, $user, $templateName);

		// pure prepend/append
		if (empty($needle))
		{
			// do a bit of flip-flopping
			if ($prepend)
			{
				$contents = $moodDisplay . $contents;
			}
			else
			{
				$contents = $contents . $moodDisplay;
			}

			return;
		}

		// do more flip-flopping!
		if ($prepend)
		{
			$replace = $moodDisplay . $needle;
		}
		else
		{
			$replace = $needle . $moodDisplay;
		}

		// add it to the master template
		$contents = str_replace($needle, $replace, $contents);
	}

	/**
	 * Helper function to generate mood display.
	 *
	 * @param XenForo_Template_Abstract
	 * @param array User of the mood to be displayed
	 * @param string Mood display template name
	 *
	 * @return string Compiled mood display template
	 */
	protected static function _getMoodTemplate(XenForo_Template_Abstract $template, $user, $templateName = 'mood_display')
	{
		// happens when we don't set a value in self::_addMoodDisplay
		if (empty($templateName))
		{
			$templateName = 'mood_display';
		}

		// quick and dirty cache prevents rendering a user more than once per page load
		if (isset(self::$_cache[$templateName][$user['user_id']]))
		{
			return self::$_cache[$templateName][$user['user_id']];
		}

		$globalParams = $template->getParams();
		$model = self::_getMoodModel();

		$params = array(
			'user' => $user,
			'visitor' => $globalParams['visitor'],
			'requestPaths' => $globalParams['requestPaths'],
			'moods' => self::_getMoodData(),
			'defaultMoodId' => $model->getDefaultMoodId(self::_getMoodData()),
			'canViewMoods' => $model->canViewMoods(),
			'canHaveMood' => $model->canHaveMood()
		);

		// render and add to our makeshift cache
		$display = $template->create($templateName, $params)->render();
		self::$_cache[$templateName][$user['user_id']] = $display;

		return $display;
	}

	/**
	 * Helper function to get moods from data registry.
	 *
	 * @return array List of moods
	 */
	protected static function _getMoodData()
	{
		return XenForo_Application::get('moods');
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
}
