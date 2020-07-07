<?php get_header(); ?>
<?php get_template_part( 'nav' ); ?>
<!-- contents -->
<div id="contents">
  <section class="p-works-dt">
    <article class="p-works-dt__article">
      <?php if(have_posts()); ?>
        <?php while(have_posts()): the_post(); ?>
          <?php the_post_thumbnail('full', array( 'class' => 'p-works-dt__thumbnail u-object-fit-img' ));?>
          <?php $site_url = get_post_meta(get_the_ID(), 'site_url', true); ?>
          <h1 class="p-works-dt__ttl mt64 mb24">
          <?php if($site_url != ""): ?>
          <a class="p-works-dt__site-link" href="<?php echo $site_url; ?>">
          <?php endif; ?>
            <?php the_title(); ?>
          <?php if($site_url != ""): ?>
          </a>
          <?php endif; ?>
          </h1>
          <header class="p-works-dt__header">
            <time class='p-works-dt__header__time mb8' datetime='<?php the_time('Y-m-d') ?>'><?php the_time('Y-m-d') ?></time>
          </header>
          <div class="p-works-dt__contents">
            <?php the_content(); ?>
          </div>      
          <dl class="p-works-dt__spec">
            <?php
              $fields = array(
                "time" => get_field('time'),
              );
            
              foreach ($fields as $key=>$field):
                if($field):
                  $fielddata = get_field_object($key);
            ?>
                  <dt class="p-works-dt__spec__ttl">
                    <?php echo $fielddata['label']; ?>
                  </dt>
                  <?php if($key == "url"): ?>
                    <dd class="p-works-dt__spec__txt p-works-dt__spec__txt--url">
                      <a class="p-works-dt__spec__txt__link" href="<?php echo $field ?>">
                        <?php echo $field ?>
                      </a>
                    </dd>
                  <?php else: ?>
                    <dd class="p-works-dt__spec__txt">
                      <?php echo $field ?>
                    </dd>
                  <?php endif; ?>
            <?php
                endif;
              endforeach;
            ?>
          </dl>
        <?php endwhile; ?>
    </article>
  </section>
  <div class="p-works-ctl pt40 pb40">
    <div class="p-works-ctl__pager mb24">
      <div class="p-works-ctl__pager__left">
      <?php
        $prevPost = get_previous_post(); //次の記事データを取得
        $prevThumbnail = get_the_post_thumbnail($prevPost->ID, array(113,60) ); //サムネイル取得
        echo $prevthumbnail;
        previous_post_link( '%link', $prevThumbnail ); //出力
      ?>
      </div>
      <div class="p-works-ctl__pager__right">
        <?php
          $nextPost = get_next_post(); //次の記事データを取得
          $nextThumbnail = get_the_post_thumbnail($nextPost->ID, array(113,60) ); //サムネイル取得
          echo $nextthumbnail;
          next_post_link( '%link', $nextThumbnail ); //出力
        ?>
      </div>
    </div>
    <p class="p-works-ctl__back"><a class="c-btn c-btn--normal" href="<?php echo get_post_type_archive_link( 'works' ); ?>">一覧へ戻る</a></p>
  </div>
</div>
<?php get_footer(); ?>