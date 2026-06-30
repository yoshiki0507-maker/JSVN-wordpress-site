/**
 * JSVN テーマ メインスクリプト
 * - モバイルナビの開閉
 * - スクロール時のヘッダー影
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var burger  = document.querySelector('.jsvn-burger');
		var nav     = document.querySelector('.jsvn-nav');
		var overlay = document.querySelector('.jsvn-nav-overlay');

		function closeNav() {
			if (!nav) return;
			nav.classList.remove('is-open');
			if (overlay) { overlay.classList.remove('is-open'); overlay.hidden = true; }
			if (burger) burger.setAttribute('aria-expanded', 'false');
			document.body.style.overflow = '';
		}

		function openNav() {
			if (!nav) return;
			nav.classList.add('is-open');
			if (overlay) { overlay.hidden = false; requestAnimationFrame(function () { overlay.classList.add('is-open'); }); }
			if (burger) burger.setAttribute('aria-expanded', 'true');
			document.body.style.overflow = 'hidden';
		}

		if (burger) {
			burger.addEventListener('click', function () {
				var expanded = burger.getAttribute('aria-expanded') === 'true';
				expanded ? closeNav() : openNav();
			});
		}

		if (overlay) overlay.addEventListener('click', closeNav);

		// メニュー内リンク／Escで閉じる
		if (nav) {
			nav.querySelectorAll('a').forEach(function (link) {
				link.addEventListener('click', closeNav);
			});
		}
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') closeNav();
		});

		// ウィンドウ拡大時にリセット
		window.addEventListener('resize', function () {
			if (window.innerWidth > 900) closeNav();
		});
	});
})();
