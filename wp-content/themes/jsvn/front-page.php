<?php
/**
 * フロントページ（トップページ）
 *
 * 学会サイトの定番構成：常時表示のメニュー＋お知らせ中心の2カラム
 * （メイン＝お知らせ・ごあいさつ／サイド＝バナー・サブメニュー）。
 *
 * @package JSVN
 */

get_header();
?>

<!-- ============================ メインビジュアル ============================ -->
<?php $jsvn_hero = jsvn_hero_image_url(); ?>
<section class="jsvn-visual <?php echo $jsvn_hero ? 'jsvn-visual--photo' : ''; ?>"
	<?php if ( $jsvn_hero ) : ?>style="background-image: linear-gradient(90deg, rgba(11,36,24,.86) 0%, rgba(11,36,24,.55) 34%, rgba(11,36,24,.12) 62%, rgba(11,36,24,0) 100%), url('<?php echo esc_url( $jsvn_hero ); ?>');"<?php endif; ?>>
	<div class="jsvn-container">
		<div class="jsvn-visual__inner">
			<div class="jsvn-visual__copy">
				<p class="jsvn-visual__en">The Japanese Society of Visiting Nursing</p>
				<h1 class="jsvn-visual__title"><?php bloginfo( 'name' ); ?></h1>
				<div class="jsvn-visual__rule" aria-hidden="true"><span></span><i>◆</i><span></span></div>
				<p class="jsvn-visual__lead">あたたかなケアを、確かな学術で。<br>訪問看護の実践と学術をつなぎ、地域で暮らす人々の療養生活を支えます。</p>
				<p class="jsvn-visual__est">学術研究 ・ 人材育成 ・ 地域連携</p>
			</div>
			<?php if ( ! $jsvn_hero ) : ?>
			<div class="jsvn-visual__art">
				<img class="jsvn-tree" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/camphor-tree.svg' ); ?>" alt="" aria-hidden="true">
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<div class="jsvn-home">
	<div class="jsvn-container jsvn-home__grid">

		<!-- ===== メインカラム ===== -->
		<main class="jsvn-home__main">

			<!-- お知らせ -->
			<section class="jsvn-newsbox">
				<div class="jsvn-newsbox__head">
					<h2>お知らせ</h2>
					<a class="jsvn-newsbox__all" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/news/' ) ); ?>">一覧へ &rsaquo;</a>
				</div>
				<div class="jsvn-news__list">
					<?php
					$news = new WP_Query( array(
						'post_type'           => 'post',
						'posts_per_page'      => 6,
						'ignore_sticky_posts' => true,
					) );
					if ( $news->have_posts() ) :
						while ( $news->have_posts() ) : $news->the_post();
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
					else :
						// 雛形（投稿が未登録のとき）
						$ph = array(
							array( '2026.06.20', 'お知らせ', '', '会員募集を開始いたしました' ),
							array( '2026.06.15', '学術大会', 'jsvn-news__cat--academic', '第1回学術集会の開催が決定しました' ),
							array( '2026.06.01', 'イベント', 'jsvn-news__cat--event', '設立記念シンポジウムを開催します' ),
							array( '2026.05.20', 'お知らせ', '', '公式サイトを公開しました' ),
						);
						foreach ( $ph as $row ) :
							?>
							<div class="jsvn-news__item">
								<span class="jsvn-news__date"><?php echo esc_html( $row[0] ); ?></span>
								<span class="jsvn-news__cat <?php echo esc_attr( $row[2] ); ?>"><?php echo esc_html( $row[1] ); ?></span>
								<span class="jsvn-news__title"><?php echo esc_html( $row[3] ); ?></span>
							</div>
							<?php
						endforeach;
					endif;
					?>
				</div>
			</section>

			<!-- ごあいさつ -->
			<section class="jsvn-home-about">
				<h2 class="jsvn-home-h2">ごあいさつ</h2>
				<p>
					高齢化と在宅医療の広がりのなかで、訪問看護が担う役割はますます大きくなっています。
					日本訪問看護学会は、現場で積み重ねられる実践知を研究という形で体系化し、
					温かなまなざしと確かな科学的根拠の両輪で、地域で暮らす一人ひとりの療養生活を支えます。
				</p>
				<p>
					職種や所属を越えて学び合える「開かれた学術の場」を大切にします。
					訪問看護に関わるすべての方の参画を、心よりお待ちしています。
				</p>
				<p style="margin:0;">
					<a class="jsvn-textlink" href="<?php echo esc_url( home_url( '/about-greeting/' ) ); ?>">理事長挨拶を読む &rsaquo;</a>

					<a class="jsvn-textlink" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">学会概要 &rsaquo;</a>
				</p>
			</section>

		</main>

		<!-- ===== サイドカラム ===== -->
		<aside class="jsvn-home__side">

			<a class="jsvn-sidebanner" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">
				<span class="jsvn-sidebanner__label">学術大会・研究会</span>
				<span class="jsvn-sidebanner__en">ACADEMIC MEETING</span>
			</a>

			<a class="jsvn-sidebanner jsvn-sidebanner--gold" href="<?php echo esc_url( home_url( '/join/' ) ); ?>">
				<span class="jsvn-sidebanner__label">入会のご案内</span>
				<span class="jsvn-sidebanner__en">MEMBERSHIP</span>
			</a>

			<a class="jsvn-sidebanner" href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">
				<span class="jsvn-sidebanner__label">学会誌・ニュースレター</span>
				<span class="jsvn-sidebanner__en">JOURNAL &amp; NEWSLETTER</span>
			</a>

			<a class="jsvn-sidebanner jsvn-sidebanner--login" href="<?php echo esc_url( jsvn_login_url() ); ?>" target="_blank" rel="noopener">
				<span class="jsvn-sidebanner__label">会員ログイン（学会バンク）</span>
				<span class="jsvn-sidebanner__en">MEMBER LOGIN</span>
			</a>

			<!-- 協賛企業・企業バナー枠（企業様用リンク） -->
			<section class="jsvn-sponsors" aria-label="協賛企業">
				<h3 class="jsvn-sponsors__head">協賛企業・賛助会員</h3>
				<div class="jsvn-sponsors__body">
					<?php if ( is_active_sidebar( 'sponsors' ) ) : ?>
						<?php dynamic_sidebar( 'sponsors' ); ?>
					<?php else : ?>
						<a class="jsvn-sponsor" href="#">企業バナー広告<br>スペース①</a>
						<a class="jsvn-sponsor" href="#">企業バナー広告<br>スペース②</a>
						<a class="jsvn-sponsor" href="#">企業バナー広告<br>スペース③</a>
					<?php endif; ?>
					<a class="jsvn-sponsors__more" href="<?php echo esc_url( home_url( '/join/' ) ); ?>">企業会員のご案内 &rsaquo;</a>
				</div>
			</section>

			<nav class="jsvn-sidemenu" aria-label="サブメニュー">
				<h3>メニュー</h3>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/about-greeting/' ) ); ?>">理事長挨拶</a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">学会概要</a></li>
					<li><a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>">定款</a></li>
					<li><a href="<?php echo esc_url( home_url( '/officers/' ) ); ?>">役員・代議員・名誉会員紹介</a></li>
					<li><a href="<?php echo esc_url( home_url( '/ethics-code/' ) ); ?>">倫理綱領・COI</a></li>
					<li><a href="<?php echo esc_url( home_url( '/reports/' ) ); ?>">情報公開（事業報告・決算）</a></li>
					<li><a href="<?php echo esc_url( home_url( '/journal-submission/' ) ); ?>">学会誌・投稿規定</a></li>
					<li><a href="<?php echo esc_url( home_url( '/certification/' ) ); ?>">認定制度</a></li>
					<li><a href="<?php echo esc_url( home_url( '/visiting-nursing/' ) ); ?>">訪問看護とは？</a></li>
					<li><a href="<?php echo esc_url( home_url( '/awards/' ) ); ?>">表彰</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a></li>
				</ul>
			</nav>

		</aside>

	</div>
</div>

<!-- ============================ ブログ & SNS ============================ -->
<section class="jsvn-blogsns">
	<div class="jsvn-container">
		<div class="jsvn-blogsns__grid">

			<!-- ブログ -->
			<div class="jsvn-blog">
				<div class="jsvn-blog__head">
					<h2>ブログ</h2>
					<a class="jsvn-textlink" href="<?php echo esc_url( home_url( '/category/blog/' ) ); ?>">ブログ一覧へ &rsaquo;</a>
				</div>
				<div class="jsvn-blog__list">
					<?php
					$blog = new WP_Query( array(
						'post_type'           => 'post',
						'posts_per_page'      => 3,
						'category_name'       => 'blog',
						'ignore_sticky_posts' => true,
					) );
					if ( $blog->have_posts() ) :
						while ( $blog->have_posts() ) : $blog->the_post();
							?>
							<a class="jsvn-blogcard" href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;">
								<span class="jsvn-blogcard__thumb"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'jsvn-card' ); } ?></span>
								<span class="jsvn-blogcard__body">
									<span class="jsvn-blogcard__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
									<h3><?php echo esc_html( wp_trim_words( get_the_title(), 26, '…' ) ); ?></h3>
								</span>
							</a>
							<?php
						endwhile;
						wp_reset_postdata();
					else :
						$bph = array(
							array( '2026.06.18', '設立準備委員会の様子をご紹介します' ),
							array( '2026.06.10', '訪問看護の現場から：ある一日' ),
							array( '2026.06.02', '学術大会に向けた準備が始まりました' ),
						);
						foreach ( $bph as $b ) :
							?>
							<div class="jsvn-blogcard">
								<span class="jsvn-blogcard__thumb"></span>
								<span class="jsvn-blogcard__body">
									<span class="jsvn-blogcard__date"><?php echo esc_html( $b[0] ); ?></span>
									<h3><?php echo esc_html( $b[1] ); ?></h3>
								</span>
							</div>
							<?php
						endforeach;
					endif;
					?>
				</div>
			</div>

			<!-- SNS -->
			<div class="jsvn-social">
				<div class="jsvn-social__head"><h2>SNS</h2></div>
				<div class="jsvn-social__card">
					<p class="jsvn-social__note">最新情報はSNSでも発信しています。ぜひフォローしてください。</p>
					<?php jsvn_sns_icons(); ?>
					<div class="jsvn-social__embed">
						Instagram／X／Facebook の最新投稿を、ここに自動表示できます。<br>
						（公開時に各SNSと連携して有効化します）
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<?php
get_footer();
