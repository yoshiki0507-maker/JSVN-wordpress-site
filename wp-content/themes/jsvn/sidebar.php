<?php
/**
 * サイドバー
 *
 * @package JSVN
 */
?>
<aside class="jsvn-sidebar" aria-label="サイドバー">
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php else : ?>

		<section class="widget">
			<h3 class="widget-title">カテゴリー</h3>
			<ul>
				<?php wp_list_categories( array( 'title_li' => '', 'show_count' => true ) ); ?>
			</ul>
		</section>

		<section class="widget">
			<h3 class="widget-title">最近のお知らせ</h3>
			<ul>
				<?php
				$recent = wp_get_recent_posts( array( 'numberposts' => 5, 'post_status' => 'publish' ) );
				if ( $recent ) {
					foreach ( $recent as $post_item ) {
						echo '<li><a href="' . esc_url( get_permalink( $post_item['ID'] ) ) . '">' . esc_html( $post_item['post_title'] ) . '</a></li>';
					}
				} else {
					echo '<li>準備中です。</li>';
				}
				?>
			</ul>
		</section>

		<section class="widget">
			<h3 class="widget-title">入会のご案内</h3>
			<p style="font-size:.9rem;color:var(--jsvn-ink-soft);">訪問看護に関わるすべての方を歓迎します。</p>
			<a class="jsvn-btn jsvn-btn--coral" href="<?php echo esc_url( home_url( '/join/' ) ); ?>" style="width:100%;justify-content:center;">入会案内を見る</a>
		</section>

	<?php endif; ?>
</aside>
