<?php

/**
 * News feed handler for mood changes.
 *
 * @package XenMoods
 */
class XenMoods_NewsFeedHandler_Mood extends XenForo_NewsFeedHandler_User
{
	protected function _prepareEdit(array $item)
	{
		$item['mood'] = unserialize($item['extra_data']);

		unset($item['extra_data']);

		return $item;
	}

	protected function _getDefaultTemplateTitle($contentType, $action)
	{
		return 'news_feed_item_user_mood';
	}
}