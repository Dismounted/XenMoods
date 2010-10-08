<?php
/**
 * Mood data writer helper.
 *
 * @package XenMoods
 */

class XenMoods_DataWriter_Helper_Mood
{
	/**
	 * Verifies that the provided integer is a valid mood ID
	 *
	 * @param integer $moodId
	 * @param XenForo_DataWriter The current running data writer
	 * @param string Field being affected
	 *
	 * @return boolean
	 */
	public static function verifyMoodId($moodId, XenForo_DataWriter $dw, $fieldName = false)
	{
		if ($moodId === 0)
		{
			// explicitly set to 0, use system default
			return true;
		}

		if ($dw->getModelFromCache('XenMoods_Model_Mood')->getMoodById($moodId))
		{
			return true;
		}

		$dw->error(new XenForo_Phrase('please_select_valid_mood'), $fieldName);
		return false;
	}
}