# Portfolio Tiles — Redesign Spec

**Date:** 2026-04-11
**Status:** Approved for planning
**Author:** Brainstorming session with Jay-ar

---

## 1. Overview

Restructure two of the bento tiles on the public portfolio (`/`) and add one new tile, plus a small carousel size tweak. Goal is to remove a redundant tile, add a trust-signal tile that surfaces already-seeded data, and rebalance the grid so the most important content dominates visually.

All changes are to `resources/views/livewire/portfolio.blade.php` and `resources/css/app.css`. No database schema changes. No new Livewire components. The existing `tbl_certifications` table is already seeded with 3 records.

---

## 2. Scope

### In scope

| # | Change | Why |
|---|---|---|
| A | Merge the green "Let's Connect" tile and the yellow "Connect With Me" tile into a single tall green tile on the right side of the grid | Both tiles carry "connect" messaging; the separation creates redundancy and visitor decision friction. "Connect With Me" is also the weakest tile visually. |
| B | Add a **Certifications** tile in the freed slot (old yellow position) | `tbl_certifications` has 3 seeded records that currently don't appear anywhere on the site. Credentials are a trust signal, especially for recruiter/client visitors. |
| C | Re-proportion grid rows 3–4 so Projects gets ~65% of the combined height and Certifications gets ~35% | Projects has richer, scrollable content and deserves more vertical presence. Certifications stays as a tidy supporting strip. |
| D | Shrink Skills Carousel cards from ~160–180px to ~120–130px | Cards currently feel oversized relative to the tile; smaller cards let more peek on each side and read as "chip-sized skill badges." |

### Out of scope

- Any of the other 27 items from the full tile-audit checklist (title-style unification, profile photo in hero, icon-set unification site-wide, color desaturation pass, Git/GitHub logo fix, About sub-tile label wrapping, etc.) — all parked for a future session.
- Mobile-specific layout redesign — mobile continues to use the existing single-column stacking.
- Any back-office / admin panel work — separate long-term project already spec'd elsewhere.
- Adding a credential URL field to `tbl_certifications` — not needed for this spec's flip-card design.
- Editing seeded certification descriptions.

---

## 3. Grid layout

### Before

```
┌─────────────────────────────┬──────────┐
│  HERO                       │  ABOUT   │
├─────────────────────────────┤          │
│  SKILLS CAROUSEL            │          │
├─────────────────────────────┼──────────┤
│  PROJECTS                   │  LET'S   │
│                             │ CONNECT  │
├─────────────────────────────┤ (green)  │
│  CONNECT WITH ME (yellow)   │          │
└─────────────────────────────┴──────────┘
```

### After

```
┌─────────────────────────────┬──────────┐
│  HERO                       │  ABOUT   │
├─────────────────────────────┤          │
│  SKILLS CAROUSEL            │          │
├─────────────────────────────┼──────────┤
│                             │          │
│  PROJECTS (taller, ~65%)    │  LET'S   │
│                             │ CONNECT  │
│                             │ (green,  │
├─────────────────────────────┤  merged, │
│  CERTIFICATIONS (~35%,      │  tall)   │
│  purple)                    │          │
└─────────────────────────────┴──────────┘
```

### Grid math

| Tile | Columns | Rows | Height |
|---|---|---|---|
| Hero | 1–2 | 1 | unchanged |
| About | 3 | 1–2 | unchanged |
| Skills Carousel | 1–2 | 2 | ~20% shorter (see §6) |
| Projects | 1–2 | 3 | `min-h-[520px]` |
| Certifications | 1–2 | 4 | `min-h-[280px]` |
| Let's Connect (merged) | 3 | 3–4 | auto-fills `520 + 280 + gap ≈ 816px` |

Rows 3 and 4 become non-equal on `lg:` desktop. On `md:` tablet the grid already collapses to 2 columns (merged Let's Connect drops below Projects). On mobile (single column) every tile stacks — unchanged.

---

## 4. Merged "Let's Connect" tile

### Placement & color

Column 3, rows 3–4 (same slot as current green tile). Green background retained — it reads as "action / reach out" and visitors already associate this corner of the page with contact.

### Content order (top → bottom)

1. **Status badge** — pill-shaped, green-dot + "Available for freelance projects". Must be the top element — it's the strongest conversion signal on the site.
2. **Title** — "LET'S CONNECT", matching the title scale of About / Projects. Short white underline separator beneath.
3. **Subtitle** — "Ready to bring your ideas to life?"
4. **Contact block** — dark semi-transparent inner card containing:
   - ✉ `jrevis029@gmail.com`
   - 📞 `+63 976 159 8467`
   - 📍 `Tondo, Manila · Remote` (location restored with city for trust)
5. **Primary CTA** — "✉ Send Message" button in amber (`#f59e0b`-ish) — chosen for contrast against the green tile; a same-color-family button would disappear.
6. **Divider** — thin horizontal rule with "Connect elsewhere" label.
7. **Social row** — brand icons rendered monochrome white. Renders from a configured list; up to 5 max. Today's seeded list contains 3 (GitHub, LinkedIn, Facebook). Layout adapts to whatever's populated (2, 3, 4, or 5).

### Icons

Use [Heroicons](https://heroicons.com/) line-style SVG, inlined into the Blade template (no extra HTTP request, themeable via `currentColor`). Both the contact-method icons (envelope, phone, location-pin) and the social-brand icons (GitHub, LinkedIn, Facebook) come from the same icon family where possible; for brand marks use simplified official SVG marks when Heroicons doesn't have one.

### Retired content

Delete:
- The entire yellow "Connect With Me" tile block (its 6 random emoji icons, "Currently Available For" label, and "Freelance Projects" green badge — the availability line folds into the status badge at the top of the merged tile).
- Any CSS scoped to the retired tile (unused styles for its `social` class).

---

## 5. Certifications tile

### Placement & color

Columns 1–2, row 4 (replaces the old yellow social strip exactly). Purple / indigo background — `#4c3b9c`-range, distinct from the adjacent red Projects and green Let's Connect. Purple reads as "academic / achievement" which matches credential content.

### Title

"CERTIFICATIONS" with white underline separator below — same treatment as About and Projects.

### Card layout (3 flip-cards in a horizontal row)

At `lg:` desktop, 3 cards laid horizontally with 16px gaps, each roughly 280×240px. At `md:` tablet: 2 cards per row, the third wraps to a second row. On mobile: stacked 1 column, full width.

### Card front (default state)

- **Background:** frosted — `rgba(255, 255, 255, 0.1)` fill, `1px solid rgba(255, 255, 255, 0.2)` border, rounded corners.
- **Logo:** 48–60px, top-left area. Sources:
  - `public/skills_logo/laravel_logo.png` (existing)
  - `public/cert_logos/san_beda.png` (user-sourced — see §8)
  - `public/cert_logos/dost_tip_nitro.png` (user-sourced — see §8)
- **Cert name:** bold white, 2-line max with tight leading, ellipsis truncation beyond.
- **Issuer:** smaller white at 80% opacity, one line.
- **Date badge:** pill-shaped, `rgba(255, 255, 255, 0.15)` fill, white text, format: `Mon YYYY` (e.g. "Jun 2022").
- **Flip-hint indicator (⟳):** small icon top-right corner, 60% opacity — signals interactivity to visitors who don't know the card flips.

### Card back (flipped state)

- Same footprint and frosted background as the front.
- Full `description` text from the seeded data, white, readable.
- Smaller issuer + date summary at the bottom for context (helps visitor remember which card they're reading).
- "← Back" affordance in the bottom-right corner.

### Flip mechanics

- **Trigger:** click on desktop + tap on mobile. Same event binding.
- **Animation:** horizontal Y-axis flip, 600ms, `cubic-bezier(0.4, 0, 0.2, 1)` (ease-out). Implemented via CSS `transform: rotateY(180deg)` with `backface-visibility: hidden` on both faces and `transform-style: preserve-3d` on the card container.
- **State management:** the Livewire portfolio component holds a single `$flippedCertId` property (nullable). Flipping a card sets this; flipping another card sets it to the new id (which implicitly unflips the previous). Flipping the same card again sets it to null. All state is server-side to survive page re-renders.
- **Keyboard:** cards are `<button>` elements so Tab focuses them natively. Enter/Space triggers the flip. Esc on a focused flipped card flips it back. Focus outline visible and high-contrast.
- **Reduced motion:** `@media (prefers-reduced-motion: reduce)` short-circuits the 600ms flip to an instant state swap — no rotation animation.

### Data source

Existing `tbl_certifications` table. No new columns, no migration. The Livewire portfolio component adds a new property:

```php
public ?int $flippedCertId = null;

public function flipCert(int $id): void
{
    $this->flippedCertId = $this->flippedCertId === $id ? null : $id;
}
```

Blade loops over `Certification::active()->orderBy('sort_order')->get()` (or similar pattern matching the existing Skills/Projects rendering) and renders each as a flip-card.

---

## 6. Skills Carousel card resize

### Current

`.carousel-item` CSS sets `width: 100px; height: 100px;` but the rendered cards visually appear larger (likely due to padding/icon scaling). The tile wraps 5 visible cards on desktop with a partial peek on the right.

### New

Shrink the rendered card footprint to ~**120×120px** (keeping square aspect) with tighter padding:

```css
.carousel-item {
    width: 120px;
    height: 120px;
    padding: 0.5rem;  /* was 0.75rem */
}
```

### Effect

- Overall carousel tile height drops ~15–20% (fewer vertical pixels per card row).
- More cards peek on each side → stronger "scroll for more" affordance.
- Cards read as "chip-sized skill badges" rather than oversized tiles.

### What stays the same

- Auto-scroll animation (`scroll 20s linear infinite`).
- Hover-to-pause behavior.
- Left/right nav arrow buttons (can be addressed in a later session; out of scope here).
- Pagination dots at the bottom.
- Gradient mask on left/right edges.
- Logo aspect ratio per card.

---

## 7. Styling approach

### CSS organization

All new styles land in `resources/css/app.css`, grouped by tile. The existing file uses a mix of `@apply` Tailwind + raw CSS — we follow the same style.

### Color tokens

New CSS custom properties (top of file) so later work can tune them centrally:

```css
:root {
    --cert-tile-bg: #4c3b9c;
    --cert-card-bg: rgba(255, 255, 255, 0.1);
    --cert-card-border: rgba(255, 255, 255, 0.2);
    --cta-amber: #f59e0b;
    --cta-amber-hover: #d97706;
}
```

### Light-mode considerations

- Purple Certifications tile: same saturated purple in both modes (works against both dark and light page backgrounds).
- Frosted cards: bump opacity in light mode — `rgba(255, 255, 255, 0.6)` instead of `0.1` — so the cards remain visible against a light page.
- Amber Send Message button: same in both modes.
- Flip-card shadows: identical in both modes.

### Accessibility-first patterns

- Every interactive element is a real `<button>` or `<a>`, not a div.
- `aria-label` on icon-only buttons (brand social icons, flip hint, Send Message).
- `aria-expanded` on each cert card button reflecting whether its back face is showing.
- `aria-live="polite"` region announcing "Card flipped to show description" / "Card flipped back" for screen readers. (Small but matters for a portfolio that signals craft.)

---

## 8. Assets to prepare (user task)

Before implementation, the user (Jay-ar) must source and place:

| File | Source options |
|---|---|
| `public/cert_logos/san_beda.png` | San Beda University website → footer / about page. Wikipedia (commons). 64–128px PNG with transparent background. |
| `public/cert_logos/dost_tip_nitro.png` | DOST Philippines official site. TIP NITRO Academy of Entrepreneurs site. Since this is a joint cert, either logo works — pick the more recognizable one. 64–128px PNG with transparent background. |

These are not blocking for CSS/markup work — placeholder styling can render first with a generic fallback icon until the real logos are dropped in.

---

## 9. Risks & open questions

1. **Purple shade choice.** `#4c3b9c` is a starting point. Final hex can be tuned during implementation once the tile is visible against the real neighboring tiles (red Projects, green Let's Connect). Acceptable adjustment range: indigo-500 through violet-700 in Tailwind terms.

2. **Flip-card performance on low-end devices.** CSS 3D transforms are GPU-accelerated on all modern browsers and phones. Android devices from ~2019+ handle this fine. If any jank shows up in testing, the fallback is `@media (prefers-reduced-motion: reduce)` which short-circuits the animation.

3. **Social icon count.** Seeded data has 3 (GitHub, LinkedIn, Facebook). The tile will look slightly empty with only 3 icons laid across a row that could fit 5. Two mitigations considered: (a) center the row and space icons generously — chosen; (b) add placeholder "+" button to nudge adding more — rejected as gimmicky. Can revisit after 3-icon version ships.

4. **Amber CTA on green tile.** Amber is picked for high contrast, but if it clashes visually with the hero's orange buttons (which also use amber-adjacent tones), the Send Message button can switch to white with green text as the alternative. Decide during implementation.

5. **Scrollbar styling inside Projects.** Projects already has a scrollable inner card list. With the new taller proportion (520px vs previous ~400px), more project rows are visible at once, which means the scrollbar appears less often. No change needed, just noting the side-effect.

6. **Carousel nav arrows color.** Checklist item 6.6 flagged that the green circle arrows don't match the carousel palette. This is explicitly out of scope for this spec — deferred to a future session. Nav arrow behavior/position unchanged here.

---

## 10. Verification checklist

After implementation, Phase A is complete when:

- [ ] Public portfolio at `/` renders without errors on desktop, tablet, and mobile.
- [ ] Old yellow "Connect With Me" tile is gone; no orphaned CSS or dead markup left behind.
- [ ] Merged green "Let's Connect" tile shows: status pill → title → subtitle → contact block → amber Send Message → socials row.
- [ ] Let's Connect spans full height of rows 3–4 on `lg:` desktop.
- [ ] Purple Certifications tile sits in left 2 columns of row 4, displaying 3 flip-cards.
- [ ] Cert card flip works on click and tap, animates smoothly, is keyboard-accessible, and respects `prefers-reduced-motion`.
- [ ] Only one cert card is flipped at a time — flipping a second auto-unflips the first.
- [ ] Cert card backs show the full description text from the seeded database.
- [ ] Cert logos render for all 3 cards (Laravel existing, San Beda + DOST sourced).
- [ ] Projects tile has visibly more height than Certifications (roughly 65/35 ratio).
- [ ] Skills carousel cards are noticeably smaller (~120px vs previous ~160–180px).
- [ ] Light mode and dark mode both work — frosted cards stay visible in both.
- [ ] Lighthouse accessibility audit: 95+ (no regressions from current score).

---

## 11. Critical files

**Modified:**

| File | Change |
|---|---|
| `resources/views/livewire/portfolio.blade.php` | Volt single-file component. At the top: load `Certification::active()->ordered()->get()` alongside existing skills/projects loading, add `public ?int $flippedCertId = null` property and `flipCert(int $id)` method. In markup: delete yellow tile; restructure green tile to merged layout; add Certifications tile with flip-card loop; adjust row heights. |
| `resources/css/app.css` | Add CSS vars for new tokens; add flip-card styles; add purple tile + frosted card styles; adjust `.carousel-item` size; update grid row heights. |

**Created:**

| File | Purpose |
|---|---|
| `public/cert_logos/san_beda.png` | San Beda Uni logo (user-sourced) |
| `public/cert_logos/dost_tip_nitro.png` | DOST / TIP NITRO logo (user-sourced) |

**Unchanged:**

| File | Reason |
|---|---|
| `database/migrations/*` | No schema changes |
| `database/seeders/CertificationSeeder.php` | Data already adequate |
| `app/Models/Certification.php` | Exists; read-only usage |
| Anything in the `.claude/worktrees/happy-shannon/` worktree | That's the back-office branch; orthogonal |

---

## 12. Suggested implementation order

1. Add CSS custom properties and the new color tokens.
2. Update grid row heights (Projects `min-h-[520px]`, Certifications `min-h-[280px]`).
3. Delete the old yellow "Connect With Me" tile markup.
4. Restructure the green "Let's Connect" tile into its merged layout (without socials yet — visually verify the stack).
5. Add the brand-icon social row at the bottom of Let's Connect.
6. Swap contact-block emojis for Heroicon SVGs.
7. Add the Livewire `$flippedCertId` property + method to the portfolio component.
8. Add the Certifications tile markup (front face only — verify layout).
9. Add the flip-card back face + 3D transform CSS.
10. Add the ⟳ hint indicator and the aria-expanded / aria-live bits.
11. Shrink the carousel card size.
12. Final pass — light mode check, dark mode check, mobile stacking check, keyboard tab order, reduced-motion check.
