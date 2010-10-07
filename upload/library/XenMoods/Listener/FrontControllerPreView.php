<?php
/**
 * XenMoods listener for front_controller_pre_view code event.
 *
 * @package XenMoods
 */

class XenMoods_Listener_FrontControllerPreView
{
	/**
	 * Initialise the code event
	 *
	 * @param XenForo_FrontController
	 * @param XenForo_ControllerResponse_Abstract
	 * @param XenForo_ViewRenderer_Abstract
	 * @param array Parameters used to help prepare the container
	 * @return void
	 */
	public static function init(XenForo_FrontController $fc, XenForo_ControllerResponse_Abstract &$controllerResponse, XenForo_ViewRenderer_Abstract &$viewRenderer, array &$containerParams)
	{
		new self($fc, $controllerResponse, $viewRenderer, $containerParams);
	}

	/**
	 * Construct and execute code event.
	 *
	 * @param XenForo_FrontController
	 * @param XenForo_ControllerResponse_Abstract
	 * @param XenForo_ViewRenderer_Abstract
	 * @param array Parameters used to help prepare the container
	 * @return void
	 */
	protected function __construct(XenForo_FrontController $fc, XenForo_ControllerResponse_Abstract &$controllerResponse, XenForo_ViewRenderer_Abstract &$viewRenderer, array &$containerParams)
	{
		// only execute if we are a public-facing view, very hackish!
		if (strpos($controllerResponse->viewName, 'XenForo_ViewPublic') !== false)
		{
			$controllerResponse->params['moods'] = $this->_getMoodData();
		}
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
}
