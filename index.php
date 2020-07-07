<?php get_header(); ?>
<?php get_template_part( 'nav' ); ?>
<!-- main-visual-->
<div class="is-zidx-30 is-fixed" id="main-visual">
  <div class="p-outer-box">
    <picture class="p-main-frame"><img class="p-main-img" src="<?php bloginfo('template_url'); ?>/img/main-s.jpg"/></picture>
  </div>
  <transition name="fade3">
    <div class="p-main-logo is-zidx10" :class="[ isDown ? 'is-opacity0' : '' ]" v-if="isLoad"><img class="p-main-logo__mark mb16" src="<?php bloginfo('template_url'); ?>/img/logo.svg"/><img class="p-main-logo__ttl mb16" src="<?php bloginfo('template_url'); ?>/img/logo-ttl.svg" alt="DOU"/>
      <p class="p-main-logo__txt">Web Designer / Engineer in Okayama</p>
    </div>
  </transition>
</div>
<transition name="fade05">
  <div class="p-main-visual-frame is-zidx10 is-fixed" v-if="!isDown">
    <div class="p-main-visual-frame__inner"></div>
    <div class="p-scroll is-zidx50">
      <div class="p-scroll__txt">Scroll</div>
      <div class="p-scroll__arrow"></div>
    </div>    
  </div>  
</transition>
<div class="p-main-space" ref="mainSpace">
</div>
<!-- contents -->
<div id="contents">
  <section class="p-lead pt64 pb64" ref="lead">
    <transition name="slide-fade">
      <div class="p-lead__contents" >
        <h2 class="c-ttl c-ttl--en p-lead__ttl mb24"><span class="p-lead__ttl--obi">Design Engineered to Touch Your Mind.</span></h2>
        <p class="c-lead__txt p-lead__txt mb24">
          <span class="p-lead__txt--obi">いいデザインってなんだろう。</span><br/><span class="p-lead__txt--obi">WEBページで伝える。</span><br/><span class="p-lead__txt--obi">グラフィックで伝える。</span><br/><span class="p-lead__txt--obi">動画で伝える。</span><br/><span class="p-lead__txt--obi">文章で伝える。</span><br/><span class="p-lead__txt--obi">いいデザインって、ちゃんと伝わることだ。</span></p>
        <p class="c-lead__txt p-lead__txt mb24"><span class="p-lead__txt--obi">でも、それだけでいいんだっけ。</span><br/><span class="p-lead__txt--obi">Webページへ訪れた人が、</span><br/><span class="p-lead__txt--obi">アクションを起こす。</span><br/><span class="p-lead__txt--obi">グラッフィックを見た人が、</span><br/><span class="p-lead__txt--obi">動画を見た人が、感動する。</span><br/><span class="p-lead__txt--obi">文章を読んだ人の、心が動く。</span><br/><span class="p-lead__txt--obi">いいデザインって人を動かすことだ。      </span></p>
        <p class="c-lead__txt p-lead__txt"><span class="p-lead__txt--obi">人に伝わるデザインを。</span><br/><span class="p-lead__txt--obi">心を動かすデザインを。</span></p>
      </div>
    </transition>
  </section>
  <section class="p-service u-bg-dark pt64 pb64">
    <h2 class="c-ttl c-ttl--jp c-ttl--bar mb40">できること</h2>
    <div class="p-service__wrap">
      <article class="p-service__box p-service__box--02 pt32 pb32">
      <fade-in>
        <h3 class="p-service__subttl">ロゴ制作</h3><img class="p-service__img" src="<?php bloginfo('template_url'); ?>/img/icon_a.png" width="120" height="120" alt="Logo design"/>
        <p class="p-service__txt">名刺やWebサイトに欠かせないロゴの制作が得意です。その他のグラフィックデザインについてもご相談ください。</p>
      </fade-in>        
      </article>
      <article class="p-service__box p-service__box--01 pt32 pb32">
      <fade-in>
        <h3 class="p-service__subttl">Webページ制作</h3>
        <img class="p-service__img" src="<?php bloginfo('template_url'); ?>/img/icon_pc.png" width="120" height="120" alt="Web design"/>
        <p class="p-service__txt">デザイン〜コーディングまでおまかせください。依頼者の方の思いをロゴ〜Webまで一貫したデザインで形にするのが得意です。</p>
      </fade-in>
      </article>
      <article class="p-service__box p-service__box--03 pt32 pb32">
      <fade-in>
        <h3 class="p-service__subttl">動画制作</h3><img class="p-service__img" src="<?php bloginfo('template_url'); ?>/img/icon_movie.png" width="120" height="120" alt="Movie design"/>
        <p class="p-service__txt">写真、動画、グラフィックを編集してムービーを作成します。ウェデイングに欠かせないプロフィールムービー等の制作が得意です。</p>
      </fade-in>        
      </article>
    </div>
  </section>
  <section class="p-works p-works--top u-bg pt64 pb64">
    <h2 class="c-ttl c-ttl--en c-ttl--bar mb40">Works</h2>
    <?php 
      $args = array(
      'paged'     => $paged,
      'post_type' => 'Works',
      'posts_per_page' => 3,
      'post_status' => 'publish'
      ); 
      $my_query = new WP_Query($args);
      if ($my_query->have_posts()) :
    ?>
    <ul class="p-works__ul p-works__ul--top mb32">
      <?php
        $i = 1;
        while ($my_query->have_posts()) : $my_query->the_post();
        // ここに記事を表示するコードを書く
      ?>
      <li class="p-works__list  p-works__list--top p-works__list--<?php echo sprintf('%02d', $i); ?>">
        <fade-in>
        <a class="p-works__link p-works__link--top" href="<?php echo get_post_permalink(); ?>">
          <?php the_post_thumbnail(array(600, 300), array( 'class' => 'p-works__img mb12' ));?>
          <h3 class="p-works__ttl mb8"><?php the_title(); ?></h3>
          <header class='p-works__header'>
            <time class='p-works__header__time' datetime='<?php the_time('Y') ?>'><?php the_time('Y') ?></time> / 
            <div class="p-works__header__category">
              <?php
                foreach((get_the_category()) as $cat){
                  echo $cat->cat_name;
                  if ($cat != end(get_the_category())) {
                     echo ',';
                  }
                }
              ?>
            </div>
          </header>
        </a>
        </fade-in>  
      </li> 
      <?php
        $i++;
        endwhile;
      ?>
    </ul>
    <?php
      wp_reset_query();endif;
	  ?>
    <p><a class="c-btn c-btn--normal" href="<?php echo get_post_type_archive_link( 'works' ); ?>">もっと見る</a></p>
  </section>
  <section class="p-blog p-blog--top u-bg-dark pt64 pb64">
    <h2 class="c-ttl c-ttl--en c-ttl--bar mb40">Blog</h2>

      <ul class="p-blog__ul p-blog__ul--top mb32">
      <?php 
        $args = array(
        'paged'          => $paged,
        'post_type' => 'Blog',
        'posts_per_page' => 3,
        'post_status' => 'publish'  
        ); 
        $my_query = new WP_Query($args);
        if ($my_query->have_posts()) :
        $i = 1;
        while ($my_query->have_posts()) : $my_query->the_post();
        // ここに記事を表示するコードを書く
      ?>                          
      <li class="p-blog__list p-blog__list--top p-blog__list--<?php echo sprintf('%02d', $i); ?>">
        <fade-in>
        <a class="p-blog__card" href="<?php the_permalink(); ?>">                       <div class="p-blog__left">
            <?php the_post_thumbnail(array(600, 300), array( 'class' => 'u-object-fit-img p-blog__img' ));?>
          </div>
          <div class="p-blog__right">
            <div class="p-blog__supple">
              <div class="c-tag c-tag--normal">
              <?php 
                // カテゴリ名取得
                $terms = wp_get_object_terms($post->ID, 'blog_cat');
                $term = $terms[0];
                echo $term->name;
              ?>
              </div>
              <time class="c-time p-blog__time" datetime="<?php the_time('Y/m/d') ?>"><?php the_time('Y/m/d') ?></time>
            </div>
            <h3 class="p-blog__ttl mt8"><?php the_title(); ?></h3>

          </div>
        </a>
        </fade-in>
      </li>
      <?php
        $i++;
        endwhile;
      ?>
    </ul>
    <?php
      wp_reset_query();endif;
    ?>      
    <p><a class="c-btn c-btn--normal" href="<?php echo get_post_type_archive_link( 'blog' ); ?>">もっと見る</a></p>
  </section>
  <section class="p-works p-works--top u-bg pt64 pb64">
    <h2 class="c-ttl c-ttl--en c-ttl--bar mb40">Exhibition</h2>
    <?php 
      $args = array(
      'paged'     => $paged,
      'post_type' => 'Exhibition',
      'posts_per_page' => 3,
      'post_status' => 'publish'
      ); 
      $my_query = new WP_Query($args);
      if ($my_query->have_posts()) :
    ?>
    <ul class="p-works__ul p-works__ul--top mb32">
      <?php
        $i = 1;
        while ($my_query->have_posts()) : $my_query->the_post();
        // ここに記事を表示するコードを書く
      ?>
      <li class="p-works__list  p-works__list--top p-works__list--<?php echo sprintf('%02d', $i); ?>">
        <fade-in>
        <a class="p-works__link p-works__link--top" href="<?php echo get_post_permalink(); ?>">
          <?php the_post_thumbnail(array(600, 300), array( 'class' => 'p-works__img mb12' ));?>
          <h3 class="p-works__ttl mb8"><?php the_title(); ?></h3>
          <header class='p-works__header'>
            <time class='p-works__header__time' datetime='<?php the_time('Y-m-d') ?>'><?php the_time('Y-m-d') ?></time> 
          </header>
        </a>
        </fade-in>  
      </li> 
      <?php
        $i++;
        endwhile;
      ?>
    </ul>
    <?php
      wp_reset_query();endif;
	  ?>
    <p><a class="c-btn c-btn--normal" href="<?php echo get_post_type_archive_link( 'exhibition' ); ?>">もっと見る</a></p>
  </section>
  <section class="p-contact u-bg-dark pt64 pb64">
    <h2 class="c-ttl c-ttl--en c-ttl--bar mb40">Contact</h2>
    <p class="p-contact__txt mb32">制作のご依頼・ご相談やこのサイトに関するお問い合わせはこちらから。</p>
    <p><a class="c-btn c-btn--point p-contact__btn" href="<?php echo home_url( '/contact' ); ?>">お問い合わせフォームへ</a></p>
  </section>
</div><?php get_footer(); ?>