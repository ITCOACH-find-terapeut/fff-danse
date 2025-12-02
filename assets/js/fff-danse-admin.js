/**
 * FFF Danse Admin JavaScript
 *
 * Handles AJAX YouTube import in the admin interface.
 *
 * @package FFF_Danse
 */

(function($) {
	'use strict';

	$(function() {
		var $btn = $('#fff_danse_fetch_btn');
		if (!$btn.length) {
			return;
		}

		var $urlInput = $('#fff_danse_youtube_url');
		var $result = $('#fff_danse_fetch_result');

		$btn.on('click', function(e) {
			e.preventDefault();

			var video = $urlInput.val().trim();
			var postId = $('#post_ID').val();

			if (!video) {
				alert(FFF_DANSE.strings.enter_video || 'Indtast en YouTube URL eller video-ID først.');
				return;
			}

			if (!postId) {
				alert(FFF_DANSE.strings.enter_post || 'Post ID mangler.');
				return;
			}

			// Show loading state
			$btn.prop('disabled', true).text(FFF_DANSE.strings.loading || 'Henter...');
			$result.css({
				'color': '#666',
				'font-weight': 'normal'
			}).text(FFF_DANSE.strings.fetching || 'Henter data fra YouTube...');

			$.post(FFF_DANSE.ajax_url, {
				action: 'fff_danse_fetch_youtube',
				nonce: FFF_DANSE.nonce,
				post_id: postId,
				video: video
			}).done(function(res) {
				if (res.success) {
					$result.css({
						'color': '#00a32a',
						'font-weight': 'normal'
					}).text(res.data.message || FFF_DANSE.strings.success || 'OK');

					// Clear the input
					$urlInput.val('');

					// Optionally reload page to show updated fields
					if (res.data.updated && res.data.updated.length > 0) {
						setTimeout(function() {
							window.location.reload();
						}, 1500);
					}
				} else {
					var msg = (res.data && res.data.message) ? res.data.message : (FFF_DANSE.strings.error_unknown || 'Ukendt fejl.');
					$result.css({
						'color': '#b32d2e',
						'font-weight': 'bold'
					}).text(FFF_DANSE.strings.error_prefix + ' ' + msg);
				}
			}).fail(function() {
				$result.css({
					'color': '#b32d2e',
					'font-weight': 'bold'
				}).text(FFF_DANSE.strings.error_ajax || 'Fejl ved AJAX-kald.');
			}).always(function() {
				$btn.prop('disabled', false).text(FFF_DANSE.strings.button_text || 'Hent fra YouTube');
			});
		});

		// Allow Enter key to trigger import
		$urlInput.on('keypress', function(e) {
			if (e.which === 13) {
				e.preventDefault();
				$btn.click();
			}
		});
	});

})(jQuery);



