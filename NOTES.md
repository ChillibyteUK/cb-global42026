# Project notes — cb-global42026

Things that are **not** in this repo, plus per-environment setup steps. The
theme code itself is fully committed; everything below lives in the WordPress
database or has to be done per install, so it won't travel with a `git clone`.

---

## Moving to another machine

A checkout of this repo gives you all the code. It does **not** give you:

- **Contact Form 7 forms and their Mail tabs** — form markup and every email
  template. Reference copies are in [CF7 forms](#cf7-forms) below.
- **Site-Wide Settings values** — phone, email, GA/GTM IDs, the policy PDFs.
  The *fields* are in `acf-json/group_lc_site_wide_settings.json`; the *values*
  are options rows in the DB.
- **Yoast settings** — including "Show Landing Pages in search results? No".
  The theme also enforces landing-page noindex in `inc/landing-pages.php`, so
  this is belt-and-braces rather than the only guard.
- **Content** — pages, the blocks placed on them, landing pages, posts.
- **Uploads** — `wp-content/uploads` (brochure PDFs, images, policy PDFs).

So: repo + DB dump + uploads.

## Per-environment setup

1. `npm install`, then `npm run dist`.
   Note `npm run dist` does **not** regenerate `theme.json` — run
   `npm run generate-theme-json` after changing `src/css/tokens.css`.
   `npm run watch` does cover it.
2. **Flush permalinks** (Settings → Permalinks, just hit Save). Without this
   `/lp/{slug}/` 404s, because the `landing_page` CPT's rewrite rules aren't
   registered until the rules are rebuilt.
3. **Sync ACF field groups** — Custom Fields → Field Groups → "Sync available".
   See the warning below.
4. Set the Yoast landing-page noindex option (see above).

## ACF: the JSON is the source of truth, but only if you sync

Field groups are edited two ways in this project: through the ACF UI (which
writes to the DB *and* the JSON), and by hand-editing `acf-json/*.json`
directly.

**A hand-edited JSON change must be synced before anyone saves that group in
wp-admin.** ACF regenerates the JSON from its DB copy on save, so an unsynced
hand-edit is silently deleted. This has already happened once: the CB Logo Grid
"Alignment" field was added to JSON, not synced, and was wiped the next time
that group was saved to add a formatting option to another field.

If a field exists in the JSON but doesn't appear in the editor, it hasn't been
synced.

## Temporary code to remove

`inc/run-once-dedup-media.php` — one-off media dedup after a WP All Import run.
Delete the file and its `require_once` in `functions.php` once it's been run on
the target site and the report confirms success.

---

## CF7 forms

CF7 content lives in the DB. These are reference copies so the email work is
recoverable. If you change a form, update it here too.

### Tracking mail tags

Provided by `inc/cf7.php`, available in **any** form's Mail tab — hidden fields
are injected into every form, so there's nothing to add per-form:

| Tag | Value |
| --- | --- |
| `[_cb_tracking]` | **Bundled summary.** A complete `<table>` covering form page, referring page, and any campaign parameters. Omits anything unset. Place it *outside* any existing table. Emits `Label: value` lines instead on a plain-text mail. |
| `[_cb_referrer_url]` / `[_cb_referrer_title]` | Page they were on before the form. Empty on a direct arrival. |
| `[_cb_utm_source]`, `[_cb_utm_medium]`, `[_cb_utm_campaign]`, `[_cb_utm_term]`, `[_cb_utm_content]`, `[_cb_utm_id]` | UTM parameters. Render `unset` when absent. |
| `[_cb_gclid]`, `[_cb_gbraid]`, `[_cb_wbraid]`, `[_cb_msclkid]`, `[_cb_fbclid]` | Ad click IDs (Google, Microsoft, Meta). Render `unset` when absent. |

CF7's own `[_post_title]` / `[_post_url]` give the page hosting the form — no
theme code needed, and worth including on landing-page forms where that *is*
the useful attribution.

Campaign parameters are captured on arrival and held in `sessionStorage` by
`src/js/journey.js`, because they only exist on the landing URL. They do **not**
survive the tab closing, so an ad click today and a submit tomorrow arrives as
`unset`. Switching to `localStorage` would fix that but makes it persistent
client-side marketing storage — a consent question, so it wasn't changed
unilaterally.

### Form markup conventions

Use the theme's own grid rather than a layout plugin's shortcodes:

```
<div class="row">
	<div class="col-12 col-md-6">
		<label> Contact name [text* contact-name] </label>
	</div>
	<div class="col-12 col-md-6">
		<label> Company name [text* company-name] </label>
	</div>
</div>

[submit class:btn class:btn-primary "Let's Talk"]
```

- `input`/`textarea`/`select` already get `width: 100%` from `src/css/forms.css`,
  so fields fill their column with no extra styling.
- `[submit class:btn class:btn-primary]` — CF7 accepts repeated `class:` options.
- `src/css/forms.css` carries two CF7-specific fixes: `.wpcf7-form .row` restates
  `grid-template-columns` (CF7's wrapper markup breaks the `subgrid` inheritance
  a nested `.row` normally relies on), and `.wpcf7-form p` zeroes the margin CF7's
  own autop pass adds.

### Mail template (HTML content type)

Tick **Use HTML content type**. `white-space: pre-wrap` on the message cell is
load-bearing — CF7 does not convert textarea newlines to `<br>` in HTML mode, so
without it a multi-line message collapses onto one line.

```html
<div style="font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; font-size: 15px; line-height: 1.45; color: #333333; max-width: 600px;">

  <p style="margin: 0 0 14px; font-size: 17px; font-weight: bold; color: #072a41;">New contact form submission</p>

  <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
    <tr>
      <td style="padding: 2px 14px 2px 0; font-weight: bold; color: #072a41; white-space: nowrap; vertical-align: top;">From</td>
      <td style="padding: 2px 0; vertical-align: top;">[your-name] &lt;<a href="mailto:[your-email]" style="color: #467cc6;">[your-email]</a>&gt;</td>
    </tr>
    <tr>
      <td style="padding: 2px 14px 2px 0; font-weight: bold; color: #072a41; white-space: nowrap; vertical-align: top;">Tel</td>
      <td style="padding: 2px 0; vertical-align: top;">[your-tel]</td>
    </tr>
    <tr>
      <td style="padding: 2px 14px 2px 0; font-weight: bold; color: #072a41; white-space: nowrap; vertical-align: top;">Company</td>
      <td style="padding: 2px 0; vertical-align: top;">[company-name]</td>
    </tr>
    <tr>
      <td style="padding: 2px 14px 2px 0; font-weight: bold; color: #072a41; white-space: nowrap; vertical-align: top;">Reason</td>
      <td style="padding: 2px 0; vertical-align: top;">[contact-reason]</td>
    </tr>
    <tr>
      <td style="padding: 2px 14px 2px 0; font-weight: bold; color: #072a41; white-space: nowrap; vertical-align: top;">Existing customer</td>
      <td style="padding: 2px 0; vertical-align: top;">[is-customer]</td>
    </tr>
    <tr>
      <td style="padding: 10px 14px 2px 0; font-weight: bold; color: #072a41; white-space: nowrap; vertical-align: top;">Message</td>
      <td style="padding: 10px 0 2px; vertical-align: top; white-space: pre-wrap;">[your-message]</td>
    </tr>
  </table>

  [_cb_tracking]

  <p style="margin: 20px 0 0; padding-top: 12px; border-top: 1px solid #e2e2e2; font-size: 12px; color: #808080;">
    Contact form submitted on <a href="[_site_url]" style="color: #808080;">[_site_title]</a>.
  </p>

</div>
```

Email-specific constraints worth remembering: inline styles only (Outlook
ignores `<style>` blocks), tables rather than flexbox, no CSS custom properties
— hence the hardcoded hex values, which are `navy-800 #072a41`,
`blue-500 #467cc6`, `grey-300 #e2e2e2`, `grey-500 #808080`. Avoid stacked
`<br><br>` for spacing; Outlook renders consecutive breaks inconsistently.
