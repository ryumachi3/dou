<?php get_header(); ?>
<?php get_template_part( 'nav' ); ?>
<!-- contents -->
<div id="contents">
  <section class="p-works u-bg">
    <h2 class="c-ttl c-ttl--jp c-ttl--bar">Works</h2>
    <?php 
      $args = array(
      'paged'     => $paged,
      'post_type' => 'Works',
      'posts_per_page' => 10,
      'post_status' => 'publish',        
      ); 
      $my_query = new WP_Query($args);
      if ($my_query->have_posts()) :
    ?>    
    <ul class="p-works__ul mb32">
      <?php
        $i = 1;
        while ($my_query->have_posts()) : $my_query->the_post();
        // ここに記事を表示するコードを書く
      ?>
      <li class="p-works__list p-works__list--archive p-works__list--<?php echo sprintf('%02d', $i); ?>">
        <fade-in>
        <a class="p-works__link p-works__link--archive" href="<?php echo get_post_permalink(); ?>">
          <?php the_post_thumbnail(array(600, 300), array( 'class' => 'p-works__img p-works__img--archive mb12' ));?>
          <div class="p-works__ttls">
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
  </section>
</div><?php get_footer(); ?>