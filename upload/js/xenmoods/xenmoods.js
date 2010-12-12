// create XenMoods namespace
var XenMoods = {};

/** @param {jQuery} $ jQuery Object */
!function($, window, document, _undefined)
{
	XenMoods.UpdateMood = function($link)
	{
		$link.click(function(e)
		{
			e.preventDefault();

			XenForo.ajax(
				$link.attr('href'),
				{},
				function(ajaxData, textStatus)
				{
					$link.parents('.xenOverlay').data('overlay').close();

					if (ajaxData._redirectMessage && ajaxData.moodImageUrl)
					{
						XenForo.alert(ajaxData._redirectMessage, '', 1000, function()
						{
							// linked moods are the user's own!
							$('.userMood a img').xfFadeUp(XenForo.speed.normal, function()
							{
								$('.userMood a img')
									.attr('src', ajaxData.moodImageUrl)
									.xfFadeDown(XenForo.speed.normal);
							});
						});
					}
				}
			);
		});
	};

	// *********************************************************************

	XenForo.register('.UpdateMood', 'XenMoods.UpdateMood');

}
(jQuery, this, document);