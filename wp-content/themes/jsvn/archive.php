<?php
/**
 * アーカイブ（カテゴリー・カスタム投稿タイプ一覧など）
 *
 * @package JSVN
 */

get_header();
?>

<section class="jsvn-pagehero">
	<div class="jsvn-container">
		<?php jsvn_breadcrumb(); ?>
		<h1><?php echo esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>
	</div>
</section>

<div class="jsvn-content">
	<div class="jsvn-container">
		<?php if ( have_posts() ) : ?>
			<div class="jsvn-cards">
				<?php while ( have_posts() ) : the_post(); ?>
					<article class="jsvn-card">
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="jsvn-card__thumb" href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'jsvn-card' ); ?>
							</a>
						<?php else : ?>
							<div class="jsvn-card__thumb"></div>
						<?php endif; ?>
						<div class="jsvn-card__body">
							<div class="jsvn-card__meta"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></div>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( jsvn_get_excerpt( 80 ) ); ?></p>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php jsvn_pagination(); ?>
		<?php else : ?>
			<div class="jsvn-article">
				<p>該当する記事がありませんでした。</p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
