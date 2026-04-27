# Recipient Card Component - Quick Reference

## File: `recipient-selector.html`

This is a **standalone, pure HTML file** (no PHP/Blade) that you can open directly in any browser to see the interactive recipient selector in action.

---

## How to Open

**Method 1: Direct Browser**
Double-click the file, or drag it into your browser window.

**Method 2: Via URL**
```
file:///C:/xampp/htdocs/playhouse/resources/views/pages/admin-panel/sms-blast/mockup/recipient-selector.html
```

**Method 3: Via Laravel (if you want PHP data)**
Add route to `routes/web.php`:
```php
Route::get('/mockup/recipient-selector', function() {
    return view('pages.admin-panel.sms-blast.mockup.recipient-selector');
});
```

---

## What You'll See

### Main Interface

**Left Side - Recipient List**
- Search bar (filters cards by name or mobile number)
- Two tabs: "Parents" (8 sample) and "Guardians" (3 sample)
- Grid of recipient cards (2 columns on tablet, up to 3 on desktop)
- Each card shows:
  - Custom checkbox (left)
  - Avatar circle with first initial
  - Name (bold)
  - Mobile number with phone icon
  - Badge (Parent = blue, Guardian = purple)
- "Select All" checkbox below grid

**Right Side - Selected Sidebar**
- Sticky panel (stays visible while scrolling)
- Shows all selected recipients (avatars + name + mobile)
- Remove button (×) appears on hover
- Empty state message when nothing selected
- Cost calculator:
  - Count of selected
  - Cost per SMS: ₱1.50
  - Total estimated cost
- "Continue" button

---

## Interactive Features (Try These!)

✅ **Click a card** → Toggles selection (checkboxes update, card highlights)

✅ **Search** → Type "Maria" or "0917" to filter cards in real-time

✅ **Switch tabs** → Click "Parents" or "Guardians" to filter by type

✅ **Select All** → Check the "Select All Parents/Guardians" checkbox

✅ **Remove from sidebar** → Hover over selected card in right sidebar, click ×

✅ **Keyboard shortcut** → Press `Ctrl+A` (or `Cmd+A` on Mac) to select all visible

✅ **Cost updates live** → As you select/deselect, total cost recalculates

✅ **Toast notifications** → Light green popup in bottom-right corner

---

## Visual States

### Selected Card
- Border: Thick dark border
- Background: Light gray tint (lighter on dark mode)
- Checkbox: Filled with checkmark

### Unselected Card
- Border: Light gray (darker on dark mode)
- Background: White (dark gray in dark mode)
- Checkbox: Empty outline

### Hover Effect
- Card lifts slightly (translateY)
- Shadow appears
- Border becomes accent color

### Remove Button
- Hidden by default
- Appears on hover over selected card in sidebar
- Red color on hover

---

## Technical Details

### CSS Variables Used

```css
--color-primary: #1b1b18;        /* Black/dark gray */
--color-primary-light: #2d2d26;  /* Lighter for hover */
--color-accent: #fcfcf9;         /* Off-white background */
```

**Dark mode override** (when `body.dark` class added):
```css
--color-primary: #EDEDEC;        /* Off-white */
--color-primary-light: #d0d0ce;
--color-accent: #161615;         /* Near-black */
```

### JavaScript State

```javascript
let selected = new Set([
    'M06-00001',  // Maria Santos
    'M06-00003',  // Ana Reyes
    'M06G-00001'  // Elena Cruz (Guardian)
]);

let currentTab = 'parents';  // or 'guardians'
```

The `Set` ensures no duplicates and O(1) lookups.

### Key Functions

| Function | Purpose |
|----------|---------|
| `render()` | Clears & rebuilds entire grid + sidebar |
| `toggle(id)` | Adds/removes from selected Set, then renders |
| `switchTab(tab)` | Changes currentTab, re-renders grid |
| `renderSelectedList()` | Updates sidebar HTML |
| `toggleSelectAll()` | Select all visible cards of current type |
| `showToast(msg)` | Shows floating notification |

---

## Mock Data

**Parents (8):**
| ID | Name | Mobile |
|----|------|--------|
| M06-00001 | Maria Santos | 0917-123-4567 |
| M06-00002 | Juan Dela Cruz | 0905-987-6543 |
| M06-00003 | Ana Reyes | 0922-555-1234 |
| M06-00004 | Roberto Lim | 0932-111-2222 |
| M06-00005 | Sophia Garcia | 0945-333-4444 |
| M06-00006 | Luis Tan | 0912-666-7777 |
| M06-00007 | Carmen Diaz | 0938-222-3333 |
| M06-00008 | Antonio Reyes | 0906-444-5555 |

**Guardians (3):**
| ID | Name | Mobile |
|----|------|--------|
| M06G-00001 | Elena Cruz | 0918-777-8888 |
| M06G-00002 | Pedro Mendoza | 0921-999-0000 |
| M06G-00003 | Carmen Villanueva | 0935-123-4567 |

**Pre-selected:** Maria Santos (M06-00001), Ana Reyes (M06-00003), Elena Cruz (M06G-00001)

---

## Integration Checklist

When converting to real Laravel/Livewire:

- [ ] Replace `parents` array with `@json($parents)` from controller
- [ ] Replace `guardians` array with `@json($guardians)`
- [ ] Replace `selected` Set with Livewire property: `@entangle('selectedIds')`
- [ ] Add `wire:click` to cards: `wire:click="toggle('{{ $p['id'] }}')`
- [ ] Add `wire:model` to checkboxes: `wire:model="selectedIds"`
- [ ] Remove `render()` function (Livewire auto-renders)
- [ ] Connect `proceed()` to next step: `$this->step = 2`
- [ ] Add validation in component: `rules()[ 'selectedIds' => 'required|array|min:1' ]`
- [ ] Persist selection to session: `session(['sms_recipients' => $this->selectedIds])`
- [ ] Load recipients from database in `mount()` or `render()`

---

## Common Questions

**Q: How do I change the cost per SMS?**  
A: Edit line 260 in the `<script>`: `const costPerSms = 1.50;` and line 304 display.

**Q: How to show phone icon only for certain types?**  
A: In the card HTML template (inside `render()`), conditionally add `<i class="fas fa-phone"></i>`.

**Q: How to add more search fields?**  
A: Modify the `search` event listener to check additional properties (e.g., `p.type`).

**Q: How to persist selection across page reloads?**  
A: Store `selectedIds` in `localStorage`:
```javascript
localStorage.setItem('smsSelected', JSON.stringify(Array.from(selected)));
```

**Q: How to disable a card (make it unselectable)?**  
A: Add `pointer-events: none; opacity: 0.5;` CSS class when rendering.

---

## Component Variants

### Compact Version (Smaller Avatars)
Change `.avatar` to `.avatar avatar-xs` (32px instead of 40px).

### Horizontal Layout (List Mode)
```css
.recipient-card {
    display: flex;
    align-items: center;
}
```

### Large Cards (More Info)
Add more fields after mobile:
```html
<div class="text-xs text-gray-500">Relationship: Father</div>
<div class="text-xs text-gray-500">Birthday: Jan 15</div>
```

---

## Files Summary

```
📁 mockup/
├── create.blade.php                  ← Full wizard (Blade)
├── index.blade.php                   ← History list (Blade)
├── details.blade.php                 ← Blast details (Blade)
├── templates.blade.php               ← Template manager (Blade)
├── recipient-card-demo.blade.php     ← Isolated component demo (Blade)
└── recipient-selector.html           ← ⭐ STANDALONE PURE HTML (this file!)
```

**Start here:** Open `recipient-selector.html` for the quickest, simplest demo.

---

## Next Actions

1. **Review** the mockup with stakeholders
2. **Decide** on features (search? filters? pagination?)
3. **Choose** framework (Livewire recommended for Laravel)
4. **Implement** component using mockup as reference
5. **Test** with real database (1000+ recipients)
6. **Optimize** with virtual scroll if needed

---

**Need help?**  
See main documentation: `../README.md` or `../../../../docs/SMS-BLAST-FEATURE.md`
