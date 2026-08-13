<?php
/**
 * 固定ページ「役員・代議員・名誉会員紹介」（スラッグ: officers）
 * 役員名簿（カスタム投稿 jsvn_officer）を役職ごとに一覧表示します。
 *
 * @package JSVN
 */

get_header();

while ( have_posts() ) : the_post();
	?>
	<section class="jsvn-pagehero">
		<div class="jsvn-container">
			<?php jsvn_breadcrumb(); ?>
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<div class="jsvn-content">
		<div class="jsvn-container">
			<article class="jsvn-article" style="max-width:none;">
				<?php
				// 固定ページ本文（前書きなど）があれば表示
				if ( trim( get_the_content() ) ) {
					the_content();
				}
				// 役員名簿を描画
				jsvn_render_officers();
				// 賛助会員を描画（登録があれば）
				jsvn_render_supporters();
				?>
			</article>
		</div>
	</div>
	<?php
endwhile;

get_footer();
