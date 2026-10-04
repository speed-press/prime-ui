# PrimeUI Widgets for Elementor

Free Elementor widget pack. Not affiliated with Elementor or WordPress.org.

| Field | Value |
|---|---|
| Display name | PrimeUI Widgets for Elementor |
| Slug | `primeui` |
| Folder | `primeui` |
| Main file | `primeui.php` |
| Text domain | `primeui` |
| Version | 1.2.0 |
| License | GPL-2.0-or-later |
| Requires | WordPress 6.0, PHP 7.4, Elementor 3.20+ |
| Category in Elementor | PrimeUI |

## Install

1. Deactivate any older SpeedPress Add-Ons copy so both plugins do not register widgets.
2. Upload the `primeui` folder to `wp-content/plugins/primeui/`.
3. Activate **PrimeUI Widgets for Elementor**.
4. In wp-admin open **PrimeUI** and leave the widgets you want enabled.
5. Edit a page with Elementor and open the **PrimeUI** category.

If the site whitescreens, delete `wp-content/plugins/primeui/` by FTP and check `wp-content/debug.log`.

## What it is

About 200 catalog widgets plus a few handmade widgets. Catalog widgets share one renderer. They are original blocks, not copies of Essential Addons, ElementsKit, HappyAddons, Premium Addons, or Elementor Pro.

There is no license screen. Every widget is free.

## Admin

**PrimeUI** in wp-admin is the widget switchboard: search, group filters, enable, disable, save. Disabled widgets stay out of the Elementor panel.

## Widgets

Handmade widgets, each in its own PHP file:

- CTA Card (`spae-cta-card`) — 10 layouts of its own, button icon left or right
- Feature Grid
- Stats Bar
- FAQ
- Price Table
- Testimonial

Catalog widgets (200), registered from `includes/class-catalog.php`. Examples:

- Dual Heading, Gradient Heading, Typed Heading, Highlight Heading
- Info Box, Icon Box, Image Box, Hover Card, Flip Card, Overlay Card
- Feature List, Check List, Numbered List, Icon List
- Steps Process, Timeline, Horizontal Timeline
- Content Tabs, Vertical Tabs, Toggle List
- Alert Box, Notice Bar, Badge Pill, Ribbon
- Countdown, progress, team, posts, logo, video, map, button, and table types

Elementor widget names are prefixed `spae-`.

## Layouts

This is the honest state of layouts.

- **CTA Card** has 10 layouts written for that widget: split, centered dark, gradient banner, image overlay, outline, stacked eyebrow, inline bar, side accent, two buttons, image left.
- Other catalog widgets have a **Built-in layout** dropdown. The label includes the widget name. The visual set is shared by widget family (cards, proof, media, list, data, nav, posts, heading, info, content). Two icon boxes do not have 10 separately coded templates.
- FAQ, pricing, stats, feature grid, and testimonial do not have a 10-layout picker yet.

## Style controls on catalog widgets

Design skin, align, radius, columns, gap, title color, text color, background, accent, button color, border, shadow, padding, typography.

## Files

```
primeui/
  primeui.php
  readme.txt
  assets/css/frontend.css
  assets/css/layouts.css
  assets/css/admin.css
  assets/js/frontend.js
  assets/js/admin.js
  includes/class-plugin.php
  includes/class-catalog.php
  includes/class-catalog-widget.php
  includes/class-layouts.php
  includes/class-renderer.php
  includes/class-widgets-manager.php
  includes/generated-widget-classes.php
  includes/widgets/
```

## Trademark

Display name may say “for Elementor”. The slug is `primeui` and does not contain `wordpress` or start with `elementor-`.
