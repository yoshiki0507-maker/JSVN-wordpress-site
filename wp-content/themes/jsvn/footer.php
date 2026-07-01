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
				<div class="jsvn-brand">
					<span class="jsvn-brand__mark" aria-hidden="true">看</span>
					<span class="jsvn-brand__text">
						<span class="jsvn-brand__name" style="color:#fff;"><?php bloginfo( 'name' ); ?></span>
						<span class="jsvn-brand__en">Japan Society of Visiting Nursing</span>
					</span>
				</div>
				<p><?php echo esc_html( get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : '訪問看護の実践と学術を支え、地域で暮らす人々の「その人らしい療養」を支援します。' ); ?></p>
				<?php if ( function_exists( 'jsvn_sns_icons' ) ) { jsvn_sns_icons(); } ?>
			</div>

			<div class="jsvn-footer__col">
				<h4>学会について</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">ご挨拶・理念</a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/organization/' ) ); ?>">組織・役員</a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/charter/' ) ); ?>">定款・規程</a></li>
				</ul>
			</div>

			<div class="jsvn-footer__col">
				<h4>活動・学術</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">学術大会・研究会</a></li>
					<li><a href="<?php echo esc_url( home_url( '/journal/' ) ); ?>">学会誌・刊行物</a></li>
					<li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせ</a></li>
				</ul>
			</div>

			<div class="jsvn-footer__col">
				<h4>会員の方へ</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/join/' ) ); ?>">入会のご案内</a></li>
					<li><a href="<?php echo esc_url( home_url( '/login/' ) ); ?>">会員ログイン</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a></li>
				</ul>
			</div>

		</div>
	</div>

	<div class="jsvn-footer__bottom">
		<div class="jsvn-container">
			&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> All Rights Reserved.
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
