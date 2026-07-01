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
				array( 'label' => '理事長挨拶', 'url' => home_url( '/about-greeting/' ) ),
				array( 'label' => '学会概要', 'url' => home_url( '/about/' ) ),
				array( 'label' => '定款', 'url' => home_url( '/articles/' ) ),
				array( 'label' => '役員・代議員・名誉会員紹介', 'url' => home_url( '/officers/' ) ),
				array( 'label' => '代議員・役員選出規定', 'url' => home_url( '/election-rules/' ) ),
			),
		),
		array(
			'label'    => '規程・倫理',
			'url'      => home_url( '/ethics-code/' ),
			'children' => array(
				array( 'label' => '倫理綱領', 'url' => home_url( '/ethics-code/' ) ),
				array( 'label' => '科学者の行動規範', 'url' => home_url( '/scientist-conduct/' ) ),
				array( 'label' => '研究倫理ガイドライン', 'url' => home_url( '/research-ethics/' ) ),
			),
		),
		array(
			'label'    => '情報公開',
			'url'      => home_url( '/balance-sheet/' ),
			'children' => array(
				array( 'label' => '貸借対照表', 'url' => home_url( '/balance-sheet/' ) ),
			),
		),
		array(
			'label'    => '学術活動',
			'url'      => home_url( '/events/' ),
			'children' => array(
				array( 'label' => '学術大会・研究会', 'url' => home_url( '/events/' ) ),
				array( 'label' => 'ニュースレター', 'url' => home_url( '/newsletter/' ) ),
				array( 'label' => '表彰', 'url' => home_url( '/awards/' ) ),
			),
		),
		array( 'label' => '訪問看護とは？', 'url' => home_url( '/visiting-nursing/' ) ),
		array( 'label' => 'お知らせ', 'url' => home_url( '/news/' ) ),
		array( 'label' => '入会案内', 'url' => home_url( '/join/' ) ),
		array( 'label' => 'お問い合わせ', 'url' => home_url( '/contact/' ) ),
	);
}

/**
 * フォールバックメニューを出力
 */
function jsvn_render_fallback_menu() {
	echo '<ul class="jsvn-nav__menu">';
	foreach ( jsvn_menu_structure() as $item ) {
		$has_children = ! empty( $item['children'] );
		$li_class     = $has_children ? ' class="menu-item-has-children"' : '';
		echo '<li' . $li_class . '>';
		echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] );
		if ( $has_children ) {
			echo ' <span class="caret" aria-hidden="true">▾</span>';
		}
		echo '</a>';
		if ( $has_children ) {
			echo '<ul class="sub-menu">';
			foreach ( $item['children'] as $child ) {
				echo '<li><a href="' . esc_url( $child['url'] ) . '">' . esc_html( $child['label'] ) . '</a></li>';
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}

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
			'title'   => '表彰',
			'content' => "<p>訪問看護の実践・研究に顕著な貢献をされた方を表彰します。</p>\n<ul>\n<li>学会賞（優れた研究）</li>\n<li>奨励賞（若手研究者）</li>\n<li>実践功労賞</li>\n</ul>\n<p><em>※ 詳細は準備中です。</em></p>",
		),
		'join' => array(
			'title'   => '入会のご案内',
			'content' => "<p>訪問看護に関わるすべての方を歓迎します。入会をご希望の方は、以下をご確認ください。</p>\n<h2>会員種別と年会費（予定）</h2>\n<table><tbody>\n<tr><th>正会員</th><td>年会費 5,000円</td></tr>\n<tr><th>学生会員</th><td>年会費 2,000円</td></tr>\n<tr><th>賛助会員</th><td>1口 10,000円</td></tr>\n</tbody></table>\n<h2>お手続き</h2>\n<p>入会手続きは「学会バンク」を通じて行う予定です。準備が整い次第、こちらにお申し込みリンクを掲載します。</p>",
		),
		'contact' => array(
			'title'   => 'お問い合わせ',
			'content' => "<p>本会へのお問い合わせは、以下よりお願いいたします。</p>\n<p>メール：（準備中）<br>お問い合わせフォームを設置予定です。</p>",
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
		echo '<h2 class="jsvn-officer-role-h">' . esc_html( $role ) . '<span>' . count( $grouped[ $role ] ) . '名</span></h2>';
		echo '<div class="jsvn-officers">';
		foreach ( $grouped[ $role ] as $id ) {
			$affil   = get_post_meta( $id, '_jsvn_affiliation', true );
			$license = get_post_meta( $id, '_jsvn_license', true );
			$bio     = get_post_field( 'post_content', $id );
			echo '<article class="jsvn-officer">';
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
 * サンプル役員を初期投入（理事長1・副理事長2・常任理事3・理事はサンプル数名）
 * ※ 実構成は 理事長1／副理事長2／常任理事3／理事15。残りは管理画面から追加してください。
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
		array( '理事', '○○ ○○', '○○大学 講師', '看護師／修士（看護学）' ),
		array( '理事', '○○ ○○', '○○病院 看護部長', '看護師／認定看護管理者' ),
		array( '理事', '○○ ○○', '○○訪問看護ステーション 管理者', '看護師' ),
	);
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
