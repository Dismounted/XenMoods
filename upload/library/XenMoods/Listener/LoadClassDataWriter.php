<?php
/**
 * XenMoods listener for load_class_datawriter code event.
 *
 * @package XenMoods
 */

class XenMoods_Listener_LoadClassDataWriter
{
	/**
	 * Initialise the code event.
	 *
	 * @param string The name of the class to be created
	 * @param array A modifiable list of classes that wish to extend the class.
	 *
	 * @return void
	 */
	public static function init($class, array &$extend)
	{
		// extend with XenMoods-specific methods
		if ($class == 'XenForo_DataWriter_User')
		{
			$extend[] = 'XenMoods_XFCP_DataWriter_User';
		}
	}
}
