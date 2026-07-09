<?php
/**
 * サイドバー
 *
 * ウィジェット未設定でも日本語で自動表示：
 *  - 最新のブログ（3件）
 *  - 協賛企業バナー（小さめ／カスタマイザーで登録）
 *  - 入会のご案内
 * ※ [外観 > ウィジェット] の「サイドバー」にウィジェットを追加した場合は
 *   それが上部に加わります（英語のウィジェット画面を触らなくてもOK）。
 *
 * @package JSVN
 */
?>
<aside class="jsvn-sidebar" aria-label="サイドバー">

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php endif; ?>

	<section class="widget">
		<h3 class="widget-title">最新のブログ</h3>
		<?php if ( ! jsvn_render_latest_blog( 3 ) ) : ?>
			<p style="font-size:.9rem;color:var(--jsvn-ink-soft);margin:0;">ブログはまだありません。［投稿］でカテゴリー「ブログ」を選んで公開すると表示されます。</p>
		<?php endif; ?>
		<?php $jsvn_blog_cid = jsvn_blog_cat_id(); ?>
		<p style="margin:.7rem 0 0;">
			<a class="jsvn-textlink" href="<?php echo esc_url( $jsvn_blog_cid ? get_category_link( $jsvn_blog_cid ) : home_url( '/category/blog/' ) ); ?>">ブログ一覧へ &rsaquo;</a>
		</p>
	</section>

	<?php if ( jsvn_sponsor_banners() ) : ?>
		<section class="widget">
			<h3 class="widget-title">協賛企業・賛助会員</h3>
			<?php jsvn_render_sponsor_banners(); ?>
		</section>
	<?php endif; ?>

	<section class="widget">
		<h3 class="widget-title">入会のご案内</h3>
		<p style="font-size:.9rem;color:var(--jsvn-ink-soft);">訪問看護に関わるすべての方を歓迎します。</p>
		<a class="jsvn-btn jsvn-btn--coral" href="<?php echo esc_url( home_url( '/join/' ) ); ?>" style="width:100%;justify-content:center;">入会案内を見る</a>
	</section>

</aside>
