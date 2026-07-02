<?php
/**
 * 固定ページ「会員数・会員分布」（スラッグ: members）
 * 都道府県別の会員数を日本地図で可視化して表示します。
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
				if ( trim( get_the_content() ) ) {
					the_content();
				}
				if ( function_exists( 'jsvn_render_member_map' ) ) {
					jsvn_render_member_map();
				}
				if ( function_exists( 'jsvn_render_member_quals' ) ) {
					jsvn_render_member_quals();
				}
				?>
			</article>
		</div>
	</div>
	<?php
endwhile;

get_footer();
