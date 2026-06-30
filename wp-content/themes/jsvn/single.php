<?php
/**
 * 個別記事（お知らせ・学術大会・学会誌）
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
		<div class="jsvn-container jsvn-content__layout">

			<article class="jsvn-article">
				<div class="jsvn-postmeta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?></time>
					<?php
					$cats = get_the_category();
					if ( ! empty( $cats ) ) :
						?>
						<span class="jsvn-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
						<?php
					endif;
					?>
				</div>

				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large' );
				}
				the_content();

				wp_link_pages( array(
					'before' => '<div class="jsvn-pagination">',
					'after'  => '</div>',
				) );
				?>

				<div style="margin-top:2.5rem;padding-top:1.5rem;border-top:1px solid var(--jsvn-line);">
					<?php
					previous_post_link( '<span style="margin-right:1.5rem;">&laquo; %link</span>' );
					next_post_link( '<span>%link &raquo;</span>' );
					?>
				</div>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</article>

			<?php get_sidebar(); ?>

		</div>
	</div>
	<?php
endwhile;

get_footer();
