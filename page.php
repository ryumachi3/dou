<?php get_header(); ?>
<?php get_template_part( 'nav' ); ?>
<!-- contents -->
<div id="contents">
  <section class="p-page">
    <article class="p-page__article">
      <?php while(have_posts()): the_post(); ?>
      <h1 class="c-ttl c-ttl--jp c-ttl--bar"><?php the_title(); ?></h1>
      <?php the_content(); ?>
      <?php endwhile; ?>
    </article>
  </section>
</div>
<?php get_footer(); ?>