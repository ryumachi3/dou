    <footer id="footer" class="p-footer <?php echo is_home()?'u-bg':'u-bg-dark'?>">
      <nav class="c-fnav p-fnav">
        <ul class="c-fnav__menu p-fnav__menu mb8">
          <li class="c-fnav__menu01 p-fnav__list p-fnav__list--top"><a class="p-fnav__link" href="<?php echo home_url(); ?>">ホーム</a></li>
          <li class="c-fnav__menu03 p-fnav__list"><a class="p-fnav__link" href="<?php echo get_post_type_archive_link( 'works' ); ?>">製作実績一覧</a></li>
          <li class="c-fnav__menu04 p-fnav__list"><a class="p-fnav__link" href="<?php echo get_post_type_archive_link( 'blog' ); ?>">ブログ記事一覧</a></li>
          <li class="c-fnav__menu06 p-fnav__list"><a class="p-fnav__link" href="<?php echo get_post_type_archive_link( 'exhibition' ); ?>">個人的な創作物一覧</a></li>
          <li class="c-fnav__menu07 p-fnav__list"><a class="p-fnav__link" href="<?php echo home_url( '/about' ); ?>">DOUについて</a></li>
          <li class="c-fnav__menu08 p-fnav__list"><a class="p-fnav__link" href="<?php echo home_url( '/privacy' ); ?>">プライバシーポリシー</a></li>
          <li class="c-fnav__menu09 p-fnav__list"><a class="p-fnav__link" href="<?php echo home_url( '/contact' ); ?>">お問い合わせ</a></li>     
        </ul>
        <ul class="c-sns p-fnav__sns">
          <li class="c-sns__01"><a class="c-sns__link c-sns--instagram" href="https://www.instagram.com/ryumachi3"></a></li>
          <li class="c-sns__02"><a class="c-sns__link c-sns--twitter" href="https://twitter.com/ryumachi3"></a></li>
        </ul>
      </nav>
      <small class="c-copyright p-copyright">©︎2019 by DOU All rights reserved.</small>
    </footer>
    </div> <!-- #app -->
    <script type="application/javascript" src="<?php bloginfo('template_url'); ?>/js/main.js"></script>
    <?php wp_footer(); ?>
  </body>
</html>