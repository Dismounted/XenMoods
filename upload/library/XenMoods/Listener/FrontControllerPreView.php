<?php
/**
 * XenMoods listener for front_controller_pre_view code event.
 *
 * @package XenMoods
 */

class XenMoods_Listener_FrontControllerPreView
{
	/**
	 * Initialise the code event.
	 *
	 * @param XenForo_FrontController
	 * @param XenForo_ControllerResponse_Abstract
	 * @param XenForo_ViewRenderer_Abstract
	 * @param array Parameters used to help prepare the container
	 *
	 * @return void
	 */
	public static function init(XenForo_FrontController $fc, XenForo_ControllerResponse_Abstract &$controllerResponse, XenForo_ViewRenderer_Abstract &$viewRenderer, array &$containerParams)
	{
		// only execute if we are a public-facing view
		// assumes init_dependencies listener runs correctly!
		if ($controllerResponse instanceof XenForo_ControllerResponse_View AND XenForo_Application::isRegistered('moods'))
		{
			$moodModel = self::_getMoodModel();
			$params = array(
				'moods' => self::_getMoodData(),
				'defaultMoodId' => $moodModel->getDefaultMoodId(self::_getMoodData()),
				'canViewMoods' => $moodModel->canViewMoods(),
				'canHaveMood' => $moodModel->canHaveMood()
			);

			$controllerResponse->params = array_merge($controllerResponse->params, $params);
			$containerParams = array_merge($containerParams, $params);
		}
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
