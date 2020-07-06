<?php get_header(); ?>
<?php get_template_part( 'nav' ); ?>
<!-- contents -->
<div id="contents">
  <section class="p-blog-dt">
    <article class="p-blog-dt__article">
      <?php if(have_posts()); ?>
        <?php while(have_posts()): the_post(); ?>
          <?php the_post_thumbnail('full', array( 'class' => 'p-blog-dt__thumbnail u-object-fit-img' ));?>
          <div class="c-tag c-tag--normal p-blog-dt__tag mt40">
            <?php 
              // カテゴリ名取得
              $terms = wp_get_object_terms($post->ID, 'blog_cat');
              $term = $terms[0];
              echo $term->name;
            ?>
          </div>
          <h2 class="p-blog-dt__ttl mt8 mb8"><?php the_title(); ?></h2>
          <header class="p-blog-dt__header mb40">
            <?php $blogusers = get_users(); ?>
            <a href="<?php echo $blogusers[0]->user_url ?>" class="p-blog-dt__header__author-link">
            <?php
              $args = array(
                'class' => 'p-blog-dt__header__author-img',
                );
              echo get_avatar($blogusers[0]->user_email, 32, $default, $alt, $args); 
            ?></a>
            <div class="p-blog-dt__header__txt">
              <a href="<?php echo $blogusers[0]->user_url ?>" class="p-blog-dt__header__author-name mb8"><?php echo $blogusers[0]->display_name; ?></a>
              <time class='p-blog-dt__header__time' datetime='<?php the_time('Y/m/d H:i') ?>'><?php the_time('Y/m/d H:i') ?></time>
            </div>
          </header>
          <div class="p-blog-dt__content mb40">
            <?php the_content(); ?>
          </div>
        <?php endwhile; ?>
        <ul class="c-share p-share">
          <li class="c-share__item">
            <a class="c-share__link c-share--twitter" href="https://twitter.com/intent/tweet?url=<?php echo get_the_permalink(); ?>&text=<?php echo the_title();?>%20%7C%20DOU%20%7C%20@ryumachi3"></a>
          </li>
          <li class="c-share__item">
            <a class="c-share__link c-share--facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>"></a>
          </li>
          <li class="c-share__item">
            <a class="c-share__link c-share--hatena" href="http://b.hatena.ne.jp/add?mode=confirm&url=<?php echo get_the_permalink(); ?>&title=<?php echo the_title();?>"></a>
          </li>
          <li class="c-share__item">
            <a class="c-share__link c-share--line" href="https://social-plugins.line.me/lineit/share?url=<?php echo get_the_permalink(); ?>"></a>
          </li>
        </ul>
        <div class="p-writer mb24">
            <a href="<?php echo $blogusers[0]->user_url ?>" class="p-writer__author-link">
            <?php
              $args = array(
                'class' => 'p-writer__author-img',
                );
              echo get_avatar($blogusers[0]->user_email, 56, $default, $alt, $args); 
            ?></a>
            <div class="p-writer__txt">
              <div class="p-writer__title">この記事を書いた人</div>
              <a href="<?php echo $blogusers[0]->user_url ?>" class="p-writer__author-name"><?php echo $blogusers[0]->display_name; ?></a>
            </div>
            <ul class="c-sns p-writer__sns">
              <li class="c-sns__01"><a class="c-sns__link c-sns--instagram" src="https://www.instagram.com/ryumachi3"></a></li>
              <li class="c-sns__02"><a class="c-sns__link c-sns--twitter" src="https://twitter.com/ryumachi3"></a></li>
            </ul>
        </div>
        <div class="p-blog-ctl">
          <ul class="p-blog-ctl__pager mb24">
            <?php
              $prevPost = get_previous_post(); //次の記事データを取得
              if(!empty($prevPost)):
            ?>            
            <li class="p-blog-ctl__pager__left">
            <?php
              $prevThumbnail = get_the_post_thumbnail($prevPost->ID, array(113,60) ); //サムネイル取得
              echo $prevthumbnail;
              previous_post_link( '%link', '<div class="p-blog-ctl__pager__title">%title</div>'.$prevThumbnail ); //出力
            ?>
            </li>
            <?php
              endif;
            ?>
            <?php
              $nextPost = get_next_post(); //次の記事データを取得
              if(!empty($nextPost)):
            ?>
            <li class="p-blog-ctl__pager__right">
              <?php
                $nextThumbnail = get_the_post_thumbnail($nextPost->ID, array(113,60) ); //サムネイル取得
                echo $nextthumbnail;
                next_post_link( '%link', $nextThumbnail.'<div class="p-blog-ctl__pager__title">%title</div>' ); //出力
              ?>
            </li>
            <?php
              endif;
            ?>
          </ul>
          <p class="p-blog-ctl__back"><a class="c-btn c-btn--normal" href="<?php echo get_post_type_archive_link( 'blog' ); ?>">記事一覧へ</a></p>
        </div>
    </article>
  </section>
</div>
<?php get_footer(); ?>