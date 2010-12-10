<?php
/**
 * XenMoods listener for load_class_datawriter code event.
 *
 * @package XenMoods
 */

class XenMoods_Listener_LoadClassDataWriter
{
	/**
	 * Initialise the code event
	 *
	 * @param string The name of the class to be created
	 * @param array A modifiable list of classes that wish to extend the class.
	 *
	 * @return void
	 */
	public static function init($class, array &$extend)
	{
		new self($class, $extend);
	}

	/**
	 * Construct and execute code event.
	 *
	 * @param string The name of the class to be created
	 * @param array A modifiable list of classes that wish to extend the class.
	 *
	 * @return void
	 */
	protected function __construct($class, array &$extend)
	{
		if ($class == 'XenForo_DataWriter_User')
		{
			$this->_extendUserDataWriter($extend);
		}
	}

	/**
	 * Extends XenForo_DataWriter_User with XenMoods-specific methods.
	 *
	 * @param array A modifiable list of classes that wish to extend the class.
	 *
	 * @return void
	 */
	protected function _extendUserDataWriter(array &$extend)
	{
		$extend[] = 'XenMoods_XFCP_DataWriter_User';
	}
}
