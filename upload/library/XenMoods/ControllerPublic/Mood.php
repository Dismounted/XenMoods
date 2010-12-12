<?php
/**
 * Controller for the mood functions in the public UI (e.g. mood chooser).
 *
 * @package XenMoods
 */

class XenMoods_ControllerPublic_Mood extends XenForo_ControllerPublic_Abstract
{
	/**
	 * Pre-dispatch assurances.
	 */
	protected function _preDispatch($action)
	{
		$this->_assertRegistrationRequired();
	}

	/**
	 * Displays a form to change the visitor's mood, or changes it if a mood_id is present.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */
	public function actionMoodChooser()
	{
		$visitor = XenForo_Visitor::getInstance();

		if (!$this->_getMoodModel()->canHaveMood($visitor->toArray()))
		{
			return $this->responseNoPermission();
		}

		if ($this->_input->inRequest('mood_id'))
		{
			$this->_checkCsrfFromToken($this->_input->filterSingle('_xfToken', XenForo_Input::STRING));

			$moodId = $this->_input->filterSingle('mood_id', XenForo_Input::UINT);

			if ($moodId)
			{
				$moods = $this->_getMoodData();
				if (!isset($moods[$moodId]))
				{
					$moodId = 0;
				}
			}

			$dw = XenForo_DataWriter::create('XenForo_DataWriter_User');
			$dw->setExistingData($visitor['user_id']);
			$dw->set('mood_id', $moodId);
			$dw->save();

			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				$this->getDynamicRedirect(false, false),
				null,
				array(
					'moodChooserUrl' => XenForo_Link::buildPublicLink('moods/mood-chooser'),
					'moodImageUrl' => (($moodId) ? $moods[$moodId]['image_url'] : $this->_getMoodModel()->getDefaultMoodUrl($moods))
				)
			);
		}
		else
		{
			$viewParams = array(
				'moods' => $this->_getMoodData(),
				'redirect' => $this->_input->filterSingle('redirect', XenForo_Input::STRING),
				'selected' => $visitor->get('mood_id')
			);
			return $this->responseView('XenMoods_ViewPublic_Mood_MoodChooser', 'mood_chooser', $viewParams);
		}
	}

	/**
	 * Helper function to get moods from data registry.
	 *
	 * @return array List of moods
	 */
	protected function _getMoodData()
	{
		if (XenForo_Application::isRegistered('moods'))
		{
			return XenForo_Application::get('moods');
		}
		else
		{
			return $this->_getMoodModel()->getAllMoods();
		}
	}

	/**
	 * Helper method to get the mood model.
	 *
	 * @return XenMoods_Model_Mood
	 */
	protected function _getMoodModel()
	{
		return $this->getModelFromCache('XenMoods_Model_Mood');
	}
}
