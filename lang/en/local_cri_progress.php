<?php
// Language strings for local_cri_progress.

defined('MOODLE_INTERNAL') || die();

$string['pluginname']        = 'Community Reentry Initiative — Progress';
$string['cri_progress:view'] = 'View the CRI Progress dashboard';

// Settings page
$string['settings_heading']        = 'Category mapping';
$string['settings_heading_desc']   = 'Enter the Moodle category ID for each CRI sub-category. The plugin reads courses from these categories. (Find IDs at: Site admin → Courses → Manage courses and categories — the URL shows categoryid=NN.)';
$string['settings_cat_mindset']        = 'Mindset & Emotions — category ID';
$string['settings_cat_relationships']  = 'Relationships & Communication — category ID';
$string['settings_cat_learning']       = 'Learning & Tech Skills — category ID';
$string['settings_cat_work']           = 'Work & Career — category ID';
$string['settings_cat_money']          = 'Money Matters — category ID';
$string['settings_cat_health']         = 'Physical Health — category ID';
$string['settings_cat_reentry']        = 'Reentry & Daily Life — category ID';

// Page
$string['page_title']    = 'Your Progress — Community Reentry Initiative';
$string['nocoursesyet']  = '(no courses in this category yet)';
$string['notconfigured'] = 'This category is not yet configured. Set its ID in the plugin settings.';

// Hero
$string['hero_eyebrow'] = 'Community Reentry Initiative';
$string['hero_title']   = 'You\'re building your next chapter, one course at a time.';
$string['hero_sub']     = 'Your progress across seven areas of growth — from money and mindset to work, health, and life after release. Pick up wherever you left off.';
$string['stat_completed']  = 'Completed';
$string['stat_inprogress'] = 'In Progress';
$string['stat_total']      = 'Total Courses';
$string['ring_overall']    = 'Overall';

// Instruction banner (v1.1.0)
$string['instruct_intro'] = 'Click any course below to open it.';
$string['instruct_body']  = 'The label on the right tells you what to do next — <strong>Start</strong> a new course, <strong>Continue</strong> where you left off, or <strong>Review</strong> a course you\'ve finished.';

// Section heading
$string['section_heading']      = 'Your Areas of Growth';
$string['section_meta']         = '{$a->cats} categories · {$a->total} courses';

// Action labels (v1.1.0) — replace status labels on each course row
$string['action_start']    = 'Start';
$string['action_continue'] = 'Continue';
$string['action_review']   = 'Review';

// Legend (status dots, unchanged)
$string['legend_complete']  = 'Complete';
$string['legend_progress']  = 'In Progress';
$string['legend_ready']     = 'Ready when you are';

// Card labels
$string['card_count'] = '{$a->done} of {$a->total} complete';
