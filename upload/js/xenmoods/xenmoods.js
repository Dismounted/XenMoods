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
					if (ajaxData.moodImageUrl)
					{
						// linked moods are the user's own!
						$('.userMood a img').attr('src', ajaxData.moodImageUrl);
					}
				}
			);
		});
	};

	// *********************************************************************

	XenForo.register('.UpdateMood', 'XenMoods.UpdateMood');

}
(jQuery, this, document);