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

define( 'JSVN_VERSION', '1.2.0' );

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

	// トップページ右サイドの「協賛企業・企業バナー」枠
	register_sidebar( array(
		'name'          => __( '協賛企業バナー（トップ右）', 'jsvn' ),
		'id'            => 'sponsors',
		'description'   => __( 'トップページ右側に表示される企業様用のバナー・リンク枠です。画像ウィジェットやカスタムHTMLで企業バナーを追加できます。', 'jsvn' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
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

/**
 * 会員ログイン先URL（学会バンクを利用予定）
 *
 * 実URLが決まったら [外観 > カスタマイズ] の「学会バンクのログインURL」に設定するか、
 * jsvn_login_url フィルターで上書きしてください。
 */
function jsvn_login_url() {
	$url = get_theme_mod( 'jsvn_gakkai_bank_url', '' );
	if ( empty( $url ) ) {
		$url = '#gakkai-bank'; // 未設定時のプレースホルダー
	}
	return apply_filters( 'jsvn_login_url', $url );
}

/**
 * カスタマイザーに「メインビジュアル画像」設定を追加
 */
function jsvn_customize_hero( $wp_customize ) {
	$wp_customize->add_section( 'jsvn_hero', array(
		'title'       => __( 'メインビジュアル（トップ画像）', 'jsvn' ),
		'priority'    => 30,
		'description' => __( 'トップ最上部の画像。1〜3枚まで登録でき、2枚以上でスライドショーになります。未設定のときは深緑の背景にキャッチコピーのみを上品に表示します。横長（例 1600×760px 程度）推奨。', 'jsvn' ),
	) );

	$slots = array(
		'jsvn_hero_image'   => __( 'メインビジュアル画像 1', 'jsvn' ),
		'jsvn_hero_image_2' => __( 'メインビジュアル画像 2（任意・スライド用）', 'jsvn' ),
		'jsvn_hero_image_3' => __( 'メインビジュアル画像 3（任意・スライド用）', 'jsvn' ),
	);
	foreach ( $slots as $id => $label ) {
		$wp_customize->add_setting( $id, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, array(
			'label'   => $label,
			'section' => 'jsvn_hero',
		) ) );
	}
}
add_action( 'customize_register', 'jsvn_customize_hero' );

/**
 * メインビジュアル画像のURL一覧を取得。
 * カスタマイザーで登録された画像のみを順番に返す（同梱のデフォルト画像は使わない）。
 */
function jsvn_hero_images() {
	$images = array();
	foreach ( array( 'jsvn_hero_image', 'jsvn_hero_image_2', 'jsvn_hero_image_3' ) as $key ) {
		$url = get_theme_mod( $key, '' );
		if ( $url ) {
			$images[] = $url;
		}
	}
	return $images;
}

/**
 * 先頭のメインビジュアル画像URL（後方互換用）。未設定なら空文字。
 */
function jsvn_hero_image_url() {
	$images = jsvn_hero_images();
	return ! empty( $images ) ? $images[0] : '';
}

/**
 * カスタマイザーに「学会バンクのログインURL」設定を追加
 */
function jsvn_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'jsvn_membership', array(
		'title'    => __( '会員・学会バンク', 'jsvn' ),
		'priority' => 40,
	) );
	$wp_customize->add_setting( 'jsvn_gakkai_bank_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'jsvn_gakkai_bank_url', array(
		'label'       => __( '学会バンク ログインURL', 'jsvn' ),
		'description' => __( '会員ログインのリンク先。学会バンクの会員ページURLを入力してください。', 'jsvn' ),
		'section'     => 'jsvn_membership',
		'type'        => 'url',
	) );
}
add_action( 'customize_register', 'jsvn_customize_register' );

/**
 * グローバルメニューの構造（メニュー未設定時のフォールバック用）
 *
 * 管理画面 [外観 > メニュー] で正式なメニューを作成すればそちらが優先されます。
 * ここは「まだメニューを作っていない段階」でも学会サイトらしい構成を表示するための定義です。
 */
function jsvn_menu_structure() {
	return array(
		array(
			'label'    => '学会について',
			'url'      => home_url( '/about/' ),
			'children' => array(
				array( 'label' => '設立趣旨・理念', 'url' => home_url( '/founding/' ) ),
				array( 'label' => '理事長挨拶', 'url' => home_url( '/about-greeting/' ) ),
				array( 'label' => '学会概要', 'url' => home_url( '/about/' ) ),
				array( 'label' => '会員数・会員分布', 'url' => home_url( '/members/' ) ),
				array( 'label' => '定款・規程', 'url' => home_url( '/articles/' ) ),
				array( 'label' => '役員・代議員・名誉会員紹介', 'url' => home_url( '/officers/' ) ),
				array( 'label' => '代議員・役員選出規定', 'url' => home_url( '/election-rules/' ) ),
				array( 'label' => '委員会・部会', 'url' => home_url( '/committees/' ) ),
				array( 'label' => '情報公開（事業報告・決算）', 'url' => home_url( '/reports/' ) ),
			),
		),
		array(
			'label'    => '倫理・COI',
			'url'      => home_url( '/ethics-code/' ),
			'children' => array(
				array( 'label' => '倫理綱領', 'url' => home_url( '/ethics-code/' ) ),
				array( 'label' => '科学者の行動規範', 'url' => home_url( '/scientist-conduct/' ) ),
				array( 'label' => '研究倫理ガイドライン', 'url' => home_url( '/research-ethics/' ) ),
				array( 'label' => '利益相反（COI）', 'url' => home_url( '/coi/' ) ),
			),
		),
		array(
			'label'    => '学術活動',
			'url'      => home_url( '/events/' ),
			'children' => array(
				array( 'label' => '学術大会・研究会', 'url' => home_url( '/events/' ) ),
				array( 'label' => '学会誌・投稿規定', 'url' => home_url( '/journal-submission/' ) ),
				array( 'label' => 'ニュースレター', 'url' => home_url( '/newsletter/' ) ),
				array( 'label' => '研修会・セミナー', 'url' => home_url( '/seminars/' ) ),
				array( 'label' => '表彰・研究助成', 'url' => home_url( '/awards/' ) ),
			),
		),
		array( 'label' => '認定制度', 'url' => home_url( '/certification/' ) ),
		array(
			'label'    => '入会案内',
			'url'      => home_url( '/join/' ),
			'children' => array(
				array( 'label' => '入会のご案内', 'url' => home_url( '/join/' ) ),
				array( 'label' => '各種手続き・様式ダウンロード', 'url' => home_url( '/downloads/' ) ),
				array( 'label' => 'よくある質問（FAQ）', 'url' => home_url( '/faq/' ) ),
			),
		),
		array( 'label' => '訪問看護とは？', 'url' => home_url( '/visiting-nursing/' ) ),
		array( 'label' => 'お知らせ', 'url' => home_url( '/news/' ) ),
		array( 'label' => 'お問い合わせ', 'url' => home_url( '/contact/' ) ),
	);
}

/**
 * URLを「実在する固定ページのパーマリンク」に解決する。
 * パーマリンク設定に依存せず正しいURLになるので、リンク切れ（404）を防ぐ。
 */
function jsvn_resolve_url( $url ) {
	$path = trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
	if ( '' === $path ) {
		return $url;
	}
	$page = get_page_by_path( $path );
	if ( $page ) {
		return get_permalink( $page->ID );
	}
	return $url;
}

/**
 * フォールバックメニューを出力（メニュー未割り当て時の仮表示）
 */
function jsvn_render_fallback_menu() {
	echo '<ul class="jsvn-nav__menu">';
	foreach ( jsvn_menu_structure() as $item ) {
		$has_children = ! empty( $item['children'] );
		$li_class     = $has_children ? ' class="menu-item-has-children"' : '';
		echo '<li' . $li_class . '>';
		echo '<a href="' . esc_url( jsvn_resolve_url( $item['url'] ) ) . '">' . esc_html( $item['label'] );
		echo '</a>';
		if ( $has_children ) {
			echo '<ul class="sub-menu">';
			foreach ( $item['children'] as $child ) {
				echo '<li><a href="' . esc_url( jsvn_resolve_url( $child['url'] ) ) . '">' . esc_html( $child['label'] ) . '</a></li>';
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}

/**
 * メニュー項目を1つ追加するヘルパー。
 *
 * URLがサイト内の固定ページに一致すればページ項目として、
 * それ以外はカスタムリンクとして登録する（あとから [外観 > メニュー] で自由に変更できます）。
 *
 * @param int    $menu_id 追加先メニューID
 * @param int    $parent  親メニュー項目ID（0でトップ階層）
 * @param string $label   表示名
 * @param string $url     リンク先URL
 * @return int 追加されたメニュー項目ID
 */
function jsvn_add_menu_item( $menu_id, $parent, $label, $url ) {
	$args = array(
		'menu-item-title'     => $label,
		'menu-item-parent-id' => $parent,
		'menu-item-status'    => 'publish',
	);

	// URLのパスからサイト内固定ページを探す
	$path = trim( wp_parse_url( $url, PHP_URL_PATH ), '/' );
	$page = $path ? get_page_by_path( $path ) : null;

	if ( $page ) {
		$args['menu-item-type']      = 'post_type';
		$args['menu-item-object']    = 'page';
		$args['menu-item-object-id'] = $page->ID;
	} else {
		$args['menu-item-type'] = 'custom';
		$args['menu-item-url']  = $url;
	}

	return wp_update_nav_menu_item( $menu_id, 0, $args );
}

/**
 * テーマ有効化時に、編集可能なグローバルメニューを自動作成する。
 *
 * [外観 > メニュー] に「グローバルメニュー」が作られ、'primary' の位置に割り当てられます。
 * 項目・サブ項目のリンク付けや並び替えは、管理画面から自由に編集できます。
 * すでにメニューが割り当て済み、または同名メニューがある場合はスキップします。
 */
function jsvn_seed_menu() {
	if ( ! function_exists( 'wp_update_nav_menu_item' ) ) {
		require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
	}

	$menu_name = 'グローバルメニュー';

	// すでに 'primary' に何か割り当て済みなら触らない
	$locations = get_theme_mod( 'nav_menu_locations' );
	if ( ! empty( $locations['primary'] ) && is_nav_menu( $locations['primary'] ) ) {
		return;
	}

	// 同名メニューがあれば再利用、なければ新規作成
	$menu = wp_get_nav_menu_object( $menu_name );
	if ( $menu ) {
		$menu_id = $menu->term_id;
		// すでに項目があるメニューには追加しない（二重登録防止）
		$existing = wp_get_nav_menu_items( $menu_id );
		if ( ! empty( $existing ) ) {
			$menu_id_to_assign = $menu_id;
			$menu_id           = 0;
		}
	} else {
		$menu_id = wp_create_nav_menu( $menu_name );
	}

	if ( $menu_id && ! is_wp_error( $menu_id ) ) {
		foreach ( jsvn_menu_structure() as $item ) {
			$parent_id = jsvn_add_menu_item( $menu_id, 0, $item['label'], $item['url'] );
			if ( is_wp_error( $parent_id ) ) {
				continue;
			}
			if ( ! empty( $item['children'] ) ) {
				foreach ( $item['children'] as $child ) {
					jsvn_add_menu_item( $menu_id, $parent_id, $child['label'], $child['url'] );
				}
			}
		}
		$menu_id_to_assign = $menu_id;
	}

	// 'primary' の位置にメニューを割り当てる
	if ( ! empty( $menu_id_to_assign ) ) {
		$locations             = (array) get_theme_mod( 'nav_menu_locations' );
		$locations['primary']  = (int) $menu_id_to_assign;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}
// 固定ページ作成（jsvn_seed_pages）の後に走らせ、ページ項目として正しく紐づける
add_action( 'after_switch_theme', 'jsvn_seed_menu', 20 );

/**
 * テーマ有効化時に、メニューに対応する固定ページを自動作成する。
 *
 * すでに同じスラッグのページがあればスキップ（重複作成しない）。
 * 中身はあとから自由に編集できる「たたき台」です。
 */
function jsvn_seed_pages() {
	$lorem = "<p>このページは準備中です。内容が決まり次第、こちらに掲載します。</p>";

	$pages = array(
		'about-greeting' => array(
			'title'   => '理事長挨拶',
			'content' => "<p>このたび、日本訪問看護学会のホームページをご覧いただき、誠にありがとうございます。</p>\n<p>在宅医療の広がりとともに、訪問看護が担う役割はますます重要になっています。本会は、現場の実践知と科学的根拠を結び、訪問看護の質の向上と、地域で暮らす方々の「その人らしい療養」を支えてまいります。</p>\n<p>会員の皆さまとともに、温かく、そして学術的に確かな学会を育てていきたいと考えております。</p>\n<p style=\"text-align:right;\">日本訪問看護学会　理事長　○○　○○</p>",
		),
		'about' => array(
			'title'   => '学会概要',
			'content' => "<table><tbody>\n<tr><th>名称</th><td>日本訪問看護学会</td></tr>\n<tr><th>設立</th><td>2026年（予定）</td></tr>\n<tr><th>目的</th><td>訪問看護に関する学術研究の発展、人材育成、多職種連携の推進</td></tr>\n<tr><th>事務局</th><td>（準備中）</td></tr>\n</tbody></table>",
		),
		'articles' => array(
			'title'   => '定款',
			'content' => "<h2>第1章　総則</h2>\n<p>（名称）第1条　本会は、日本訪問看護学会と称する。</p>\n<p>（目的）第2条　本会は、訪問看護に関する学術の発展に寄与することを目的とする。</p>\n<h2>第2章　会員</h2>\n<p>第3条　本会の会員は、正会員・学生会員・賛助会員とする。</p>\n<p><em>※ 全文は準備中です。</em></p>",
		),
		'officers' => array(
			'title'   => '役員・代議員・名誉会員紹介',
			'content' => "<p>日本訪問看護学会の役員をご紹介します。（理事長1名・副理事長2名・常任理事3名・理事15名で構成）</p>",
		),
		'election-rules' => array(
			'title'   => '代議員・役員選出規定',
			'content' => "<p>本規定は、日本訪問看護学会の代議員および役員の選出方法について定めるものである。</p>\n<h2>第1条（代議員の選出）</h2>\n<p>（準備中）</p>\n<h2>第2条（役員の選出）</h2>\n<p>（準備中）</p>",
		),
		'ethics-code' => array(
			'title'   => '倫理綱領',
			'content' => "<p>日本訪問看護学会会員は、訪問看護の対象となるすべての人の尊厳と権利を尊重し、専門職としての倫理を遵守する。</p>\n<ol>\n<li>対象者の尊厳と自己決定を尊重する。</li>\n<li>専門的知識と技術の維持・向上に努める。</li>\n<li>研究にあたっては倫理的配慮を最優先する。</li>\n</ol>",
		),
		'scientist-conduct' => array(
			'title'   => '科学者の行動規範',
			'content' => "<p>本会会員は、研究者・専門職として、誠実かつ公正に行動する。</p>\n<p>捏造・改ざん・盗用などの研究不正を行わず、研究の自由と責任を自覚して活動する。（準備中）</p>",
		),
		'research-ethics' => array(
			'title'   => '研究倫理ガイドライン',
			'content' => "<p>本ガイドラインは、訪問看護に関する研究を行う会員が遵守すべき倫理的事項を示すものである。</p>\n<h2>1. 研究対象者への配慮</h2>\n<p>インフォームド・コンセントの取得、個人情報の保護に十分配慮する。（準備中）</p>",
		),
		'balance-sheet' => array(
			'title'   => '貸借対照表',
			'content' => "<p>本会の財務状況を公開しています。</p>\n<table><tbody>\n<tr><th>資産の部</th><td>（準備中）</td></tr>\n<tr><th>負債の部</th><td>（準備中）</td></tr>\n<tr><th>正味財産の部</th><td>（準備中）</td></tr>\n</tbody></table>\n<p><em>※ 事業年度ごとの貸借対照表・活動計算書を掲載予定です。</em></p>",
		),
		'visiting-nursing' => array(
			'title'   => '訪問看護とは？',
			'content' => "<p>訪問看護とは、看護師などが利用者のご自宅を訪問し、主治医の指示や多職種との連携のもとで、その人が住み慣れた地域・自宅で自分らしく療養生活を送れるように支える看護サービスです。</p>\n<p>病気や障がい、加齢などで通院が難しい方でも、赤ちゃんからご高齢の方まで幅広く利用できます。</p>\n<h2>訪問看護でできること</h2>\n<ul>\n<li>健康状態のチェック・病状の観察</li>\n<li>医療的な処置やケア</li>\n<li>服薬の管理・支援</li>\n<li>療養生活・介護のご相談</li>\n<li>ご本人・ご家族への支援</li>\n</ul>",
		),
		'newsletter' => array(
			'title'   => 'ニュースレター',
			'content' => "<p>会員向けニュースレターを発行予定です。発行後、こちらにバックナンバーを掲載します。</p>\n<ul>\n<li>創刊準備号（近日公開）</li>\n</ul>",
		),
		'awards' => array(
			'title'   => '表彰・研究助成',
			'content' => "<h2>表彰</h2>\n<p>訪問看護の実践・研究に顕著な貢献をされた方を表彰します。</p>\n<ul>\n<li>学会賞（優れた研究業績）</li>\n<li>奨励賞（将来が期待される若手研究者）</li>\n<li>実践功労賞（訪問看護の実践・普及への貢献）</li>\n</ul>\n<h2>研究助成</h2>\n<p>訪問看護に関する研究を支援するため、研究助成を行います。募集要項・応募方法は準備中です。</p>",
		),
		'join' => array(
			'title'   => '入会のご案内',
			'content' => "<p>訪問看護に関わるすべての方を歓迎します。職種・経験は問いません。入会をご希望の方は、以下をご確認ください。</p>\n<h2>会員種別と年会費（予定）</h2>\n<h3>個人会員</h3>\n<table><tbody>\n<tr><th>訪問看護所属会員</th><td>年会費 5,000円</td></tr>\n<tr><th>通常会員</th><td>年会費 7,000円</td></tr>\n<tr><th>他職種連携会員</th><td>年会費 4,000円</td></tr>\n<tr><th>一般・学生会員</th><td>年会費 1,500円</td></tr>\n<tr><th>プラチナNs会員</th><td>年会費 2,000円</td></tr>\n</tbody></table>\n<h3>賛助・企業会員</h3>\n<table><tbody>\n<tr><th>賛助会員</th><td>年会費 15,000円（1口）</td></tr>\n<tr><th>企業会員</th><td>年会費 30,000円（1口）</td></tr>\n</tbody></table>\n<p><em>※ 会員種別の詳細・要件は今後確定します。</em></p>\n<h2>会員特典</h2>\n<ul>\n<li>学術集会・研究会への会員価格での参加</li>\n<li>学会誌・ニュースレターの購読</li>\n<li>研修プログラム／認定制度の利用</li>\n<li>多職種・全国の仲間とのネットワーク</li>\n</ul>\n<h2>お手続き</h2>\n<p>入会手続きは会員管理システム「学会バンク」を通じて行う予定です。準備が整い次第、こちらにお申し込みリンクを掲載します。各種様式は<a href=\"/downloads/\">各種手続き・様式ダウンロード</a>をご覧ください。</p>",
		),
		'contact' => array(
			'title'   => 'お問い合わせ',
			'content' => "<p>本会へのお問い合わせは、以下よりお願いいたします。</p>\n<p>メール：（準備中）<br>お問い合わせフォームを設置予定です。</p>",
		),

		// --- 追加ページ（他学会サイトの定番構成に合わせて拡充）---
		'members' => array(
			'title'   => '会員数・会員分布',
			'content' => "<p>日本訪問看護学会の会員は全国に広がっています。都道府県別の会員数を地図でご覧いただけます。</p>",
		),
		'founding' => array(
			'title'   => '設立趣旨・理念',
			'content' =>
				"<p style=\"font-size:1.15rem;color:#1f5a3c;font-weight:600;\">訪問看護師の臨床知を、社会を動かす力へ。</p>\n"
				. "<h2>設立趣旨</h2>\n"
				. "<p>我が国では、少子高齢化の進展、単身・高齢者のみ世帯の増加、疾病構造の変化、医療技術の高度化に伴い、療養の場は病院から地域・在宅へと大きく広がっています。がん、難病、認知症、精神疾患、小児・医療的ケア児、人生の最終段階に至るまで、在宅で支える対象はますます多様化し、訪問看護には高度な臨床判断と専門性、そして一人ひとりの暮らしに寄り添う実践力が求められています。訪問看護師は、利用者の生活の場に入り、病状だけでなく、その人の価値観や生活背景、家族、地域とのつながりを総合的に捉えながら、医療と生活を結ぶ重要な役割を担っています。</p>\n"
				. "<p>こうした時代の変化の中で、訪問看護は医療・介護・福祉・行政・地域をつなぐ存在として、その役割は年々広がり続けています。多職種との協働、地域包括ケアシステムの推進、ICTの活用、予防から看取りまで切れ目のない支援など、新たな実践が積み重ねられ、訪問看護師一人ひとりの経験や知見は、地域医療を支える貴重な財産となっています。</p>\n"
				. "<p>その一方で、日々の臨床で培われた優れた実践や工夫、地域連携の成果は、それぞれの事業所や地域に留まることも少なくありません。現場には多くの「知」が存在するにもかかわらず、それらを共有し、学術的に整理・発信し、次の実践へとつなげる機会は、まだ十分とはいえません。臨床で得られた経験を言語化し、研究として蓄積し、教育へ還元し、未来の訪問看護へつなげていくことは、訪問看護全体の発展に欠かすことのできない取り組みです。</p>\n"
				. "<p>2040年を見据え、地域で暮らし続ける人々を支える在宅医療・訪問看護の重要性は、今後さらに高まることが期待されます。その実現には、現場で培われた臨床知を社会全体で共有し、多職種や教育機関、研究者、行政と協働しながら、新たな価値を創造していく学術基盤が求められます。</p>\n"
				. "<p>日本訪問看護学会は、「訪問看護師の臨床知を、社会を動かす力へ。」という理念のもと、現場で生まれる疑問や工夫、成果を学術的知見へと高め、臨床・教育・研究・制度へ還元することを目的として設立します。本会は、現場で働く訪問看護師を中心に、教育機関、研究者、多職種、行政、地域社会と協働し、成果発表、事例報告、研究活動、人材育成、政策提言を通して、訪問看護の質の向上と社会的価値の発展に寄与することを目指します。</p>\n"
				. "<h2>理念</h2>\n"
				. "<blockquote>日本訪問看護学会は、訪問看護師の臨床知を可視化し、学術的知見として発展させ、その成果を臨床・教育・制度へ還元することにより、訪問看護の質の向上、訪問看護師の社会的価値の確立、ならびに地域で尊厳ある暮らしを支える社会の実現に寄与します。</blockquote>\n"
				. "<h2>三本柱</h2>\n"
				. "<h3>1．現場の臨床知を可視化する</h3>\n<p>訪問看護師の日々の実践に宿る判断、工夫、課題、成果を、事例発表・成果発表・ケースレポート等を通じて言語化し、可視化します。</p>\n"
				. "<h3>2．臨床知を学術的知見へ発展させる</h3>\n<p>訪問看護師の臨床知と、教育機関・研究者の研究力を融合し、現場の実践を研究へ発展させ、訪問看護の質向上に資する知見を創出します。</p>\n"
				. "<h3>3．生み出された知を臨床・教育・制度へ還元する</h3>\n<p>創出された知見を臨床現場へ還元し、訪問看護師の働きやすい環境づくり、訪問看護ステーションの質向上、教育体制の整備、制度・報酬の改善に資する基盤を築きます。</p>\n"
				. "<h2>シンボル ― クスノキ</h2>\n"
				. "<p>本会のシンボルであるクスノキには、「深く根を張り、長く成長し、多くの人々を包み支える」という願いを込めています。深く張る根は訪問看護師が積み重ねてきた臨床知を、力強い幹は学術としての確かな基盤を、そして大きく広がる枝葉は全国へ広がる学びと多職種とのつながりを表しています。人々に安らぎの木陰を与えるクスノキのように、日本訪問看護学会もまた、人と人、地域と地域、臨床と研究をつなぎ、誰もが住み慣れた地域でその人らしく暮らし続けられる社会の実現に貢献してまいります。</p>",
		),
		'committees' => array(
			'title'   => '委員会・部会',
			'content' => "<p>本会は、目的を達成するために各種委員会・部会を設置し、学術・教育・広報・倫理などの活動を行います。</p>\n<ul>\n<li>学術委員会</li>\n<li>編集委員会（学会誌）</li>\n<li>倫理委員会</li>\n<li>利益相反（COI）委員会</li>\n<li>教育・研修委員会</li>\n<li>広報委員会</li>\n</ul>\n<p><em>※ 各委員会の構成・活動内容は準備中です。</em></p>",
		),
		'reports' => array(
			'title'   => '情報公開（事業報告・決算）',
			'content' => "<p>本会は、運営の透明性を確保するため、事業計画・事業報告・決算などを公開します。</p>\n<h2>事業計画・事業報告</h2>\n<p>（準備中）</p>\n<h2>決算（貸借対照表・活動計算書）</h2>\n<p>年度ごとの<a href=\"/balance-sheet/\">貸借対照表</a>を掲載予定です。</p>\n<h2>総会資料</h2>\n<p>（準備中）</p>",
		),
		'coi' => array(
			'title'   => '利益相反（COI）',
			'content' => "<p>本会は、学術活動の公正性と信頼性を確保するため、利益相反（Conflict of Interest：COI）の管理に関する指針を定めます。</p>\n<h2>対象</h2>\n<ul>\n<li>学会誌への論文投稿者</li>\n<li>学術大会での発表者</li>\n<li>本会の役員・委員</li>\n</ul>\n<h2>COIの開示</h2>\n<p>論文投稿時・発表登録時に、過去一定期間の企業・団体等との経済的関係を開示していただきます。</p>\n<h2>指針・様式</h2>\n<p>COI管理指針および開示様式（PDF）は準備中です。確定後にこちらへ掲載します。</p>",
		),
		'journal-submission' => array(
			'title'   => '学会誌・投稿規定',
			'content' => "<p>本会は、訪問看護に関する学術論文を掲載する学会誌を発行します。</p>\n<h2>学会誌について</h2>\n<p>誌名・発行頻度・電子版（J-STAGE等）については準備中です。</p>\n<h2>投稿規定・執筆要領</h2>\n<p>投稿資格、原稿の種類（原著・研究報告・実践報告・総説など）、書式、文献の記載方法などを定めた投稿規定・執筆要領を掲載予定です。</p>\n<h2>査読について</h2>\n<p>投稿論文は、編集委員会による査読を経て採否を決定します。</p>\n<h2>バックナンバー</h2>\n<p>発行後、各号の目次・本文へのリンクを掲載します。</p>",
		),
		'seminars' => array(
			'title'   => '研修会・セミナー',
			'content' => "<p>会員の継続的な学びを支援するため、研修会・セミナー・教育講演を開催します。</p>\n<ul>\n<li>訪問看護実践セミナー</li>\n<li>研究方法・論文執筆セミナー</li>\n<li>オンライン教育講演</li>\n</ul>\n<p><em>※ 開催予定・申込方法は準備中です。</em></p>",
		),
		'certification' => array(
			'title'   => '認定制度',
			'content' => "<p>本会は「人材育成」を柱のひとつとし、訪問看護の質の向上に資する認定制度の整備を進めます。</p>\n<h2>制度の目的</h2>\n<p>訪問看護に関する高度な知識・実践力を持つ人材を認定し、生涯にわたる学びを支援します。</p>\n<h2>認定の区分（予定）</h2>\n<ul>\n<li>研修修了認定</li>\n<li>指導者認定</li>\n</ul>\n<p><em>※ 認定要件・申請方法は準備中です。</em></p>",
		),
		'downloads' => array(
			'title'   => '各種手続き・様式ダウンロード',
			'content' => "<p>入会・変更・退会などの各種手続きに必要な様式（PDF）を掲載します。</p>\n<table><tbody>\n<tr><th>入会申込書</th><td>準備中</td></tr>\n<tr><th>会員情報変更届</th><td>準備中</td></tr>\n<tr><th>退会届</th><td>準備中</td></tr>\n<tr><th>各種申請書（助成・表彰など）</th><td>準備中</td></tr>\n</tbody></table>\n<p>会員情報の変更・各種申請は、会員管理システム「学会バンク」からも行える予定です。</p>",
		),
		'faq' => array(
			'title'   => 'よくある質問（FAQ）',
			'content' => "<h2>入会について</h2>\n<p><strong>Q. 看護師以外でも入会できますか？</strong><br>A. はい。他職種連携会員など、職種を問わずご入会いただけます。</p>\n<p><strong>Q. 会費の支払い方法は？</strong><br>A. 会員管理システム「学会バンク」を通じたお支払いを予定しています。</p>\n<h2>学術大会について</h2>\n<p><strong>Q. 非会員でも参加できますか？</strong><br>A. 参加可能です（会員価格の適用は会員のみ）。</p>\n<p><em>※ 内容は今後追加します。</em></p>",
		),
		'links' => array(
			'title'   => '関連リンク',
			'content' => "<p>訪問看護・在宅ケアに関連する学会・団体・行政のリンク集です。</p>\n<ul>\n<li>関連学会（準備中）</li>\n<li>行政・関係機関（準備中）</li>\n<li>訪問看護関連団体（準備中）</li>\n</ul>",
		),
		'english' => array(
			'title'   => 'English',
			'content' => "<h2>The Japanese Society of Visiting Nursing</h2>\n<p>The Japanese Society of Visiting Nursing promotes research, education, and interprofessional collaboration in home-visit nursing, supporting people living with care in their communities.</p>\n<p><em>An English version of this website is under preparation.</em></p>",
		),
		'privacy' => array(
			'title'   => 'プライバシーポリシー',
			'content' => "<p>日本訪問看護学会（以下「本会」）は、会員および利用者の個人情報を適切に取り扱います。</p>\n<h2>1. 個人情報の利用目的</h2>\n<p>会員管理、学術大会・研修の運営、学会誌の発送、各種連絡等に利用します。</p>\n<h2>2. 第三者提供</h2>\n<p>法令に基づく場合を除き、本人の同意なく第三者に提供しません。</p>\n<h2>3. お問い合わせ</h2>\n<p>個人情報の取扱いに関するお問い合わせは、事務局までご連絡ください。</p>\n<p><em>※ 正式なポリシーは確定後に掲載します。</em></p>",
		),
		'sitemap' => array(
			'title'   => 'サイトマップ',
			'content' => "<p>本サイトの主なページ一覧です。（公開時に自動生成の設定も可能です）</p>\n<ul>\n<li>学会について（理事長挨拶／学会概要／定款・規程／役員紹介／選出規定／委員会／情報公開）</li>\n<li>倫理・COI（倫理綱領／科学者の行動規範／研究倫理ガイドライン／利益相反）</li>\n<li>学術活動（学術大会／学会誌・投稿規定／ニュースレター／研修会・セミナー／表彰・研究助成）</li>\n<li>認定制度</li>\n<li>入会案内（入会のご案内／各種様式／FAQ）</li>\n<li>訪問看護とは？／お知らせ／お問い合わせ</li>\n</ul>",
		),
		'tokushoho' => array(
			'title'   => '特定商取引法に基づく表記',
			'content' => "<p>会費等のオンライン決済を行う場合の表記です。</p>\n<table><tbody>\n<tr><th>販売事業者</th><td>日本訪問看護学会</td></tr>\n<tr><th>運営責任者</th><td>（準備中）</td></tr>\n<tr><th>所在地・連絡先</th><td>（準備中）</td></tr>\n<tr><th>お支払い方法</th><td>クレジットカード等（学会バンク）</td></tr>\n<tr><th>返金・キャンセル</th><td>（準備中）</td></tr>\n</tbody></table>\n<p><em>※ オンライン決済の要否に応じて記載します。</em></p>",
		),
		'accessibility' => array(
			'title'   => 'ウェブアクセシビリティ方針',
			'content' => "<p>本会は、どなたでも利用しやすいウェブサイトを目指し、ウェブアクセシビリティの向上に努めます。</p>\n<h2>目標とする適合レベル</h2>\n<p>JIS X 8341-3:2016 の適合レベル AA 準拠を目標とします。</p>\n<h2>取り組み</h2>\n<ul>\n<li>文字サイズや色のコントラストへの配慮</li>\n<li>キーボード操作への対応</li>\n<li>読みやすい文章・構成</li>\n</ul>",
		),
	);

	foreach ( $pages as $slug => $data ) {
		if ( get_page_by_path( $slug, OBJECT, 'page' ) ) {
			continue; // 既に存在すればスキップ
		}
		wp_insert_post( array(
			'post_title'   => $data['title'],
			'post_name'    => $slug,
			'post_content' => $data['content'] ? $data['content'] : $lorem,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		) );
	}
}
add_action( 'after_switch_theme', 'jsvn_seed_pages' );

/* =============================================================
 *  役員名簿（顔写真＋所属＋資格＋経歴）
 * ============================================================= */

/**
 * 役員のカスタム投稿タイプ
 */
function jsvn_register_officer_cpt() {
	register_post_type( 'jsvn_officer', array(
		'labels' => array(
			'name'          => __( '役員名簿', 'jsvn' ),
			'singular_name' => __( '役員', 'jsvn' ),
			'add_new_item'  => __( '役員を追加', 'jsvn' ),
			'edit_item'     => __( '役員を編集', 'jsvn' ),
			'menu_name'     => __( '役員名簿', 'jsvn' ),
		),
		'public'       => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-groups',
		'rewrite'      => array( 'slug' => 'officer' ),
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'jsvn_register_officer_cpt' );

/**
 * 役職の選択肢（表示順もこの順）
 */
function jsvn_officer_roles() {
	return array( '理事長', '副理事長', '常任理事', '理事', '監事', '名誉会員' );
}

/**
 * 入力欄（役職・所属・資格）のメタボックス
 */
function jsvn_officer_metabox() {
	add_meta_box( 'jsvn_officer_meta', __( '役員情報（役職・所属・資格）', 'jsvn' ), 'jsvn_officer_metabox_cb', 'jsvn_officer', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'jsvn_officer_metabox' );

function jsvn_officer_metabox_cb( $post ) {
	wp_nonce_field( 'jsvn_officer_save', 'jsvn_officer_nonce' );
	$role    = get_post_meta( $post->ID, '_jsvn_role', true );
	$affil   = get_post_meta( $post->ID, '_jsvn_affiliation', true );
	$license = get_post_meta( $post->ID, '_jsvn_license', true );

	echo '<p><label><strong>役職</strong><br><select name="jsvn_role" style="width:100%;">';
	foreach ( jsvn_officer_roles() as $r ) {
		echo '<option value="' . esc_attr( $r ) . '"' . selected( $role, $r, false ) . '>' . esc_html( $r ) . '</option>';
	}
	echo '</select></label></p>';
	echo '<p><label><strong>所属・肩書</strong><br><input type="text" name="jsvn_affiliation" value="' . esc_attr( $affil ) . '" style="width:100%;" placeholder="例）○○大学大学院 教授"></label></p>';
	echo '<p><label><strong>保有資格・ライセンス</strong><br><input type="text" name="jsvn_license" value="' . esc_attr( $license ) . '" style="width:100%;" placeholder="例）看護師／保健師／博士（看護学）"></label></p>';
	echo '<p style="color:#777;font-size:12px;line-height:1.6;">顔写真は右の「アイキャッチ画像」、経歴は本文に入力してください。並び順は「順序」で調整できます。</p>';
}

function jsvn_officer_save( $post_id ) {
	if ( ! isset( $_POST['jsvn_officer_nonce'] ) || ! wp_verify_nonce( $_POST['jsvn_officer_nonce'], 'jsvn_officer_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array( 'jsvn_role' => '_jsvn_role', 'jsvn_affiliation' => '_jsvn_affiliation', 'jsvn_license' => '_jsvn_license' ) as $field => $key ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
}
add_action( 'save_post_jsvn_officer', 'jsvn_officer_save' );

/**
 * 役員名簿を役職ごとにグループ表示
 */
function jsvn_render_officers() {
	$q = new WP_Query( array(
		'post_type'      => 'jsvn_officer',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	) );
	if ( ! $q->have_posts() ) {
		echo '<p>役員情報は準備中です。</p>';
		return;
	}
	$grouped = array();
	while ( $q->have_posts() ) {
		$q->the_post();
		$r = get_post_meta( get_the_ID(), '_jsvn_role', true );
		if ( ! $r ) {
			$r = '理事';
		}
		$grouped[ $r ][] = get_the_ID();
	}
	wp_reset_postdata();

	foreach ( jsvn_officer_roles() as $role ) {
		if ( empty( $grouped[ $role ] ) ) {
			continue;
		}
		$is_lead = ( '理事長' === $role );
		echo '<h2 class="jsvn-officer-role-h">' . esc_html( $role ) . '<span>' . count( $grouped[ $role ] ) . '名</span></h2>';
		echo '<div class="jsvn-officers' . ( $is_lead ? ' jsvn-officers--lead' : '' ) . '">';
		foreach ( $grouped[ $role ] as $id ) {
			$affil   = get_post_meta( $id, '_jsvn_affiliation', true );
			$license = get_post_meta( $id, '_jsvn_license', true );
			$bio     = get_post_field( 'post_content', $id );
			echo '<article class="jsvn-officer' . ( $is_lead ? ' jsvn-officer--lead' : '' ) . '">';
			echo '<div class="jsvn-officer__photo">';
			if ( has_post_thumbnail( $id ) ) {
				echo get_the_post_thumbnail( $id, 'medium' );
			} else {
				echo '<span class="jsvn-officer__noimg" aria-hidden="true"></span>';
			}
			echo '</div>';
			echo '<div class="jsvn-officer__body">';
			echo '<p class="jsvn-officer__role">' . esc_html( $role ) . '</p>';
			echo '<h3 class="jsvn-officer__name">' . esc_html( get_the_title( $id ) ) . '</h3>';
			if ( $affil ) {
				echo '<p class="jsvn-officer__affil">' . esc_html( $affil ) . '</p>';
			}
			if ( $license ) {
				echo '<p class="jsvn-officer__license"><span>資格</span>' . esc_html( $license ) . '</p>';
			}
			if ( $bio ) {
				echo '<div class="jsvn-officer__bio">' . wp_kses_post( wpautop( $bio ) ) . '</div>';
			}
			echo '</div></article>';
		}
		echo '</div>';
	}
}

/**
 * サンプル役員を初期投入（実構成：理事長1／副理事長2／常任理事3／理事15）
 * ※ 氏名・所属・資格・顔写真・経歴は管理画面から編集してください。
 */
function jsvn_seed_officers() {
	$existing = get_posts( array( 'post_type' => 'jsvn_officer', 'posts_per_page' => 1, 'fields' => 'ids', 'post_status' => 'any' ) );
	if ( $existing ) {
		return;
	}
	$samples = array(
		array( '理事長', '○○ ○○', '○○大学大学院 教授', '看護師／保健師／博士（看護学）' ),
		array( '副理事長', '○○ ○○', '○○訪問看護ステーション 統括所長', '看護師／認定看護管理者' ),
		array( '副理事長', '○○ ○○', '○○医科大学 教授', '医師／医学博士' ),
		array( '常任理事', '○○ ○○', '○○大学 准教授', '看護師／博士（看護学）' ),
		array( '常任理事', '○○ ○○', '○○在宅ケア研究所 所長', '看護師／保健師' ),
		array( '常任理事', '○○ ○○', '○○訪問看護ステーション 所長', '看護師／緩和ケア認定看護師' ),
	);
	// 理事15名
	$directors = array(
		'○○大学 講師', '○○病院 看護部長', '○○訪問看護ステーション 管理者',
		'○○大学 教授', '○○訪問看護ステーション 所長', '○○医療センター 看護師長',
		'○○大学 准教授', '○○在宅クリニック 看護師', '○○訪問看護ステーション 統括',
		'○○短期大学 教授', '○○訪問看護ステーション 副所長', '○○病院 訪問看護室長',
		'○○大学 助教', '○○地域包括支援センター 保健師', '○○訪問看護ステーション 管理者',
	);
	foreach ( $directors as $affil ) {
		$samples[] = array( '理事', '○○ ○○', $affil, '看護師' );
	}
	$i = 0;
	foreach ( $samples as $s ) {
		$id = wp_insert_post( array(
			'post_type'    => 'jsvn_officer',
			'post_status'  => 'publish',
			'post_title'   => $s[1],
			'post_content' => '○○年○○大学卒業。○○病院、○○訪問看護ステーション勤務を経て現職。専門は在宅看護・訪問看護。（プロフィールは編集画面から差し替えできます）',
			'menu_order'   => $i,
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_jsvn_role', $s[0] );
			update_post_meta( $id, '_jsvn_affiliation', $s[2] );
			update_post_meta( $id, '_jsvn_license', $s[3] );
		}
		$i++;
	}
}
add_action( 'after_switch_theme', 'jsvn_seed_officers' );

/* =============================================================
 *  SNS（Instagram / X / Facebook / YouTube）とブログ
 * ============================================================= */

/**
 * SNSのURLをカスタマイザーに追加
 */
function jsvn_customize_sns( $wp_customize ) {
	$wp_customize->add_section( 'jsvn_sns', array(
		'title'    => __( 'SNS・ソーシャル', 'jsvn' ),
		'priority' => 45,
	) );
	$networks = array(
		'instagram' => 'Instagram のURL',
		'x'         => 'X（旧Twitter）のURL',
		'facebook'  => 'Facebook のURL',
		'youtube'   => 'YouTube のURL',
	);
	foreach ( $networks as $key => $label ) {
		$wp_customize->add_setting( 'jsvn_sns_' . $key, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'jsvn_sns_' . $key, array(
			'label'   => $label,
			'section' => 'jsvn_sns',
			'type'    => 'url',
		) );
	}
}
add_action( 'customize_register', 'jsvn_customize_sns' );

/**
 * 設定済みのSNSリンク一覧を返す
 */
function jsvn_sns_links() {
	$links = array();
	foreach ( array( 'instagram', 'x', 'facebook', 'youtube' ) as $key ) {
		$url = get_theme_mod( 'jsvn_sns_' . $key, '' );
		if ( $url ) {
			$links[ $key ] = $url;
		}
	}
	// プレビュー用：未設定でもアイコンを見せたい場合のフォールバック
	if ( empty( $links ) ) {
		$links = array( 'instagram' => '#', 'x' => '#', 'facebook' => '#' );
	}
	return $links;
}

/**
 * SNSアイコン（インラインSVG）
 */
function jsvn_sns_icon( $network ) {
	$icons = array(
		'instagram' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>',
		'x'         => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-7.5 8.6L23 22h-6.8l-5-6.6L5.4 22H2.3l8-9.2L1.6 2h6.9l4.6 6.1L18.9 2zm-2.4 18h1.9L7.6 4H5.6l10.9 16z"/></svg>',
		'facebook'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12z"/></svg>',
		'youtube'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23 12s0-3.2-.4-4.7a2.5 2.5 0 0 0-1.8-1.8C19.3 5 12 5 12 5s-7.3 0-8.8.5A2.5 2.5 0 0 0 1.4 7.3C1 8.8 1 12 1 12s0 3.2.4 4.7a2.5 2.5 0 0 0 1.8 1.8c1.5.5 8.8.5 8.8.5s7.3 0 8.8-.5a2.5 2.5 0 0 0 1.8-1.8C23 15.2 23 12 23 12zM9.8 15.3V8.7l6 3.3-6 3.3z"/></svg>',
	);
	return isset( $icons[ $network ] ) ? $icons[ $network ] : '';
}

/**
 * SNSアイコン列を出力
 */
function jsvn_sns_icons( $extra_class = '' ) {
	$links = jsvn_sns_links();
	if ( empty( $links ) ) {
		return;
	}
	$names = array( 'instagram' => 'Instagram', 'x' => 'X', 'facebook' => 'Facebook', 'youtube' => 'YouTube' );
	echo '<div class="jsvn-sns ' . esc_attr( $extra_class ) . '">';
	foreach ( $links as $key => $url ) {
		echo '<a class="jsvn-sns__link jsvn-sns__link--' . esc_attr( $key ) . '" href="' . esc_url( $url ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $names[ $key ] ) . '">' . jsvn_sns_icon( $key ) . '</a>';
	}
	echo '</div>';
}

/* =============================================================
 *  会員数マップ（都道府県別の会員数を日本地図で可視化）
 * ============================================================= */

/**
 * 「名称,人数」形式の複数行テキストを連想配列に変換する。
 * 例）東京都,120  → array( '東京都' => 120 )
 * カンマは半角・全角どちらでもOK。空行や不正な行は無視。
 */
function jsvn_parse_count_lines( $text ) {
	$out = array();
	if ( ! is_string( $text ) || '' === trim( $text ) ) {
		return $out;
	}
	$lines = preg_split( '/\r\n|\r|\n/', $text );
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		// 半角/全角カンマ・読点・タブを区切りとして許可
		$parts = preg_split( '/[,，、\t]+/u', $line );
		if ( count( $parts ) < 2 ) {
			continue;
		}
		$name = trim( $parts[0] );
		$num  = (int) preg_replace( '/[^0-9]/', '', $parts[1] );
		if ( '' !== $name ) {
			$out[ $name ] = $num;
		}
	}
	return $out;
}

/**
 * 都道府県別の会員数（初期サンプル値）。
 */
function jsvn_member_counts_defaults() {
	return array(
		'北海道' => 86, '青森県' => 12, '秋田県' => 9, '岩手県' => 11, '山形県' => 10, '宮城県' => 24,
		'新潟県' => 18, '福島県' => 16, '長野県' => 20, '群馬県' => 15, '栃木県' => 14, '茨城県' => 19,
		'富山県' => 8, '石川県' => 10, '福井県' => 7, '山梨県' => 7, '埼玉県' => 38, '千葉県' => 34,
		'岐阜県' => 13, '滋賀県' => 9, '東京都' => 120, '神奈川県' => 64, '静岡県' => 26, '愛知県' => 52,
		'三重県' => 12, '京都府' => 28, '兵庫県' => 40, '奈良県' => 11, '大阪府' => 78, '和歌山県' => 8,
		'鳥取県' => 5, '岡山県' => 17, '島根県' => 6, '広島県' => 25, '山口県' => 12, '香川県' => 9,
		'徳島県' => 7, '愛媛県' => 11, '高知県' => 6, '福岡県' => 44, '大分県' => 10, '佐賀県' => 6,
		'熊本県' => 15, '宮崎県' => 8, '長崎県' => 10, '鹿児島県' => 12, '沖縄県' => 9,
	);
}

/**
 * 連想配列を「名称,人数」形式の複数行テキストに変換（Customizerの初期値・表示用）。
 */
function jsvn_counts_to_text( $counts ) {
	$lines = array();
	foreach ( $counts as $name => $v ) {
		$lines[] = $name . ',' . (int) $v;
	}
	return implode( "\n", $lines );
}

/**
 * 都道府県別の会員数。
 * [外観 > カスタマイズ > 会員数データ] の入力があればそれを使い、なければ初期値。
 * jsvn_member_counts フィルターで学会バンク連携・API等に差し替え可能。
 */
function jsvn_member_counts() {
	$defaults = jsvn_member_counts_defaults();
	$raw      = get_theme_mod( 'jsvn_pref_counts', '' );
	$parsed   = jsvn_parse_count_lines( $raw );
	$counts   = ! empty( $parsed ) ? $parsed : $defaults;
	return apply_filters( 'jsvn_member_counts', $counts );
}

/**
 * 会員数に応じた塗り色（シーケンシャル緑）
 */
function jsvn_member_color( $v ) {
	$ramp = array( '#e6f0ea', '#bcdcca', '#84c0a1', '#409072', '#1f5a3c' );
	$b = ( $v < 10 ) ? 0 : ( ( $v < 20 ) ? 1 : ( ( $v < 40 ) ? 2 : ( ( $v < 70 ) ? 3 : 4 ) ) );
	return $ramp[ $b ];
}

/**
 * 会員数マップのセクションを出力
 */
function jsvn_render_member_map() {
	$file = get_template_directory() . '/inc/japan-map-paths.php';
	if ( ! file_exists( $file ) ) {
		return;
	}
	$data   = include $file;
	$counts = jsvn_member_counts();
	$total  = array_sum( $counts );

	// --- 地図SVG ---
	$svg  = '<svg viewBox="' . esc_attr( $data['viewbox'] ) . '" xmlns="http://www.w3.org/2000/svg" class="jsvn-jpmap" role="img" aria-label="都道府県別の会員数">';
	foreach ( $data['paths'] as $nm => $d ) {
		$v    = isset( $counts[ $nm ] ) ? (int) $counts[ $nm ] : 0;
		$svg .= '<path d="' . esc_attr( $d ) . '" fill="' . esc_attr( jsvn_member_color( $v ) ) . '" stroke="#aab8af" stroke-width="0.8" class="jsvn-pref" tabindex="0" role="img" aria-label="' . esc_attr( $nm . ' 会員数 ' . $v . '名' ) . '" data-n="' . esc_attr( $nm ) . '" data-v="' . esc_attr( $v ) . '"></path>';
	}
	if ( ! empty( $data['bracket'] ) ) {
		$svg .= '<polyline points="' . esc_attr( $data['bracket'] ) . '" fill="none" stroke="#8a97a0" stroke-width="1.2" stroke-linejoin="round"/>';
	}
	if ( ! empty( $data['label'] ) ) {
		$lb   = $data['label'];
		$svg .= '<text x="' . esc_attr( $lb['x'] ) . '" y="' . esc_attr( $lb['y'] ) . '" font-size="12" fill="#5b6960">沖縄県</text>';
	}
	$svg .= '</svg>';

	// --- 上位ランキング ---
	arsort( $counts );
	$top  = array_slice( $counts, 0, 8, true );
	$rows = '';
	$i    = 1;
	foreach ( $top as $nm => $v ) {
		$rows .= '<tr><td>' . $i . '</td><td>' . esc_html( $nm ) . '</td><td><b>' . esc_html( $v ) . '</b>名</td></tr>';
		$i++;
	}

	// --- 凡例 ---
	$ramp   = array( '#e6f0ea', '#bcdcca', '#84c0a1', '#409072', '#1f5a3c' );
	$labels = array( '〜9名', '10〜19名', '20〜39名', '40〜69名', '70名以上' );
	$legend = '';
	foreach ( $labels as $k => $lab ) {
		$legend .= '<span class="jsvn-mlg"><i style="background:' . $ramp[ $k ] . '"></i>' . esc_html( $lab ) . '</span>';
	}

	echo '<div class="jsvn-members">';
	echo '<div class="jsvn-members__stats">';
	echo '<div class="jsvn-mstat"><span class="jsvn-mstat__n">' . esc_html( number_format( $total ) ) . '<small>名</small></span><span class="jsvn-mstat__l">会員総数</span></div>';
	echo '<div class="jsvn-mstat jsvn-mstat--gold"><span class="jsvn-mstat__n">47<small>都道府県</small></span><span class="jsvn-mstat__l">活動エリア</span></div>';
	echo '<div class="jsvn-mstat"><span class="jsvn-mstat__n">全国</span><span class="jsvn-mstat__l">会員在籍</span></div>';
	echo '</div>';
	echo '<div class="jsvn-members__grid">';
	echo '<div class="jsvn-members__mapwrap">' . $svg . '<div class="jsvn-members__legend">' . $legend . '</div></div>';
	echo '<aside class="jsvn-members__side"><h3>会員数の多い都道府県</h3><table class="jsvn-mtop"><tbody>' . $rows . '</tbody></table>';
	echo '<p class="jsvn-mnote">※ 各都道府県にカーソルを合わせると人数が表示されます。数値は会員データ（学会バンク等）との連携、または管理画面からの入力で更新できます。</p></aside>';
	echo '</div></div>';
}

/**
 * 保有資格別の会員数（初期サンプル値）。
 */
function jsvn_member_qualifications_defaults() {
	return array(
		'認定看護師'        => 312,
		'専門看護師'        => 86,
		'認定看護管理者'    => 54,
		'特定行為修了者'    => 128,
		'その他学会ライセンス' => 240,
	);
}

/**
 * 保有資格別の会員数。
 * [外観 > カスタマイズ > 会員数データ] の入力があればそれを使い、なければ初期値。
 * jsvn_member_qualifications フィルターで上書きできます。
 */
function jsvn_member_qualifications() {
	$defaults = jsvn_member_qualifications_defaults();
	$raw      = get_theme_mod( 'jsvn_qual_counts', '' );
	$parsed   = jsvn_parse_count_lines( $raw );
	$q        = ! empty( $parsed ) ? $parsed : $defaults;
	return apply_filters( 'jsvn_member_qualifications', $q );
}

/**
 * 保有資格別の会員数を横棒で表示
 */
function jsvn_render_member_quals() {
	$q = jsvn_member_qualifications();
	if ( empty( $q ) ) {
		return;
	}
	$max = max( $q );
	echo '<div class="jsvn-quals">';
	echo '<h2>保有資格別の会員数</h2>';
	echo '<p class="jsvn-quals__note">学会員が保有する主な資格・ライセンス（1人で複数保有の場合があります）</p>';
	echo '<div class="jsvn-qual">';
	foreach ( $q as $name => $v ) {
		$w = ( $max > 0 ) ? round( $v / $max * 100 ) : 0;
		echo '<div class="jsvn-qual__row">';
		echo '<span class="jsvn-qual__label">' . esc_html( $name ) . '</span>';
		echo '<span class="jsvn-qual__bar"><i style="width:' . esc_attr( $w ) . '%"></i></span>';
		echo '<span class="jsvn-qual__val">' . esc_html( number_format( $v ) ) . '<small>名</small></span>';
		echo '</div>';
	}
	echo '</div></div>';
}

/**
 * 会員数データを管理画面（カスタマイザー）から手入力できるようにする。
 * [外観 > カスタマイズ > 会員数データ]
 */
function jsvn_customize_members_data( $wp_customize ) {
	$wp_customize->add_section( 'jsvn_members_data', array(
		'title'       => __( '会員数データ（地図・資格）', 'jsvn' ),
		'priority'    => 45,
		'description' => __( '「名称,人数」を1行に1件ずつ入力してください。ここを書き換えると、会員分布ページの地図・順位・合計・資格グラフが自動で更新されます。', 'jsvn' ),
	) );

	// 都道府県別の会員数
	$wp_customize->add_setting( 'jsvn_pref_counts', array(
		'default'           => jsvn_counts_to_text( jsvn_member_counts_defaults() ),
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'jsvn_pref_counts', array(
		'label'       => __( '都道府県別の会員数', 'jsvn' ),
		'description' => __( '例）東京都,120 のように「都道府県名,人数」を1行ずつ。空欄や削除した県は0名として扱われます。', 'jsvn' ),
		'section'     => 'jsvn_members_data',
		'type'        => 'textarea',
		'input_attrs' => array( 'rows' => 12, 'style' => 'font-family:monospace;' ),
	) );

	// 保有資格別の会員数
	$wp_customize->add_setting( 'jsvn_qual_counts', array(
		'default'           => jsvn_counts_to_text( jsvn_member_qualifications_defaults() ),
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'jsvn_qual_counts', array(
		'label'       => __( '保有資格別の会員数', 'jsvn' ),
		'description' => __( '例）認定看護師,312 のように「資格名,人数」を1行ずつ。行を増やせば項目も増えます。', 'jsvn' ),
		'section'     => 'jsvn_members_data',
		'type'        => 'textarea',
		'input_attrs' => array( 'rows' => 6, 'style' => 'font-family:monospace;' ),
	) );
}
add_action( 'customize_register', 'jsvn_customize_members_data' );

/* =============================================================
 *  サイトの文言をカスタマイザーから編集できるようにする
 * ============================================================= */

/** テーマ設定の文言を取得 */
function jsvn_text( $key, $default = '' ) {
	return get_theme_mod( 'jsvn_' . $key, $default );
}
/** 1行テキストを出力（エスケープ済み） */
function jsvn_e( $key, $default = '' ) {
	echo esc_html( jsvn_text( $key, $default ) );
}
/** 複数行テキストを出力（改行を <br> に変換）*/
function jsvn_e_ml( $key, $default = '' ) {
	echo nl2br( esc_html( jsvn_text( $key, $default ) ) );
}

/** 編集可能な文言の定義（セクション → 項目）*/
function jsvn_content_fields() {
	return array(
		'hero' => array(
			'title'  => 'メインビジュアルの文言',
			'fields' => array(
				'hero_eyebrow' => array( '小見出し（学会名／英字）', 'text', '日本訪問看護学会 ／ The Japanese Society of Visiting Nursing' ),
				'hero_title'   => array( 'キャッチコピー（改行可）', 'textarea', "訪問看護師の臨床知を、\n社会を動かす力へ。" ),
				'hero_lead'    => array( 'リード文', 'textarea', '現場で生まれる疑問や工夫、成果を学術的知見へと高め、臨床・教育・研究・制度へ還元する。訪問看護の質の向上と社会的価値の発展に寄与します。' ),
				'hero_est'     => array( 'キーワード行', 'text', '臨床知の可視化 ・ 学術への発展 ・ 臨床・教育・制度への還元' ),
			),
		),
		'founding_home' => array(
			'title'  => 'トップの設立趣旨',
			'fields' => array(
				'home_founding_heading' => array( '見出し', 'text', '設立趣旨' ),
				'home_founding_p1'      => array( '本文1', 'textarea', '少子高齢化の進展とともに、療養の場は病院から地域・在宅へと大きく広がっています。訪問看護師は利用者の生活の場に入り、病状だけでなくその人の価値観や生活背景、家族、地域とのつながりを総合的に捉えながら、医療と生活を結ぶ重要な役割を担っています。' ),
				'home_founding_p2'      => array( '本文2', 'textarea', '日々の臨床で培われた優れた実践や知見は、事業所や地域に留まりがちです。日本訪問看護学会は「訪問看護師の臨床知を、社会を動かす力へ。」を理念に、現場で生まれる疑問や工夫、成果を学術的知見へと高め、臨床・教育・研究・制度へ還元することを目的として設立します。' ),
			),
		),
		'pillars' => array(
			'title'  => '三本柱',
			'fields' => array(
				'pillars_eyebrow' => array( '英字ラベル', 'text', 'OUR MISSION' ),
				'pillars_heading' => array( '見出し', 'text', '本会がめざす三本柱' ),
				'pillar1_title'   => array( '柱1 タイトル', 'text', '臨床知を可視化する' ),
				'pillar1_desc'    => array( '柱1 説明', 'textarea', '訪問看護師の日々の実践に宿る判断・工夫・課題・成果を、事例発表やケースレポートを通じて言語化し、可視化します。' ),
				'pillar2_title'   => array( '柱2 タイトル', 'text', '学術的知見へ発展させる' ),
				'pillar2_desc'    => array( '柱2 説明', 'textarea', '現場の臨床知と、教育機関・研究者の研究力を融合し、実践を研究へ発展させ、訪問看護の質向上に資する知見を創出します。' ),
				'pillar3_title'   => array( '柱3 タイトル', 'text', '臨床・教育・制度へ還元する' ),
				'pillar3_desc'    => array( '柱3 説明', 'textarea', '創出した知見を現場へ還元し、働きやすい環境づくり、教育体制の整備、制度・報酬の改善に資する基盤を築きます。' ),
			),
		),
		'banners' => array(
			'title'  => 'サイドバナー・お知らせ・協賛',
			'fields' => array(
				'newsbox_heading'    => array( 'お知らせ 見出し', 'text', 'お知らせ' ),
				'banner_events_label' => array( 'バナー1 見出し', 'text', '学術大会・研究会' ),
				'banner_events_en'    => array( 'バナー1 英字', 'text', 'ACADEMIC MEETING' ),
				'banner_join_label'   => array( 'バナー2 見出し', 'text', '入会のご案内' ),
				'banner_join_en'      => array( 'バナー2 英字', 'text', 'MEMBERSHIP' ),
				'banner_journal_label' => array( 'バナー3 見出し', 'text', '学会誌・ニュースレター' ),
				'banner_journal_en'   => array( 'バナー3 英字', 'text', 'JOURNAL & NEWSLETTER' ),
				'banner_login_label'  => array( 'バナー4 見出し', 'text', '会員ログイン（学会バンク）' ),
				'banner_login_en'     => array( 'バナー4 英字', 'text', 'MEMBER LOGIN' ),
				'sponsors_heading'    => array( '協賛企業 見出し', 'text', '協賛企業・賛助会員' ),
			),
		),
		'blogsns' => array(
			'title'  => 'ブログ・SNS',
			'fields' => array(
				'blog_heading' => array( 'ブログ 見出し', 'text', 'ブログ' ),
				'sns_heading'  => array( 'SNS 見出し', 'text', 'SNS' ),
				'sns_note'     => array( 'SNS 説明', 'textarea', '最新情報はSNSでも発信しています。ぜひフォローしてください。' ),
			),
		),
		'common' => array(
			'title'  => 'ヘッダー・フッター',
			'fields' => array(
				'cta_label'   => array( 'ヘッダーの入会ボタン', 'text', '入会のご案内' ),
				'footer_desc' => array( 'フッター紹介文', 'textarea', '訪問看護の実践と学術を支え、地域で暮らす人々の「その人らしい療養」を支援します。' ),
				'footer_col1' => array( 'フッター見出し1', 'text', '学会について' ),
				'footer_col2' => array( 'フッター見出し2', 'text', '活動・学術' ),
				'footer_col3' => array( 'フッター見出し3', 'text', '会員の方へ' ),
			),
		),
	);
}

/** カスタマイザーへ「サイトの文言」パネルを登録 */
function jsvn_customize_content( $wp_customize ) {
	$wp_customize->add_panel( 'jsvn_content', array(
		'title'       => __( 'サイトの文言（テキスト編集）', 'jsvn' ),
		'description' => __( 'トップページやヘッダー・フッターの文章をここから編集できます。', 'jsvn' ),
		'priority'    => 25,
	) );
	foreach ( jsvn_content_fields() as $sec => $def ) {
		$sid = 'jsvn_c_' . $sec;
		$wp_customize->add_section( $sid, array(
			'title' => $def['title'],
			'panel' => 'jsvn_content',
		) );
		foreach ( $def['fields'] as $key => $f ) {
			list( $label, $type, $default ) = $f;
			$wp_customize->add_setting( 'jsvn_' . $key, array(
				'default'           => $default,
				'sanitize_callback' => ( 'textarea' === $type ) ? 'sanitize_textarea_field' : 'sanitize_text_field',
			) );
			$wp_customize->add_control( 'jsvn_' . $key, array(
				'label'   => $label,
				'section' => $sid,
				'type'    => $type,
			) );
		}
	}
}
add_action( 'customize_register', 'jsvn_customize_content' );
