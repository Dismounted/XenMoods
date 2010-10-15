<?php
/**
 * Extends the default model to provide mood methods.
 *
 * @package XenMoods
 */

class XenMoods_XFCP_Model_User extends XFCP_XenMoods_XFCP_Model_User
{
	/**
	 * Disassociates users from a specific mood. It moves all those users onto the default.
	 *
	 * @param integer Mood ID
	 *
	 * @return void
	 */
	public function disassociateMood($moodId)
	{
		$this->_getDb()->query('
			UPDATE xf_user
			SET mood_id = 0
			WHERE mood_id = ?
		', $moodId);
	}
}
