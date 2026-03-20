<?php get_header(); ?>
<?php get_template_part( 'nav' ); ?>
<!-- contents -->
<div id="contents" class="u-bg-dark">
  <section class="p-diary pt88 pb64 ">
<?php
$diary_date = get_query_var('diary_date');
$diary_num  = max(1, intval(get_query_var('diary_num') ?: 1));

if ($diary_date):
  // ── 詳細ページ ──────────────────────────────────────
  $ymd     = preg_replace('/[^0-9]/', '', $diary_date);
  $entries = get_diary_entries_for_date($ymd);
  $entry   = $entries[$diary_num - 1] ?? null;
  if ($entry):
    $num_suf    = ($diary_num > 1) ? ' <span class="p-diary__ttl-sub">その' . $diary_num . '</span>' : '';
    $page_title = format_diary_date($entry['publishedAt']) . $num_suf;
    list($prev, $next) = get_diary_prev_next($ymd, $diary_num);
?>
    <h1 class="c-ttl c-ttl--en c-ttl--bar mb40">Diary</h1>
    <p class="p-diary__desc mb40">日誌ではなく、日記。</p>
    <div class="p-diary__wrap p-diary__wrap--detail">
      <div class="p-diary__book p-diary__book--detail">
        <article class="p-diary__page p-diary__page--detail">
          <h2 class="p-diary__ttl"><?php echo $page_title; ?></h2>
          <div class="p-diary__content"><?php echo $entry['content']; ?></div>
        </article>
      </div>
      <nav class="p-diary__detail-nav mt32">
        <?php if ($prev): ?>
        <?php $prev_suf = ($prev['num'] > 1) ? ' <span class="p-diary__ttl-sub">その' . $prev['num'] . '</span>' : ''; ?>
        <a class="p-diary__nav-link p-diary__nav-link--prev" href="<?php echo esc_url(get_diary_url($prev['ymd'], $prev['num'])); ?>">
          <span class="p-diary__nav-label">← 前の日記へ</span>
          <span class="p-diary__nav-date"><?php echo esc_html(format_diary_date($prev['entry']['publishedAt'])); ?><?php echo $prev_suf; ?></span>
        </a>
        <?php else: ?><span class="p-diary__nav-link p-diary__nav-link--prev is-disabled"></span><?php endif; ?>
        <a class="c-btn c-btn--normal p-diary__nav-link--list" href="<?php echo home_url('/diary'); ?>">日記一覧へ</a>
        <?php if ($next): ?>
        <?php $next_suf = ($next['num'] > 1) ? ' <span class="p-diary__ttl-sub">その' . $next['num'] . '</span>' : ''; ?>
        <a class="p-diary__nav-link p-diary__nav-link--next" href="<?php echo esc_url(get_diary_url($next['ymd'], $next['num'])); ?>">
          <span class="p-diary__nav-label">次の日記へ →</span>
          <span class="p-diary__nav-date"><?php echo esc_html(format_diary_date($next['entry']['publishedAt'])); ?><?php echo $next_suf; ?></span>
        </a>
        <?php else: ?><span class="p-diary__nav-link p-diary__nav-link--next is-disabled"></span><?php endif; ?>
      </nav>
    </div>
<?php else: ?>
    <h1 class="c-ttl c-ttl--en c-ttl--bar mb40">Diary</h1>
    <p class="p-diary__empty">該当の日記が見つかりませんでした。</p>
    <p class="mt32"><a class="c-btn c-btn--normal" href="<?php echo home_url('/diary'); ?>">← 日記一覧へ</a></p>
<?php endif; ?>

<?php else:
  // ── アーカイブページ ─────────────────────────────────
  $per_page = 20;
  $offset   = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
  $diary_data = fetch_microcms_diary($per_page, $offset);
  if ($diary_data && !empty($diary_data['contents'])):
    $total   = $diary_data['totalCount'];
    $entries = $diary_data['contents'];

    // 同じ日付のエントリに連番をつける（新しい方が「その2」）
    $date_counts = [];
    $date_index  = [];
    foreach ($entries as $e) {
      $dk = (new DateTime($e['publishedAt']))->setTimezone(new DateTimeZone('Asia/Tokyo'))->format('Ymd');
      $date_counts[$dk] = ($date_counts[$dk] ?? 0) + 1;
    }
    $titled_entries = [];
    foreach ($entries as $e) {
      $dk = (new DateTime($e['publishedAt']))->setTimezone(new DateTimeZone('Asia/Tokyo'))->format('Ymd');
      $date_index[$dk] = ($date_index[$dk] ?? 0) + 1;
      $number = $date_counts[$dk] - $date_index[$dk] + 1;
      $num_suf = ($number > 1) ? ' <span class="p-diary__ttl-sub">その' . $number . '</span>' : '';
      $url     = get_diary_url($dk, $number);
      $e['_title']     = '<a class="p-diary__ttl-link" href="' . esc_url($url) . '">' . format_diary_date($e['publishedAt']) . $num_suf . '</a>';
      $e['_diary_num'] = $number;
      $titled_entries[] = $e;
    }
?>
    <h1 class="c-ttl c-ttl--en c-ttl--bar mb40">Diary</h1>
    <p class="p-diary__desc mb40">日誌ではなく、日記。<br>その日あったことではなくて、忘れたくない感覚や考えたことを残しておくメモのようなもの。</p>
    <div class="p-diary__wrap p-diary__wrap--archive">
      <?php
        for ($i = 0; $i < count($titled_entries); $i += 2):
          $pair = array_slice($titled_entries, $i, 2);
      ?>
      <div class="p-diary__book p-diary__book--archive mb16">
        <?php foreach ($pair as $j => $entry):
          $page_cls = ($j === 0) ? 'p-diary__page--new' : 'p-diary__page--prev';
        ?>
        <article class="p-diary__page <?php echo $page_cls; ?>">
          <h2 class="p-diary__ttl"><?php echo $entry['_title']; ?></h2>
          <div class="p-diary__content"><?php echo $entry['content']; ?></div>
        </article>
        <?php endforeach; ?>
      </div>
      <?php endfor; ?>
    </div>
    <?php if ($total > $per_page): ?>
    <div class="p-diary__pager mt32">
      <?php if ($offset > 0): ?>
      <a class="c-btn c-btn--normal" href="?offset=<?php echo $offset - $per_page; ?>">← 新しい日記</a>
      <?php endif; ?>
      <?php if ($offset + $per_page < $total): ?>
      <a class="c-btn c-btn--normal" href="?offset=<?php echo $offset + $per_page; ?>">古い日記 →</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  <?php else: ?>
    <h1 class="c-ttl c-ttl--en c-ttl--bar mb40">Diary</h1>
    <p class="p-diary__empty">日記はまだありません。</p>
  <?php endif; ?>
<?php endif; ?>
  </section>
</div>
<?php get_footer(); ?>
