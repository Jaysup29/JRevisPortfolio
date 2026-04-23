# Hero Resume Button — Design Spec

**Date:** 2026-04-11
**Status:** Approved for planning
**Author:** Brainstorming session with Jay-ar

---

## 1. Purpose

Replace the two placeholder buttons in the Hero tile (`View Projects` and `Contact Me`) with a single working **Resume** button that opens a PDF in a new tab.

Both existing buttons today are visually styled but have no `wire:click`, no `href`, and no click handler — they are decorative placeholders. The new Resume button will be a real, functional CTA.

---

## 2. Scope

### In scope

- Delete the "View Projects" `<button>` at `resources/views/livewire/portfolio.blade.php:230–232`.
- Replace the "Contact Me" `<button>` at lines 233–235 with an `<a>` link styled as a solid yellow button (same visual treatment the old "View Projects" button had — solid fill, not outlined).
- New link opens `{{ asset('resume.pdf') }}` in a new tab (`target="_blank" rel="noopener"`).
- Include a small inline Heroicon "document-arrow-down" SVG inside the button, left of the text, to signal "this is a downloadable file."
- User (Jay-ar) drops the actual PDF at `public/resume.pdf` separately — not part of this spec's work. The button markup ships whether or not the PDF exists yet.

### Out of scope

- The hidden `Log In` button at lines 236–238 — unchanged.
- Any analytics / click tracking.
- Generating, writing, or styling the resume PDF itself.
- Any other hero changes (profile photo, bio copy, subtitle contrast, decorative corner shapes, etc.) — those are parked in the broader tile-audit checklist.
- Replacing `asset('resume.pdf')` with a different storage pattern (S3, CDN, etc.).

---

## 3. Visual treatment

### Before (today)

Two buttons, flex-row, solid yellow + outlined yellow:

```
┌───────────────┐ ┌───────────────┐
│ View Projects │ │ Contact Me    │
└───────────────┘ └───────────────┘
```

### After

A single prominent solid-yellow button with a document icon:

```
┌──────────────────────┐
│  📄  Resume          │
└──────────────────────┘
```

The single-CTA layout reads cleaner than two buttons of similar weight; a document icon tells visitors at a glance that clicking leads to a file.

### CSS / Tailwind classes

The new `<a>` inherits the **exact same classes** the old "View Projects" button used (solid yellow):

```
bg-portfolio-yellow hover:bg-yellow-500 text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all hover:scale-105
```

Plus `inline-flex items-center gap-2` so the icon + label sit on one line with consistent spacing.

### Icon

Heroicons outline "document-arrow-down" (already a pattern established by the Certifications flip-card and Let's Connect contact block — we stay within that icon family).

```html
<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M14 9l3 3m0 0l3-3m-3 3V3" />
</svg>
```

---

## 4. Markup (target state)

Replace lines 228–239 of `resources/views/livewire/portfolio.blade.php`:

```html
                        <!-- Mobile CTA Buttons -->
                        <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <button class="bg-portfolio-yellow hover:bg-yellow-500 text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all hover:scale-105">
                                View Projects
                            </button>
                            <button class="border border-portfolio-yellow text-portfolio-yellow hover:bg-portfolio-yellow hover:text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all">
                                Contact Me
                            </button>
                            <button class="{{ $showLogin ? 'text-portfolio-yellow hover:bg-portfolio-yellow' : 'cursor-help' }}  hover:text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all" wire:click="toggleLoginButton">
                                {{ $showLogin ? 'Log In' : '' }}
                            </button>
                        </div>
```

with:

```html
                        <!-- Hero CTAs -->
                        <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <a href="{{ asset('resume.pdf') }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex items-center justify-center gap-2 bg-portfolio-yellow hover:bg-yellow-500 text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 9l3 3m0 0l3-3m-3 3V3" />
                                </svg>
                                Resume
                            </a>
                            <button class="{{ $showLogin ? 'text-portfolio-yellow hover:bg-portfolio-yellow' : 'cursor-help' }}  hover:text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all" wire:click="toggleLoginButton">
                                {{ $showLogin ? 'Log In' : '' }}
                            </button>
                        </div>
```

The hidden Login button is preserved verbatim — only the two placeholder CTA buttons are removed and replaced.

---

## 5. Asset

**File:** `public/resume.pdf`
**Source:** user-supplied (Jay-ar drops his own PDF into the directory)
**When:** at any point — before or after the markup ships. The button works immediately either way.
**Missing-file behavior:** clicking when the file isn't there causes the browser to navigate to `resume.pdf` which returns a 404 page. Acceptable — visitors don't crash the site, they just see a not-found page. No defensive code needed.

---

## 6. Risks & open questions

1. **Broken link if PDF is never added.** Mitigation: user has been notified to drop the file in `public/resume.pdf`. No code fallback (YAGNI — adding a "file not found" check would require a filesystem check on every page render).

2. **Deployment / build pipeline.** The existing Vite build does not process `public/` files — they ship as-is. `resume.pdf` will be served directly by Laravel's `public` directory. No build-tool changes needed.

3. **Mobile tap target size.** The single button keeps the existing `px-6 py-3` padding — already well above 44×44px minimum on all breakpoints. No regression.

4. **Screen reader clarity.** `<a>` with `target="_blank"` is ambiguous for screen readers. Add an `aria-label="View resume (opens in new tab)"` to make the behavior explicit. This is a small improvement worth building in.

---

## 7. Verification checklist

Implementation is complete when:

- [ ] "View Projects" button is gone from the hero.
- [ ] "Contact Me" button is gone from the hero.
- [ ] A single "Resume" button with document icon renders in the hero, styled solid yellow.
- [ ] Clicking the button opens `/resume.pdf` in a new browser tab.
- [ ] The hidden Login button at lines 236–238 continues to work unchanged.
- [ ] `aria-label="View resume (opens in new tab)"` is present on the link.
- [ ] Hero still renders correctly on mobile (button full-width, stacks vertically with Login placeholder).
- [ ] No console errors, no Blade parse errors.
- [ ] Light mode and dark mode both work (the yellow CTA color is the same in both modes already).

---

## 8. Critical files

| File | Change |
|---|---|
| `resources/views/livewire/portfolio.blade.php` | Lines 228–239 restructured per §4. |
| `public/resume.pdf` | User-supplied asset (not part of this spec's git commits). |

No CSS, model, migration, seeder, or test changes.

---

## 9. Suggested implementation order

One commit should be enough for this change:

1. Edit `resources/views/livewire/portfolio.blade.php` — delete the two placeholder buttons, insert the new `<a>` link with icon and `aria-label`.
2. Run `php artisan view:clear` to flush compiled view cache.
3. Visually verify in the browser (refresh hero, click Resume — should open `/resume.pdf` in a new tab, 404 acceptable if file not yet present).
4. Commit.
5. (User task, separate) Drop `resume.pdf` into `public/`.
