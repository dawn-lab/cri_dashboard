<?php
// v1.1.0 — adds instruction banner and action labels (Start / Continue / Review)
// replacing the static status labels. Data layer unchanged from v1.0.0.

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/completionlib.php');
require_login();

$PAGE->set_url(new moodle_url('/local/cri_progress/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('page_title', 'local_cri_progress'));
$PAGE->set_heading('');
$PAGE->requires->css(new moodle_url('/local/cri_progress/styles.css'));

echo $OUTPUT->header();

// ────────────────────────────────────────────────────────────────
//  DATA LAYER  (unchanged from v1.0.0)
// ────────────────────────────────────────────────────────────────
$groups = [
    'cat_mindset'       => ['title' => 'Mindset & Emotions',           'key' => 'mindset'],
    'cat_relationships' => ['title' => 'Relationships & Communication','key' => 'relationships'],
    'cat_learning'      => ['title' => 'Learning & Tech Skills',       'key' => 'learning'],
    'cat_work'          => ['title' => 'Work & Career',                'key' => 'work'],
    'cat_money'         => ['title' => 'Money Matters',                'key' => 'money'],
    'cat_health'        => ['title' => 'Physical Health',              'key' => 'health'],
    'cat_reentry'       => ['title' => 'Reentry & Daily Life',         'key' => 'reentry'],
];

$icons = [
    'mindset'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 1.98-3A2.5 2.5 0 0 1 9.5 2z"/><path d="M14.5 2a2.5 2.5 0 0 0-2.5 2.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-1.98-3A2.5 2.5 0 0 0 14.5 2z"/></svg>',
    'relationships' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>',
    'learning'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
    'work'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
    'money'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
    'health'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
    'reentry'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/></svg>',
];

// Arrow icon used on every action pill
$arrow_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>';

// 3-state status helper
function cri_course_status($course, $userid) {
    $completion = new completion_info($course);
    if (!$completion->is_enabled()) return 'ready';
    if ($completion->is_course_complete($userid)) return 'complete';
    $activities = $completion->get_activities();
    foreach ($activities as $activity) {
        $data = $completion->get_data($activity, false, $userid);
        if ($data && $data->completionstate != COMPLETION_INCOMPLETE) return 'progress';
    }
    return 'ready';
}

// Action label for each status (replaces old static labels)
function cri_action_label($status) {
    switch ($status) {
        case 'complete': return get_string('action_review',   'local_cri_progress');
        case 'progress': return get_string('action_continue', 'local_cri_progress');
        default:         return get_string('action_start',    'local_cri_progress');
    }
}

// Pre-calculate everything before rendering
$cards = [];
$overall_total = 0;
$overall_done  = 0;
$overall_prog  = 0;

foreach ($groups as $settingkey => $meta) {
    $catid = (int)get_config('local_cri_progress', $settingkey);
    $courses = [];
    if (!empty($catid)) {
        $courses = $DB->get_records('course',
            ['category' => $catid, 'visible' => 1],
            'sortorder ASC',
            'id, fullname, shortname, enablecompletion');
    }

    $coursedata = [];
    $done = 0;
    $prog = 0;
    foreach ($courses as $course) {
        $status = cri_course_status($course, $USER->id);
        if ($status === 'complete') $done++;
        if ($status === 'progress') $prog++;
        $coursedata[] = [
            'id'     => $course->id,
            'name'   => $course->fullname,
            'status' => $status,
            'action' => cri_action_label($status),
        ];
    }
    $total = count($coursedata);
    $pct = $total > 0 ? round(($done / $total) * 100) : 0;

    $overall_total += $total;
    $overall_done  += $done;
    $overall_prog  += $prog;

    $cards[] = [
        'key'       => $meta['key'],
        'title'     => $meta['title'],
        'catid'     => $catid,
        'courses'   => $coursedata,
        'total'     => $total,
        'done'      => $done,
        'pct'       => $pct,
        'icon'      => $icons[$meta['key']],
        'configured'=> !empty($catid),
    ];
}

$overall_pct = $overall_total > 0 ? round(($overall_done / $overall_total) * 100) : 0;

// Ring math
$hero_c = 314.159;
$hero_offset = $hero_c - ($hero_c * $overall_pct / 100);
$card_c = 150.796;

?>

<div class="cri-wrap">

  <!-- Hero card -->
  <section class="cri-hero">
    <div class="cri-hero-text">
      <div class="cri-hero-eyebrow"><?php echo get_string('hero_eyebrow', 'local_cri_progress'); ?></div>
      <h1 class="cri-hero-title"><?php echo get_string('hero_title', 'local_cri_progress'); ?></h1>
      <p class="cri-hero-sub"><?php echo get_string('hero_sub', 'local_cri_progress'); ?></p>
      <div class="cri-hero-stats">
        <div class="cri-hero-stat">
          <div class="cri-hero-stat-num"><?php echo $overall_done; ?></div>
          <div class="cri-hero-stat-label"><?php echo get_string('stat_completed', 'local_cri_progress'); ?></div>
        </div>
        <div class="cri-hero-stat">
          <div class="cri-hero-stat-num"><?php echo $overall_prog; ?></div>
          <div class="cri-hero-stat-label"><?php echo get_string('stat_inprogress', 'local_cri_progress'); ?></div>
        </div>
        <div class="cri-hero-stat">
          <div class="cri-hero-stat-num"><?php echo $overall_total; ?></div>
          <div class="cri-hero-stat-label"><?php echo get_string('stat_total', 'local_cri_progress'); ?></div>
        </div>
      </div>
    </div>
    <div class="cri-hero-ring">
      <svg viewBox="0 0 120 120">
        <circle class="cri-ring-track" cx="60" cy="60" r="50"/>
        <circle class="cri-ring-fill cri-hero-ring-fill" cx="60" cy="60" r="50"
                stroke-dasharray="<?php echo $hero_c; ?>"
                stroke-dashoffset="<?php echo $hero_c; ?>"
                data-target="<?php echo $hero_offset; ?>"/>
      </svg>
      <div class="cri-hero-ring-center">
        <div class="cri-hero-ring-pct" data-target="<?php echo $overall_pct; ?>">0%</div>
        <div class="cri-hero-ring-cap"><?php echo get_string('ring_overall', 'local_cri_progress'); ?></div>
      </div>
    </div>
  </section>

  <!-- v1.1.0 — Instruction banner -->
  <div class="cri-instruct">
    <div class="cri-instruct-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="16" x2="12" y2="12"/>
        <line x1="12" y1="8" x2="12.01" y2="8"/>
      </svg>
    </div>
    <div class="cri-instruct-text">
      <strong><?php echo get_string('instruct_intro', 'local_cri_progress'); ?></strong>
      <?php echo get_string('instruct_body', 'local_cri_progress'); ?>
    </div>
  </div>

  <!-- Section heading -->
  <div class="cri-section-head">
    <h2><?php echo get_string('section_heading', 'local_cri_progress'); ?></h2>
    <div class="cri-section-meta"><?php
      echo get_string('section_meta', 'local_cri_progress', (object)['cats' => count($cards), 'total' => $overall_total]);
    ?></div>
  </div>

  <!-- Category grid -->
  <div class="cri-grid">
    <?php foreach ($cards as $i => $c): ?>
      <?php
        $card_offset = $card_c - ($card_c * $c['pct'] / 100);
        $delay = 0.05 + ($i * 0.07);
      ?>
      <article class="cri-card cri-cat-<?php echo $c['key']; ?>" style="animation-delay: <?php echo $delay; ?>s;">
        <div class="cri-card-head">
          <div class="cri-card-icon"><?php echo $c['icon']; ?></div>
          <div class="cri-card-meta">
            <h3 class="cri-card-title"><?php echo s($c['title']); ?></h3>
            <?php if ($c['configured']): ?>
              <div class="cri-card-count"><?php
                echo get_string('card_count', 'local_cri_progress', (object)['done' => '<strong>' . $c['done'] . '</strong>', 'total' => $c['total']]);
              ?></div>
            <?php else: ?>
              <div class="cri-card-count cri-warn">Not configured</div>
            <?php endif; ?>
          </div>
          <div class="cri-card-ring">
            <svg viewBox="0 0 56 56">
              <circle class="cri-ring-track" cx="28" cy="28" r="24"/>
              <circle class="cri-ring-fill" cx="28" cy="28" r="24"
                      stroke-dasharray="<?php echo $card_c; ?>"
                      stroke-dashoffset="<?php echo $card_c; ?>"
                      data-target="<?php echo $card_offset; ?>"/>
            </svg>
            <div class="cri-card-ring-pct"><?php echo $c['pct']; ?>%</div>
          </div>
        </div>

        <div class="cri-card-bar">
          <div class="cri-card-bar-fill" style="width:0%" data-target="<?php echo $c['pct']; ?>%"></div>
        </div>

        <?php if (empty($c['courses'])): ?>
          <p class="cri-empty"><?php echo get_string('nocoursesyet', 'local_cri_progress'); ?></p>
        <?php else: ?>
          <ul class="cri-course-list">
            <?php foreach ($c['courses'] as $course): ?>
              <li class="cri-course-item cri-status-<?php echo $course['status']; ?>">
                <a href="<?php echo (new moodle_url('/course/view.php', ['id' => $course['id']]))->out(); ?>" class="cri-course-link">
                  <div class="cri-status-dot cri-dot-<?php echo $course['status']; ?>">
                    <?php if ($course['status'] === 'complete'): ?>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php endif; ?>
                  </div>
                  <span class="cri-course-name"><?php echo s($course['name']); ?></span>
                  <span class="cri-action">
                    <?php echo s($course['action']); ?>
                    <?php echo $arrow_svg; ?>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </div>

  <!-- Legend -->
  <div class="cri-legend">
    <div class="cri-legend-item">
      <div class="cri-status-dot cri-dot-complete">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <?php echo get_string('legend_complete', 'local_cri_progress'); ?>
    </div>
    <div class="cri-legend-item">
      <div class="cri-status-dot cri-dot-progress"></div>
      <?php echo get_string('legend_progress', 'local_cri_progress'); ?>
    </div>
    <div class="cri-legend-item">
      <div class="cri-status-dot cri-dot-ready"></div>
      <?php echo get_string('legend_ready', 'local_cri_progress'); ?>
    </div>
  </div>

</div>

<script>
(function() {
  function go() {
    var heroFill = document.querySelector('.cri-hero-ring-fill');
    if (heroFill && heroFill.dataset.target) {
      heroFill.setAttribute('stroke-dashoffset', heroFill.dataset.target);
    }
    var heroPctEl = document.querySelector('.cri-hero-ring-pct');
    if (heroPctEl) {
      var target = parseInt(heroPctEl.dataset.target, 10) || 0;
      var n = 0;
      var step = Math.max(1, Math.ceil(target / 30));
      var tick = setInterval(function() {
        n += step;
        if (n >= target) { n = target; clearInterval(tick); }
        heroPctEl.textContent = n + '%';
      }, 30);
    }
    document.querySelectorAll('.cri-card .cri-ring-fill').forEach(function(el) {
      if (el.dataset.target) el.setAttribute('stroke-dashoffset', el.dataset.target);
    });
    document.querySelectorAll('.cri-card-bar-fill').forEach(function(el) {
      if (el.dataset.target) el.style.width = el.dataset.target;
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() { setTimeout(go, 220); });
  } else {
    setTimeout(go, 220);
  }
})();
</script>

<?php
echo $OUTPUT->footer();
