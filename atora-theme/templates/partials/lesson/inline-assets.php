<script>
(function () {
	'use strict';

	document.querySelectorAll('.atora-lite-player[data-src]').forEach(function (wrap) {
		wrap.addEventListener('click', function () {
			var src = wrap.getAttribute('data-src');
			if (!src) {
				return;
			}

			var iframe = document.createElement('iframe');
			iframe.src = src;
			iframe.setAttribute('allowfullscreen', '');
			iframe.setAttribute('allow', 'autoplay; fullscreen; picture-in-picture');
			iframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
			iframe.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;border:none;';

			wrap.innerHTML = '';
			wrap.appendChild(iframe);
			wrap.classList.remove('atora-lite-player');
		}, { once: true });
	});
})();
</script>

<style>
.atora-lesson-wrap {
	--lesson-surface: rgba(255, 255, 255, 0.88);
	--lesson-surface-strong: rgba(255, 255, 255, 0.96);
	--lesson-border: rgba(20, 26, 39, 0.1);
	--lesson-shadow: 0 18px 44px rgba(17, 24, 39, 0.08);
	background:
		radial-gradient(circle at top left, rgba(30, 94, 255, 0.12), transparent 24%),
		linear-gradient(180deg, #faf6ef 0%, #f4efe7 100%);
	color: var(--atora-text, #141a27);
}

.atora-lesson-layout {
	display: grid;
	grid-template-columns: minmax(0, 1fr);
	gap: 1.25rem;
	width: min(1240px, calc(100vw - 1.4rem));
	margin: 0 auto;
	padding: 1.4rem 0 5rem;
}

.atora-lesson-layout.has-sidebar {
	grid-template-columns: minmax(0, 1fr) 320px;
}

.atora-lesson-header,
.atora-lesson-content,
.atora-lesson-video,
.atora-lesson-resources,
.atora-lesson-progress,
.atora-lesson-next,
.atora-lesson-live-class,
.atora-lesson-student-comment,
.atora-lesson-read-evidence,
.atora-lesson-gate,
.atora-resource-card {
	background: linear-gradient(180deg, var(--lesson-surface-strong) 0%, var(--lesson-surface) 100%);
	border: 1px solid var(--lesson-border);
	border-radius: 24px;
	box-shadow: var(--lesson-shadow);
}

.atora-lesson-header,
.atora-lesson-content,
.atora-lesson-video,
.atora-lesson-resources,
.atora-lesson-progress,
.atora-lesson-next,
.atora-lesson-live-class,
.atora-lesson-student-comment,
.atora-lesson-read-evidence,
.atora-lesson-gate {
	padding: 1.4rem;
}

.atora-lesson-breadcrumb {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 0.45rem;
	font-size: 0.8rem;
	font-family: "IBM Plex Mono", monospace;
	text-transform: uppercase;
	letter-spacing: 0.1em;
	color: var(--atora-text-subtle, #6f7888);
}

.atora-lesson-breadcrumb a {
	text-decoration: none;
	color: var(--atora-accent, #1e5eff);
}

.atora-lesson-title {
	font-family: "Fraunces", Georgia, serif;
	font-size: clamp(2.1rem, 5vw, 3.2rem);
	line-height: 1.05;
	color: var(--atora-text, #141a27);
	margin: 0 0 0.6rem;
}

.atora-lesson-subtitle {
	color: var(--atora-text-muted, #4f596b);
	font-size: 1.02rem;
}

.atora-badge {
	display: inline-flex;
	align-items: center;
	padding: 0.4rem 0.7rem;
	border-radius: 999px;
	font-size: 0.72rem;
	font-weight: 800;
	text-transform: uppercase;
	letter-spacing: 0.08em;
	font-family: "IBM Plex Mono", monospace;
}

.atora-badge-gray {
	background: rgba(17, 24, 39, 0.08);
	color: var(--atora-text, #141a27);
}

.atora-badge-amber {
	background: rgba(183, 121, 20, 0.12);
	color: #8a5603;
}

.atora-badge-red {
	background: rgba(209, 78, 78, 0.12);
	color: #862020;
}

.atora-lesson-video-ratio,
.atora-resource-video {
	position: relative;
	width: 100%;
	padding-bottom: 56.25%;
	height: 0;
	overflow: hidden;
	border-radius: 20px;
	background: #111827;
}

.atora-lesson-video-ratio iframe,
.atora-lesson-video-ratio video,
.atora-resource-video iframe,
.atora-resource-video video {
	position: absolute;
	inset: 0;
	width: 100%;
	height: 100%;
	border: 0;
}

.atora-lite-thumb {
	position: absolute;
	inset: 0;
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.atora-lite-overlay {
	position: absolute;
	inset: 0;
	background: linear-gradient(180deg, rgba(17, 24, 39, 0.08), rgba(17, 24, 39, 0.58));
}

.atora-lite-play {
	position: absolute;
	inset: 0;
	margin: auto;
	width: 4.5rem;
	height: 4.5rem;
	border-radius: 999px;
	background: rgba(255, 255, 255, 0.92);
	border: 0;
	box-shadow: 0 18px 34px rgba(17, 24, 39, 0.18);
}

.atora-lesson-content,
.atora-lesson-content p,
.atora-lesson-content li,
.atora-lesson-live-class__notes,
.atora-lesson-next__text,
.atora-resource-desc {
	color: var(--atora-text-muted, #4f596b);
}

.atora-resources-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 1rem;
}

.atora-resource-card {
	padding: 1rem;
}

.atora-resource-title,
.atora-lesson-tips-title,
.atora-lesson-resources-title {
	color: var(--atora-text, #141a27);
	font-family: "Fraunces", Georgia, serif;
}

.atora-lesson-tips-list {
	margin: 0;
	padding-left: 1.1rem;
}

.atora-lesson-progress__bar,
.atora-progress-bar {
	height: 0.7rem;
	border-radius: 999px;
	background: rgba(17, 24, 39, 0.08);
	overflow: hidden;
}

.atora-lesson-progress__bar-fill {
	display: block;
	height: 100%;
	background: linear-gradient(90deg, var(--atora-accent, #1e5eff), #5d8cff);
}

.atora-btn,
.atora-btn-primary,
.atora-btn-secondary {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-height: 2.8rem;
	padding: 0.78rem 1.2rem;
	border-radius: 999px;
	text-decoration: none;
	font-weight: 700;
}

.atora-btn-primary {
	background: var(--atora-accent, #1e5eff);
	color: #fff;
}

.atora-btn-secondary {
	background: rgba(255, 255, 255, 0.84);
	border: 1px solid var(--lesson-border);
	color: var(--atora-text, #141a27);
}

.atora-lesson-sidebar {
	display: grid;
	gap: 1rem;
}

@media (max-width: 1040px) {
	.atora-lesson-layout.has-sidebar {
		grid-template-columns: 1fr;
	}
}

@media (max-width: 760px) {
	.atora-lesson-layout {
		width: min(100vw - 1rem, 100%);
	}

	.atora-resources-grid {
		grid-template-columns: 1fr;
	}

	.atora-lesson-title {
		font-size: clamp(1.9rem, 10vw, 2.8rem);
	}
}
</style>
