# Portfolio Visual Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Transform the existing Metro/Lumia tile portfolio into a futuristic, elegant experience with glassmorphism hover effects, morphing blob backgrounds, splash intro animation, and modernized color palette — all while preserving the existing layout and functionality.

**Architecture:** CSS-first approach — all background animations (blobs, grain, keyframes) are pure CSS. JavaScript handles interactive effects (inner glow, 3D tilt, splash sequence, typing animation, scroll reveal observer). Splash screen is a fixed overlay that hides after user clicks CTA. All effects adapt to dark/light mode via Tailwind's `dark:` prefix and CSS custom properties.

**Tech Stack:** Tailwind CSS 3, Alpine.js (via Livewire), Vanilla JavaScript, CSS keyframe animations, Intersection Observer API, sessionStorage.

---

## File Structure

| File | Responsibility |
|------|---------------|
| `tailwind.config.js` | Color palette values, custom animation definitions |
| `resources/css/app.css` | Morphing blob keyframes, glass hover states, film grain, scroll reveal classes, splash screen styles, inner glow, ripple animations |
| `resources/views/components/layouts/guest.blade.php` | Background layer HTML (blobs, grain overlay, spotlight), splash screen overlay HTML |
| `resources/views/livewire/portfolio.blade.php` | Inner glow elements inside tiles, updated color classes, tile entrance animation classes, splash sequence JS, typing animation JS, inner glow JS, scroll reveal JS |
| `resources/js/app.js` | Cursor spotlight tracking (global mousemove) |

---

### Task 1: Update Color Palette in Tailwind Config

**Files:**
- Modify: `tailwind.config.js:14-19`

- [ ] **Step 1: Update color values**

Replace the colors object in `tailwind.config.js`:

```javascript
colors: {
    'portfolio-dark': '#1a1a2e',
    'portfolio-blue': '#4361ee',
    'portfolio-green': '#2ec4b6',
    'portfolio-red': '#e63946',
    'portfolio-yellow': '#f4a261',
},
```

Note: `portfolio-yellow` keeps its name for backward compatibility but the value is now amber `#f4a261`.

- [ ] **Step 2: Build to verify no errors**

Run: `npm run build`
Expected: Build succeeds, CSS output contains new color values.

- [ ] **Step 3: Commit**

```bash
git add tailwind.config.js
git commit -m "feat: update color palette to modernized muted tones"
```

---

### Task 2: Add Background Effects CSS (Blobs, Grain, Spotlight)

**Files:**
- Modify: `resources/css/app.css` (append new styles)

- [ ] **Step 1: Add morphing blob keyframes and styles**

Append to the end of `resources/css/app.css` (outside the `@layer components` block):

```css
/* ===== BACKGROUND EFFECTS ===== */

/* Morphing Organic Blobs */
.morph-bg {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  z-index: 0;
  overflow: hidden;
  pointer-events: none;
}

.morph-blob {
  position: absolute;
  border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%;
  filter: blur(60px);
  opacity: 0.3;
}

.dark .morph-blob { opacity: 0.3; }
.morph-blob { opacity: 0.15; }

.morph-blob-1 {
  width: 450px; height: 450px;
  background: linear-gradient(135deg, #4361ee, #2ec4b6);
  top: 5%; left: 5%;
  animation: morphBlob1 12s ease-in-out infinite;
}

.morph-blob-2 {
  width: 380px; height: 380px;
  background: linear-gradient(135deg, #e63946, #f4a261);
  bottom: 5%; right: 5%;
  animation: morphBlob2 14s ease-in-out infinite;
}

.morph-blob-3 {
  width: 300px; height: 300px;
  background: linear-gradient(135deg, #2ec4b6, #4361ee);
  top: 40%; left: 40%;
  animation: morphBlob3 16s ease-in-out infinite;
}

@keyframes morphBlob1 {
  0%, 100% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; transform: translate(0, 0) rotate(0deg); }
  25% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; transform: translate(60px, -40px) rotate(45deg); }
  50% { border-radius: 30% 70% 50% 50% / 35% 65% 35% 65%; transform: translate(-30px, 50px) rotate(90deg); }
  75% { border-radius: 55% 45% 65% 35% / 40% 60% 40% 60%; transform: translate(40px, 20px) rotate(135deg); }
}

@keyframes morphBlob2 {
  0%, 100% { border-radius: 50% 50% 40% 60% / 55% 45% 55% 45%; transform: translate(0, 0) rotate(0deg); }
  33% { border-radius: 35% 65% 55% 45% / 50% 50% 50% 50%; transform: translate(-60px, 40px) rotate(-60deg); }
  66% { border-radius: 65% 35% 45% 55% / 40% 60% 40% 60%; transform: translate(40px, -50px) rotate(-120deg); }
}

@keyframes morphBlob3 {
  0%, 100% { border-radius: 45% 55% 60% 40% / 50% 50% 50% 50%; transform: translate(0, 0); }
  50% { border-radius: 55% 45% 40% 60% / 40% 60% 40% 60%; transform: translate(-40px, -30px) rotate(60deg); }
}

/* Film Grain Overlay */
.film-grain {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  pointer-events: none;
  z-index: 100;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size: 256px;
}

.dark .film-grain { opacity: 0.03; }
.film-grain { opacity: 0.02; }

/* Cursor Spotlight */
.cursor-spotlight {
  position: fixed;
  width: 500px; height: 500px;
  border-radius: 50%;
  pointer-events: none;
  transform: translate(-50%, -50%);
  z-index: 1;
  transition: opacity 0.3s;
}

.dark .cursor-spotlight {
  background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(67,97,238,0.03) 30%, transparent 70%);
}

.cursor-spotlight {
  background: radial-gradient(circle, rgba(0,0,0,0.02) 0%, transparent 70%);
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
  .morph-blob { animation: none !important; }
  .film-grain { display: none; }
}
```

- [ ] **Step 2: Build to verify**

Run: `npm run build`
Expected: Build succeeds.

- [ ] **Step 3: Commit**

```bash
git add resources/css/app.css
git commit -m "feat: add morphing blobs, film grain, and cursor spotlight CSS"
```

---

### Task 3: Add Background HTML Elements to Layout

**Files:**
- Modify: `resources/views/components/layouts/guest.blade.php`

- [ ] **Step 1: Add background elements after opening body tag**

In `guest.blade.php`, right after `<body class="antialiased">`, add:

```html
    <!-- Background Effects -->
    <div class="morph-bg" aria-hidden="true">
        <div class="morph-blob morph-blob-1"></div>
        <div class="morph-blob morph-blob-2"></div>
        <div class="morph-blob morph-blob-3"></div>
    </div>
    <div class="film-grain" aria-hidden="true"></div>
    <div class="cursor-spotlight" id="cursorSpotlight" aria-hidden="true"></div>
```

- [ ] **Step 2: Update the body/background color**

In `portfolio.blade.php`, update the root div background:

Change `bg-gray-100 dark:bg-gray-900` to `bg-[#f0f1f5] dark:bg-[#0a0a14]`

- [ ] **Step 3: Build and verify visually**

Run: `npm run build`
Open in browser — morphing blobs should be visible behind content, film grain overlay should be subtle.

- [ ] **Step 4: Commit**

```bash
git add resources/views/components/layouts/guest.blade.php resources/views/livewire/portfolio.blade.php
git commit -m "feat: add morphing blob background, film grain, and spotlight elements"
```

---

### Task 4: Add Cursor Spotlight JS

**Files:**
- Modify: `resources/js/app.js`

- [ ] **Step 1: Add spotlight tracking to app.js**

Add this at the end of `resources/js/app.js`:

```javascript
// Cursor spotlight - follows mouse across page
document.addEventListener('mousemove', (e) => {
    const spotlight = document.getElementById('cursorSpotlight');
    if (spotlight) {
        spotlight.style.left = e.clientX + 'px';
        spotlight.style.top = e.clientY + 'px';
    }
});
```

- [ ] **Step 2: Build and verify**

Run: `npm run build`
Open in browser, move mouse — a subtle light should follow the cursor.

- [ ] **Step 3: Commit**

```bash
git add resources/js/app.js
git commit -m "feat: add cursor spotlight tracking"
```

---

### Task 5: Add Glass Hover + Inner Glow CSS

**Files:**
- Modify: `resources/css/app.css` (append)

- [ ] **Step 1: Add glass hover states and inner glow styles**

Append to `resources/css/app.css`:

```css
/* ===== TILE HOVER EFFECTS ===== */

/* Inner glow element - follows cursor inside tile */
.tile-glow {
  position: absolute;
  width: 300px; height: 300px;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  opacity: 0;
  transition: opacity 0.4s ease;
  z-index: 1;
  pointer-events: none;
}

.portfolio-card-colored:hover .tile-glow {
  opacity: 1;
}

/* Default glow color */
.tile-glow { background: radial-gradient(circle, rgba(67,97,238,0.2) 0%, transparent 70%); }
.tile-glow-white { background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); }

/* Light mode glow */
:not(.dark) .tile-glow { background: radial-gradient(circle, rgba(0,0,0,0.06) 0%, transparent 70%); }
:not(.dark) .tile-glow-white { background: radial-gradient(circle, rgba(0,0,0,0.06) 0%, transparent 70%); }

/* Glass hover - dark mode */
.dark .portfolio-card-colored {
  border: 1px solid rgba(255,255,255,0.04);
  transition: background 0.5s ease, backdrop-filter 0.5s ease, box-shadow 0.5s ease, border-color 0.5s ease, transform 0.3s ease;
}

.dark .portfolio-card-colored.bg-portfolio-dark:hover {
  background: rgba(26, 26, 46, 0.65) !important;
  backdrop-filter: blur(20px);
  border-color: rgba(67, 97, 238, 0.3);
  box-shadow: 0 0 50px rgba(67, 97, 238, 0.15), inset 0 1px 0 rgba(255,255,255,0.06);
}

.dark .portfolio-card-colored.bg-portfolio-blue:hover,
.dark .portfolio-card-colored.dark\:bg-blue-600:hover {
  background: rgba(67, 97, 238, 0.55) !important;
  backdrop-filter: blur(20px);
  border-color: rgba(255, 255, 255, 0.25);
  box-shadow: 0 0 50px rgba(67, 97, 238, 0.2), inset 0 1px 0 rgba(255,255,255,0.08);
}

.dark .portfolio-card-colored.bg-portfolio-green:hover,
.dark .portfolio-card-colored.dark\:bg-green-600:hover {
  background: rgba(46, 196, 182, 0.55) !important;
  backdrop-filter: blur(20px);
  border-color: rgba(255, 255, 255, 0.25);
  box-shadow: 0 0 50px rgba(46, 196, 182, 0.2), inset 0 1px 0 rgba(255,255,255,0.08);
}

.dark .portfolio-card-colored.bg-portfolio-red:hover,
.dark .portfolio-card-colored.dark\:bg-red-600:hover {
  background: rgba(230, 57, 70, 0.55) !important;
  backdrop-filter: blur(20px);
  border-color: rgba(255, 255, 255, 0.25);
  box-shadow: 0 0 50px rgba(230, 57, 70, 0.2), inset 0 1px 0 rgba(255,255,255,0.08);
}

.dark .portfolio-card-colored.bg-portfolio-yellow:hover,
.dark .portfolio-card-colored.dark\:bg-yellow-500:hover {
  background: rgba(244, 162, 97, 0.6) !important;
  backdrop-filter: blur(20px);
  border-color: rgba(255, 255, 255, 0.25);
  box-shadow: 0 0 50px rgba(244, 162, 97, 0.2), inset 0 1px 0 rgba(255,255,255,0.1);
}

/* Glass hover - light mode */
.portfolio-card-colored {
  border: 1px solid rgba(0,0,0,0.04);
  transition: background 0.5s ease, backdrop-filter 0.5s ease, box-shadow 0.5s ease, border-color 0.5s ease, transform 0.3s ease;
}

:not(.dark) .portfolio-card-colored:hover {
  backdrop-filter: blur(16px);
  border-color: rgba(0,0,0,0.1);
}

/* Disable default hover scale from existing CSS */
.portfolio-card-colored:hover {
  transform: none;
  box-shadow: none;
}
```

- [ ] **Step 2: Build and verify**

Run: `npm run build`
Expected: Build succeeds.

- [ ] **Step 3: Commit**

```bash
git add resources/css/app.css
git commit -m "feat: add glass hover states and inner glow CSS"
```

---

### Task 6: Add Inner Glow HTML + JS to Tiles

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php`

- [ ] **Step 1: Add inner glow div inside each major tile**

Inside each `.portfolio-card-colored` tile in `portfolio.blade.php`, add as the first child element:

For the hero tile (bg-portfolio-dark):
```html
<div class="tile-glow" id="glow-hero"></div>
```

For the about tile (bg-portfolio-blue):
```html
<div class="tile-glow tile-glow-white" id="glow-about"></div>
```

For the contact tile (bg-portfolio-green):
```html
<div class="tile-glow tile-glow-white" id="glow-contact"></div>
```

For the projects tile (bg-portfolio-red):
```html
<div class="tile-glow tile-glow-white" id="glow-projects"></div>
```

For the social tile (bg-portfolio-yellow):
```html
<div class="tile-glow tile-glow-white" id="glow-social"></div>
```

- [ ] **Step 2: Add inner glow + 3D tilt JavaScript**

In the `<script>` section at the bottom of `portfolio.blade.php`, add:

```javascript
// Inner glow follows cursor inside tile
document.querySelectorAll('.portfolio-card-colored').forEach(tile => {
    const glow = tile.querySelector('.tile-glow');
    if (!glow) return;

    tile.addEventListener('mousemove', (e) => {
        const rect = tile.getBoundingClientRect();
        glow.style.left = (e.clientX - rect.left) + 'px';
        glow.style.top = (e.clientY - rect.top) + 'px';

        // 3D tilt
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const rotateX = (y - rect.height / 2) / rect.height * -6;
        const rotateY = (x - rect.width / 2) / rect.width * 6;
        tile.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-2px)`;
    });

    tile.addEventListener('mouseleave', () => {
        tile.style.transform = 'perspective(800px) rotateX(0) rotateY(0) translateY(0)';
    });
});
```

- [ ] **Step 3: Ensure tile content stays above glow**

Add `position: relative; z-index: 2;` to all direct content children of tiles (headings, paragraphs, buttons) — or add a wrapper div with those styles around the content of each tile.

- [ ] **Step 4: Build and verify**

Run: `npm run build`
Open in browser, hover over tiles — inner glow should follow cursor, tile should tilt, glass effect should reveal morphing blobs.

- [ ] **Step 5: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "feat: add inner glow and 3D tilt to portfolio tiles"
```

---

### Task 7: Add Scroll Reveal Animation

**Files:**
- Modify: `resources/css/app.css` (append)
- Modify: `resources/views/livewire/portfolio.blade.php`

- [ ] **Step 1: Add scroll reveal CSS**

Append to `resources/css/app.css`:

```css
/* ===== SCROLL REVEAL ===== */
.scroll-reveal {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.scroll-reveal.revealed {
  opacity: 1;
  transform: translateY(0);
}

@media (prefers-reduced-motion: reduce) {
  .scroll-reveal {
    opacity: 1;
    transform: none;
    transition: none;
  }
}
```

- [ ] **Step 2: Add scroll-reveal class to each tile**

In `portfolio.blade.php`, add the class `scroll-reveal` to each major section div (hero, about, carousel, contact, projects, social). Each tile should also get a `data-reveal-delay` attribute for stagger:

```
data-reveal-delay="0"    (hero)
data-reveal-delay="100"  (about)
data-reveal-delay="200"  (carousel)
data-reveal-delay="300"  (contact)
data-reveal-delay="400"  (projects)
data-reveal-delay="500"  (social)
```

- [ ] **Step 3: Add Intersection Observer JS**

In the `<script>` section of `portfolio.blade.php`, replace the existing fade-in observer with:

```javascript
// Scroll reveal with stagger
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const delay = parseInt(entry.target.dataset.revealDelay || '0');
            setTimeout(() => {
                entry.target.classList.add('revealed');
            }, delay);
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.scroll-reveal').forEach(el => {
    revealObserver.observe(el);
});
```

- [ ] **Step 4: Build and verify**

Run: `npm run build`
Open in browser, scroll down — tiles should animate into view with stagger delays.

- [ ] **Step 5: Commit**

```bash
git add resources/css/app.css resources/views/livewire/portfolio.blade.php
git commit -m "feat: add scroll reveal animation with stagger delays"
```

---

### Task 8: Add Splash Screen HTML + CSS

**Files:**
- Modify: `resources/css/app.css` (append)
- Modify: `resources/views/livewire/portfolio.blade.php`

- [ ] **Step 1: Add splash screen CSS**

Append to `resources/css/app.css`:

```css
/* ===== SPLASH SCREEN ===== */
.splash-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  z-index: 200;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  transition: opacity 1s ease, visibility 1s ease;
}

.dark .splash-overlay { background: #0a0a14; }
.splash-overlay { background: #f0f1f5; }

.splash-overlay.hidden {
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
}

/* Ripple container */
.ripple-container {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  overflow: hidden;
  pointer-events: none;
}

.ripple-ring {
  position: absolute;
  top: 50%; left: 50%;
  border-radius: 50%;
  transform: translate(-50%, -50%) scale(0);
  border: 2px solid currentColor;
  animation: rippleExpand 1.2s cubic-bezier(0, 0.5, 0.3, 1) forwards;
  pointer-events: none;
}

@keyframes rippleExpand {
  0% { transform: translate(-50%, -50%) scale(0); opacity: 0.6; border-width: 3px; }
  100% { transform: translate(-50%, -50%) scale(1); opacity: 0; border-width: 1px; }
}

/* Flash pulse */
.splash-flash {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  opacity: 0;
  pointer-events: none;
  z-index: 0;
}

.splash-flash.flash {
  animation: flashFade 0.4s ease-out forwards;
}

@keyframes flashFade {
  0% { opacity: 1; }
  100% { opacity: 0; }
}

/* Center greeting */
.splash-greet {
  font-size: 72px;
  font-weight: 900;
  letter-spacing: -3px;
  opacity: 0;
  transform: scale(0.3);
  min-height: 90px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  z-index: 1;
}

.splash-greet.pop-in {
  animation: greetPopIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.splash-greet.pop-out {
  animation: greetPopOut 0.2s ease-in forwards;
}

@keyframes greetPopIn {
  0% { opacity: 0; transform: scale(0.3); }
  60% { opacity: 1; transform: scale(1.08); }
  100% { opacity: 1; transform: scale(1); }
}

@keyframes greetPopOut {
  0% { opacity: 1; transform: scale(1); }
  100% { opacity: 0; transform: scale(1.4); filter: blur(6px); }
}

/* Name reveal */
.splash-name {
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
  position: relative;
  z-index: 1;
}

.splash-name.visible {
  opacity: 1;
  transform: translateY(0);
}

.splash-name h1 {
  font-size: 56px;
  font-weight: 900;
  letter-spacing: -2px;
}

.dark .splash-name h1 { color: white; }
.splash-name h1 { color: #1a1a2e; }
.splash-name h1 span { color: #f4a261; }

.splash-name .splash-title {
  color: #4361ee;
  font-size: 16px;
  font-weight: 600;
  margin-top: 6px;
  height: 24px;
}

/* Typing cursor */
.typing-cursor {
  display: inline-block;
  width: 2px;
  height: 1em;
  background: #4361ee;
  margin-left: 2px;
  animation: cursorBlink 1s step-end infinite;
  vertical-align: text-bottom;
}

@keyframes cursorBlink {
  50% { opacity: 0; }
}

/* CTA button */
.splash-cta {
  opacity: 0;
  transform: translateY(15px);
  transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
  margin-top: 32px;
  position: relative;
  z-index: 1;
}

.splash-cta.visible {
  opacity: 1;
  transform: translateY(0);
}

.splash-cta button {
  background: transparent;
  border: 1px solid rgba(255,255,255,0.12);
  color: white;
  padding: 16px 40px;
  border-radius: 100px;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  letter-spacing: 0.5px;
  transition: all 0.4s ease;
  position: relative;
  overflow: hidden;
}

:not(.dark) .splash-cta button {
  border-color: rgba(0,0,0,0.12);
  color: #1a1a2e;
}

.splash-cta button::before {
  content: '';
  position: absolute;
  top: 50%; left: 50%;
  width: 0; height: 0;
  background: rgba(67,97,238,0.12);
  border-radius: 50%;
  transform: translate(-50%, -50%);
  transition: width 0.6s ease, height 0.6s ease;
}

.splash-cta button:hover {
  border-color: rgba(67,97,238,0.5);
  box-shadow: 0 0 40px rgba(67,97,238,0.2);
  transform: translateY(-3px);
}

.splash-cta button:hover::before {
  width: 400px; height: 400px;
}

.splash-cta button span {
  position: relative;
  z-index: 1;
}

/* Tile choreographed entrance */
.tile-enter-hidden { opacity: 0; pointer-events: none; }

.tile-enter-hero { animation: tileFromLeft 0.8s cubic-bezier(0.16,1,0.3,1) 0.1s forwards; pointer-events: auto; }
.tile-enter-side { animation: tileFromRight 0.7s cubic-bezier(0.16,1,0.3,1) 0.4s forwards; pointer-events: auto; }
.tile-enter-bottom-1 { animation: tileFromBelow 0.6s cubic-bezier(0.16,1,0.3,1) 0.7s forwards; pointer-events: auto; }
.tile-enter-bottom-2 { animation: tileFromBelow 0.6s cubic-bezier(0.16,1,0.3,1) 0.9s forwards; pointer-events: auto; }
.tile-enter-stretch { animation: tileStretch 0.7s cubic-bezier(0.16,1,0.3,1) 1.2s forwards; pointer-events: auto; }

@keyframes tileFromLeft { 0%{opacity:0;transform:translateX(-80px) scale(0.9)} 100%{opacity:1;transform:translateX(0) scale(1)} }
@keyframes tileFromRight { 0%{opacity:0;transform:translateX(80px) scale(0.9)} 100%{opacity:1;transform:translateX(0) scale(1)} }
@keyframes tileFromBelow { 0%{opacity:0;transform:translateY(50px) scale(0.9)} 100%{opacity:1;transform:translateY(0) scale(1)} }
@keyframes tileStretch { 0%{opacity:0;transform:scaleX(0.4) scaleY(0.8)} 60%{opacity:1;transform:scaleX(1.03) scaleY(1)} 100%{opacity:1;transform:scaleX(1) scaleY(1)} }

@media (prefers-reduced-motion: reduce) {
  .splash-greet, .splash-name, .splash-cta { transition: none; animation: none; opacity: 1; transform: none; }
  .tile-enter-hidden { opacity: 1; pointer-events: auto; }
  .tile-enter-hero, .tile-enter-side, .tile-enter-bottom-1, .tile-enter-bottom-2, .tile-enter-stretch { animation: none; opacity: 1; }
}
```

- [ ] **Step 2: Add splash screen HTML**

In `portfolio.blade.php`, add the splash screen as the very first child inside the root `<div>`:

```html
<!-- Splash Screen -->
<div class="splash-overlay" id="splashOverlay"
     x-data="{ shown: true }"
     x-show="shown"
     x-cloak>
    <div class="ripple-container" id="rippleContainer"></div>
    <div class="splash-flash" id="splashFlash"></div>
    <div style="position:relative;z-index:1;text-align:center;">
        <div class="splash-greet" id="splashGreet"></div>
        <div class="splash-name" id="splashName">
            <h1>I'm Jay-ar<span>.</span></h1>
            <div class="splash-title" id="splashTyped"></div>
        </div>
        <div class="splash-cta" id="splashCta">
            <button onclick="enterPortfolio()">
                <span>Get to know me &rarr;</span>
            </button>
        </div>
    </div>
</div>
```

- [ ] **Step 3: Build to verify CSS compiles**

Run: `npm run build`
Expected: Build succeeds.

- [ ] **Step 4: Commit**

```bash
git add resources/css/app.css resources/views/livewire/portfolio.blade.php
git commit -m "feat: add splash screen HTML and CSS with ripple animations"
```

---

### Task 9: Add Splash Screen JavaScript

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php` (script section)

- [ ] **Step 1: Add splash sequence JS**

Add this in the `<script>` block of `portfolio.blade.php`:

```javascript
// ===== SPLASH SCREEN =====
const greetingsData = [
    { word: 'Kumusta', color: '#f4a261' },
    { word: 'Hello', color: '#4361ee' },
    { word: 'Hola', color: '#e63946' },
    { word: 'Bonjour', color: '#2ec4b6' },
    { word: 'こんにちは', color: '#a78bfa' },
    { word: '안녕하세요', color: '#f472b6' },
    { word: 'Ciao', color: '#34d399' },
    { word: 'Hallo', color: '#fbbf24' },
    { word: 'Olá', color: '#60a5fa' },
    { word: 'Merhaba', color: '#fb923c' },
    { word: 'Привет', color: '#a78bfa' },
    { word: '你好', color: '#4361ee' },
    { word: 'Namaste', color: '#2ec4b6' },
    { word: 'Aloha', color: '#f4a261' },
];

function initSplash() {
    // Skip splash if already seen this session
    if (sessionStorage.getItem('splashSeen')) {
        document.getElementById('splashOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
        revealTilesInstantly();
        return;
    }

    document.body.style.overflow = 'hidden';
    const greetEl = document.getElementById('splashGreet');
    const rippleContainer = document.getElementById('rippleContainer');
    const flashEl = document.getElementById('splashFlash');
    let cycleIndex = 0;

    function spawnRipple(color) {
        const size = 600 + Math.random() * 400;
        const ripple = document.createElement('div');
        ripple.className = 'ripple-ring';
        ripple.style.width = size + 'px';
        ripple.style.height = size + 'px';
        ripple.style.color = color;
        rippleContainer.appendChild(ripple);

        flashEl.classList.remove('flash');
        void flashEl.offsetWidth;
        flashEl.style.background = 'radial-gradient(circle at center, ' + color + '11 0%, transparent 60%)';
        flashEl.classList.add('flash');

        setTimeout(() => ripple.remove(), 1200);
    }

    function showGreet(index) {
        greetEl.textContent = greetingsData[index].word;
        greetEl.style.color = greetingsData[index].color;
        greetEl.classList.remove('pop-out');
        void greetEl.offsetWidth;
        greetEl.classList.add('pop-in');
        spawnRipple(greetingsData[index].color);
    }

    function hideGreet() {
        greetEl.classList.remove('pop-in');
        greetEl.classList.add('pop-out');
    }

    showGreet(0);

    const interval = setInterval(() => {
        hideGreet();
        setTimeout(() => {
            cycleIndex++;
            if (cycleIndex >= greetingsData.length) {
                clearInterval(interval);
                greetEl.classList.remove('pop-out');
                greetEl.textContent = 'Hello';
                greetEl.style.color = '#4361ee';
                void greetEl.offsetWidth;
                greetEl.classList.add('pop-in');
                spawnRipple('#4361ee');
                setTimeout(() => spawnRipple('#4361ee'), 200);
                setTimeout(() => spawnRipple('#4361ee'), 400);
                setTimeout(showNameReveal, 1200);
                return;
            }
            showGreet(cycleIndex);
        }, 200);
    }, 350);
}

function showNameReveal() {
    const greetEl = document.getElementById('splashGreet');
    greetEl.classList.remove('pop-in');
    greetEl.classList.add('pop-out');

    setTimeout(() => {
        greetEl.style.display = 'none';
        document.getElementById('splashName').classList.add('visible');
        setTimeout(startSplashTyping, 500);
    }, 300);
}

function startSplashTyping() {
    const text = "I build things for the web.";
    const el = document.getElementById('splashTyped');
    let i = 0;
    function typeChar() {
        if (i < text.length) {
            el.innerHTML = text.substring(0, i + 1) + '<span class="typing-cursor"></span>';
            i++;
            setTimeout(typeChar, 55);
        } else {
            el.innerHTML = text + '<span class="typing-cursor"></span>';
            setTimeout(() => document.getElementById('splashCta').classList.add('visible'), 300);
        }
    }
    typeChar();
}

function enterPortfolio() {
    sessionStorage.setItem('splashSeen', 'true');
    document.getElementById('splashOverlay').classList.add('hidden');
    document.body.style.overflow = 'auto';

    setTimeout(() => {
        const tileIds = [
            { id: 'section-home', cls: 'tile-enter-hero' },
            { id: 'section-about', cls: 'tile-enter-side' },
            { id: 'section-contact', cls: 'tile-enter-bottom-1' },
            { id: 'section-projects', cls: 'tile-enter-bottom-2' },
        ];
        tileIds.forEach(t => {
            const el = document.getElementById(t.id);
            if (el) el.classList.add(t.cls);
        });
    }, 400);
}

function revealTilesInstantly() {
    document.querySelectorAll('.tile-enter-hidden').forEach(el => {
        el.classList.remove('tile-enter-hidden');
    });
}

// Start splash on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(initSplash, 300);
});
```

- [ ] **Step 2: Add tile-enter-hidden class to major tiles**

In `portfolio.blade.php`, add `tile-enter-hidden` class to each major section tile (hero, about, contact, projects). This hides them until the splash reveals them.

- [ ] **Step 3: Build and verify full splash flow**

Run: `npm run build`
Open in browser — splash should play, greetings cycle with ripples, name reveals, CTA appears, click enters portfolio with choreographed tiles.

- [ ] **Step 4: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "feat: add splash screen JavaScript with greeting cycle and ripple sync"
```

---

### Task 10: Add Hero Typing Animation

**Files:**
- Modify: `resources/views/livewire/portfolio.blade.php`

- [ ] **Step 1: Replace static subtitle with typing target**

In the hero section of `portfolio.blade.php`, change the subtitle paragraph:

From:
```html
<p class="text-lg sm:text-xl lg:text-2xl mb-4 sm:mb-6 text-blue-200 dark:text-blue-300">
    Full Stack Web Developer
</p>
```

To:
```html
<p class="text-lg sm:text-xl lg:text-2xl mb-4 sm:mb-6 text-blue-200 dark:text-blue-300" id="heroTyped"></p>
```

- [ ] **Step 2: Add typing JS for hero subtitle**

Add to the script section:

```javascript
function startHeroTyping() {
    const text = "Full Stack Web Developer";
    const el = document.getElementById('heroTyped');
    if (!el) return;
    let i = 0;
    function typeChar() {
        if (i < text.length) {
            el.innerHTML = text.substring(0, i + 1) + '<span class="typing-cursor"></span>';
            i++;
            setTimeout(typeChar, 80);
        } else {
            el.innerHTML = text + '<span class="typing-cursor"></span>';
        }
    }
    typeChar();
}
```

Call `startHeroTyping()` after the splash enter animation completes (inside `enterPortfolio()`, after a 1.5s delay), or immediately if splash was skipped (inside `revealTilesInstantly()`).

- [ ] **Step 3: Build and verify**

Run: `npm run build`
Expected: After splash completes and tiles appear, the hero subtitle types out.

- [ ] **Step 4: Commit**

```bash
git add resources/views/livewire/portfolio.blade.php
git commit -m "feat: add hero subtitle typing animation"
```

---

### Task 11: Final Integration, Dark/Light Mode Testing, Cleanup

**Files:**
- All modified files

- [ ] **Step 1: Test dark mode**

Toggle dark mode — verify:
- Morphing blobs are visible (opacity 0.3)
- Film grain is subtle (opacity 0.03)
- Glass hover shows blobs through tiles
- Splash background matches dark bg (#0a0a14)
- All text is readable
- Cursor spotlight uses light variant

- [ ] **Step 2: Test light mode**

Toggle to light mode — verify:
- Background is #f0f1f5
- Morphing blobs are softer (opacity 0.15)
- Film grain is minimal (opacity 0.02)
- Glass hover still works but with higher opacity
- Splash background matches light bg
- CTA button border and text are dark
- All text is readable

- [ ] **Step 3: Test mobile**

Resize to mobile viewport — verify:
- Splash greeting text scales down (use responsive font sizes if needed)
- Tiles still layout correctly
- Mobile bottom nav still works
- No horizontal overflow from blobs

- [ ] **Step 4: Test splash sessionStorage skip**

Refresh page — splash should NOT play again (sessionStorage stores 'splashSeen'). Open new tab — splash should play again (sessionStorage is per-tab).

- [ ] **Step 5: Remove old hover scale from portfolio-card-colored**

In `resources/css/app.css`, remove or override the old hover transform:

```css
/* Remove this rule: */
.portfolio-card-colored:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}
```

Replace with the new glass hover behavior (already defined in Task 5).

- [ ] **Step 6: Final build and visual check**

Run: `npm run build`
Full walkthrough: splash → greetings → name → CTA → tiles fly in → scroll → hover glass + glow + tilt → dark/light toggle.

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "feat: complete portfolio visual redesign - final integration and cleanup"
```
