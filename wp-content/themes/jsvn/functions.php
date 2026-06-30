<?php
/**
 * JSVN テーマ functions
 *
 * 日本訪問看護学会 公式サイト用カスタムテーマ。
 *
 * @package JSVN
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 直接アクセスを禁止
}

define( 'JSVN_VERSION', '1.0.0' );

/**
 * テーマの基本セットアップ
 */
function jsvn_setup() {
	// 自動でタイトルタグを出力
	add_theme_support( 'title-tag' );

	// アイキャッチ画像
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'jsvn-card', 720, 405, true );

	// HTML5 マークアップ
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	// カスタムロゴ
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// 投稿フォーマット・RSS
	add_theme_support( 'automatic-feed-links' );

	// ブロックエディタの基本スタイル
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	// ナビゲーションメニュー
	register_nav_menus( array(
		'primary' => __( 'グローバルメニュー', 'jsvn' ),
		'footer'  => __( 'フッターメニュー', 'jsvn' ),
		'utility' => __( 'ユーティリティ（上部）メニュー', 'jsvn' ),
	) );

	// 翻訳ファイル
	load_theme_textdomain( 'jsvn', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'jsvn_setup' );

/**
 * CSS / JS の読み込み
 */
function jsvn_assets() {
	// Google Fonts（明朝＝学術 / ゴシック＝親しみ）
	wp_enqueue_style(
		'jsvn-fonts',
		'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Noto+Serif+JP:wght@500;600;700&display=swap',
		array(),
		null
	);

	// メインスタイル
	wp_enqueue_style( 'jsvn-style', get_stylesheet_uri(), array( 'jsvn-fonts' ), JSVN_VERSION );

	// メインスクリプト
	wp_enqueue_script(
		'jsvn-script',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		JSVN_VERSION,
		true
	);

	// コメント返信
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'jsvn_assets' );

/**
 * preconnect（フォント高速化）
 */
function jsvn_resource_hints( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$hints[] = array( 'href' => 'https://fonts.googleapis.com' );
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'jsvn_resource_hints', 10, 2 );

/**
 * ウィジェットエリア
 */
function jsvn_widgets() {
	register_sidebar( array(
		'name'          => __( 'サイドバー', 'jsvn' ),
		'id'            => 'sidebar-1',
		'description'   => __( '記事・固定ページの横に表示されるエリアです。', 'jsvn' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'jsvn_widgets' );

/**
 * カスタム投稿タイプ: 学術大会 / 研究会（イベント）
 */
function jsvn_register_post_types() {
	register_post_type( 'jsvn_event', array(
		'labels' => array(
			'name'          => __( '学術大会・研究会', 'jsvn' ),
			'singular_name' => __( '学術大会・研究会', 'jsvn' ),
			'add_new_item'  => __( '新しい大会・研究会を追加', 'jsvn' ),
			'edit_item'     => __( '大会・研究会を編集', 'jsvn' ),
			'menu_name'     => __( '学術大会', 'jsvn' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-megaphone',
		'rewrite'      => array( 'slug' => 'events' ),
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest' => true,
	) );

	// 学会誌・論文（ジャーナル）
	register_post_type( 'jsvn_journal', array(
		'labels' => array(
			'name'          => __( '学会誌・刊行物', 'jsvn' ),
			'singular_name' => __( '学会誌・刊行物', 'jsvn' ),
			'menu_name'     => __( '学会誌', 'jsvn' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-book-alt',
		'rewrite'      => array( 'slug' => 'journal' ),
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'jsvn_register_post_types' );

/**
 * お知らせ用カテゴリー（投稿のカテゴリー色分けに利用）
 * 既定カテゴリのスラッグから表示クラスを返す
 */
function jsvn_category_class( $category_slug ) {
	$map = array(
		'event'    => 'jsvn-news__cat--event',
		'academic' => 'jsvn-news__cat--academic',
	);
	return isset( $map[ $category_slug ] ) ? $map[ $category_slug ] : '';
}

/**
 * 抜粋の文字数・末尾
 */
function jsvn_excerpt_length( $length ) {
	return 80;
}
add_filter( 'excerpt_length', 'jsvn_excerpt_length' );

function jsvn_excerpt_more( $more ) {
	return ' …';
}
add_filter( 'excerpt_more', 'jsvn_excerpt_more' );

/**
 * 簡易パンくず
 */
function jsvn_breadcrumb() {
	if ( is_front_page() ) {
		return;
	}
	echo '<nav class="jsvn-breadcrumb" aria-label="breadcrumb">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">ホーム</a>';

	if ( is_singular( 'post' ) ) {
		echo ' &rsaquo; <span>お知らせ</span>';
	} elseif ( is_home() ) {
		echo ' &rsaquo; <span>お知らせ</span>';
	} elseif ( is_post_type_archive() ) {
		echo ' &rsaquo; <span>' . esc_html( post_type_archive_title( '', false ) ) . '</span>';
	} elseif ( is_page() ) {
		$post = get_post();
		if ( $post && $post->post_parent ) {
			echo ' &rsaquo; <a href="' . esc_url( get_permalink( $post->post_parent ) ) . '">' . esc_html( get_the_title( $post->post_parent ) ) . '</a>';
		}
		echo ' &rsaquo; <span>' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_category() || is_archive() ) {
		echo ' &rsaquo; <span>' . esc_html( wp_strip_all_tags( get_the_archive_title() ) ) . '</span>';
	} elseif ( is_search() ) {
		echo ' &rsaquo; <span>検索結果</span>';
	} elseif ( is_single() ) {
		echo ' &rsaquo; <span>' . esc_html( get_the_title() ) . '</span>';
	}
	echo '</nav>';
}

/**
 * ページネーション出力
 */
function jsvn_pagination() {
	$links = paginate_links( array(
		'type'      => 'list',
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;',
	) );
	if ( $links ) {
		echo '<div class="jsvn-pagination">' . wp_kses_post( str_replace( array( '<ul class=\'page-numbers\'>', '</ul>', '<li>', '</li>' ), '', $links ) ) . '</div>';
	}
}

/**
 * 抜粋（ない場合は本文から生成）を安全に取得
 */
function jsvn_get_excerpt( $length = 90 ) {
	$text = get_the_excerpt();
	$text = wp_strip_all_tags( $text );
	if ( mb_strlen( $text ) > $length ) {
		$text = mb_substr( $text, 0, $length ) . '…';
	}
	return $text;
}

/**
 * 初回有効化時にパーマリンクを更新（カスタム投稿タイプ用）
 */
function jsvn_flush_rewrite() {
	jsvn_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'jsvn_flush_rewrite' );

/**
 * body_class に補助クラスを追加
 */
function jsvn_body_classes( $classes ) {
	if ( ! is_front_page() ) {
		$classes[] = 'jsvn-subpage';
	}
	return $classes;
}
add_filter( 'body_class', 'jsvn_body_classes' );
