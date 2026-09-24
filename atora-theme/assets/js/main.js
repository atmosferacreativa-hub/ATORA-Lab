(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		document.body.classList.add('is-ready');
		setupStickyHeader();
		setupMobileNavigation();
		setupSearchPanel();
		setupRevealObserver();
	});

	function setupStickyHeader() {
		var header = document.querySelector('[data-site-header]');
		if (!header) {
			return;
		}

		var sync = function () {
			header.classList.toggle('is-scrolled', window.scrollY > 12);
		};

		sync();
		window.addEventListener('scroll', sync, { passive: true });
	}

	function setupMobileNavigation() {
		var header = document.querySelector('[data-site-header]');
		var toggle = document.querySelector('[data-mobile-toggle]');
		var nav = document.querySelector('[data-navigation]');

		if (!header || !toggle || !nav) {
			return;
		}

		var close = function () {
			toggle.setAttribute('aria-expanded', 'false');
			header.classList.remove('menu-open');
			document.body.classList.remove('meridian-lock-scroll');
		};

		toggle.addEventListener('click', function () {
			var expanded = toggle.getAttribute('aria-expanded') === 'true';
			toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
			header.classList.toggle('menu-open', !expanded);
			document.body.classList.toggle('meridian-lock-scroll', !expanded);
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') {
				close();
			}
		});

		document.addEventListener('click', function (event) {
			if (window.innerWidth > 960) {
				return;
			}

			if (!header.contains(event.target)) {
				close();
			}
		});
	}

	function setupSearchPanel() {
		var panel = document.querySelector('[data-search-panel]');
		var toggles = document.querySelectorAll('[data-search-toggle]');
		var closeButton = document.querySelector('[data-search-close]');

		if (!panel || !toggles.length) {
			return;
		}

		var setState = function (open) {
			panel.hidden = !open;
			panel.classList.toggle('is-open', open);
			document.body.classList.toggle('meridian-search-open', open);

			toggles.forEach(function (toggle) {
				toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			});

			if (open) {
				var input = panel.querySelector('input[type="search"]');
				if (input) {
					window.setTimeout(function () {
						input.focus();
					}, 60);
				}
			}
		};

		toggles.forEach(function (toggle) {
			toggle.addEventListener('click', function () {
				var isOpen = panel.classList.contains('is-open');
				setState(!isOpen);
			});
		});

		if (closeButton) {
			closeButton.addEventListener('click', function () {
				setState(false);
			});
		}

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') {
				setState(false);
			}
		});
	}

	function setupRevealObserver() {
		var items = document.querySelectorAll('[data-reveal]');
		if (!items.length || typeof IntersectionObserver === 'undefined') {
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-visible');
						observer.unobserve(entry.target);
					}
				});
			},
			{
				rootMargin: '0px 0px -12% 0px',
				threshold: 0.12
			}
		);

		items.forEach(function (item) {
			observer.observe(item);
		});
	}
})();
