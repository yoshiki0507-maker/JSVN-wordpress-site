<?php
/**
 * 404 ページ
 *
 * @package JSVN
 */

get_header();
?>

<section class="jsvn-pagehero">
	<div class="jsvn-container">
		<h1>ページが見つかりませんでした</h1>
	</div>
</section>

<div class="jsvn-content">
	<div class="jsvn-container">
		<div class="jsvn-article" style="text-align:center;">
			<p style="font-family:var(--jsvn-serif);font-size:1.3rem;color:var(--jsvn-navy);">404 Not Found</p>
			<p>お探しのページは移動または削除された可能性があります。<br>お手数ですが、トップページからお探しください。</p>
			<div style="margin-top:1.5rem;">
				<a class="jsvn-btn jsvn-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">トップページへ戻る</a>
			</div>
			<div style="margin-top:2rem;max-width:420px;margin-inline:auto;">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
