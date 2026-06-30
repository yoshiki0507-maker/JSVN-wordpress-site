<?php
/**
 * 検索結果
 *
 * @package JSVN
 */

get_header();
?>

<section class="jsvn-pagehero">
	<div class="jsvn-container">
		<?php jsvn_breadcrumb(); ?>
		<h1>「<?php echo esc_html( get_search_query() ); ?>」の検索結果</h1>
	</div>
</section>

<div class="jsvn-content">
	<div class="jsvn-container jsvn-content__layout">
		<div class="jsvn-main-col">
			<?php if ( have_posts() ) : ?>
				<div class="jsvn-news__list">
					<?php while ( have_posts() ) : the_post(); ?>
						<a class="jsvn-news__item" href="<?php the_permalink(); ?>" style="text-decoration:none;">
							<span class="jsvn-news__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
							<span class="jsvn-news__title"><?php the_title(); ?></span>
						</a>
					<?php endwhile; ?>
				</div>
				<?php jsvn_pagination(); ?>
			<?php else : ?>
				<div class="jsvn-article">
					<p>該当する記事が見つかりませんでした。別のキーワードでお試しください。</p>
					<?php get_search_form(); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</div>

<?php
get_footer();
