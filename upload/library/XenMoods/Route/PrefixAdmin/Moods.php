<?php
/**
 * Route prefix handler for moods in the admin control panel.
 *
 * @package XenMoods
 * @author Hanson Wong
 * @copyright (c) 2010 Hanson Wong
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

class XenMoods_Route_PrefixAdmin_Moods implements XenForo_Route_Interface
{
	/**
	 * Match a specific route for an already matched prefix.
	 *
	 * @see XenForo_Route_Interface::match()
	 */
	public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
	{
		$action = $router->resolveActionWithIntegerParam($routePath, $request, 'mood_id');
		return $router->getRouteMatch('XenMoods_ControllerAdmin_Mood', $action, 'moods');
	}

	/**
	 * Method to build a link to the specified page/action with the provided
	 * data and params.
	 *
	 * @see XenForo_Route_BuilderInterface
	 */
	public function buildLink($originalPrefix, $outputPrefix, $action, $extension, $data, array &$extraParams)
	{
		return XenForo_Link::buildBasicLinkWithIntegerParam($outputPrefix, $action, $extension, $data, 'mood_id', 'title');
	}
}