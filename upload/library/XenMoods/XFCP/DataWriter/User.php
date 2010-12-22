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

	/**
	 * Post-save handling.
	 */
	protected function _postSave()
	{
		parent::_postSave();

		// publish events to news feed
		if ($this->isUpdate())
		{
			$this->_publishIfMoodChanged();

			//TODO: check when core can handle custom fields
		}
	}

	/**
	 * Wrapper around _publish. Will publish mood changes.
	 */
	protected function _publishIfMoodChanged()
	{
		if ($this->isChanged('mood_id') && $newValue = $this->get('mood_id'))
		{
			// (nearly) always better to get data from the cache
			$moods = $this->getModelFromCache('XenForo_Model_DataRegistry')->get('moods');
			if (!is_array($moods))
			{
				$moods = $this->getModelFromCache('XenMoods_Model_Mood')->getAllMoods();
			}

			// moods may change in the future, so get the titles now
			$oldValue = $this->getExisting('mood_id');
			$oldMood = $newMood = array();

			if (isset($moods[$oldValue]))
			{
				$oldMood = $moods[$oldValue]['title'];
			}
			if (isset($moods[$newValue]))
			{
				$newMood = $moods[$newValue]['title'];
			}

			if (!empty($newMood))
			{
				$this->_publishMood(array(
					'old' => $oldMood,
					'new' => $newMood
				));
			}
		}
	}

	/**
	 * Publish a mood change to the news feed.
	 *
	 * @param mixed extra data
	 */
	protected function _publishMood($extraData = null)
	{
		$this->_getNewsFeedModel()->publish(
			$this->get('user_id'),
			$this->get('username'),
			'mood',
			0,
			'edit',
			$extraData
		);
	}
}
