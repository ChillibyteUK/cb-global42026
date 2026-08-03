// .cb-tabbed-content__display is server-rendered with the first item's
// content already inside it (see blocks/cb-tabbed-content.php), so this is
// pure progressive enhancement — copies whichever item's .panel content
// into the shared display pane whenever a different one is opened. Below
// "lg" .display is hidden entirely by CSS, so this listener is harmless
// there — the native <details> accordion doesn't need it.
export function initTabbedContent() {
	document.querySelectorAll('.cb-tabbed-content__items').forEach((items) => {
		const display = items.querySelector('.cb-tabbed-content__display');

		if (!display) {
			return;
		}

		items.querySelectorAll('.cb-tabbed-content__item').forEach((item) => {
			item.addEventListener('toggle', () => {
				if (!item.open) {
					return;
				}

				const panel = item.querySelector('.cb-tabbed-content__panel');
				display.innerHTML = panel ? panel.innerHTML : '';
			});
		});
	});
}
