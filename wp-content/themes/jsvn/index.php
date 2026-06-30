<?php
/**
 * メインのフォールバックテンプレート（お知らせ一覧 / ブログインデックス）
 *
 * @package JSVN
 */

get_header();

$page_title = is_home() ? 'お知らせ' : ( single_post_title( '', false ) ? single_post_title( '', false ) : 'お知らせ' );
?>

<section class="jsvn-pagehero">
	<div class="jsvn-container">
		<?php jsvn_breadcrumb(); ?>
		<h1><?php echo esc_html( $page_title ); ?></h1>
	</div>
</section>

<div class="jsvn-content">
	<div class="jsvn-container jsvn-content__layout">

		<div class="jsvn-main-col">
			<?php if ( have_posts() ) : ?>
				<div class="jsvn-news__list">
					<?php while ( have_posts() ) : the_post();
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
					<?php endwhile; ?>
				</div>
				<?php jsvn_pagination(); ?>
			<?php else : ?>
				<div class="jsvn-article">
					<p>まだ投稿がありません。管理画面の「投稿」から、お知らせを追加してください。</p>
				</div>
			<?php endif; ?>
		</div>

		<?php get_sidebar(); ?>

	</div>
</div>

<?php
get_footer();
