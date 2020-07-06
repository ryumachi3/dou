
<header class="is-zidx20" id="header" :class="[isTopPage ? '' : 'p-header--white',isBreadcrumbDown || isPC &amp;&amp; !isTopPage ? 'p-header--border' : '']">
  <h1 class="c-header-logo p-header-logo is-zidx10" :class="[ isDown || isMenu &amp;&amp; !isPC ? 'is-display-block' : '' ]"><a class="c-header-logo__link" href="<?php echo (is_home() || is_front_page() )? '#top': '/' ?>">
    <img class="c-header-logo__blog p-header-logo__blog" src="<?php bloginfo('template_url'); ?>/img/logo_blog.svg" width="39" height="21" alt="ドウ" :class="[ isBlogPage ? 'is-opacity1' : '' ]"/></a></h1>
  <div class="c-toggle p-toggle" v-on:click="isMenu=true" v-if="!isPC">
    <div class="c-toggle__bar c-toggle__bar--01"></div>
    <div class="c-toggle__bar c-toggle__bar--02"></div>
    <div class="c-toggle__bar c-toggle__bar--03"></div>
  </div>
  <nav class="p-gnav" id="gnav" v-if="isMenu &amp;&amp; (!isPC || isPC &amp;&amp; isTopPage &amp;&amp; !isDown || isPC &amp;&amp; !isTopPage)" :class="[ isMenu ? 'is-opacity1' : '' ]">
    <ul class="p-gnav__menu">
      <li class="p-gnav__menu__item"><a class="p-gnav__menu__link" href="/" :class="[ isTopPage ? 'p-gnav__menu__link--bar' : '' ]">Home</a></li>
      <li class="p-gnav__menu__item"><a class="p-gnav__menu__link" href="<?php echo get_post_type_archive_link( 'works' ); ?>" :class="[ isWorksPage ? 'p-gnav__menu__link--bar' : '' ]">Works</a></li>
      <li class="p-gnav__menu__item"><a class="p-gnav__menu__link" href="<?php echo get_post_type_archive_link( 'blog' ); ?>" :class="[ isBlogPage ? 'p-gnav__menu__link--bar' : '' ]" >Blog</a></li>
      <li class="p-gnav__menu__item"><a class="p-gnav__menu__link" href="<?php echo home_url( '/about' ); ?>" :class="[ isAboutPage ? 'p-gnav__menu__link--bar' : '' ]" >About</a></li>
      <li class="p-gnav__menu__item"><a class="p-gnav__menu__link" href="<?php echo home_url( '/contact' ); ?>" :class="[ isContactPage ? 'p-gnav__menu__link--bar' : '' ]">Contact</a></li>
    </ul>
    <div class="p-gnav__close" v-on:click="isMenu=false" v-if="!isPC"><a class="p-gnav__close__link">Close</a></div>
  </nav>
  <ul class="c-sns p-header__sns" :class="[isDown || isMenu &amp;&amp; !isPC ? 'p-header__sns--menu' : 'p-header__sns--top', isDown  &amp;&amp; !isMenu ? '' : 'is-flex']" v-if="!(isDown &amp;&amp; isPC)">
    <li class="c-sns__01"><a class="c-sns__link c-sns--instagram" href="https://www.instagram.com/ryumachi3"></a></li>
    <li class="c-sns__02"><a class="c-sns__link c-sns--twitter" href="https://twitter.com/ryumachi3"></a></li>
  </ul>
</header><?php
if (function_exists('bcn_display') && !is_front_page()) {
  echo '<ul class="c-breadcrumbs p-breadcrumbs">';
    bcn_display_list();
  echo '</ul>';
}
?>