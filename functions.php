<?php

// microCMS
define('MICROCMS_API_KEY', '04oMfTUtCvsb0CWmQneoJuXum6W1iYqgtETJ');

function format_diary_date($date_str) {
  $days = ['日', '月', '火', '水', '木', '金', '土'];
  $date = new DateTime($date_str);
  $date->setTimezone(new DateTimeZone('Asia/Tokyo'));
  $month = $date->format('n');
  $day = $date->format('j');
  $dow = $days[(int)$date->format('w')];
  return "{$month}月{$day}日({$dow})";
}

function fetch_microcms_diary($limit = 10, $offset = 0) {
  $url = "https://dou.microcms.io/api/v1/diary?limit={$limit}&offset={$offset}&orders=-publishedAt";
  $response = wp_remote_get($url, array(
    'headers' => array('X-MICROCMS-API-KEY' => MICROCMS_API_KEY)
  ));
  if (is_wp_error($response)) return null;
  return json_decode(wp_remote_retrieve_body($response), true);
}

// 指定JST日付(Ymd)の全エントリを取得（古い順）
// wp_remote_get がブラケットをURLエンコードしてmicroCMSのfilterが効かないため
// 全件取得してPHP側で日付フィルタリングする
function get_diary_entries_for_date($ymd) {
  $tz   = new DateTimeZone('Asia/Tokyo');
  $data = fetch_microcms_diary(100, 0);
  if (!$data || empty($data['contents'])) return [];
  $result = [];
  foreach ($data['contents'] as $e) {
    $d = new DateTime($e['publishedAt']);
    $d->setTimezone($tz);
    if ($d->format('Ymd') === $ymd) {
      $result[] = $e;
    }
  }
  // 古い順（publishedAt ASC）に並べる
  usort($result, function($a, $b) {
    return strcmp($a['publishedAt'], $b['publishedAt']);
  });
  return $result;
}

// 指定日付のN番目のエントリを取得（num=1が最古）
function get_diary_entry_by_date($ymd, $num = 1) {
  $entries = get_diary_entries_for_date($ymd);
  return $entries[intval($num) - 1] ?? null;
}

// 日記詳細ページのURL生成
function get_diary_url($ymd, $num = 1) {
  $base = home_url('/diary/' . $ymd);
  return ($num > 1) ? $base . '-' . $num : $base;
}

// 前後の日記エントリを返す（newest-firstの全件リストから検索）
// 戻り値: ['prev' => [...], 'next' => [...]]  prev=古い、next=新しい
function get_diary_prev_next($ymd, $num) {
  $data = fetch_microcms_diary(100, 0);
  if (!$data || empty($data['contents'])) return [null, null];

  $tz          = new DateTimeZone('Asia/Tokyo');
  $entries     = $data['contents']; // 新しい順
  $date_totals = [];
  foreach ($entries as $e) {
    $dk = (new DateTime($e['publishedAt']))->setTimezone($tz)->format('Ymd');
    $date_totals[$dk] = ($date_totals[$dk] ?? 0) + 1;
  }
  $date_seen = [];
  $indexed   = [];
  foreach ($entries as $e) {
    $dk = (new DateTime($e['publishedAt']))->setTimezone($tz)->format('Ymd');
    $date_seen[$dk] = ($date_seen[$dk] ?? 0) + 1;
    $n = $date_totals[$dk] - $date_seen[$dk] + 1;
    $indexed[] = ['ymd' => $dk, 'num' => $n, 'entry' => $e];
  }

  $current_idx = null;
  foreach ($indexed as $i => $item) {
    if ($item['ymd'] === $ymd && $item['num'] === intval($num)) {
      $current_idx = $i;
      break;
    }
  }
  if ($current_idx === null) return [null, null];

  // 新しい順配列なので index-1=新しい(next)、index+1=古い(prev)
  $next = ($current_idx > 0) ? $indexed[$current_idx - 1] : null;
  $prev = isset($indexed[$current_idx + 1]) ? $indexed[$current_idx + 1] : null;
  return [$prev, $next];
}

// ページタイトル生成
function get_site_title() {
  $site = get_bloginfo('name');

  // 日記詳細ページ
  $diary_date = get_query_var('diary_date');
  if ($diary_date) {
    $ymd   = preg_replace('/[^0-9]/', '', $diary_date);
    $num   = max(1, intval(get_query_var('diary_num') ?: 1));
    $entry = get_diary_entry_by_date($ymd, $num);
    if ($entry) {
      $label = format_diary_date($entry['publishedAt']);
      if ($num > 1) $label .= ' その' . $num;
      return esc_html($label) . ' | ' . $site;
    }
  }

  // 日記アーカイブページ
  if (is_page('diary')) return '日誌ではなく、日記。 | ' . $site;

  // カスタム投稿タイプアーカイブ
  if (is_post_type_archive('Works'))      return 'Works | ' . $site;
  if (is_post_type_archive('Blog'))       return 'Blog | ' . $site;
  if (is_post_type_archive('Exhibition')) return 'Exhibition | ' . $site;

  // 投稿・固定ページ詳細（タイトルあり）
  if (is_singular() && get_the_title()) return esc_html(get_the_title()) . ' | ' . $site;

  return $site;
}

// リライトルール登録
add_action('init', function() {
  // 詳細ページ: /diary/YYYYMMDD-N or /diary/YYYYMMDD
  add_rewrite_rule(
    '^diary/([0-9]{8})-([0-9]+)/?$',
    'index.php?pagename=diary&diary_date=$matches[1]&diary_num=$matches[2]',
    'top'
  );
  add_rewrite_rule(
    '^diary/([0-9]{8})/?$',
    'index.php?pagename=diary&diary_date=$matches[1]&diary_num=1',
    'top'
  );
});

// クエリvar登録
add_filter('query_vars', function($vars) {
  $vars[] = 'diary_date';
  $vars[] = 'diary_num';
  return $vars;
});

// wp_head: OGPメタタグ出力
add_action('wp_head', function() {
  $diary_date = get_query_var('diary_date');
  if (!$diary_date) return;
  $ymd = preg_replace('/[^0-9]/', '', $diary_date);
  $num = max(1, intval(get_query_var('diary_num') ?: 1));
  if (strlen($ymd) !== 8) return;

  $entry = get_diary_entry_by_date($ymd, $num);
  if (!$entry) return;

  $title    = format_diary_date($entry['publishedAt']);
  if ($num > 1) $title .= ' その' . $num;
  $ogp_url  = get_template_directory_uri() . '/img/ogp_diary.png';
  $page_url = get_diary_url($ymd, $num);
  $excerpt  = mb_strimwidth(strip_tags($entry['content']), 0, 120, '…');

  echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
  echo '<meta property="og:description" content="' . esc_attr($excerpt) . '" />' . "\n";
  echo '<meta property="og:url" content="' . esc_attr($page_url) . '" />' . "\n";
  echo '<meta property="og:image" content="' . esc_attr($ogp_url) . '" />' . "\n";
  echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
  echo '<meta name="twitter:image" content="' . esc_attr($ogp_url) . '" />' . "\n";
});

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