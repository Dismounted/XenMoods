<?php
/**
 * Controller to manage moods in the admin control panel.
 *
 * @package XenMoods
 */

class XenMoods_ControllerAdmin_Mood extends XenForo_ControllerAdmin_Abstract
{
	protected function _preDispatch($action)
	{
		$this->assertAdminPermission('mood');
	}

	/**
	 * Displays a list of moods.
	 *
	 * @return XenForo_Controller_ResponseAbstract
	 */
	public function actionIndex()
	{
		$moods = $this->_getMoodModel()->getAllMoods();

		$viewParams = array(
			'moods' => $moods
		);

		return $this->responseView('XenMoods_ViewAdmin_Mood_List', 'mood_list', $viewParams);
	}

	/**
	 * Displays a form to add a mood.
	 *
	 * @return XenForo_Controller_ResponseAbstract
	 */
	public function actionAdd()
	{
		$viewParams = array(
			'mood' => array()
		);
		return $this->responseView('XenMoods_ViewAdmin_Mood_Edit', 'mood_edit', $viewParams);
	}

	/**
	 * Displays a form to edit an existing mood.
	 *
	 * @return XenForo_Controller_ResponseAbstract
	 */
	public function actionEdit()
	{
		$moodId = $this->_input->filterSingle('mood_id', XenForo_Input::UINT);
		$mood = $this->_getMoodOrError($moodId);

		$viewParams = array(
			'mood' => $mood
		);
		return $this->responseView('XenMoods_ViewAdmin_Mood_Edit', 'mood_edit', $viewParams);
	}

	/**
	 * Adds a new mood or updates an existing one.
	 *
	 * @return XenForo_Controller_ResponseAbstract
	 */
	public function actionSave()
	{
		$this->_assertPostOnly();

		$moodId = $this->_input->filterSingle('mood_id', XenForo_Input::UINT);
		$dwInput = $this->_input->filter(array(
			'title' => XenForo_Input::STRING,
			'image_url' => XenForo_Input::STRING
		));

		$dw = XenForo_DataWriter::create('XenMoods_DataWriter_Mood');
		if ($moodId)
		{
			$dw->setExistingData($moodId);
		}
		$dw->bulkSet($dwInput);
		$dw->save();

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('moods')
		);
	}

	/**
	 * Validates the specified mood field.
	 *
	 * @return XenForo_Controller_ResponseAbstract
	 */
	public function actionValidateField()
	{
		$this->_assertPostOnly();

		return $this->_validateField('XenMoods_DataWriter_Mood', array(
			'existingDataKey' => $this->_input->filterSingle('mood_id', XenForo_Input::UINT)
		));
	}

	/**
	 * Deletes the specified mood.
	 *
	 * @return XenForo_Controller_ResponseAbstract
	 */
	public function actionDelete()
	{
		if ($this->isConfirmedPost())
		{
			return $this->_deleteData(
				'XenMoods_DataWriter_Mood', 'mood_id',
				XenForo_Link::buildAdminLink('moods')
			);
		}
		else
		{
			$moodId = $this->_input->filterSingle('mood_id', XenForo_Input::UINT);
			$mood = $this->_getMoodOrError($moodId);

			$viewParams = array(
				'mood' => $mood
			);
			return $this->responseView('XenMoods_ViewAdmin_Mood_Delete', 'mood_delete', $viewParams);
		}
	}

	/**
	 * Makes an existing mood the default.
	 *
	 * @return XenForo_Controller_ResponseAbstract
	 */
	public function actionMakeDefault()
	{
		if ($this->isConfirmedPost())
		{
			$moodId = $this->_input->filterSingle('mood_id', XenForo_Input::UINT);

			$dw = XenForo_DataWriter::create('XenMoods_DataWriter_Mood');
			$dw->setExistingData($moodId);
			$dw->set('is_default', true);
			$dw->save();

			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildAdminLink('moods')
			);
		}
		else
		{
			$moodId = $this->_input->filterSingle('mood_id', XenForo_Input::UINT);
			$mood = $this->_getMoodOrError($moodId);

			$viewParams = array(
				'mood' => $mood
			);
			return $this->responseView('XenMoods_ViewAdmin_Mood_Make_Default', 'mood_make_default', $viewParams);
		}
	}

	/**
	 * Gets a valid mood or throws an exception.
	 *
	 * @param string $moodId
	 *
	 * @return array
	 */
	protected function _getMoodOrError($moodId)
	{
		$info = $this->_getMoodModel()->getMoodById($moodId);
		if (!$info)
		{
			throw $this->responseException($this->responseError(new XenForo_Phrase('requested_mood_not_found'), 404));
		}

		return $info;
	}

	/**
	 * @return XenMoods_Model_Mood
	 */
	protected function _getMoodModel()
	{
		return $this->getModelFromCache('XenMoods_Model_Mood');
	}
}