/**
 * CB Post Index — category/year filtering plus a debounced AJAX search
 * (ported from cb-pluto2026's blocks/cb-insights-index.php, same logic:
 * client-side filtering for category/year, server round-trip for text
 * search since that needs a real WP_Query 's' match). The handler this
 * calls is registered in inc/post-index.php.
 */
export function initPostIndex() {
	document.querySelectorAll('.cb-post-index').forEach((block) => {
		const results = block.querySelector('.cb-post-index__results');
		const searchInput = block.querySelector('.cb-post-index__search-input');
		const resetBtn = block.querySelector('.cb-post-index__reset');
		const catFilters = block.querySelectorAll('.cb-post-index__filter[data-filter]');
		const yearFilters = block.querySelectorAll('.cb-post-index__filter[data-year]');

		if (!results) {
			return;
		}

		const getActiveCat = () => {
			const active = block.querySelector('.cb-post-index__filter--active[data-filter]');
			return active ? active.dataset.filter : 'all';
		};

		const getActiveYear = () => {
			const active = block.querySelector('.cb-post-index__filter--active[data-year]');
			return active ? active.dataset.year : 'all';
		};

		const resetFilterButtons = () => {
			catFilters.forEach((btn) => btn.classList.remove('cb-post-index__filter--active'));
			block.querySelector('.cb-post-index__filter[data-filter="all"]').classList.add('cb-post-index__filter--active');
			yearFilters.forEach((btn) => btn.classList.remove('cb-post-index__filter--active'));
			block.querySelector('.cb-post-index__filter[data-year="all"]').classList.add('cb-post-index__filter--active');
		};

		const applyFilters = () => {
			const cat = getActiveCat();
			const year = getActiveYear();

			results.querySelectorAll('.cb-post-index__year-group').forEach((group) => {
				let anyVisible = false;

				group.querySelectorAll('.cb-post-index__item').forEach((item) => {
					const cats = (item.dataset.category || '').split(' ');
					const matchesCat = cat === 'all' || cats.includes(cat);
					const matchesYear = year === 'all' || item.dataset.year === year;
					const matches = matchesCat && matchesYear;

					item.style.display = matches ? '' : 'none';
					if (matches) anyVisible = true;
				});

				group.style.display = anyVisible ? '' : 'none';
			});
		};

		catFilters.forEach((btn) => {
			btn.addEventListener('click', () => {
				catFilters.forEach((f) => f.classList.remove('cb-post-index__filter--active'));
				btn.classList.add('cb-post-index__filter--active');
				applyFilters();
			});
		});

		yearFilters.forEach((btn) => {
			btn.addEventListener('click', () => {
				yearFilters.forEach((f) => f.classList.remove('cb-post-index__filter--active'));
				btn.classList.add('cb-post-index__filter--active');
				applyFilters();
			});
		});

		if (!searchInput) {
			return;
		}

		let debounceTimer;

		const runSearch = (term) => {
			const data = new FormData();
			data.append('action', 'cb_post_index_search');
			data.append('nonce', results.dataset.nonce);
			data.append('search_term', term);
			data.append('category', getActiveCat());

			fetch(results.dataset.ajaxUrl, { method: 'POST', body: data })
				.then((response) => response.json())
				.then((response) => {
					if (response.success) {
						results.innerHTML = response.data.html;
						resetFilterButtons();
						applyFilters();
					}
				});
		};

		searchInput.addEventListener('input', () => {
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(() => runSearch(searchInput.value.trim()), 300);
		});

		if (resetBtn) {
			resetBtn.addEventListener('click', () => {
				searchInput.value = '';
				resetFilterButtons();
				runSearch('');
			});
		}
	});
}
