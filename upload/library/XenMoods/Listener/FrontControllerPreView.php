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
		
	}
}
