$(function() {
	$('.retroclockbox_sm').flipcountdown({size:'sm', beforeDateTime:sale_date, current_date: current_date, ctr:0});

	if ($('.countdown')) {
		if (screen.width > 1366) {
			var container_width = $('.container-fluid').width();
			var left_width = $('.navbar-minimize').width() + $('.navbar-header:not(.countdown)').width();
			var right_width = $('.navbar-right').width();

			if ($('.banner-text').length) {
				var countdown_width = $('.banner-text').width() / 2;
			} else {
				var countdown_width = ($('.countdown .text').width() + $('.countdown .counter').width())/2;
			}

			var middle = ((container_width-right_width) - left_width)/2;

			if ($('.banner-text').length) {
				$('.countdown').css('padding-left',left_width + (middle-countdown_width)+'px');
			} else {
				$('.countdown').css('padding-left',left_width + (middle-countdown_width)+'px');
			}

			$('.text').css('visibility','');
			$('.counter').css('visibility','');
			$('.banner-text').css('visibility','');
			$('.countdown-body').hide();
		} else {
			$('.countdown-nav').hide();
			$('.countdown-body').removeClass('hide');
		}
	}
});