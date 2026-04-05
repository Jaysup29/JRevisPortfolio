# Portfolio Visual Redesign — Design Spec

## Overview

Enhance the existing Nokia Lumia / Windows 8 Metro tile portfolio with futuristic, elegant visual effects while preserving the grid layout, section structure, and all existing functionality. Must work in both dark and light mode.

## 1. Color Palette — Modernized Muted Tones

Replace current saturated colors with sophisticated muted versions. Same hues, refined feel.

| Element | Current | New |
|---------|---------|-----|
| Background | `#f3f4f6` / `#111827` | `#0a0a14` (dark) / `#f0f1f5` (light) |
| Hero Dark | `#1e3a5f` | `#1a1a2e` |
| Blue (About) | `#4a90e2` | `#4361ee` |
| Green (Contact) | `#2dd4bf` | `#2ec4b6` |
| Red (Projects) | `#ef4444` | `#e63946` |
| Yellow → Amber | `#fbbf24` | `#f4a261` |

All colors must have light-mode equivalents that maintain contrast and readability.

## 2. Introduction Splash Screen

Full-screen splash that plays before the portfolio reveals. Sequence:

### Phase 1 — Multilingual Greetings (≈5 seconds)
- Greetings cycle rapidly at center: Kumusta, Hello, Hola, Bonjour, こんにちは, 안녕하세요, Ciao, Hallo, Olá, Merhaba, Привет, 你好, Namaste, Aloha
- Each greeting is large (72px), bold (900 weight), centered both vertically and horizontally
- Each greeting has a unique color from the palette
- Pop-in animation: scale from 0.3 → 1 with overshoot
- Pop-out animation: scale to 1.4 with blur(6px) fade
- **Synced water drop ripple**: each greeting spawns a circular ripple ring in its matching color that expands outward from center and fades
- Subtle flash pulse on the background with each drop
- Final greeting lands on "Hello" (#4361ee) with 3 rapid ripples for emphasis

### Phase 2 — Name Reveal
- "Hello" fades out with zoom+blur
- "I'm Jay-ar." slides up and fades in (56px, 900 weight, period in amber)
- Typing animation: "I build things for the web." types out character by character (55ms per char, blue cursor blink)

### Phase 3 — Call to Action
- "Get to know me →" button fades up
- Pill-shaped, transparent background, subtle white border
- Hover: border turns blue, radial glow expands from center, lifts up 3px
- Click triggers portfolio reveal

### Phase 4 — Portfolio Reveal
- Splash overlay fades out (1s ease)
- Tiles enter choreographed:
  - Hero: slides from left (0.8s, delay 0.1s)
  - About: slides from right (0.7s, delay 0.4s)
  - Contact: rises from below (0.6s, delay 0.7s)
  - Projects: rises from below (0.6s, delay 0.9s)
  - Social: stretches horizontally (0.7s, delay 1.2s)
- All use cubic-bezier(0.16, 1, 0.3, 1) easing

### Splash — Technical Notes
- Body overflow: hidden during splash, auto after reveal
- Splash uses fixed positioning, z-index 200
- Greetings start with Kumusta (Filipino — user's native language)
- Interval: 350ms per greeting, 200ms transition gap
- No skip button — user must watch then click CTA
- Splash only shows on first page load (use sessionStorage to track)

## 3. Background Effects

### Morphing Organic Blobs
- 3 blobs with gradient fills, 60px blur, 0.3 opacity
- Blob 1: blue→teal gradient, top-left, 450px, 12s animation
- Blob 2: red→amber gradient, bottom-right, 380px, 14s animation
- Blob 3: teal→blue gradient, center, 300px, 16s animation
- Each blob morphs border-radius and translates/rotates continuously
- Fixed position, z-index 0

### Film Grain Overlay
- SVG-based fractalNoise texture
- Fixed position, z-index 100 (above content), pointer-events: none
- Opacity: 0.03 (dark mode) / 0.02 (light mode)
- 256px background-size, repeating

### Light Mode Adaptation
- Blobs: reduce opacity to 0.15, shift to pastel versions of same colors
- Film grain: reduce to 0.02 opacity
- Background: `#f0f1f5` instead of `#0a0a14`

## 4. Tile Hover Effects

### Default State (no hover)
- Solid colored backgrounds (Metro flat style)
- Border: 1px solid rgba(255,255,255,0.04) dark / rgba(0,0,0,0.04) light
- No shadows, no glow

### Hover State — Glass Reveal + Inner Glow
When cursor hovers directly on a tile:

**Glassmorphism reveal:**
- Background transitions from solid to semi-transparent (opacity 0.55-0.65)
- `backdrop-filter: blur(20px)` reveals morphing blobs through the tile
- Border brightens to rgba(255,255,255,0.25) with tile-colored tint
- Colored box-shadow glow: `0 0 50px {color}20`
- Inner highlight: `inset 0 1px 0 rgba(255,255,255,0.08)`
- Transition: 0.5s ease on all properties

**Inner glow at cursor:**
- A 300px radial gradient follows the cursor position inside the tile
- Hero tiles: `rgba(67,97,238,0.2)` center → transparent
- Colored tiles: `rgba(255,255,255,0.15)` center → transparent
- Opacity 0 by default, 1 on hover
- Position updated via mousemove event on tile

**3D tilt:**
- Tile tilts toward cursor: max ±6° on both axes
- `perspective(800px)` on transform
- translateY(-2px) lift on hover
- Smooth reset on mouseleave

### Light Mode Adaptation
- Glass: slightly higher opacity (0.7-0.8) for readability
- Inner glow: reduce to rgba(0,0,0,0.06)
- Border: rgba(0,0,0,0.1) on hover
- Box-shadow glow: softer, lower opacity

## 5. Scroll Reveal Animation

Tiles animate into view on scroll (not just page load):
- Start: opacity 0, translateY(30px)
- End: opacity 1, translateY(0)
- Duration: 0.6s ease
- Stagger: each tile delayed by 0.1-0.15s after previous
- Use Intersection Observer, trigger once per element
- Threshold: 0.1 (trigger when 10% visible)

## 6. Hero Section — Typing Animation

- "Full Stack Web Developer" types out on page load (after splash)
- 80ms per character
- Blinking cursor: 2px wide, blue (#4361ee), 1s step-end blink
- Cursor remains visible after typing completes

## 7. Cursor Spotlight

- 500px diameter radial gradient follows cursor across entire page
- Dark mode: `rgba(255,255,255,0.05)` center → `rgba(67,97,238,0.03)` mid → transparent
- Light mode: `rgba(0,0,0,0.02)` center → transparent
- Fixed position, pointer-events: none, z-index 1
- Purely ambient — does NOT trigger tile effects (hover-only for tiles)

## 8. Preserved Elements

These must NOT change:
- Nokia Lumia / Windows 8 grid layout (mobile-grid system)
- Section structure: Hero, About (with auto-rotate), Tech Carousel, Contact, Projects, Social
- Mobile bottom navigation bar (floating pill design)
- Dark/light mode toggle functionality
- All Livewire/Alpine.js component behavior
- Responsive breakpoints (sm, md, lg, xl)
- Database-driven content (skills, projects)

## 9. Performance Considerations

- All animations use CSS transforms/opacity (GPU-accelerated)
- Morphing blobs: CSS-only keyframe animations (no JS)
- Film grain: SVG filter, not canvas
- Inner glow: lightweight mousemove handler, only active during hover
- Splash animations: removed from DOM after portfolio reveal
- `prefers-reduced-motion`: disable morphing blobs, scroll reveal, typing animation; keep static layout

## 10. Files to Modify

| File | Changes |
|------|---------|
| `resources/css/app.css` | New color variables, glass hover classes, scroll reveal, morphing blob keyframes, film grain |
| `resources/views/livewire/portfolio.blade.php` | Splash screen HTML, inner glow elements, section IDs for scroll reveal, updated color classes |
| `resources/views/components/layouts/guest.blade.php` | Morphing blob background, grain overlay, spotlight element |
| `resources/js/app.js` | Inner glow mousemove, scroll reveal observer, splash sequence logic, typing animation |
| `tailwind.config.js` | Updated color palette values |
