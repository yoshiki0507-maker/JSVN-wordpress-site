<?php
/**
 * 学術大会・研究会 一覧（スラッグ「events」の固定ページ用テンプレート）
 *
 * /events/ が404にならないよう固定ページとして機能させつつ、
 * ［学術大会］（jsvn_event）に登録があれば一覧表示する。
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

			<?php if ( trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
				<div class="jsvn-article" style="margin-bottom:2.2rem;"><?php the_content(); ?></div>
			<?php endif; ?>

			<?php
			$jsvn_events = new WP_Query( array(
				'post_type'      => 'jsvn_event',
				'posts_per_page' => 30,
				'orderby'        => 'date',
				'order'          => 'DESC',
			) );
			if ( $jsvn_events->have_posts() ) :
				?>
				<div class="jsvn-cards">
					<?php
					while ( $jsvn_events->have_posts() ) :
						$jsvn_events->the_post();
						?>
						<article class="jsvn-card">
							<?php if ( has_post_thumbnail() ) : ?>
								<a class="jsvn-card__thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'jsvn-card' ); ?></a>
							<?php else : ?>
								<div class="jsvn-card__thumb"></div>
							<?php endif; ?>
							<div class="jsvn-card__body">
								<div class="jsvn-card__meta"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></div>
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo esc_html( jsvn_get_excerpt( 80 ) ); ?></p>
							</div>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>

		</div>
	</div>
	<?php
endwhile;

get_footer();
