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

<!-- ============================ メインビジュアル（スライドショー） ============================ -->
<?php
// カスタマイザーで登録された画像のみ表示（未登録なら深緑背景＋コピーのみ）
$jsvn_slides = jsvn_hero_images();
?>
<section class="jsvn-visual<?php echo empty( $jsvn_slides ) ? ' jsvn-visual--plain' : ''; ?>" data-jsvn-slider>
	<?php if ( ! empty( $jsvn_slides ) ) : ?>
	<div class="jsvn-slides" aria-hidden="true">
		<?php foreach ( $jsvn_slides as $jsvn_i => $jsvn_src ) : ?>
			<div class="jsvn-slide<?php echo 0 === $jsvn_i ? ' is-active' : ''; ?>" style="background-image:url('<?php echo esc_url( $jsvn_src ); ?>');"></div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<div class="jsvn-visual__scrim"></div>
	<div class="jsvn-container">
		<div class="jsvn-visual__inner">
			<div class="jsvn-visual__copy">
				<p class="jsvn-visual__en"><?php jsvn_e( 'hero_eyebrow', '日本訪問看護学会 ／ The Japanese Society of Visiting Nursing' ); ?></p>
				<h1 class="jsvn-visual__title"><?php jsvn_e_ml( 'hero_title', "訪問看護師の臨床知を、\n社会を動かす力へ。" ); ?></h1>
				<div class="jsvn-visual__rule" aria-hidden="true"><span></span><i>◆</i><span></span></div>
				<p class="jsvn-visual__lead"><?php jsvn_e_ml( 'hero_lead', '現場で生まれる疑問や工夫、成果を学術的知見へと高め、臨床・教育・研究・制度へ還元する。訪問看護の質の向上と社会的価値の発展に寄与します。' ); ?></p>
				<p class="jsvn-visual__est"><?php jsvn_e( 'hero_est', '臨床知の可視化 ・ 学術への発展 ・ 臨床・教育・制度への還元' ); ?></p>
			</div>
		</div>
	</div>
	<?php if ( count( $jsvn_slides ) > 1 ) : ?>
	<div class="jsvn-visual__dots" aria-label="メインビジュアルのスライド切り替え">
		<?php foreach ( $jsvn_slides as $jsvn_i => $jsvn_src ) : ?>
			<button class="jsvn-dot<?php echo 0 === $jsvn_i ? ' is-active' : ''; ?>" data-i="<?php echo esc_attr( $jsvn_i ); ?>" aria-label="スライド<?php echo esc_attr( $jsvn_i + 1 ); ?>"></button>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</section>

<div class="jsvn-home">
	<div class="jsvn-container jsvn-home__grid">

		<!-- ===== メインカラム ===== -->
		<main class="jsvn-home__main">

			<!-- お知らせ -->
			<section class="jsvn-newsbox">
				<div class="jsvn-newsbox__head">
					<h2><?php jsvn_e( 'newsbox_heading', 'お知らせ' ); ?></h2>
					<a class="jsvn-newsbox__all" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/news/' ) ); ?>">一覧へ &rsaquo;</a>
				</div>
				<div class="jsvn-news__list">
					<?php
					$news_args = array(
						'post_type'           => 'post',
						'posts_per_page'      => 6,
						'ignore_sticky_posts' => true,
					);
					$jsvn_blog_cid = jsvn_blog_cat_id();
					if ( $jsvn_blog_cid ) {
						$news_args['category__not_in'] = array( $jsvn_blog_cid ); // ブログはお知らせ欄に出さない
					}
					$news = new WP_Query( $news_args );
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
						// 投稿がまだ無いとき
						?>
						<p class="jsvn-feed-empty">現在お知らせはありません。［投稿］から追加すると、ここに表示されます。</p>
						<?php
					endif;
					?>
				</div>
			</section>

			<!-- 設立趣旨（ダイジェスト） -->
			<section class="jsvn-home-about">
				<h2 class="jsvn-home-h2"><?php jsvn_e( 'home_founding_heading', '設立趣旨' ); ?></h2>
				<p><?php jsvn_e_ml( 'home_founding_p1', '少子高齢化の進展とともに、療養の場は病院から地域・在宅へと大きく広がっています。訪問看護師は利用者の生活の場に入り、病状だけでなくその人の価値観や生活背景、家族、地域とのつながりを総合的に捉えながら、医療と生活を結ぶ重要な役割を担っています。' ); ?></p>
				<p><?php jsvn_e_ml( 'home_founding_p2', '日々の臨床で培われた優れた実践や知見は、事業所や地域に留まりがちです。日本訪問看護学会は「訪問看護師の臨床知を、社会を動かす力へ。」を理念に、現場で生まれる疑問や工夫、成果を学術的知見へと高め、臨床・教育・研究・制度へ還元することを目的として設立します。' ); ?></p>
				<p style="margin:0;">
					<a class="jsvn-textlink" href="<?php echo esc_url( home_url( '/founding/' ) ); ?>">設立趣旨・理念を読む &rsaquo;</a>
					<a class="jsvn-textlink" href="<?php echo esc_url( home_url( '/about-greeting/' ) ); ?>">理事長挨拶 &rsaquo;</a>
				</p>
			</section>

		</main>

		<!-- ===== サイドカラム ===== -->
		<aside class="jsvn-home__side">

			<a class="jsvn-sidebanner" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">
				<span class="jsvn-sidebanner__label"><?php jsvn_e( 'banner_events_label', '学術大会・研究会' ); ?></span>
				<span class="jsvn-sidebanner__en"><?php jsvn_e( 'banner_events_en', 'ACADEMIC MEETING' ); ?></span>
			</a>

			<a class="jsvn-sidebanner jsvn-sidebanner--gold" href="<?php echo esc_url( home_url( '/join/' ) ); ?>">
				<span class="jsvn-sidebanner__label"><?php jsvn_e( 'banner_join_label', '入会のご案内' ); ?></span>
				<span class="jsvn-sidebanner__en"><?php jsvn_e( 'banner_join_en', 'MEMBERSHIP' ); ?></span>
			</a>

			<a class="jsvn-sidebanner" href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">
				<span class="jsvn-sidebanner__label"><?php jsvn_e( 'banner_journal_label', '学会誌・ニュースレター' ); ?></span>
				<span class="jsvn-sidebanner__en"><?php jsvn_e( 'banner_journal_en', 'JOURNAL & NEWSLETTER' ); ?></span>
			</a>

			<a class="jsvn-sidebanner jsvn-sidebanner--login" href="<?php echo esc_url( jsvn_login_url() ); ?>" target="_blank" rel="noopener">
				<span class="jsvn-sidebanner__label"><?php jsvn_e( 'banner_login_label', '会員ログイン（学会バンク）' ); ?></span>
				<span class="jsvn-sidebanner__en"><?php jsvn_e( 'banner_login_en', 'MEMBER LOGIN' ); ?></span>
			</a>

			<!-- 協賛企業・企業バナー枠（企業様用リンク） -->
			<section class="jsvn-sponsors" aria-label="協賛企業">
				<h3 class="jsvn-sponsors__head"><?php jsvn_e( 'sponsors_heading', '協賛企業・賛助会員' ); ?></h3>
				<div class="jsvn-sponsors__body">
					<?php if ( ! jsvn_render_sponsor_banners() ) : ?>
						<p class="jsvn-sponsors__empty">［外観 &gt; カスタマイズ &gt; 協賛企業バナー］から企業バナーを登録すると、ここに表示されます。</p>
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

<!-- ============================ 三本柱 ============================ -->
<section class="jsvn-section jsvn-section--green">
	<div class="jsvn-container">
		<div class="jsvn-section-head">
			<span class="jsvn-eyebrow"><?php jsvn_e( 'pillars_eyebrow', 'OUR MISSION' ); ?></span>
			<h2><?php jsvn_e( 'pillars_heading', '本会がめざす三本柱' ); ?></h2>
		</div>
		<div class="jsvn-pillars">
			<article class="jsvn-pillar">
				<div class="jsvn-pillar__icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
				</div>
				<h3><?php jsvn_e( 'pillar1_title', '臨床知を可視化する' ); ?></h3>
				<p><?php jsvn_e_ml( 'pillar1_desc', '訪問看護師の日々の実践に宿る判断・工夫・課題・成果を、事例発表やケースレポートを通じて言語化し、可視化します。' ); ?></p>
			</article>
			<article class="jsvn-pillar">
				<div class="jsvn-pillar__icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5V6a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v13.5"/><path d="M4 19.5A1.5 1.5 0 0 1 5.5 18H19"/><path d="M9 8h6M9 11h6"/></svg>
				</div>
				<h3><?php jsvn_e( 'pillar2_title', '学術的知見へ発展させる' ); ?></h3>
				<p><?php jsvn_e_ml( 'pillar2_desc', '現場の臨床知と、教育機関・研究者の研究力を融合し、実践を研究へ発展させ、訪問看護の質向上に資する知見を創出します。' ); ?></p>
			</article>
			<article class="jsvn-pillar">
				<div class="jsvn-pillar__icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9"/><path d="M3 4v5h5"/><path d="M12 7v5l3 2"/></svg>
				</div>
				<h3><?php jsvn_e( 'pillar3_title', '臨床・教育・制度へ還元する' ); ?></h3>
				<p><?php jsvn_e_ml( 'pillar3_desc', '創出した知見を現場へ還元し、働きやすい環境づくり、教育体制の整備、制度・報酬の改善に資する基盤を築きます。' ); ?></p>
			</article>
		</div>
	</div>
</section>

<!-- ============================ ブログ & SNS ============================ -->
<section class="jsvn-blogsns">
	<div class="jsvn-container">
		<div class="jsvn-blogsns__grid">

			<!-- ブログ -->
			<div class="jsvn-blog">
				<div class="jsvn-blog__head">
					<h2><?php jsvn_e( 'blog_heading', 'ブログ' ); ?></h2>
					<?php
					// パーマリンク設定に依存しない正しいカテゴリーURLを使う
					$jsvn_blog_cid  = jsvn_blog_cat_id();
					$jsvn_blog_link = $jsvn_blog_cid ? get_category_link( $jsvn_blog_cid ) : home_url( '/category/blog/' );
					?>
					<a class="jsvn-textlink" href="<?php echo esc_url( $jsvn_blog_link ); ?>">ブログ一覧へ &rsaquo;</a>
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
						// 投稿がまだ無いとき
						?>
						<p class="jsvn-feed-empty">現在ブログの投稿はありません。［投稿］でカテゴリー「ブログ」を選んで公開すると、ここに表示されます。</p>
						<?php
					endif;
					?>
				</div>
			</div>

			<!-- SNS -->
			<div class="jsvn-social">
				<div class="jsvn-social__head"><h2><?php jsvn_e( 'sns_heading', 'SNS' ); ?></h2></div>
				<div class="jsvn-social__card">
					<p class="jsvn-social__note"><?php jsvn_e_ml( 'sns_note', '最新情報はSNSでも発信しています。ぜひフォローしてください。' ); ?></p>
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
