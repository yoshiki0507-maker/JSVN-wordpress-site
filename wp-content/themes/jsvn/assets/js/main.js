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
			if (window.innerWidth > 1100) closeNav();
		});

		/* ---- ドロップダウン：クリック・タップ・キーボードで開閉 ---- */
		var menu = document.querySelector('.jsvn-nav__menu');
		if (menu) {
			var parents = Array.prototype.slice.call(
				menu.querySelectorAll(':scope > li.menu-item-has-children')
			);

			function closeAll(except) {
				parents.forEach(function (li) {
					if (li === except) return;
					li.classList.remove('is-open');
					var b = li.querySelector(':scope > .jsvn-submenu-toggle');
					if (b) b.setAttribute('aria-expanded', 'false');
				});
			}

			parents.forEach(function (li) {
				var link = li.querySelector(':scope > a');
				var sub  = li.querySelector(':scope > .sub-menu');
				if (!sub) return;

				// 開閉ボタンを自動生成（親リンクはページ遷移用に残す）
				var btn = document.createElement('button');
				btn.type = 'button';
				btn.className = 'jsvn-submenu-toggle';
				btn.setAttribute('aria-expanded', 'false');
				var name = link ? link.textContent.trim() : '';
				btn.setAttribute('aria-label', name + ' のサブメニューを開く');
				btn.innerHTML = '<span class="jsvn-submenu-toggle__caret" aria-hidden="true"></span>';
				if (link && link.parentNode === li) {
					li.insertBefore(btn, link.nextSibling);
				} else {
					li.appendChild(btn);
				}

				btn.addEventListener('click', function (e) {
					e.preventDefault();
					e.stopPropagation();
					var isOpen = li.classList.contains('is-open');
					closeAll(li);
					if (isOpen) {
						li.classList.remove('is-open');
						btn.setAttribute('aria-expanded', 'false');
					} else {
						li.classList.add('is-open');
						btn.setAttribute('aria-expanded', 'true');
					}
				});
			});

			// メニュー外をクリック／タップしたら閉じる
			document.addEventListener('click', function (e) {
				if (!menu.contains(e.target)) closeAll(null);
			});
			// Escで閉じる
			menu.addEventListener('keydown', function (e) {
				if (e.key === 'Escape') closeAll(null);
			});
		}
	});
})();

/**
 * 会員数マップの格式あるツールチップ
 */
(function () {
	'use strict';
	document.addEventListener('DOMContentLoaded', function () {
		var prefs = document.querySelectorAll('.jsvn-jpmap .jsvn-pref');
		if (!prefs.length) return;
		var tip = document.createElement('div');
		tip.className = 'jsvn-maptip';
		document.body.appendChild(tip);
		function move(e) {
			var t = e.currentTarget;
			tip.innerHTML = '<span class="jsvn-maptip__n">' + t.getAttribute('data-n') +
				'</span><span class="jsvn-maptip__v">' + t.getAttribute('data-v') + '<i>名</i></span>';
			var x = e.clientX + 16, y = e.clientY + 16;
			if (x + 160 > window.innerWidth) x = e.clientX - 160;
			tip.style.left = x + 'px';
			tip.style.top = y + 'px';
			tip.classList.add('is-on');
		}
		function leave() { tip.classList.remove('is-on'); }
		prefs.forEach(function (p) {
			p.addEventListener('mousemove', move);
			p.addEventListener('mouseleave', leave);
		});
	});
})();

/**
 * メインビジュアルのスライドショー
 */
(function () {
	'use strict';
	document.addEventListener('DOMContentLoaded', function () {
		var root = document.querySelector('[data-jsvn-slider]');
		if (!root) return;
		var slides = root.querySelectorAll('.jsvn-slide');
		var dots = root.querySelectorAll('.jsvn-dot');
		if (slides.length < 2) return;
		var i = 0, timer;
		function go(n) {
			slides[i].classList.remove('is-active');
			if (dots[i]) dots[i].classList.remove('is-active');
			i = (n + slides.length) % slides.length;
			slides[i].classList.add('is-active');
			if (dots[i]) dots[i].classList.add('is-active');
		}
		function start() { timer = setInterval(function () { go(i + 1); }, 6000); }
		function reset() { clearInterval(timer); start(); }
		dots.forEach(function (d) {
			d.addEventListener('click', function () { go(parseInt(d.getAttribute('data-i'), 10)); reset(); });
		});
		start();
	});
})();
