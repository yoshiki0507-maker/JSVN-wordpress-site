<?php
/**
 * 検索フォーム
 *
 * @package JSVN
 */
?>
<form role="search" method="get" class="jsvn-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:flex;gap:.5rem;">
	<label class="screen-reader-text" for="jsvn-s">サイト内検索</label>
	<input type="search" id="jsvn-s" name="s" value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="キーワードで検索"
		style="flex:1;padding:.7em 1em;border:1px solid var(--jsvn-line);border-radius:8px;font-family:inherit;">
	<button type="submit" class="jsvn-btn jsvn-btn--primary">検索</button>
</form>
