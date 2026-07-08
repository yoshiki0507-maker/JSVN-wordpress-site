<?php
/**
 * お知らせ一覧（スラッグ「news」の固定ページ用テンプレート）
 *
 * スラッグ news の固定ページに自動適用され、投稿（お知らせ）を一覧表示します。
 * これにより /news/ が 404 にならず、お知らせの一覧ページとして機能します。
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
			$jsvn_news_args = array(
				'post_type'           => 'post',
				'posts_per_page'      => 50,
				'ignore_sticky_posts' => true,
			);
			$jsvn_blog_cid = jsvn_blog_cat_id();
			if ( $jsvn_blog_cid ) {
				$jsvn_news_args['category__not_in'] = array( $jsvn_blog_cid ); // ブログはお知らせ一覧に出さない
			}
			$jsvn_news = new WP_Query( $jsvn_news_args );
			if ( $jsvn_news->have_posts() ) :
				?>
				<div class="jsvn-news__list">
					<?php
					while ( $jsvn_news->have_posts() ) :
						$jsvn_news->the_post();
						$cats      = get_the_category();
						$cat_name  = ! empty( $cats ) ? $cats[0]->name : 'お知らせ';
						$cat_slug  = ! empty( $cats ) ? $cats[0]->slug : '';
						$cat_class = jsvn_category_class( $cat_slug );
						?>
						<a class="jsvn-news__item" href="<?php the_permalink(); ?>" style="text-decoration:none;">
							<span class="jsvn-news__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
							<span class="jsvn-news__cat <?php echo esc_attr( $cat_class ); ?>"><?php echo esc_html( $cat_name ); ?></span>
							<span class="jsvn-news__title"><?php the_title(); ?></span>
						</a>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php else : ?>
				<div class="jsvn-article">
					<p>現在お知らせはありません。新しいお知らせは［投稿］から追加すると、ここに一覧表示されます。</p>
				</div>
			<?php endif; ?>

		</div>
	</div>
	<?php
endwhile;

get_footer();
