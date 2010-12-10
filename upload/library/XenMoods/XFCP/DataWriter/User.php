<?php
/**
 * Extends the default user data writer to add mood field.
 *
 * @package XenMoods
 */

class XenMoods_XFCP_DataWriter_User extends XFCP_XenMoods_XFCP_DataWriter_User
{
	/**
	 * Gets the fields that are defined for the table. See parent for explanation.
	 *
	 * @return array
	 */
	protected function _getFields()
	{
		$fields = parent::_getFields();
		$fields['xf_user']['mood_id'] = array(
			'type' => self::TYPE_UINT,
			'default' => 0,
			'verification' => array('XenMoods_DataWriter_Helper_Mood', 'verifyMoodId')
		);

		return $fields;
	}
}
