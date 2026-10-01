# Community Reentry Initiative — Progress Dashboard

**Plugin name:** `local_cri_progress`
**Version:** 1.1.0
**Type:** Moodle local plugin
**Requires:** Moodle 4.0 or higher

## What it does

Provides learners with a dashboard at `/local/cri_progress/index.php` showing their progress across the seven sub-categories of the Community Reentry Initiative course catalog. Reads completion data from Moodle's standard completion API; does not write to any tables.

## What's new in v1.1.0

- **Instruction banner** added directly below the hero card, telling learners how to use the dashboard and what each action label means.
- **Action labels replace status labels** on each course row. Instead of static text reading "Complete / In Progress / Ready," each course now shows a clickable action pill:
  - **Start** — for courses not yet begun
  - **Continue** — for courses already in progress
  - **Review** — for completed courses (the material remains accessible as ongoing reference)
- Pills change color on hover to reinforce that each course row is clickable.
- No data layer changes from v1.0.0 — completion math, settings, and category mapping are identical.

## Install

1. Site administration → Plugins → Install plugins
2. Upload `local_cri_progress.zip`
3. Confirm install and run database upgrade

## Post-install configuration (required)

After install, navigate to:

**Site administration → Plugins → Local plugins → Community Reentry Initiative — Progress**

Enter the Moodle category ID for each of the seven CRI sub-categories on this environment:

- Mindset & Emotions
- Relationships & Communication
- Learning & Tech Skills
- Work & Career
- Money Matters
- Physical Health
- Reentry & Daily Life

**Defaults are intentionally blank** — category IDs differ between environments and must be set explicitly.

### Finding category IDs

Site administration → Courses → Manage courses and categories. Click into each sub-category. The URL will show `categoryid=NN` — that NN value is what goes in the settings page.

## Prerequisites for the dashboard to populate

- Completion tracking enabled site-wide (Advanced features → Enable completion tracking)
- Each course inside the seven sub-categories must have course completion enabled with at least one defined completion condition

## Notes

- No new database tables — the plugin reads existing `mdl_course`, `mdl_course_completions`, and `mdl_course_modules_completion` tables only
- No cron tasks added — relies on Moodle's standard `\core\task\completion_regular_task` for course completion aggregation
- Theme-agnostic — all CSS is scoped under `.cri-wrap` to prevent style bleed
- Multi-tenant safe — queries the current user's data regardless of tenant

## License

Copyright © 2026 MaxxContent LLC.

This plugin is licensed under the GNU General Public License v3.0 or later — the same license as Moodle itself. See [LICENSE](LICENSE) for the full text.

## Contact

For questions about plugin behavior or configuration, contact the MaxxLMS team. Full technical documentation available separately.
