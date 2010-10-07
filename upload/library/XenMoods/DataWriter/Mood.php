<?php
/**
 * Data writer for moods.
 *
 * @package XenMoods
 */

class XenMoods_DataWriter_Mood extends XenForo_DataWriter
{
	/**
	 * Title of the phrase that will be created when a call to set the
	 * existing data fails (when the data doesn't exist).
	 *
	 * @var string
	 */
	protected $_existingDataErrorPhrase = 'requested_mood_not_found';

	/**
	 * Gets the fields that are defined for the table. See parent for explanation.
	 *
	 * @return array
	 */
	protected function _getFields()
	{
		return array(
			'xf_mood' => array(
				'mood_id' => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
				'title' => array('type' => self::TYPE_STRING, 'required' => true, 'maxLength' => 50,
						'requiredError' => 'please_enter_valid_title'
				),
				'image_url' => array('type' => self::TYPE_STRING, 'required' => true, 'maxLength' => 200,
						'requiredError' => 'please_enter_valid_url'
				)
			)
		);
	}

	/**
	 * Gets the actual existing data out of data that was passed in. See parent for explanation.
	 *
	 * @param mixed
	 *
	 * @return array|false
	 */
	protected function _getExistingData($data)
	{
		if (!$id = $this->_getExistingPrimaryKey($data))
		{
			return false;
		}

		return array('xf_mood' => $this->_getMoodModel()->getMoodById($id));
	}

	/**
	 * Gets SQL condition to update the existing record.
	 *
	 * @return string
	 */
	protected function _getUpdateCondition($tableName)
	{
		return 'mood_id = ' . $this->_db->quote($this->getExisting('mood_id'));
	}

	/**
	 * Post-save handling.
	 */
	protected function _postSave()
	{
		$this->_rebuildMoodCache();
	}

	/**
	 * Post-delete handling.
	 */
	protected function _postDelete()
	{
		$this->_rebuildMoodCache();
	}

	/**
	 * Rebuilds the mood cache.
	 */
	protected function _rebuildMoodCache()
	{
		$this->_getMoodModel()->rebuildMoodCache();
	}

	/**
	 * @return XenForo_Model_Mood
	 */
	protected function _getMoodModel()
	{
		return $this->getModelFromCache('XenMoods_Model_Mood');
	}
}