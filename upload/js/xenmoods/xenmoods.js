// create XenMoods namespace
var XenMoods = {};

/** @param {jQuery} $ jQuery Object */
!function($, window, document, _undefined)
{
	XenMoods.UpdateMood = function($link)
	{
		$link.click(function(e)
		{
			if ($link.parents('.xenOverlay').length == 0)
			{
				// only do ajax stuff if we are in an overlay
				return;
			}

			e.preventDefault();

			XenForo.ajax(
				$link.attr('href'),
				{},
				function(ajaxData, textStatus)
				{
					$link.parents('.xenOverlay').data('overlay').close();

					if (ajaxData._redirectMessage
							&& ajaxData.moodChooserUrl
							&& ajaxData.moodImageUrl)
					{
						XenForo.alert(ajaxData._redirectMessage, '', 1000);

						// linked moods are the user's own!
						$('.userMood a img')
							.delay(1500) // callbacks on alert() are cached...
							.xfFadeUp(XenForo.speed.normal, function()
							{
								$('.userMood a').attr('href', ajaxData.moodChooserUrl);
								$('.userMood a img')
									.attr('src', ajaxData.moodImageUrl)
									.xfFadeDown(XenForo.speed.normal);
							});
					}
				}
			);
		});
	};

	// *********************************************************************

	XenForo.register('a.UpdateMood', 'XenMoods.UpdateMood');

}
(jQuery, this, document);