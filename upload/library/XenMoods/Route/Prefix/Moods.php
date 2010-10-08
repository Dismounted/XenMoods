<?php
/**
 * Route prefix handler for moods in the front-end UI (e.g. mood chooser).
 *
 * @package XenMoods
 */

class XenMoods_Route_Prefix_Moods implements XenForo_Route_Interface
{
	/**
	 * Match a specific route for an already matched prefix.
	 *
	 * @see XenForo_Route_Interface::match()
	 */
	public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
	{
		return $router->getRouteMatch('XenMoods_ControllerPublic_Mood', $routePath, 'moods');
	}
}
