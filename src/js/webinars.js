/**
 * CB Webinars — each video thumbnail opens a <dialog> modal (the actual
 * open/close mechanics are dialog.js's generic data-dialog-target/
 * data-dialog-close wiring; this just manages the YouTube iframe's
 * lifecycle so all the modals on a page aren't loading/playing video at
 * once — the iframe is only created when its dialog opens, and torn down
 * when it closes.
 */
export function initWebinars() {
	document.querySelectorAll('.cb-webinars__video').forEach((trigger) => {
		const dialog = document.getElementById(trigger.getAttribute('data-dialog-target'));

		if (!(dialog instanceof HTMLDialogElement)) {
			return;
		}

		const player = dialog.querySelector('.cb-webinars__player');
		const youtubeId = trigger.dataset.youtubeId;

		if (!player || !youtubeId) {
			return;
		}

		trigger.addEventListener('click', () => {
			const iframe = document.createElement('iframe');
			iframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1`;
			iframe.title = trigger.getAttribute('aria-label') || 'YouTube video player';
			iframe.setAttribute(
				'allow',
				'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share'
			);
			iframe.allowFullscreen = true;
			player.replaceChildren(iframe);
		});

		dialog.addEventListener('close', () => {
			player.replaceChildren();
		});
	});
}
