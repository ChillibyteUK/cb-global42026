# cb-global42026

Read this in full before touching anything — several decisions here are
deliberate and not obvious from the code alone. If you're about to reach for
Sass, Bootstrap, jQuery, an icon font, or flexbox-for-everything, stop and
re-read the relevant section below first.

## What this is

A standalone WordPress theme skeleton — no parent theme, no framework
dependency. It exists to be checked out per client project, renamed, and
built on. It is **not** a live dependency: this repo is a one-time-checkout
base. Fixes made here are not expected to sync back into projects that have
already forked from it, and there is no submodule/subtree relationship to
maintain.

It replaces an earlier workflow built on Understrap (a Bootstrap 5 WordPress
starter theme used as a WP parent/child theme pair). That workflow is still
used for the rare WooCommerce project — this skeleton is deliberately for
everything else, which is the large majority of projects.

## The one rule that explains most of the file layout

**Bootstrap-style class names, zero Bootstrap.** `.container`, `.row`,
`.col-6`, `.btn`, `.navbar`, `.d-flex`, etc. all exist and are used exactly
like they'd look in a Bootstrap project — but every one of them is defined
in this theme's own CSS. There is no Bootstrap package, no Bootstrap Sass,
no Bootstrap JS, no Popper. The naming was kept on purpose for muscle-memory
continuity (for the theme author and any devs who've worked in Bootstrap
before) — **do not assume behavioural parity with actual Bootstrap.**
Anything not explicitly implemented here (most Bootstrap components) simply
doesn't exist, no matter how standard it looks.

## Architecture decisions and why

- **No Sass.** Native CSS nesting (browsers handle it natively at this
  project's browser baseline — see below) replaces the one thing Sass was
  doing that mattered. Don't add Sass back in "just for tokens" or "just for
  mixins" — there was a deliberate decision to not need a preprocessor at
  all here.
- **CSS Grid, not flexbox, for layout.** `.row`/`.col-*` are Grid-based (see
  "How the grid actually works" below) — this was chosen over flexbox
  because the person building this genuinely prefers Grid's mental model,
  and it sets up cleanly for `subgrid`. `.navbar` itself is flexbox — a
  single-row toolbar is a legitimate flex use case; Grid isn't a mandate for
  every layout, just the default for anything row/column/page-structure
  shaped.
- **Design tokens are CSS custom properties**, not Sass variables — see
  `src/css/tokens.css`. This is also what makes color/font-size options
  show up in the Gutenberg editor (`src/build/generate-theme-json.js` reads
  this file to produce `theme.json`).
- **Breakpoints are the one exception to "tokens live in tokens.css."**
  A CSS custom property can't be read inside an `@media` condition, so
  breakpoints live in `src/build/tokens.config.js` instead. `src/css/nav.css`
  hardcodes the `lg` breakpoint (992px) directly in a plain `@media` query
  for the same reason, in a comment noting it must stay in sync with that
  file. If you change a breakpoint, grep for the old pixel value across
  `src/css/*.css` — there is no single source of truth enforced by tooling,
  only by convention.
- **No Bootstrap JS, no jQuery.** `src/js/nav-toggle.js` is a ~20-line
  vanilla replacement for Bootstrap's Collapse component (mobile nav toggle).
  `src/js/dialog.js` wires up the native `<dialog>` element (`showModal()`/
  `close()`) as the modal solution — not a JS component library.
- **No icon font.** Icons are inline SVG (see `header.php`'s nav toggle
  button for the pattern). Don't add Font Awesome or similar back in.
- **Buttons and cards have zero framework opinion.** `.btn` in
  `src/css/forms.css` is a bare minimal base — the theme author designs
  buttons and cards per-project rather than using a framework's look, so
  don't build out an opinionated button/card system here without being
  asked.
- **Anything with its own background must set its own text colour.** Blocks
  registered with `'color' => array( 'text' => ... )` put a `has-*-color`
  class on their outer `<section>` (see `cb_bg_fg_classes()` in
  `inc/utilities.php`), and that colour *inherits down the whole block*.
  That's deliberate — it's what lets a "plain"-style card, with no
  background of its own, pick up the block's foreground colour. But it means
  any element that paints a light background of its own (white/grey cards,
  striped table rows, panels) **must** declare a `color` alongside it, or a
  navy-background + white-text block renders it white-on-white. This has
  leaked four separate times; don't rely on `color: inherit` on a card that
  has a background. Cards without a background are the *only* thing that
  should inherit.
- **An `aspect-ratio` image wrapper inside a flex container needs
  `overflow: hidden`.** The standard card pattern is a flex-column card
  wrapping `<div class="…__image"><img></div>`, with `aspect-ratio` on the
  wrapper and `width/height: 100%; object-fit: cover` on the `<img>`. As a
  *flex item*, that wrapper gets sized from the `<img>`'s own intrinsic
  ratio and silently ignores its `aspect-ratio` — images then render at
  assorted heights and bleed past the card. `overflow: hidden` on the
  wrapper is what makes `aspect-ratio` win; it's load-bearing, not just
  cosmetic cropping, so don't "tidy it away". The card's own
  `overflow: hidden` does **not** substitute — that only clips, it doesn't
  change how the wrapper is sized. Has bitten `cb-webinars`,
  `cb-charity-cards`, `cb-post-index`, `cb-related-posts` and
  `cb-all-customers`. (A wrapper with a pinned flex basis, e.g.
  `flex: 0 0 3rem` on `.cb-usp-cards__icon`, is exempt — its main size is
  already fixed, so there's no intrinsic-ratio fight.) `cb-text-image`'s
  16:9 variant needs `width: 100%` on top of this for a related reason —
  see the comment there.
- **Tables are real but opt-in.** `src/css/tables.css` exists and is
  genuinely styled, but is not imported by default in `src/css/theme.css` —
  uncomment the `@import` if a project needs one.
- **Deliberately absent, don't add back without being asked:** sidebars/
  widget areas, comments, tags, author archives, `archive.php`, `search.php`.
  All confirmed rare-to-never in real usage across ~40 projects/year. If a
  specific project needs one, add it there, not here.
- **Site-Wide Settings (ACF options page) is core, not per-project.** `inc/options.php` + `acf-json/group_lc_site_wide_settings.json` — "crucial to every theme" per the person building this. GA/GTM only fire for logged-out visitors (`inc/head-tags.php`) so the team's own traffic doesn't skew analytics — a real, previously-known gap in the old `lc-iology2025` theme (fires for everyone there), fixed here from the start rather than retrofitted. GTM's noscript fallback is on `wp_body_open`, not buried in the footer — that's where Google's own docs say it belongs.

## Browser support baseline

Modern evergreen only — see `.browserslistrc` (last 2 versions of Chrome/
Firefox/Safari/Edge, no IE11). This is why CSS Grid, `subgrid` (used as
progressive enhancement via `@supports`, not depended on), `clamp()`,
native `<dialog>`, `:has()`, and CSS nesting are all used without fallback
layers. Don't add polyfills or fallback CSS for older browsers unless
explicitly asked — it would be working against a deliberate decision.

## How the grid actually works

`.row { display: grid; grid-template-columns: repeat(var(--grid-columns), 1fr); }`
and `.col-N { grid-column: span N; }` — `--grid-columns` defaults to 12
(`src/css/tokens.css`). **The span number is only meaningful relative to
whatever `grid-template-columns` its own `.row` ancestor has.** You cannot
mix "some children spanning against a 12-col row" with "other children
spanning against a 5-col row" inside the *same* `.row` — a `.col-*` class is
not portable across different column-count contexts.

If a project needs a genuinely different column count (the theme author's
example: five equal columns), don't repurpose `.col-*` classes for it.
`.grid` (`src/css/layout.css`) is the built solution: `display: grid;
grid-template-columns: repeat(auto-fit, minmax(var(--grid-min), 1fr));`
— no column count is declared at all, the browser fits as many as the
minimum item width (`--grid-min`, default `16rem` in `tokens.css`)
allows, and reflows automatically as the viewport changes. Tune it per
instance with an inline `style="--grid-min: 10rem"` rather than adding
a new class per layout.

This is deliberately *not* a replacement for `.row`/`.col-*` — use `.row`
when you need deliberate, exact spans (page structure); use `.grid`
when items just need to be "roughly N up, however many fit" (card grids,
feature lists). A row-modifier approach (overriding `--grid-columns` for one
specific `.row` with its own `.col-*-of-5`-style classes) was considered and
rejected in favour of `.grid` for this use case — more bookkeeping for
less benefit when the real need is "N similar items," not exact spans.

Nested `.row`s use `subgrid` for their columns where the browser supports it
(`@supports (grid-template-columns: subgrid)` in `src/css/layout.css`),
falling back to their own independent 12-column grid otherwise.

## File layout

```
style.css              Theme header — no `Template:` line, this is standalone
functions.php           Requires inc/*.php, nothing else
inc/                    Loaded in order by functions.php — nothing autoloads
  setup.php             add_theme_support, register_nav_menus
  enqueue.php           Enqueues css/theme.min.css + js/theme.min.js, filemtime-versioned
  class-cb-global-4-nav-walker.php
                        Lightweight Walker_Nav_Menu — nav-link/dropdown-menu classes, no JS
  blocks.php            ACF block registration — has the marker comment add_block.sh writes to.
                        Also registers render_callbacks that wrap plain core blocks
                        (paragraph/heading/list/separator/buttons) in .container
  editor.php            add_editor_style() — loads theme.min.css + editor.min.css into the block editor
  options.php           Registers the Site-Wide Settings ACF options page (theme-general-settings slug), hooked to acf/init
  head-tags.php          Font preload (fonts/*.woff2 glob) + GA/GTM (logged-out only) + Google/Bing verification, reading from the options page
  posttypes.php         CPT registration (case_study /customers, webinar, landing_page /lp) +
                        cb_global42026_use_page_template_for_cpts(), which serves page.php for
                        CPT singulars so block-built layouts don't inherit single.php's blog markup
  taxonomies.php        Custom taxonomy registration
  landing-pages.php     Everything landing_page-specific bar its registration — noindex (Yoast
                        + core robots), sitemap exclusion, and the /lp/ -> / redirect
  post-index.php        Server side of the CB Post Index block (filtering/paging)
  icon-upload.php       Handles the Site-Wide Settings icon_upload slot — sanitises an SVG into
                        img/icons/ and clears the field
  cf7.php               Contact Form 7 referrer + campaign (UTM/click ID) capture. Injects hidden
                        fields into every form and exposes them as special mail tags, including
                        the bundled [_cb_tracking] table. Pairs with src/js/journey.js
  block-usage.php        [block_usage_table] shortcode — QA utility, lists every block file against the published content using it
  utilities.php          Reusable, project-agnostic functions (parse_phone, pluralise, estimate_reading_time_in_minutes, cb_bg_fg_classes, cb_block_classes) — safe to lift verbatim into any project on this skeleton
  helpers.php           Project-specific helpers — the inline logo SVG, case study cards,
                        cb_render_breadcrumbs()
  run-once-dedup-media.php
                        TEMPORARY — one-off media dedup after a WP All Import run. Delete this
                        and its require in functions.php once it's been run.
header.php / footer.php / index.php / page.php / single.php / 404.php
                        Deliberately minimal — most real page layouts are built from ACF blocks, not these
blocks/                 ACF block PHP render templates (add_block.sh scaffolds here)
  {slug}.php
acf-json/               ACF field group JSON. group_lc_site_wide_settings.json (email, phone,
                        ga_property, gtm_property, google_site_verification,
                        bing_site_verification) is the starter Site-Wide Settings group — add
                        more fields per-project via the field editor, and make sure they
                        actually sync to this folder (not just the DB) or add_block.sh also
                        scaffolds groups here for blocks
fonts/                  Drop .woff2 files here — preloaded automatically, no registration step
src/
  css/                  Theme-wide CSS. theme.css is the @import entry point.
    tokens.css          Design tokens as CSS custom properties (colors, spacing, type)
    base.css            Reboot-equivalent element reset
    layout.css           .container / .row / subgrid
    nav.css             .navbar, mobile toggle, dropdown submenus
    forms.css           Minimal form base + .btn
    tables.css          Opt-in, not imported by default
    typography.css      @font-face, h2/paragraph measure caps, prose link styling
    animate.css         Pre-hides everything scroll-animate.js reveals, so nothing flashes
                        before GSAP runs. Selectors must stay in sync with that file
    single.css          Single post template (not a block, so not in src/blocks/)
    footer.css          Footer
    accordion.css       .accordion component
    link-arrow.css      .link-arrow / .card-link — the "Learn more →" pattern shared by
                        several card blocks; the arrow only nudges when an ancestor has .card-link
    editor.css          Block-editor-only styles, loaded via inc/editor.php
    utilities.css        GENERATED — do not hand-edit, see generate-utilities.js
    blocks.css           GENERATED — concatenation of src/blocks/*.css
  blocks/               Block-specific CSS, separate from theme-wide src/css/.
                        {block-slug}.css — add_block.sh does NOT create this file;
                        drop one in here and it's picked up automatically on next
                        build (glob, alphabetical order, no registration step).
  js/
    theme.js            Entry point — imports every module below and calls each on DOMContentLoaded
    nav-toggle.js        Mobile nav collapse — vanilla JS replacement for Bootstrap Collapse
    nav-dropdown.js      Submenu open/close
    nav-scroll.js        Adds .scrolled to the header past a scroll threshold
    dialog.js            Native <dialog> wiring — vanilla JS replacement for Bootstrap Modal
    smooth-scroll.js     Lenis smooth scrolling
    scroll-animate.js    All GSAP ScrollTrigger entrance animations, one function per block.
                         staggerFadeUpGrid(gridSelector, cardSelector) is the shared
                         "grid of cards" entrance — reuse it rather than adding a near-copy.
                         Selectors here MUST stay in sync with src/css/animate.css, which
                         pre-hides the same elements (see the comment in that file)
    crossfade-slider.js  initCrossfadeSlider(track, slide, interval = 6000) — autoplay-only
                         crossfade, no nav/pagination. Shared by CB Quote Slider and
                         CB Text Stat Slider; per-slider timing is set at the call site in theme.js
    counter.js           Counts any [data-counter] element up from zero when scrolled into view.
                         Matches the decimal places of the target value. Generic — a block only
                         needs to emit the attribute, no JS change
    tabbed-content.js    CB Tabbed Content panels
    post-index.js        CB Post Index filtering/search
    webinars.js          Lazily builds/tears down the YouTube iframe on dialog open/close
    journey.js           Records the page trail + campaign params (UTM, gclid et al) in
                         sessionStorage and fills the hidden CF7 fields from inc/cf7.php.
                         Field names are a deliberate coupling with that file
  build/
    tokens.config.js     Breakpoints + utility/grid definitions — source of truth for generate-utilities.js
    generate-utilities.js  Generates src/css/utilities.css and src/css/blocks.css
    generate-theme-json.js Generates theme.json from src/css/tokens.css
    postcss.config.js    postcss-import + postcss-nesting + autoprefixer
    rollup.config.js / babel.config.js / terser.config.json / banner.js
                        JS bundling — no nodeResolve/commonjs, there are no npm JS deps to bundle
    browser-sync.config.js
css/ , js/              Compiled output — committed to git (not gitignored), same convention as
                        the Understrap-based themes this replaced
add_block.sh            Scaffolds a block: PHP template + ACF JSON + registers in inc/blocks.php.
                        Does not create a CSS file — see src/blocks/ above.
rm_block.sh             Removes a block: PHP template, src/blocks/{slug}.css if present, the
                        inc/blocks.php registration, and the acf-json group
setup.sh                One-time bootstrap for a NEW project checked out from this skeleton —
                        prompts for a theme name + slug, renames every cb-global42026 /
                        CB Global 4 / cb_global42026_ / CB_Global_4_ reference, resets git to
                        a fresh single commit. Refuses to run twice (idempotency guard) or on a
                        dirty tree. Does NOT create a GitHub repo — that's a deliberate manual
                        step (gh repo create), not automated. See README.md.
theme.json              GENERATED by generate-theme-json.js — do not hand-edit
```

## Build commands

```
npm install
npm run watch      # rebuild CSS/JS on save
npm run watch-bs   # same + browser-sync live reload, proxies localhost/
npm run dist       # one-off full build
npm run generate-theme-json
./add_block.sh
```

`npm run css` = `generate-utilities.js` (writes `utilities.css` + `blocks.css`)
→ PostCSS (`postcss-import`, `postcss-nesting`, `autoprefixer`) → minify.
`npm run js` = rollup (bundles `src/js/theme.js`) → terser.

## Working conventions carried over from the previous (Understrap) themes

- Compiled `css/`/`js/` output is committed to git, not gitignored.
- `add_block.sh`'s marker-comment insertion pattern (`// INSERT NEW BLOCKS
  HERE.` in `inc/blocks.php`) is load-bearing — don't remove or reformat
  that comment.
- ACF is the assumed block-building method (`acf_register_block_type`), not
  native block.json/render.php blocks.

## PHP style in block templates

The person building this is particular about this, so follow it exactly —
don't drift toward "conventional-looking" PHP-in-HTML at the expense of it:

- **Braces only, no colon control-structure syntax.** `if ( $x ) { ... }`,
  never `if ( $x ): ... endif;`.
- **No short ternaries.** Write `$x ? $x : $fallback` in full, even though
  it evaluates `$x` twice — never `$x ?: $fallback`.
- **Don't shadow WP core globals with local variable names** — `$link`,
  `$post`, etc. ACF block `render_template` files are `include`d in global
  scope, so `$link = get_field(...)` at a template's top level silently
  overwrites WP's legacy Links Manager global rather than just shadowing
  it locally. Prefer specific/prefixed names (`$cta_link`, `$slink`) for
  anything that maps to a known WP global.
- **No redundant `?><?php` between sibling `if` blocks.** Given a sequence
  of conditions that each conditionally emit a chunk of markup, wrap the
  whole sequence in ONE continuous `<?php ... ?>`, dropping into HTML only
  where markup is actually emitted — don't close and immediately reopen
  PHP tags with nothing but whitespace between them. So:
  ```php
  <?php
  if ( $heading ) {
      ?>
  <h2>...</h2>
      <?php
  }
  if ( $subtitle ) {
      ?>
  <p>...</p>
      <?php
  }
  ?>
  ```
  not:
  ```php
  <?php if ( $heading ) { ?>
  <h2>...</h2>
  <?php } ?>
  <?php if ( $subtitle ) { ?>
  <p>...</p>
  <?php } ?>
  ```
  Reason given: cleaner diffs, less visual noise from tag-switching.
