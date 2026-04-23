# Portfolio Tiles Redesign — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Redesign the public portfolio's bento layout — merge two contact-related tiles into one, add a Certifications tile with flip-cards, rebalance grid proportions, and shrink the skills carousel cards.

**Architecture:** Pure markup + CSS + Volt single-file component changes. No database schema changes, no new Livewire components. Reads from already-seeded `tbl_certifications` table.

**Tech Stack:** Laravel 11, Livewire 3 Volt, Tailwind CSS (via Vite), Alpine.js (CDN).

**Spec:** `docs/superpowers/specs/2026-04-11-portfolio-tiles-redesign.md`

---

## Conventions for this plan

- **Path style:** Forward slashes in commands (bash via Git for Windows). All paths relative to the repo root (`C:/xampp/htdocs/JRevisPortfolio/`).
- **Critical preservation:** The id `section-contact` on the green tile must NOT be renamed. It is referenced by the mobile nav scroll-to (line ~699) and the entrance-animation choreography (line ~1022).
- **Test scope:** This is a visual/markup/CSS refactor. The spec did not require automated tests. Each task ends in visual verification (refresh browser, eyeball it). One exception: Task 10 adds a method to the Volt component — a manual inline test is described in its verification step.
- **Commits:** Each task ends in exactly one commit. Commit messages use conventional `feat:` / `refactor:` / `style:` / `chore:` prefixes. Pass via heredoc to preserve newlines. Co-Authored-By trailer can be included if desired.
- **Assets:** Before starting the Certifications tile (Task 11), the user must drop `san_beda.png` and `dost_tip_nitro.png` into `public/cert_logos/`. Tasks 11–14 can be written first and the assets added later — the certifications tile will render with broken-image icons until the files exist.

---

## File Structure

### Files modified

| File | Role |
|---|---|
| `resources/views/livewire/portfolio.blade.php` | Volt component. Load certifications data + add flip-state method in the PHP block at the top. Delete yellow tile markup. Restructure green tile into merged layout. Add Certifications tile. |
| `resources/css/app.css` | Add CSS custom properties for new tokens; add flip-card 3D styles; add purple tile + frosted card styles; adjust grid row heights; shrink `.carousel-item`. |

### Files created

| File | Role |
|---|---|
| `public/cert_logos/san_beda.png` | Issuer logo (user-sourced) |
| `public/cert_logos/dost_tip_nitro.png` | Issuer logo (user-sourced) |

### Files deleted

None.

---

## Tasks

### Task 1: Add CSS custom properties for new design tokens

**Files:**
- Modify: `resources/css/app.css` (insert near the top, after any existing `:root` block or at the very top of the file if none exists)

- [ ] **Step 1: Find insertion point**

Run: `grep -n "^:root\|^@layer\|^body" resources/css/app.css | head -5`
Expected: shows the first `:root` or `@layer` line if one exists. If nothing returns, insert at line 1 instead.

- [ ] **Step 2: Add the token block at the top of `resources/css/app.css`**

Insert this block as the first lines of the file (above any existing `@import` or rule):

```css
:root {
    /* Certifications tile */
    --cert-tile-bg: #4c3b9c;
    --cert-card-bg: rgba(255, 255, 255, 0.10);
    --cert-card-bg-light: rgba(255, 255, 255, 0.60);
    --cert-card-border: rgba(255, 255, 255, 0.20);

    /* Let's Connect — amber CTA */
    --cta-amber: #f59e0b;
    --cta-amber-hover: #d97706;

    /* Grid proportions */
    --tile-projects-min-h: 520px;
    --tile-certs-min-h: 280px;
}
```

If a `:root` block already exists, merge these variables INTO it instead of creating a second block.

- [ ] **Step 3: Verify the CSS compiles**

Run: `php artisan view:clear`
Expected: `Compiled views cleared successfully.`

If Vite dev server is running, check the terminal for any CSS build errors.

- [ ] **Step 4: Commit**

```bash
git add resources/css/app.css
git commit -m "$(cat <<'EOF'
chore(portfolio): add CSS custom properties for tile redesign tokens

Centralizes the purple Certifications tile color, frosted card backgrounds,
amber CTA color, and new row-height minimums so later tasks reference
variables instead of hardcoded values.
EOF
)"
```

---

### Task 2: Adjust grid row heights (Projects taller, Certifications shorter)

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (the Projects tile `<div>` around line 593; no height change to yellow tile yet — that gets deleted in Task 3)

- [ ] **Step 1: Update the Projects tile's min-height utility**

Find the Projects tile opening div (should be near line 593):

```html
<div id="section-projects" class="lg:row-start-3 lg:row-end-4 md:col-span-2 lg:col-span-2 portfolio-card-colored bg-portfolio-red dark:bg-red-600 text-white min-h-[300px] scroll-reveal tile-enter-hidden" data-reveal-delay="400">
```

Change the `min-h-[300px]` to `min-h-[520px]`:

```html
<div id="section-projects" class="lg:row-start-3 lg:row-end-4 md:col-span-2 lg:col-span-2 portfolio-card-colored bg-portfolio-red dark:bg-red-600 text-white min-h-[520px] scroll-reveal tile-enter-hidden" data-reveal-delay="400">
```

- [ ] **Step 2: Visually verify**

Refresh the public portfolio. Projects tile should now be noticeably taller than before. Let's Connect on the right stays unchanged (it was already tall from `row-start-3 row-end-5`).

- [ ] **Step 3: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "$(cat <<'EOF'
style(portfolio): increase Projects tile min-height to 520px

First step of grid re-proportioning. Projects takes more vertical weight.
The Certifications tile (new, replacing old yellow social tile) will get
its shorter min-height when it's added in Task 11.
EOF
)"
```

---

### Task 3: Delete the old yellow "Connect With Me" tile

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (delete lines ~625–667)

The yellow tile is a standalone `<div>` with `bg-portfolio-yellow`. It is independent of the rest — deleting it leaves a grid hole that Task 11 will fill with Certifications.

- [ ] **Step 1: Locate the yellow tile block**

Run: `grep -n "bg-portfolio-yellow\|<!-- Social Links -->" resources/views/livewire/portfolio.blade.php`
Expected: shows `<!-- Social Links -->` comment line and the yellow tile `<div>` (should be near 626–627).

- [ ] **Step 2: Delete the yellow tile**

Delete from the opening `<!-- Social Links -->` comment through the closing `</div>` that ends the yellow tile block. The exact block is:

```html
            <!-- Social Links -->
            <div class="lg:col-span-2 lg:row-start-4 lg:row-end-5 portfolio-card-colored bg-portfolio-yellow dark:bg-yellow-500 text-gray-800 dark:text-gray-900 mb-8 lg:mb-0 scroll-reveal tile-enter-hidden" data-reveal-delay="500">
                <div class="tile-glow tile-glow-white" id="glow-social"></div>
                <div class="text-center">
                    <h3 class="text-xl sm:text-2xl font-bold mb-6 sm:mb-8 text-gray-700 dark:text-gray-800">Connect With Me</h3>
                    <!-- Social Icons Grid for Mobile -->
                    <div class="grid grid-cols-3 gap-4 sm:flex sm:justify-center sm:space-x-6 mb-6 sm:mb-8">
                        <a href="https://github.com" class="social-icon ...
                        ... (all 6 social links) ...
                    </div>
                    <div class="text-center">
                        <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-700 mb-2">Currently Available For</div>
                        <div class="inline-block bg-green-500 ...">
                            ✨ Freelance Projects
                        </div>
                    </div>
                </div>
            </div>
```

Delete this entire block. The exact start is the `<!-- Social Links -->` comment, and the end is the matching outer `</div>` — typically ~41 lines.

- [ ] **Step 3: Confirm no orphaned references**

Run: `grep -n "glow-social\|#glow-social" resources/views/livewire/portfolio.blade.php resources/css/app.css`
Expected: zero matches if the yellow tile was the only consumer. If any references remain, remove them too (they were JS selectors for the cursor-glow effect on the yellow tile).

- [ ] **Step 4: Visually verify**

Refresh the portfolio. The yellow tile should be gone. There is now a visible **empty slot** in row 4, cols 1–2 — the grid doesn't collapse, it just shows blank space. This is expected until Task 11 adds Certifications.

- [ ] **Step 5: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "$(cat <<'EOF'
refactor(portfolio): remove yellow "Connect With Me" tile

Its socials move into the merged Let's Connect tile (task 8),
and its freed grid slot becomes the new Certifications tile (task 11).
EOF
)"
```

---

### Task 4: Add status badge at top of merged Let's Connect tile

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (inside the green tile's inner container, around line 466)

- [ ] **Step 1: Locate the insertion point**

Inside the green tile, find the `<div class="text-center mb-6">` block (around line 466) that contains the title "LET'S CONNECT". The status badge goes immediately ABOVE this heading block, still inside the main container at line 465.

- [ ] **Step 2: Insert the status badge markup**

In the file, locate:

```html
                <div class="h-full flex flex-col p-4 sm:p-6">
                    <div class="text-center mb-6">
                        <h3 class="text-3xl sm:text-4xl font-bold mb-2">LET'S CONNECT</h3>
```

Insert a new block between the outer `<div class="h-full...">` and the `<div class="text-center mb-6">`:

```html
                <div class="h-full flex flex-col p-4 sm:p-6">
                    <!-- Status pill: always-on availability signal -->
                    <div class="flex justify-center mb-4">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-900/60 border border-green-400/40 text-green-100 text-xs sm:text-sm font-semibold">
                            <span class="w-2 h-2 rounded-full bg-green-300 animate-pulse"></span>
                            Available for freelance projects
                        </span>
                    </div>

                    <div class="text-center mb-6">
                        <h3 class="text-3xl sm:text-4xl font-bold mb-2">LET'S CONNECT</h3>
```

- [ ] **Step 3: Visually verify**

Refresh. The green tile now shows a pill-shaped badge at the top: green dot + "Available for freelance projects" text. The pulse animation is subtle.

- [ ] **Step 4: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "$(cat <<'EOF'
feat(portfolio): add availability status pill to Let's Connect tile

Top-of-tile badge signals "open for freelance" — moved here from the
deleted yellow tile's "Currently Available For" block. Pulsing green
dot reinforces the live-status feel.
EOF
)"
```

---

### Task 5: Restore location detail (Tondo, Manila · Remote)

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (around line 510)

- [ ] **Step 1: Find the current location line**

Run: `grep -n "Philippines (Remote Available)" resources/views/livewire/portfolio.blade.php`
Expected: matches line 510.

- [ ] **Step 2: Update the text**

Change:

```html
                                            <span class="text-green-200 text-sm">Philippines (Remote Available)</span>
```

to:

```html
                                            <span class="text-green-200 text-sm">Tondo, Manila · Remote Available</span>
```

- [ ] **Step 3: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "$(cat <<'EOF'
style(portfolio): restore city to location (Tondo, Manila · Remote)

Specific city adds trust/legitimacy; "Remote Available" stays as a
suffix separated by middle-dot.
EOF
)"
```

---

### Task 6: Swap contact-block emojis for Heroicon SVGs

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (lines ~483–512 — the "Get In Touch" block)

The current contact block uses four emojis: 📬 (Get In Touch), 📧 (Email), 📱 (Phone), 📍 (Location). Swap each for an inline Heroicon SVG rendered monochrome white.

- [ ] **Step 1: Replace the 📬 "Get In Touch" emoji (line ~484)**

Find:

```html
                                <h4 class="font-semibold mb-3 flex items-center gap-2">
                                    <span class="text-xl">📬</span>
                                    Get In Touch
                                </h4>
```

Replace the `<span class="text-xl">📬</span>` with an inline Heroicon inbox SVG:

```html
                                <h4 class="font-semibold mb-3 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z" />
                                    </svg>
                                    Get In Touch
                                </h4>
```

- [ ] **Step 2: Replace the 📧 Email emoji (line ~489)**

Find:

```html
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">📧</span>
```

Replace:

```html
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                        </svg>
```

- [ ] **Step 3: Replace the 📱 Phone emoji (line ~498)**

Find:

```html
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">📱</span>
```

Replace:

```html
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                        </svg>
```

- [ ] **Step 4: Replace the 📍 Location emoji (line ~507)**

Find:

```html
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">📍</span>
```

Replace:

```html
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
```

Note the location SVG has TWO `<path>` elements (pin + circle).

- [ ] **Step 5: Visually verify**

Refresh. All 4 emojis in the contact block are now clean monochrome SVGs. Colors inherit from parent `text-white` / `text-green-200`.

- [ ] **Step 6: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "$(cat <<'EOF'
style(portfolio): swap contact-block emojis for Heroicon SVGs

Consistent icon style, themeable via currentColor, matches a more
"product" feel vs. platform-rendered emoji.
EOF
)"
```

---

### Task 7: Restyle Send Message button with amber CTA

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (the button around line 518–524)

- [ ] **Step 1: Find the existing Send Message button**

Run: `grep -n 'wire:click="toggleContactForm"' resources/views/livewire/portfolio.blade.php`
Expected: two matches (the primary button + the cancel button inside the form).

We're editing the FIRST match (the one that shows when the form is hidden).

- [ ] **Step 2: Swap the button styling to amber**

Find:

```html
                        <button 
                            wire:click="toggleContactForm"
                            class="w-full bg-green-800 dark:bg-green-900 hover:bg-green-900 dark:hover:bg-green-800 text-white py-3 px-4 rounded-lg font-semibold transition-all hover:scale-105 flex items-center justify-center gap-2"
                        >
                            <span class="text-lg">✉️</span>
                            Send Message
                        </button>
```

Replace with:

```html
                        <button 
                            wire:click="toggleContactForm"
                            class="w-full py-3 px-4 rounded-lg font-semibold transition-all hover:scale-105 flex items-center justify-center gap-2 shadow-lg"
                            style="background-color: var(--cta-amber); color: #1a1a2e;"
                            onmouseover="this.style.backgroundColor='var(--cta-amber-hover)'"
                            onmouseout="this.style.backgroundColor='var(--cta-amber)'"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.125A59.769 59.769 0 0121.485 12 59.768 59.768 0 013.27 20.875L5.999 12zm0 0h7.5" />
                            </svg>
                            Send Message
                        </button>
```

(Uses inline style + JS hover handlers for the amber colors, since Tailwind has no `amber-amber` arbitrary value. The CSS vars from Task 1 drive the color.)

- [ ] **Step 3: Visually verify**

Refresh. The Send Message button is now amber (orange-yellow) with dark navy text, paper-airplane icon on the left. Hover darkens the amber.

- [ ] **Step 4: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "$(cat <<'EOF'
style(portfolio): restyle Send Message button with amber CTA

Contrasts against the green tile so the primary action stands out.
Drop the envelope emoji in favor of a paper-airplane Heroicon for
consistency with the rest of the tile's icon treatment.
EOF
)"
```

---

### Task 8: Add "Connect elsewhere" social-icon row at bottom of Let's Connect

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (insert before the closing `</div>` of the green tile's inner container at line ~589)

We add 3 brand icons: GitHub, LinkedIn, Facebook — the three seeded in the spec. Monochrome white for consistency.

- [ ] **Step 1: Locate insertion point**

Find the closing of the `@endif` block and the outer container close inside the green tile:

```html
                    @endif
                </div>
            </div>
```

The insertion goes between `@endif` and the two closing `</div>`s — specifically after `@endif` but before the first `</div>`.

- [ ] **Step 2: Insert the socials section**

Insert this block immediately after the `@endif` (around line 588):

```html
                    @endif

                    <!-- Connect elsewhere — social brand icons -->
                    <div class="mt-6 pt-4 border-t border-green-500/40">
                        <div class="text-center text-xs uppercase tracking-wider text-green-200 mb-3">Connect elsewhere</div>
                        <div class="flex items-center justify-center gap-4">
                            <!-- GitHub -->
                            <a href="https://github.com/Jaysup29" target="_blank" rel="noopener" aria-label="GitHub"
                               class="text-white hover:text-green-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 .5C5.73.5.5 5.73.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.56v-2.17c-3.2.7-3.87-1.37-3.87-1.37-.52-1.33-1.28-1.69-1.28-1.69-1.05-.71.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.77 2.7 1.26 3.36.96.1-.75.4-1.26.72-1.55-2.55-.29-5.24-1.28-5.24-5.69 0-1.26.45-2.29 1.18-3.1-.12-.29-.51-1.47.11-3.06 0 0 .97-.31 3.18 1.18.92-.26 1.91-.39 2.89-.39.98 0 1.97.13 2.89.39 2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.24 2.77.12 3.06.74.81 1.18 1.84 1.18 3.1 0 4.42-2.69 5.4-5.25 5.68.41.36.78 1.06.78 2.13v3.16c0 .31.21.68.8.56A11.52 11.52 0 0023.5 12C23.5 5.73 18.27.5 12 .5z"/>
                                </svg>
                            </a>
                            <!-- LinkedIn -->
                            <a href="https://www.linkedin.com/in/jayarrevis/" target="_blank" rel="noopener" aria-label="LinkedIn"
                               class="text-white hover:text-green-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.063 2.063 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener" aria-label="Facebook"
                               class="text-white hover:text-green-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
```

The brand SVG paths above are simplified public-domain marks (GitHub Octocat, LinkedIn "in", Facebook "f"). They render as filled shapes using `currentColor`.

- [ ] **Step 3: Visually verify**

Refresh. At the bottom of the green Let's Connect tile there is now a thin green divider, a "Connect elsewhere" label, and 3 white brand icons evenly spaced. Hovering any icon lightens its color.

- [ ] **Step 4: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "$(cat <<'EOF'
feat(portfolio): add social-icon row inside merged Let's Connect tile

Replaces the 6-emoji social row from the deleted yellow tile with
3 monochrome brand SVGs (GitHub, LinkedIn, Facebook — matching the
3 accounts currently linked in the portfolio). Sits below the
contact block under a "Connect elsewhere" divider.
EOF
)"
```

---

### Task 9: Add flip-card state to the Volt component

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (the PHP `new class extends Component` block at the top, approximately lines 1–80)

We add `$certifications` loading in `mount()`, a `$flippedCertId` property, and a `flipCert(int $id)` method.

- [ ] **Step 1: Find the Volt component class declaration**

Run: `head -80 resources/views/livewire/portfolio.blade.php | grep -n "public\|mount\|use App"`
Expected: shows the `use App\Models\Skill;` import, the `public $projects = [];` line, the `public function mount()` line, etc.

- [ ] **Step 2: Add the Certification import**

Find the line `use App\Models\Project;` and add below it:

```php
use App\Models\Certification;
```

- [ ] **Step 3: Add new public properties**

Find the existing properties block (around line 11–22):

```php
    public $activeSection = 'about';
    public $projects = [];
    public $technologies = [];
```

Add below `public $projects = [];`:

```php
    public $certifications = [];
    public ?int $flippedCertId = null;
```

- [ ] **Step 4: Load certifications in `mount()`**

Find the `mount()` method (around line 25):

```php
    public function mount()
    {
        $this->loadTechnologies();
        $this->loadProjects();
    }
```

Add a call to a new loader method:

```php
    public function mount()
    {
        $this->loadTechnologies();
        $this->loadProjects();
        $this->loadCertifications();
    }
```

- [ ] **Step 5: Add the `loadCertifications()` private method**

Find an existing private loader (e.g. `loadProjects()`) as a style reference. Below it, add:

```php
    private function loadCertifications()
    {
        $this->certifications = Certification::active()->ordered()->get()->toArray();
    }
```

- [ ] **Step 6: Add the `flipCert` public method**

Below the loader methods, add:

```php
    public function flipCert(int $id): void
    {
        $this->flippedCertId = $this->flippedCertId === $id ? null : $id;
    }
```

- [ ] **Step 7: Manual verification of the state logic**

Since this is a Volt component and we aren't writing formal tests, verify via browser dev tools after Task 10 (when the tile markup exists). For now, confirm the PHP compiles:

Run: `php artisan view:clear && php artisan route:list 2>&1 | grep -i portfolio | head -3`
Expected: the portfolio route still resolves without error. If you see a PHP parse error, re-read the file and check indentation / missing braces.

- [ ] **Step 8: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "$(cat <<'EOF'
feat(portfolio): add certifications loading + flip state to Volt component

Loads tbl_certifications via the existing active/ordered scopes,
adds a nullable flippedCertId property, and exposes flipCert(int $id)
which toggles the flip (setting null when flipping the same card twice,
or replacing the id to auto-unflip any previously flipped card).
EOF
)"
```

---

### Task 10: Add the Certifications tile markup (front face only)

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (insert at the spot where the yellow tile used to live — right after the Projects tile's closing `</div>`, around line ~622)

- [ ] **Step 1: Locate insertion point**

Run: `grep -n "<!-- Social Links -->\|<!-- Projects Section\|end projects\|section-projects" resources/views/livewire/portfolio.blade.php | head -5`
Expected: Identifies where the projects tile ends. The Certifications tile goes immediately after Projects' closing `</div>`.

- [ ] **Step 2: Insert the Certifications tile markup**

Insert this block at the spot where the yellow tile used to be (after Projects closes, before the closing of the outer mobile-grid wrapper at line ~668):

```html
            <!-- Certifications Section -->
            <div id="section-certifications"
                 class="lg:col-span-2 lg:row-start-4 lg:row-end-5 portfolio-card-colored text-white mb-8 lg:mb-0 scroll-reveal tile-enter-hidden"
                 style="background-color: var(--cert-tile-bg); min-height: var(--tile-certs-min-h);"
                 data-reveal-delay="500">
                <div class="tile-glow tile-glow-white" id="glow-certs"></div>
                <div class="h-full flex flex-col p-4 sm:p-6">
                    <div class="text-center mb-4 sm:mb-6">
                        <h3 class="text-2xl sm:text-3xl font-bold mb-2">CERTIFICATIONS</h3>
                        <div class="w-16 h-1 bg-white/60 mx-auto rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 flex-1">
                        @foreach ($certifications as $cert)
                            <button type="button"
                                    wire:click="flipCert({{ $cert['id'] }})"
                                    aria-expanded="{{ $flippedCertId === $cert['id'] ? 'true' : 'false' }}"
                                    aria-label="Certification: {{ $cert['name'] }}. Click to {{ $flippedCertId === $cert['id'] ? 'hide' : 'show' }} description."
                                    class="cert-card text-left rounded-lg p-4 flex flex-col justify-between relative transition-transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-white/60"
                                    style="background-color: var(--cert-card-bg); border: 1px solid var(--cert-card-border); min-height: 180px;">
                                <div>
                                    @if (!empty($cert['icon_path']))
                                        <img src="{{ asset($cert['icon_path']) }}" alt="" class="w-10 h-10 object-contain mb-2" />
                                    @else
                                        <div class="w-10 h-10 rounded bg-white/10 flex items-center justify-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white/70" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <h4 class="font-bold text-sm sm:text-base leading-tight mb-1 line-clamp-2">{{ $cert['name'] }}</h4>
                                    <p class="text-xs sm:text-sm text-white/80 line-clamp-1">{{ $cert['issuer'] }}</p>
                                </div>
                                <div class="mt-2">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-white/15">
                                        {{ \Carbon\Carbon::parse($cert['issued_at'])->format('M Y') }}
                                    </span>
                                </div>
                                <!-- Flip-hint indicator -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute top-2 right-2 text-white/50" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
```

- [ ] **Step 3: Update the entrance-animation choreography**

Find the JS block around line 1019-1025 that lists the tile IDs for entrance animation:

```javascript
            const entranceList = [
                { id: 'section-home', cls: 'tile-enter-hero' },
                { id: 'section-about', cls: 'tile-enter-side' },
                { id: 'section-carousel', cls: 'tile-enter-stretch' },
                { id: 'section-contact', cls: 'tile-enter-bottom-1' },
                { id: 'section-projects', cls: 'tile-enter-bottom-2' },
            ];
```

Add a line for the new certifications tile:

```javascript
            const entranceList = [
                { id: 'section-home', cls: 'tile-enter-hero' },
                { id: 'section-about', cls: 'tile-enter-side' },
                { id: 'section-carousel', cls: 'tile-enter-stretch' },
                { id: 'section-contact', cls: 'tile-enter-bottom-1' },
                { id: 'section-projects', cls: 'tile-enter-bottom-2' },
                { id: 'section-certifications', cls: 'tile-enter-bottom-2' },
            ];
```

(Reusing `tile-enter-bottom-2` gives it the same entrance animation as Projects — they animate in together.)

- [ ] **Step 4: Visually verify**

Refresh. The purple Certifications tile now fills the slot where the yellow tile used to be. You see the title "CERTIFICATIONS" with an underline and 3 frosted-white cards showing:
- Laravel PHP Framework / Incentive Media / Jun 2022
- Machine Learning Using TensorFlow / San Beda University / Sep 2019 (fallback check-mark icon)
- Entrepreneurship Master Class & Incubation / DOST & TIP NITRO… / May 2021 (fallback check-mark icon)

A ⟳ icon is visible in the top-right corner of each card.

Clicking a card doesn't visually do anything yet (no flip animation) — but the Livewire request should fire. Check Network tab in dev tools: you should see a Livewire update request setting `flippedCertId` to the clicked card's id.

- [ ] **Step 5: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "$(cat <<'EOF'
feat(portfolio): add Certifications tile with front-face cards

Purple tile with 3 frosted-white cards showing seeded certifications
(Laravel, TensorFlow ML, Entrepreneurship). Each card wires to
flipCert() and shows a circular-arrows hint icon. Flip animation
itself lands in the next task. Includes the entrance-animation
registration so the tile animates in with Projects on first load.
EOF
)"
```

---

### Task 11: Add flip-card 3D CSS

**Files:**
- Modify: `resources/css/app.css` (add a new `.cert-card` style block near the bottom, or alongside any other card-style blocks)

- [ ] **Step 1: Add the flip-card 3D styles to `resources/css/app.css`**

Append to the end of the file:

```css
/* Certification flip cards */
.cert-card {
    perspective: 1000px;
    transform-style: preserve-3d;
    position: relative;
}

.cert-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    transform-style: preserve-3d;
}

.cert-card.is-flipped .cert-card-inner {
    transform: rotateY(180deg);
}

.cert-card-face {
    position: absolute;
    inset: 0;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-radius: 0.5rem;
}

.cert-card-face--back {
    transform: rotateY(180deg);
    background-color: var(--cert-card-bg);
    border: 1px solid var(--cert-card-border);
}

/* Light-mode contrast bump for the frosted cards */
html:not(.dark) .cert-card {
    background-color: var(--cert-card-bg-light) !important;
    color: #1a1a2e;
}

html:not(.dark) .cert-card-face--back {
    background-color: var(--cert-card-bg-light);
    color: #1a1a2e;
}

/* Respect reduced motion */
@media (prefers-reduced-motion: reduce) {
    .cert-card-inner {
        transition: none;
    }
}
```

- [ ] **Step 2: Verify CSS compiles**

Run: `php artisan view:clear`
Expected: no errors.

- [ ] **Step 3: Commit**

```bash
git add resources/css/app.css
git commit -m "$(cat <<'EOF'
style(portfolio): add flip-card 3D transform styles

Sets up perspective, backface-visibility, and the 600ms rotateY
animation that the next task will wire to the flipped-state class.
Light-mode override bumps card opacity to 0.6 so frosted cards
stay readable on a light page. prefers-reduced-motion instantly
short-circuits the rotation.
EOF
)"
```

---

### Task 12: Restructure the Cert card markup for flip (front + back faces)

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (the Certifications `@foreach` loop added in Task 10)

We restructure each card so the outer `<button>` contains an inner "card-inner" wrapper with both a front face and a back face.

- [ ] **Step 1: Replace the Certifications `@foreach` block**

Find the `@foreach ($certifications as $cert)` block inside the Certifications tile. Replace the entire `<button>...</button>` inside it with this new version that has front + back faces:

```html
                        @foreach ($certifications as $cert)
                            @php $isFlipped = $flippedCertId === $cert['id']; @endphp
                            <button type="button"
                                    wire:click="flipCert({{ $cert['id'] }})"
                                    aria-expanded="{{ $isFlipped ? 'true' : 'false' }}"
                                    aria-label="Certification: {{ $cert['name'] }}. Click to {{ $isFlipped ? 'hide' : 'show' }} description."
                                    class="cert-card text-left rounded-lg relative transition-transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-white/60 {{ $isFlipped ? 'is-flipped' : '' }}"
                                    style="background-color: transparent; min-height: 200px;">
                                <div class="cert-card-inner">
                                    <!-- Front face -->
                                    <div class="cert-card-face"
                                         style="background-color: var(--cert-card-bg); border: 1px solid var(--cert-card-border);">
                                        <div>
                                            @if (!empty($cert['icon_path']))
                                                <img src="{{ asset($cert['icon_path']) }}" alt="" class="w-10 h-10 object-contain mb-2" />
                                            @else
                                                <div class="w-10 h-10 rounded bg-white/10 flex items-center justify-center mb-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white/70" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <h4 class="font-bold text-sm sm:text-base leading-tight mb-1 line-clamp-2">{{ $cert['name'] }}</h4>
                                            <p class="text-xs sm:text-sm text-white/80 line-clamp-1">{{ $cert['issuer'] }}</p>
                                        </div>
                                        <div class="mt-2">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-white/15">
                                                {{ \Carbon\Carbon::parse($cert['issued_at'])->format('M Y') }}
                                            </span>
                                        </div>
                                        <!-- Flip-hint indicator (top-right) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute top-3 right-3 text-white/50" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                    </div>

                                    <!-- Back face -->
                                    <div class="cert-card-face cert-card-face--back overflow-y-auto">
                                        <div>
                                            <p class="text-xs sm:text-sm leading-relaxed">{{ $cert['description'] }}</p>
                                        </div>
                                        <div class="mt-3 pt-2 border-t border-white/20 text-xs text-white/70 flex items-center justify-between">
                                            <span>{{ $cert['issuer'] }} · {{ \Carbon\Carbon::parse($cert['issued_at'])->format('M Y') }}</span>
                                            <span class="inline-flex items-center gap-1 text-white/60">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                                                </svg>
                                                Back
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        @endforeach
```

- [ ] **Step 2: Add `aria-live` announcement region**

Add this just before the closing `</div>` of the Certifications tile (inside `<div class="h-full flex flex-col p-4 sm:p-6">`, after the grid of cards):

```html
                    <div aria-live="polite" class="sr-only">
                        @if ($flippedCertId)
                            Card flipped to show description.
                        @else
                            All cards showing front.
                        @endif
                    </div>
```

- [ ] **Step 3: Visually verify**

Refresh. Click a Certifications card. The card flips with a smooth ~600ms Y-axis rotation, revealing the full description on the back. Click it again — it flips back. Click a DIFFERENT card — the current one should flip back while the new one flips forward.

Keyboard: Tab to a card, press Enter or Space — flips. Press Tab to another card, Enter/Space — other flips, first auto-unflips.

Screen reader test (if available): flipping announces "Card flipped to show description."

- [ ] **Step 4: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "$(cat <<'EOF'
feat(portfolio): wire cert card flip animation with front/back faces

Each card now contains both front and back faces inside a 3D-transformed
inner container. Flipping state comes from the Livewire flippedCertId
property — only one card can be flipped at a time. aria-live announces
flips for screen readers.
EOF
)"
```

---

### Task 13: Shrink Skills Carousel card size

**Files:**
- Modify: `resources/css/app.css` (the `.carousel-item` rule around line 69)

- [ ] **Step 1: Find the current `.carousel-item` rule**

Run: `grep -n ".carousel-item {" resources/css/app.css`
Expected: line 69 (approximately).

- [ ] **Step 2: Update the card dimensions**

Find:

```css
  .carousel-item {
      flex: 0 0 auto;
      width: 100px;
      height: 100px;
      border-radius: 0.5rem;
      padding: 0.75rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s ease;
  }
```

Replace `width`, `height`, and `padding`:

```css
  .carousel-item {
      flex: 0 0 auto;
      width: 120px;
      height: 120px;
      border-radius: 0.5rem;
      padding: 0.5rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s ease;
  }
```

(Width/height went from 100 → 120px. Padding went from 0.75rem → 0.5rem for tighter look.)

If the actual rendered size looks BIGGER than expected after refresh, the CSS is being overridden by inline styles or utility classes elsewhere in the carousel markup. Run `grep -n "carousel-item" resources/views/livewire/portfolio.blade.php` to find any Tailwind class overrides and adjust there instead.

- [ ] **Step 3: Visually verify**

Refresh. Scroll to the Skills carousel. Cards should look slightly smaller and tighter; more cards should peek on the right edge.

- [ ] **Step 4: Commit**

```bash
git add resources/css/app.css
git commit -m "$(cat <<'EOF'
style(portfolio): shrink carousel card size (120×120px, tighter padding)

Cards now read as chip-sized skill badges rather than oversized tiles.
Tighter padding and slightly larger footprint lets more cards peek
on the right edge, reinforcing the scroll-for-more affordance.
EOF
)"
```

---

### Task 14: Asset drop — real issuer logos for San Beda and DOST

**Files:**
- Create: `public/cert_logos/san_beda.png`
- Create: `public/cert_logos/dost_tip_nitro.png`

This task is **user-performed** — source the real logos, save them as PNGs with transparent backgrounds, 64–128px.

- [ ] **Step 1: Create the target directory if it doesn't exist**

```bash
mkdir -p public/cert_logos
```

- [ ] **Step 2: Source the San Beda University logo**

Go to https://www.sanbeda.edu.ph/ (or the San Beda Wikipedia article) and save the official logo. Save as:

```
public/cert_logos/san_beda.png
```

Target size: 128×128px, PNG with transparent background.

- [ ] **Step 3: Source the DOST / TIP NITRO logo**

Go to https://www.dost.gov.ph/ or search "DOST Philippines logo." TIP NITRO is an academy of TIP (Technological Institute of the Philippines) — the DOST logo alone is fine since DOST is more recognizable. Save as:

```
public/cert_logos/dost_tip_nitro.png
```

Target size: 128×128px, PNG with transparent background.

- [ ] **Step 4: Update the seeded data to reference the new paths**

Open `database/seeders/CertificationSeeder.php`. Find the TensorFlow entry's `icon_path` (currently `null`) and the Entrepreneurship entry's `icon_path` (currently `null`). Update them:

```php
            [
                'name' => 'Machine Learning Using TensorFlow',
                'issuer' => 'San Beda University',
                'issued_at' => '2019-09-01',
                'icon_path' => 'cert_logos/san_beda.png',
                ...
            ],
            [
                'name' => 'Entrepreneurship Master Class & Incubation',
                'issuer' => 'DOST & TIP NITRO Academy of Entrepreneurs',
                'issued_at' => '2021-05-01',
                'icon_path' => 'cert_logos/dost_tip_nitro.png',
                ...
            ],
```

- [ ] **Step 5: Update the database records**

Since the seeder uses `firstOrCreate(['name' => ...])`, running `db:seed` again won't change existing rows. Use tinker instead to update:

```bash
php artisan tinker --execute="
\App\Models\Certification::where('name', 'Machine Learning Using TensorFlow')->update(['icon_path' => 'cert_logos/san_beda.png']);
\App\Models\Certification::where('name', 'Entrepreneurship Master Class & Incubation')->update(['icon_path' => 'cert_logos/dost_tip_nitro.png']);
echo 'Updated.';
"
```

Expected output: `Updated.`

- [ ] **Step 6: Visually verify**

Refresh the portfolio. All 3 cert cards should now show real logos (Laravel, San Beda, DOST). The fallback check-mark icon no longer appears on any card.

- [ ] **Step 7: Commit**

```bash
git add public/cert_logos/ database/seeders/CertificationSeeder.php
git commit -m "$(cat <<'EOF'
chore(portfolio): add San Beda + DOST logos for certifications

Replaces the generic check-mark fallback icons on the Machine Learning
and Entrepreneurship cert cards with the real issuer logos. Seeder
updated so fresh db:seed runs populate the paths for new environments;
existing rows were updated via tinker.
EOF
)"
```

---

### Task 15: Final visual QA pass

**Files:**
- None modified. Pure verification.

- [ ] **Step 1: Desktop check (`lg:` breakpoint, ≥ 1024px)**

Visit the portfolio. Confirm:
- Projects tile is taller than Certifications tile (roughly 65 / 35 proportion).
- Let's Connect spans the full height of rows 3+4, taller than before.
- Certifications tile displays 3 flip-cards in a row.
- Clicking a cert card flips it smoothly.
- Clicking another cert card auto-flips the first one back.
- Let's Connect has: status pill → title → subtitle → contact block → amber Send Message → divider → 3 brand socials.
- No yellow "Connect With Me" tile visible anywhere.
- Skills carousel cards look chip-sized (smaller than before).

- [ ] **Step 2: Tablet check (`md:` breakpoint, 768–1023px)**

Resize browser to ~900px wide. Confirm:
- Certifications tile collapses to 2 cards in a row + 1 below.
- Let's Connect drops below Projects (2-column grid stacking).
- Flip still works.

- [ ] **Step 3: Mobile check (< 768px)**

Resize to ~400px wide. Confirm:
- Every tile is stacked full-width single column.
- Certifications shows 1 card per row.
- Tap-to-flip works on each card.
- Skills carousel still scrolls.

- [ ] **Step 4: Dark mode check**

Click the dark-mode toggle. Refresh. Verify everything still looks right in dark mode. Pay attention to:
- Purple Certifications tile (same color).
- Frosted cards (same opacity).
- Amber Send Message button (still amber).
- Brand social icons still visible.

- [ ] **Step 5: Light mode check**

Click the toggle again to return to light mode. Verify:
- Frosted cards are visible (`--cert-card-bg-light` at 0.6 opacity kicks in).
- Card text is dark and readable on the lighter background.
- Everything else looks reasonable.

- [ ] **Step 6: Reduced-motion check**

In browser dev tools → rendering → Emulate CSS media feature `prefers-reduced-motion: reduce`. Refresh. Click a cert card — it should flip INSTANTLY without the 600ms rotation.

- [ ] **Step 7: Keyboard / a11y check**

Tab through the cert cards. Each should get a visible focus ring. Press Enter/Space — card flips. Tab away, come back, flip again with Enter. Confirm `aria-expanded` value in dev tools updates to `true` / `false` on each flip.

- [ ] **Step 8: Commit the Phase marker**

If everything checks out:

```bash
git commit --allow-empty -m "$(cat <<'EOF'
chore(portfolio): mark tiles redesign complete — QA passed

All four in-scope changes verified:
- Merged Let's Connect (status + contact + amber CTA + socials)
- Certifications tile with working flip cards
- Grid proportions (Projects taller, Cert shorter)
- Carousel cards chip-sized

Desktop / tablet / mobile / dark / light / reduced-motion / keyboard
all verified.
EOF
)"
```

---

## Verification Checklist

Phase complete when ALL of the following are true:

- [ ] Old yellow "Connect With Me" tile markup is gone, no orphaned references to `glow-social`.
- [ ] Merged green Let's Connect tile renders: status pill → "LET'S CONNECT" → subtitle → contact block (Email/Phone/Location with Heroicon SVGs and city restored) → amber "Send Message" button → "Connect elsewhere" divider → 3 brand social icons (GH/LI/FB).
- [ ] Clicking Send Message toggles the inline contact form (existing behavior preserved).
- [ ] Purple Certifications tile sits where the yellow tile used to be, displays 3 frosted cards.
- [ ] Cert cards flip on click/tap with a smooth ~600ms Y-axis rotation.
- [ ] Only one cert card flipped at a time — flipping another auto-unflips the previous.
- [ ] Cert card front shows: logo / fallback check-icon / cert name / issuer / date pill / ⟳ hint top-right.
- [ ] Cert card back shows: full description / smaller issuer+date summary / "Back" affordance.
- [ ] Real San Beda and DOST logos display on the 2 previously logoless cards (Task 14).
- [ ] Projects tile is visibly taller than Certifications tile on desktop.
- [ ] Skills carousel cards are visibly smaller (120px vs prior ~100px rendered larger, now tighter).
- [ ] Both light and dark mode work; frosted cards adapt visibility.
- [ ] Keyboard: Tab reaches cards, Enter/Space flips, Esc flips back (focused card only).
- [ ] Screen reader: flipping announces via aria-live.
- [ ] `prefers-reduced-motion: reduce` short-circuits the animation.
- [ ] No PHP errors, no browser console errors.
- [ ] Mobile nav at the bottom still scrolls to contact correctly (the `section-contact` id was preserved).

---

## Out of Scope (deferred)

Everything in the 29 other items of the tile audit checklist:
- Hero profile photo, bio rewrite, subtitle contrast, CTA hierarchy, corner decorations.
- About tile saturation, sub-tile labels, icon unification, Journey/Values nav discoverability, multi-panel hint.
- Projects red saturation, icons, tech chip styling, count indicator, View All footer.
- Skills carousel logo accuracy (Git vs GitHub), background color system, logo style, jQuery/MySQL corrections, nav arrow color, pagination style, card depth/hover.
- Site-wide: color desaturation pass, unified title styling, icon set, logo accuracy audit.

All tracked for future sessions.
