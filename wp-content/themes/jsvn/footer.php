<?php
/**
 * フッター
 *
 * @package JSVN
 */
?>
</main><!-- #jsvn-main -->

<footer class="jsvn-footer">
	<div class="jsvn-container">
		<div class="jsvn-footer__grid">

			<div class="jsvn-footer__brand">
				<img class="jsvn-footer__logo" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
				<p><?php jsvn_e_ml( 'footer_desc', '訪問看護の実践と学術を支え、地域で暮らす人々の「その人らしい療養」を支援します。' ); ?></p>
				<?php if ( function_exists( 'jsvn_sns_icons' ) ) { jsvn_sns_icons(); } ?>
			</div>

			<div class="jsvn-footer__col">
				<h4><?php jsvn_e( 'footer_col1', '学会について' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/founding/' ) ); ?>">設立趣旨・理念</a></li>
					<li><a href="<?php echo esc_url( home_url( '/officers/' ) ); ?>">役員・代議員・名誉会員</a></li>
					<li><a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>">定款・規程</a></li>
				</ul>
			</div>

			<div class="jsvn-footer__col">
				<h4><?php jsvn_e( 'footer_col2', '活動・学術' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">学術大会・研究会</a></li>
					<li><a href="<?php echo esc_url( home_url( '/journal-submission/' ) ); ?>">学会誌・投稿規定</a></li>
					<li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせ</a></li>
				</ul>
			</div>

			<div class="jsvn-footer__col">
				<h4><?php jsvn_e( 'footer_col3', '会員の方へ' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/join/' ) ); ?>">入会のご案内</a></li>
					<li><a href="<?php echo esc_url( jsvn_login_url() ); ?>" target="_blank" rel="noopener">会員ログイン（学会バンク）</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a></li>
				</ul>
			</div>

		</div>
	</div>

	<nav class="jsvn-footer__utility" aria-label="ユーティリティ">
		<div class="jsvn-container">
			<a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">プライバシーポリシー</a>
			<a href="<?php echo esc_url( home_url( '/sitemap/' ) ); ?>">サイトマップ</a>
			<a href="<?php echo esc_url( home_url( '/tokushoho/' ) ); ?>">特定商取引法に基づく表記</a>
			<a href="<?php echo esc_url( home_url( '/accessibility/' ) ); ?>">ウェブアクセシビリティ方針</a>
			<a href="<?php echo esc_url( home_url( '/links/' ) ); ?>">関連リンク</a>
			<a href="<?php echo esc_url( home_url( '/english/' ) ); ?>">English</a>
		</div>
	</nav>

	<div class="jsvn-footer__bottom">
		<div class="jsvn-container">
			&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> All Rights Reserved.
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
