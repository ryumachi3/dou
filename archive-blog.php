<?php get_header(); ?>
<?php get_header(); ?>
<?php get_template_part( 'nav' ); ?>
<!-- contents -->
<div id="contents">
  <section class="p-blog">
      <h2 class="c-ttl c-ttl--en c-ttl--bar">Blog</h2>
      <div class="p-blog__wrap">
        <div class="p-blog__main">
          <?php 
            // ブログトップページのみ、ピックアップ記事を表示
            if( !is_paged() ) : 
          ?>
          <ul class="p-blog-pick mb40">
            <?php 
              $args = array(
                'post_type' => 'Blog',
                'meta_query' => array(
                array(
                  'key'=>'pickup', // フィールド名
                  'value'=>'1' // 真の場合「1」、偽の場合「0」
                ),
                'post_status' => 'publish',
              ));
              $pickup = get_posts($args);
              if(($pickup)){ // ピックアップの記事が存在するか確認
              foreach($pickup as $post):setup_postdata($post);
              // ここに記事を表示するコードを書く
            ?>                          
            <li>
              <a class="p-blog-pick__link" href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail(array(1200, 600), array( 'class' => 'u-object-fit-img p-blog-pick__img' ));?>
                <div class="c-tag c-tag--normal p-blog-pick__tag">
                  <?php 
                    // カテゴリ名取得
                    $terms = wp_get_object_terms($post->ID, 'blog_cat');
                    $term = $terms[0];
                    echo $term->name;
                  ?>
                </div>
                <h3 class="p-blog-pick__ttl"><?php the_title(); ?></h3>
                <time class="c-time p-blog-pick__time" datetime="<?php the_time('Y/m/d') ?>"><?php the_time('Y/m/d') ?></time>
              </a>
            </li>
            <?php
              endforeach; 
              }
            ?>
          </ul>
          <?php endif; ?>
          <h3 class="c-ttl-sub c-ttl-sub--jp c-ttl-sub--bar mb32">新着記事</h3>
          <ul class="p-blog__ul mb32">
            <?php 
              $args = array(
              'paged'          => $paged,
              'post_type' => 'Blog',
              'posts_per_page' => 16,
              'post_status' => 'publish',  
              ); 
              $my_query = new WP_Query($args);
              if ($my_query->have_posts()) :
              while ($my_query->have_posts()) : $my_query->the_post();
              // ここに記事を表示するコードを書く
            ?>                          
            <li class="p-blog__list">
              <a class="p-blog__card" href="<?php the_permalink(); ?>">
                <div class="p-blog__left">
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
            </li>
            <?php
              endwhile;
            ?>
          </ul>
        <?php
          wp_reset_query();endif;
          if(function_exists('custom_wp_pagenavi')) custom_wp_pagenavi($my_query);
        ?>
      </div>
      <div class="p-sidebar">
        <div class="p-sidebar__box p-category mb32">
          <h4 class="p-sidebar__ttl p-sidebar__ttl--category">カテゴリー</h4>
          <div class="p-sidebar__contents">
            <ul class="p-sidebar__lists pt16 pb16">
              <?php 
                // カテゴリ名取得
                $terms = get_terms('blog_cat','hide_empty=0');
                foreach($terms as $term){
              ?>
              <li class="p-sidebar__list">
                <a class="p-category__link <?php echo 'p-category__link--'.esc_html($term->slug); ?>"><?php echo esc_html($term->name); ?></a>
              </li>
              <?php
                }
              ?>
            </ul>
          </div>
        </div>
        <div class="p-sidebar__box p-profile">
          <h4 class="p-sidebar__ttl p-sidebar__ttl--profile">DOUについて</h4>
          <div class="p-sidebar__contents pt24 pb24">
            <div class="p-profile__contents">
            <div class="p-profile__img">
              <img class="p-profile__icon" src="<?php bloginfo('template_url'); ?>/img/profile_icon.png"/>
              <ul class="c-sns p-profile__sns">
                <li class="c-sns__01"><a class="c-sns__link c-sns--instagram" href="https://www.instagram.com/ryumachi3"></a></li>
                <li class="c-sns__02"><a class="c-sns__link c-sns--twitter" href="https://twitter.com/ryumachi3"></a></li>
              </ul>
            </div>
            <?php $blogusers = get_users(); ?>
            <div class="p-profile__txt">
              <dl>
                <dt class="p-profile__name mb8"><?php echo $blogusers[0]->display_name; ?></dt>
                <dd class="p-profile__description">デザインからプログラミングまでワンストップで対応するデザイナー兼エンジニア。主にWebデザインに関することを書いていきます。</dd>
              </dl>
            </div>
            </div>
            <p class="p-profile__btn mt16"><a class="c-btn c-btn--normal" href="<?php echo home_url( '/about' ); ?>" >もっと見る</a></p>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php get_footer(); ?>