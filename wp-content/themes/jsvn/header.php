<?php
/**
 * ヘッダー
 *
 * @package JSVN
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#1f3a5f">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#jsvn-main">本文へスキップ</a>

<?php
// 上部のユーティリティメニュー（会員ログイン・お問い合わせなど）
$has_utility = has_nav_menu( 'utility' );
?>
<div class="jsvn-topbar">
	<div class="jsvn-container">
		<?php if ( $has_utility ) : ?>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'utility',
				'container'      => false,
				'menu_class'     => 'jsvn-topbar__menu',
				'depth'          => 1,
				'fallback_cb'    => false,
			) );
			?>
		<?php else : ?>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a>
			<a href="<?php echo esc_url( jsvn_login_url() ); ?>" target="_blank" rel="noopener">会員ログイン（学会バンク）</a>
		<?php endif; ?>
	</div>
</div>

<header class="jsvn-header">
	<div class="jsvn-container jsvn-header__inner">

		<div class="jsvn-brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="jsvn-brand__link" style="text-decoration:none;">
					<img class="jsvn-brand__img"
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>"
						alt="<?php bloginfo( 'name' ); ?>">
				</a>
			<?php endif; ?>
		</div>

		<button class="jsvn-burger" aria-label="メニューを開く" aria-expanded="false" aria-controls="jsvn-primary-nav">
			<span></span><span></span><span></span>
		</button>

		<nav class="jsvn-nav" id="jsvn-primary-nav" aria-label="グローバルメニュー">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'jsvn-nav__menu',
					'depth'          => 2,
				) );
			} else {
				// メニュー未設定時の仮表示（学会向けの構成）
				jsvn_render_fallback_menu();
			}
			?>
		</nav>

		<div class="jsvn-header__cta">
			<a class="jsvn-btn jsvn-btn--coral" href="<?php echo esc_url( home_url( '/join/' ) ); ?>"><?php jsvn_e( 'cta_label', '入会のご案内' ); ?></a>
		</div>

	</div>
</header>
<div class="jsvn-nav-overlay" hidden></div>

<main id="jsvn-main">
