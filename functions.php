<?php

// カスタム投稿タイプの１ページの最大表示件数
/*function change_posts_per_page($query) {
    if ( is_admin() || ! $query->is_main_query() ){
         return;
    }
    if ( $query->is_post_type_archive( 'Blog' ) ) {
         $query->set( 'posts_per_page', '2' );
         return;
    }
}
add_action( 'pre_get_posts', 'change_posts_per_page' );
*/

function template_directory() {
  // テンプレートディレクトリの絶対パスを出力するショートコード
	ob_start();
	bloginfo('template_directory');
	$td .= ob_get_clean();
	return $td;
}
// tdの部分が呼び出し時のショートコードの名前
add_shortcode('td', 'template_directory');

//----------------------
// pagenaviのクラス名変更
//----------------------
add_filter( 'wp_pagenavi_class_pages', 'custom_wp_pagenavi_class' );
add_filter( 'wp_pagenavi_class_page', 'custom_wp_pagenavi_class' );
add_filter( 'wp_pagenavi_class_extend', 'custom_wp_pagenavi_class' );
add_filter( 'wp_pagenavi_class_current', 'custom_wp_pagenavi_class' );
add_filter( 'wp_pagenavi_class_first', 'custom_wp_pagenavi_class' );
add_filter( 'wp_pagenavi_class_previouspostslink', 'custom_wp_pagenavi_class' );
add_filter( 'wp_pagenavi_class_nextpostslink', 'custom_wp_pagenavi_class' );
add_filter( 'wp_pagenavi_class_last', 'custom_wp_pagenavi_class' );
add_filter( 'wp_pagenavi_class_smaller', 'custom_wp_pagenavi_class' );
function custom_wp_pagenavi_class($class_name) {
  switch($class_name) {
    case 'pages':
      $class_name = 'p-pagenavi__pages';
      break;
    case 'page':
      $class_name = 'p-pagenavi__page';
      break;
    case 'extend':
      $class_name = 'p-pagenavi__extend';
      break;
    case 'current':
      $class_name = 'p-pagenavi__current';
      break;
    case 'first':
      $class_name = 'fas fa-angle-double-left p-pagenavi__first';
      break;
    case 'previouspostslink':
      $class_name = 'fas fa-angle-left p-pagenavi__prev';
      break;
    case 'nextpostslink':
      $class_name = 'fas fa-angle-right p-pagenavi__next';
      break;
    case 'last':
      $class_name = 'fas fa-angle-double-right p-pagenavi__last';
      break;
    case 'smaller':
      $class_name = 'p-pagenavi--smaller';
      break;
  }
  return $class_name;
}

function custom_wp_pagenavi($my_query) {
  $args = array(
    'wrapper_class' => 'p-pagenavi',
    'query' => $my_query
  );
  wp_pagenavi( $args );
}

// 投稿フォーマット
add_theme_support( 'post-formats', array( 'gallery' ) );

// ループ回数を取得
function loopNumber(){
global $wp_query;
return $wp_query->current_post+1;
}

// アイキャッチ画像を利用
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size(960, 960, true);

// アイキャッチ画像のショートコード
function thumbnail_disp() {
    return get_the_post_thumbnail();
}

function title_disp() {
    return get_the_title();
}

add_shortcode('thumbnail','thumbnail_disp');
add_shortcode('the_title','title_disp');

// ウィジット
register_sidebar(array(
	'before_widget' => '<div class="breadcrumbs">',
	'after_widget' => '</div>',
	'name' => 'パンくずリスト'
));

//------------------------
// カテゴリー別アーカイブ
//------------------------
/*
add_filter('getarchives_where', 'custom_archives_where', 10, 2);
add_filter('getarchives_join', 'custom_archives_join', 10, 2);

function custom_archives_join($x, $r) {
  global $wpdb;
  $cat_ID = $r['cat'];
  if (isset($cat_ID)) {
    return $x . " INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";
  } else {
    return $x;
  }
}

function custom_archives_where($x, $r) {
  global $wpdb;
  $cat_ID = $r['cat'];
  if (isset($cat_ID)) {
    return $x . " AND $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->term_taxonomy.term_id IN ($cat_ID)";
  } else {
    $x;
  }
}

function wp_get_cat_archives($opts, $cat) {
  $args = wp_parse_args($opts, array('echo' => '1')); // default echo is 1.
  $echo = $args['echo'] != '0'; // remember the original echo flag.
  $args['echo'] = 0;
  $args['cat'] = $cat;

  $archives = wp_get_archives(build_query($args));
  $archs = explode('</li>', $archives);
  $links = array();

  foreach ($archs as $archive) {
    $link = preg_replace("/href='([^']+)'/", "href='$1?cat={$cat}'", $archive);
    array_push($links, $link);
  }
  $result = implode('</li>', $links);

  if ($echo) {
    echo $result;
  } else {
    return $result;
  }
}
*/
//------------
// ギャラリー
//------------
/*
//ギャラリーのリンク先をデフォルトで「なし」に変更
function image_gallery_default_link( $settings ) {
    $settings['galleryDefaults']['link'] = 'none';
    return $settings;
}
add_filter( 'media_view_settings', 'image_gallery_default_link');
//本体ギャラリーCSS停止
add_filter( 'use_default_gallery_style', '__return_false' );
//ギャラリーのclass名にsliderを付加
add_filter( 'gallery_style', 'add_gallery_slider');
function add_gallery_slider($style){
	return str_replace('gallery ', 'gallery slider ',$style );
}
//ギャラリーのbrを削除
add_filter( 'the_content', 'remove_br_gallery', 11, 2);
function remove_br_gallery($output) {
    return preg_replace('/<br style=(.*)>/mi','',$output);
}
*/

//本文抜粋表示の[...]←を変更
function my_excerpt_more($more) {
   return '...';
}
add_filter('excerpt_more', 'my_excerpt_more');

//----------------------------------
// query_postsを使わなくする
//----------------------------------
/*
function my_post_queries( $query ) {
  // 管理画面のクエリを変更せず、さらにメインクエリだけにする
  if ( ! is_admin() && $query->is_main_query() ) {

		// デフォルトのページ毎記事数
		$query->set( 'posts_per_page', 12 );
		
		// カテゴリーページのクエリを変更
    if ( is_category('Works') ) {
			$query->set( 'category_name', 'Works' );
			$paged = get_query_var('paged')? get_query_var('paged') : 1;
			$query->set( 'paged', $paged );
			$query->set( 'posts_per_page', 8 );
		}

		if ( is_category('Blog') ) {
			$query->set( 'category_name', 'Blog' );
			$paged = get_query_var('paged')? get_query_var('paged') : 1;
			$query->set( 'paged', $paged );
			$query->set( 'year', get_query_var('year') );
			$query->set( 'posts_per_page', 12 );
		}

  }
}
add_action( 'pre_get_posts', 'my_post_queries' );
*/

// メディアを追加で「HTTPエラー」が出るのでその対処
add_filter( 'wp_image_editors', 'change_graphic_lib' );
function change_graphic_lib($array) {
return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
}

//　LINE Notifyとの連携
/*
if ( ! function_exists( 'deme_send_linenotify' ) ) {
	function deme_send_linenotify( $message, $image_thumbnail = '', $image_fullsize = '' ) {
		$url = 'https://notify-api.line.me/api/notify';
		$token = 'G7o5zw0gLnKOtcUcTD4wMNLaVxizcX6cSK5zCCUqczE';
		$response = wp_remote_post( $url, array(
			'method' => 'POST',
			'headers' => array(
				'Authorization' => 'Bearer '.$token,
			),
			'body' => array(
			 	'message' => $message,
			  'imageThumbnail' => $image_thumbnail,
			  'imageFullsize' => $image_fullsize,
			),
		));
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo "Error: $error_message";
		}
	}
}

//ContactForm7の通知をLINEに送る処理
function deme_wpcf7_mail_sent( $contact_form ) {
	$message = "WordPressページよりフォーム送信されました。\n";
	$message .= "タイトル：" . $contact_form->title;
	deme_send_linenotify( $message );
}
add_action( 'wpcf7_mail_sent', 'deme_wpcf7_mail_sent', 10, 1 );
?>
*/