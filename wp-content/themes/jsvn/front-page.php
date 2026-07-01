<?php
/**
 * フロントページ（トップページ）
 *
 * 訪問看護の「温かさ」と学会の「学術性」を両立したトップページ。
 * 各文言は管理画面の固定ページ／カスタマイザーに依存せず、まず雛形として表示し、
 * 後から実コンテンツへ差し替えできる構成にしています。
 *
 * @package JSVN
 */

get_header();
?>

<!-- ============================ ヒーロー ============================ -->
<section class="jsvn-hero">
	<div class="jsvn-container jsvn-hero__inner">

		<div class="jsvn-hero__copy">
			<span class="jsvn-hero__badge">🌿 Society for Visiting Nursing</span>
			<h1>
				あたたかなケアを、<br>
				<span class="accent">確かな学術</span>で支える。
			</h1>
			<p class="jsvn-hero__lead">
				日本訪問看護学会は、在宅で療養する人とその家族に寄り添う訪問看護の実践を、
				研究・教育・連携の力で前へ進めるための学会です。現場の知恵とエビデンスをつなぎ、
				「その人らしい暮らし」を地域で支えます。
			</p>
			<div class="jsvn-hero__actions">
				<a class="jsvn-btn jsvn-btn--coral" href="<?php echo esc_url( home_url( '/join/' ) ); ?>">入会のご案内</a>
				<a class="jsvn-btn jsvn-btn--ghost-light" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">学会について</a>
			</div>
		</div>

		<!-- 最新のお知らせカード -->
		<div class="jsvn-hero__card">
			<h3>📌 新着のお知らせ</h3>
			<ul>
				<?php
				$hero_news = new WP_Query( array(
					'post_type'           => 'post',
					'posts_per_page'      => 4,
					'ignore_sticky_posts' => true,
				) );
				if ( $hero_news->have_posts() ) :
					while ( $hero_news->have_posts() ) : $hero_news->the_post();
						?>
						<li>
							<span class="date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
							<a href="<?php the_permalink(); ?>"><?php echo esc_html( wp_trim_words( get_the_title(), 22, '…' ) ); ?></a>
						</li>
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					// 投稿がまだ無い場合の雛形
					$placeholder = array(
						array( '2026.06.01', '日本訪問看護学会 設立準備委員会を発足しました' ),
						array( '2026.06.15', '第1回学術集会の開催日程が決定しました' ),
						array( '2026.06.20', '会員募集を開始いたしました' ),
					);
					foreach ( $placeholder as $row ) :
						?>
						<li><span class="date"><?php echo esc_html( $row[0] ); ?></span><span><?php echo esc_html( $row[1] ); ?></span></li>
						<?php
					endforeach;
				endif;
				?>
			</ul>
		</div>

	</div>

	<!-- 下端の波（温かみのある曲線で次セクションへ）-->
	<svg class="jsvn-hero__wave" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
		<path fill="#f8f5ee" d="M0,40 C240,90 480,0 720,30 C960,60 1200,90 1440,40 L1440,80 L0,80 Z"></path>
	</svg>
</section>

<!-- ============================ ご挨拶 / 理念 ============================ -->
<section class="jsvn-section jsvn-mission">
	<div class="jsvn-container">
		<div class="jsvn-mission__grid">

			<div class="jsvn-mission__visual">
				<blockquote>
					「治す」だけでなく、<br>
					「その人らしく生ききる」を、<br>
					看護の力で支えたい。
				</blockquote>
				<cite>― 日本訪問看護学会 設立準備委員会</cite>
			</div>

			<div class="jsvn-mission__body">
				<span class="jsvn-eyebrow" style="color:var(--jsvn-gold);font-weight:700;letter-spacing:.2em;">GREETING &amp; MISSION</span>
				<h2>ご挨拶</h2>
				<p class="lead">
					高齢化と在宅医療の広がりのなかで、訪問看護が担う役割はますます大きくなっています。
				</p>
				<p>
					私たちは、現場で積み重ねられる実践知を、研究という形で言語化・体系化し、
					次世代の看護職へと受け継いでいくことを目指します。温かなまなざしと、
					確かな科学的根拠。その両輪で、地域で暮らす一人ひとりの療養生活を支えてまいります。
				</p>
				<p>
					本会は、職種や所属を越えて学び合える「開かれた学術の場」を大切にします。
					訪問看護に関わるすべての方の参画を、心よりお待ちしています。
				</p>
				<a class="jsvn-btn jsvn-btn--primary" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">学会について詳しく</a>
			</div>

		</div>
	</div>
</section>

<!-- ============================ 3つの柱 ============================ -->
<section class="jsvn-section jsvn-section--green">
	<div class="jsvn-container">
		<div class="jsvn-section-head">
			<span class="jsvn-eyebrow">OUR PILLARS</span>
			<h2>学会がめざす3つの柱</h2>
		</div>

		<div class="jsvn-pillars">

			<article class="jsvn-pillar">
				<div class="jsvn-pillar__icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-4.5-9.5-9A5.5 5.5 0 0 1 12 7a5.5 5.5 0 0 1 9.5 5C19 16.5 12 21 12 21z"/></svg>
				</div>
				<h3>実践を支える学術研究</h3>
				<p>現場のケアをエビデンスへ。多施設・多職種で取り組む研究を推進し、訪問看護の質の向上に貢献します。</p>
			</article>

			<article class="jsvn-pillar">
				<div class="jsvn-pillar__icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/></svg>
				</div>
				<h3>次世代を育む人材育成</h3>
				<p>学術集会・研修・認定制度を通じて、訪問看護を担う人材の学びと成長を、生涯にわたって支援します。</p>
			</article>

			<article class="jsvn-pillar">
				<div class="jsvn-pillar__icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="7" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M2 21v-1a6 6 0 0 1 12 0v1"/><path d="M16 21v-1a5 5 0 0 1 6-4.6"/></svg>
				</div>
				<h3>地域をつなぐ多職種連携</h3>
				<p>医療・介護・福祉、そして地域社会へ。垣根を越えた連携の場をつくり、在宅ケアのネットワークを広げます。</p>
			</article>

		</div>
	</div>
</section>

<!-- ============================ お知らせ一覧 ============================ -->
<section class="jsvn-section">
	<div class="jsvn-container">
		<div class="jsvn-section-head">
			<span class="jsvn-eyebrow">NEWS &amp; TOPICS</span>
			<h2>お知らせ</h2>
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
					array( '2026.06.20', 'お知らせ', 'jsvn-news__cat--', '会員募集を開始いたしました' ),
					array( '2026.06.15', '学術大会', 'jsvn-news__cat--academic', '第1回学術集会の開催が決定しました' ),
					array( '2026.06.01', 'イベント', 'jsvn-news__cat--event', '設立記念シンポジウムを開催します' ),
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

		<div class="jsvn-news__more">
			<a class="jsvn-btn jsvn-btn--outline" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/news/' ) ); ?>">お知らせ一覧へ</a>
		</div>
	</div>
</section>

<!-- ============================ 学術大会 ============================ -->
<?php
$events = new WP_Query( array(
	'post_type'      => 'jsvn_event',
	'posts_per_page' => 2,
) );
if ( $events->have_posts() ) :
	?>
	<section class="jsvn-section jsvn-section--green">
		<div class="jsvn-container">
			<div class="jsvn-section-head">
				<span class="jsvn-eyebrow">ACADEMIC MEETING</span>
				<h2>学術大会・研究会</h2>
			</div>
			<div class="jsvn-cards">
				<?php
				while ( $events->have_posts() ) : $events->the_post();
					?>
					<article class="jsvn-card">
						<div class="jsvn-card__thumb">
							<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'jsvn-card' ); endif; ?>
						</div>
						<div class="jsvn-card__body">
							<div class="jsvn-card__meta"><?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?></div>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( jsvn_get_excerpt( 70 ) ); ?></p>
						</div>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php
endif;
?>

<!-- ============================ 入会案内 CTA ============================ -->
<section class="jsvn-section jsvn-section--navy jsvn-join">
	<div class="jsvn-container">
		<div class="jsvn-join__grid">

			<div class="jsvn-join__lead">
				<span class="jsvn-eyebrow" style="color:var(--jsvn-coral-soft);">MEMBERSHIP</span>
				<h2>仲間として、<br>一緒に訪問看護の未来を。</h2>
				<p>
					入会すると、学術集会への参加や学会誌の購読、研修・認定制度の利用など、
					学びと交流の機会が広がります。職種・経験を問わず、訪問看護に想いを寄せる
					すべての方を歓迎します。
				</p>
				<ul class="jsvn-join__benefits">
					<li>学術集会・研究会への会員価格での参加</li>
					<li>学会誌・最新研究情報の購読</li>
					<li>研修プログラム／認定制度の利用</li>
					<li>多職種・全国の仲間とのネットワーク</li>
				</ul>
			</div>

			<div class="jsvn-join__card">
				<p style="margin-bottom:.4rem;color:var(--jsvn-ink-soft);font-weight:700;">年会費（予定）</p>
				<p class="price"><strong>5,000</strong><span style="color:var(--jsvn-ink-soft);">円 / 年</span></p>
				<p class="note">※ 正会員の場合。学生会員・賛助会員の区分もご用意予定です。</p>
				<a class="jsvn-btn jsvn-btn--coral" href="<?php echo esc_url( home_url( '/join/' ) ); ?>" style="width:100%;justify-content:center;margin-top:1rem;">入会手続きへ進む</a>
				<a class="jsvn-btn jsvn-btn--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" style="width:100%;justify-content:center;margin-top:.8rem;border-color:var(--jsvn-line);">まずは問い合わせる</a>
			</div>

		</div>
	</div>
</section>

<?php
get_footer();
