# SMS Blast Mockup Pages

Complete UI mockups for the SMS Blast feature with **no backend dependencies**. All pages are static HTML with mock data and JavaScript interactivity.

---

## Files Created

```
resources/views/pages/admin-panel/sms-blast/mockup/
├── create.blade.php                     # Main create blast wizard (COMPLETE)
├── index.blade.php                      # Blast history list page
├── details.blade.php                    # Single blast details
├── templates.blade.php                  # Template management
├── recipient-card-demo.blade.php        # Focused recipient card component demo
└── README.md                            # This file
```

---

## Quick Start

### View in Browser (Quickest)

Open directly:
```
file:///C:/xampp/htdocs/playhouse/resources/views/pages/admin-panel/sms-blast/mockup/create.blade.php
```

Or any other `.blade.php` file in the `mockup/` folder.

**Note:** Since these are static HTML files, some JavaScript functionality (like dark mode toggle) may not work when opened directly from filesystem. For full experience, serve through a local web server.

### View via Laravel (Recommended)

Create a test route:

**routes/web.php** (temporary):
```php
Route::get('/mockup/sms-blast', function () {
    return view('pages.admin-panel.sms-blast.mockup.create');
})->name('mockup.smsblast.create');
```

Then visit: `http://localhost:8000/mockup/smsblast`

---

## Mockup Pages Overview

### 1. `create.blade.php` (Main Feature Mockup)

**What it shows:** Complete SMS blast creation wizard with:
- ✅ **Step 1: Recipient Selection** (Left side)
  - Search bar with live filtering
  - Parents/Guardians tabs with counts
  - Grid of recipient cards with checkboxes
  - Select All checkbox
  - Clear selection button
  - Real-time selection count

- ✅ **Step 2: Selected Recipients Sidebar** (Right side)
  - Sticky sidebar with avatar list
  - Shows name, mobile, type for each
  - Remove button (×) on hover
  - Empty state when nothing selected
  - Cost calculator (₱1.50 × count)
  - "Continue to Message" button

**Interactive Features:**
- ✅ Click card to select/deselect
- ✅ Search by name or mobile (live)
- ✅ Filter by type (Parents vs Guardians tabs)
- ✅ Ctrl+A to select all visible
- ✅ Remove from sidebar with fade animation
- ✅ Cost updates in real-time
- ✅ Success toast notifications
- ✅ Keyboard shortcuts support

**Mock Data:**
- 8 Parent recipients
- 3 Guardian recipients
- 3 pre-selected by default

**Colors:**
- Parents: Blue theme (#dbeafe badge, #3b82f6 avatar)
- Guardians: Purple theme (#e9d5ff badge, #9333ea avatar)

---

### 2. `index.blade.php` - Blast History

**What it shows:**
- Stats cards (Total, Sent, Scheduled, Failed)
- Search + filter bar
- Table of all blasts with:
  - ID, Title, Message preview (truncated)
  - Recipient counts (sent/total)
  - Status badges (Sent, Scheduled, Draft, Failed)
  - Progress bars (visual indicator)
  - Action buttons (view, resend, delete)
- Pagination controls
- Quick link to templates

**Sample Data Rows:**
1. #24 Weekly Checkout Reminder (Sent 100%)
2. #23 Birthday Wishes (Scheduled 0%)
3. #22 Overtime Notification (Draft 0%)
4. #21 System Maintenance (Failed 98%)
5. #20 Weekend Promo (Sent 100%)

---

### 3. `details.blade.php` - Single Blast

**What it shows:**
- Header with back link and blast title
- Status badge (Sent/Scheduled/Failed)
- 5 stats cards (Total, Sent, Failed, Pending, Success Rate)
- Message content block (monospace font)
- Cost calculation
- Recipient table with pagination
- Filter buttons (All/Sent/Failed)
- Sidebar with blast info:
  - Created/Sent times
  - Total cost
  - Recipient breakdown (Parents vs Guardians chart)
  - Action buttons (Export CSV, Print, Resend, Duplicate, Delete)

---

### 4. `templates.blade.php` - Template Management

**What it shows:**
- Grid of 6 template cards:
  1. Birthday Greetings (Active) 🎂
  2. Time is Almost Up (Active) ⏰
  3. Overtime (Active) ⚠️
  4. Check Out (Active) ✓
  5. Weekend Promo (Inactive) %
  6. Create New (+) dashed card

Each card shows:
- Icon in colored circle
- Template name
- Description
- Message preview (truncated, monospace)
- Active/Inactive badge
- Edit + Delete/Activate buttons

- Info box listing all template variables:
  - `{child_name}`
  - `{parent_name}`
  - `{time_remaining}`
  - `{minutes_over}`
  - `{checkout_time}`

---

### 5. `recipient-card-demo.blade.php` - Focused Component Demo

**What it shows:** Standalone demo of just the recipient card component:

- Example 1: Selected Parent card
- Example 2: Unselected Guardian card
- Example 3: Grid of 6 mixed cards (3 selected, 3 not)
- Interactive demo card that toggles on checkbox click

**Purpose:** Use this to:
- Review card styling in isolation
- Test hover states
- See selected/unselected states side-by-side
- Present to designers/developers

---

## Component Anatomy

### Recipient Card HTML Structure

```html
<div class="recipient-card [selected]">
    <!-- Checkbox -->
    <div class="checkbox-wrapper">
        <input type="checkbox" [checked]>
        <div class="custom-checkbox">
            <svg>✓</svg>
        </div>
    </div>

    <!-- Avatar -->
    <div class="avatar bg-blue-100 text-blue-600">
        {{ strtoupper(substr($name, 0, 1)) }}
    </div>

    <!-- Info -->
    <div class="flex-1 min-w-0">
        <div class="font-medium text-sm truncate">
            {{ $name }}
        </div>
        <div class="text-xs text-gray-500">
            <i class="fas fa-phone"></i> {{ $mobile }}
        </div>
        <div class="mt-2">
            <span class="badge badge-parent">Parent</span>
            <!-- OR -->
            <span class="badge badge-guardian">Guardian</span>
        </div>
    </div>

    <!-- Remove Button (visible on hover) -->
    <button class="text-gray-400 hover:text-red-500">
        <i class="fas fa-times"></i>
    </button>
</div>
```

### CSS Classes

| Class | Purpose |
|-------|---------|
| `recipient-card` | Base card with border, padding, hover effects |
| `selected` | Highlights when checkbox is checked |
| `avatar` | Circle with initials, colored by type |
| `badge-parent` | Blue badge for parents |
| `badge-guardian` | Purple badge for guardians |
| `checkbox-wrapper` | Custom checkbox container |
| `fade-in` | Animation for new items |

### JavaScript Functions

**Available in `create.blade.php`:**

```javascript
toggleRecipient(card, id, type, name, mobile)  // Toggle card selection
removeFromSelection(id, button)                // Remove from sidebar
updateSelectedCount()                          // Update counters
refreshSelectedList()                          // Re-render sidebar list
filterByType('parents' | 'guardians')          // Switch tabs
toggleSelectAll()                              // Select/deselect all visible
searchRecipients(query)                        // Live search
clearSelection()                               // Clear all
proceedToMessage()                             // Continue to step 2
showToast(message, type)                       // Show notification
```

---

## Customization Guide

### Change Colors

Edit `<style>` block variables:

```css
:root {
    --color-primary: #1b1b18;      /* Main brand color */
    --color-primary-light: #2d2d26; /* Hover color */
}

/* Parent badge */
.badge-parent {
    background: #dbeafe;  /* Change to your brand blue */
    color: #1e40af;
}

/* Guardian badge */
.badge-guardian {
    background: #e9d5ff;  /* Change to your brand purple */
    color: #6b21a8;
}
```

### Change Avatar Colors

```css
.avatar {
    /* For parents - default blue */
}

/* Override per type */
.parent-avatar {
    background: #dbeafe;
    color: #1e40af;
}
.guardian-avatar {
    background: #e9d5ff;
    color: #6b21a8;
}
```

### Add More Recipient Types

Add new badge styles:
```css
.badge-staff {
    background: #fef3c7;
    color: #92400e;
}
```

Update card template:
```html
@if($recipient['type'] === 'staff')
    <span class="badge badge-staff">Staff</span>
@endif
```

### Change Card Layout

Current layout: Horizontal (checkbox + avatar + info + button)

For vertical layout:
```html
<div class="recipient-card">
    <div class="flex items-start gap-3">
        <!-- checkbox left -->
    </div>
</div>
```

Change to:
```html
<div class="recipient-card">
    <div class="flex flex-col items-center text-center">
        <!-- avatar top -->
        <!-- info below -->
    </div>
</div>
```

---

## Responsive Breakpoints

Current grid uses Tailwind defaults:

```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- On mobile: 1 column -->
    <!-- On tablet (md≥768px): 2 columns -->
    <!-- On desktop (lg≥1024px): 3 columns -->
</div>
```

To change:
- 1 column: `grid-cols-1` (mobile only)
- 2 columns: `md:grid-cols-2`
- 4 columns: `lg:grid-cols-4`

---

## Data Structure (Mock)

### Recipient Array Format

```php
$parents = [
    [
        'id' => 'M06-00001',      // Database ID (d_code)
        'name' => 'Maria Santos', // Full name
        'mobile' => '0917-123-4567', // Mobile number
        'type' => 'parent'        // Type identifier
    ],
    // ... more
];

$guardians = [
    [
        'id' => 'M06G-00001',
        'name' => 'Elena Cruz',
        'mobile' => '0918-777-8888',
        'type' => 'guardian'
    ],
];

$selectedIds = ['M06-00001', 'M06-00003', 'M06G-00001']; // Already selected
```

**In your Livewire/Controller:**
```php
public function render()
{
    $parents = M06::whereNotNull('mobileno')
        ->select('d_code as id', 'firstname', 'lastname', 'mobileno')
        ->get()
        ->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->firstname . ' ' . $p->lastname,
                'mobile' => $p->mobileno,
                'type' => 'parent'
            ];
        });

    return view('livewire.sms-blast.create', [
        'parents' => $parents,
        'selectedIds' => $this->selectedRecipients
    ]);
}
```

---

## Integration Steps

### Step 1: Copy HTML Structure

Take the recipient card HTML from `recipient-card-demo.blade.php` and paste into your Livewire component view.

### Step 2: Wire Up JavaScript

Replace mock data with Livewire properties:

**From Mock:**
```javascript
const allRecipients = @json($allRecipients);
let selectedIds = new Set(@json($selectedIds));
```

**To Livewire:**
```javascript
let selectedIds = @entangle('selectedRecipientIds').defer;
```

**Update functions to emit events:**
```javascript
function toggleRecipient(card, id, type, name, mobile) {
    if (selectedIds.has(id)) {
        selectedIds.delete(id);
    } else {
        selectedIds.add(id);
    }
    
    // Emit to Livewire
    @this.call('toggleRecipient', Array.from(selectedIds));
    
    updateUI();
}
```

### Step 3: Add Validation

In your controller/component:
```php
protected $rules = [
    'selectedRecipientIds' => 'required|array|min:1',
    'selectedRecipientIds.*' => 'string|exists:m06,d_code',
];
```

### Step 4: Persist Selection

Store selection in session or database:
```php
session(['sms_blast_recipients' => $this->selectedRecipientIds]);
```

---

## Browser Testing

Test in these browsers:
- ✅ Chrome 120+
- ✅ Firefox 119+
- ✅ Safari 17+
- ✅ Edge 120+

Test on devices:
- ✅ Desktop (1920×1080, 1366×768)
- ✅ Tablet (768×1024)
- ✅ Mobile (375×667, 414×896)

---

## Accessibility Notes

**Current features:**
- ✅ Checkboxes are focusable (tab key)
- ✅ Click labels to toggle
- ✅ Color contrast meets WCAG AA
- ✅ Semantic HTML structure

**Future improvements:**
- Add `aria-label` to buttons
- Add `role="checkbox"` for custom checkboxes
- Implement keyboard navigation (↑↓←→ to navigate cards)
- Add screen reader announcements for counts

---

## Performance

**Optimizations already in place:**
- Event delegation where possible
- Debounced search (300ms)
- Cursor-based pagination (in real app)
- Minimal re-renders

**For 1000+ recipients:**
- Implement virtual scrolling
- Load More instead of pagination
- Lazy load images (if any)

---

## File Sizes

```
create.blade.php           ~23 KB (HTML + inline CSS + JS)
index.blade.php            ~7 KB
details.blade.php          ~8 KB
templates.blade.php        ~6 KB
recipient-card-demo.php    ~5 KB
```

Lightweight - no external dependencies except Tailwind CDN + Font Awesome.

---

## Next Steps for Implementation

1. ✅ **Mockups Complete** - All pages designed
2. **Create Livewire Components** - Convert to Livewire
   ```bash
   php artisan make:livewire SmsBlastCreate
   php artisan make:livewire SmsBlastIndex
   ```
3. **Build API Endpoints** - If using Inertia.js
4. **Connect Models** - Replace mock arrays with Eloquent
5. **Add Validation** - Server-side rules
6. **Implement Queue** - For actual SMS sending
7. **Add Darkness Toggle** - If needed
8. **Write Tests** - Feature tests for flows

---

## Questions?

Refer to:
- Main documentation: `docs/SMS-BLAST-FEATURE.md`
- Backend models: `app/Models/SmsTemplate.php`, `SmsBlast.php`
- Service: `app/Services/SmsBlastService.php`
- Controller: `app/Http/Controllers/SmsBlastController.php`

---

**Status:** ✅ Mockup Complete  
**Last Updated:** April 27, 2026
