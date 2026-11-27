<?php
/**
 * 固定ページ「賛助会員」（スラッグ: supporters）
 * 賛助会員の一覧（名前・所属）を表示します。
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
				// 賛助会員を描画
				$jsvn_sup  = jsvn_render_supporters();
				// 活動協力者（クラウドファンディング協力者など）を描画
				$jsvn_coop = jsvn_render_cooperators();
				if ( ! $jsvn_sup && ! $jsvn_coop ) {
					echo '<p>準備中です。［外観 &gt; カスタマイズ &gt; 賛助会員 ／ 活動協力者］から登録できます。</p>';
				}
				?>
			</article>
		</div>
	</div>
	<?php
endwhile;

get_footer();
