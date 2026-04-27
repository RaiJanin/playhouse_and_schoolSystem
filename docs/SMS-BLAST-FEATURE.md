# SMS Blast Feature

Bulk SMS messaging system for sending messages to multiple parents/guardians with scheduling and predefined templates.

---

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Database Schema](#database-schema)
4. [Setup & Installation](#setup--installation)
5. [Configuration](#configuration)
6. [Usage Guide](#usage-guide)
7. [API Reference](#api-reference)
8. [Templates](#templates)
9. [Scheduling](#scheduling)
10. [Troubleshooting](#troubleshooting)

---

## Overview

### Purpose
The SMS Blast feature allows administrators to:
- Send bulk SMS messages to all registered parents or guardians
- Schedule messages for future delivery
- Use predefined message templates (Birthday, Timeout, Overtime, Check-out)
- Track delivery status (sent, failed, pending)
- View detailed delivery reports

### Key Features
- **Bulk Recipient Selection**: Multi-select from all parents or guardians
- **Message Templates**: Predefined and custom messages with variable substitution
- **Scheduling**: Send immediately or schedule for later
- **Status Tracking**: Real-time tracking of sent/failed/pending messages
- **Audit Trail**: Complete history of all blasts with recipient details
- **Error Handling**: Failed messages logged with error details

### Technology Stack
- **Backend**: Laravel 10, PHP 8+
- **Database**: MySQL
- **Queue**: Database driver (for scheduled blasts)
- **SMS Provider**: iSMS Malaysia API
- **Authentication**: Laravel Breeze (auth middleware)

---

## Architecture

### Component Diagram

```
┌─────────────────┐
│  Admin Browser  │
│   (Blade UI)    │
└────────┬────────┘
         │
         ▼
┌─────────────────────┐
│ SmsBlastController  │
│  (HTTP Handlers)    │
└─────────┬───────────┘
          │
          ▼
┌─────────────────────┐
│  SmsBlastService    │
│ (Business Logic)    │
└─────────┬───────────┘
          │
    ┌─────┴─────┐
    ▼           ▼
┌──────┐    ┌──────────┐
│ SMS  │    │  Queue   │
│ API  │    │  Worker  │
└──────┘    └──────────┘
```

### File Structure

```
app/
├── Console/
│   └── Commands/
│       └── ProcessScheduledSmsBlasts.php    # Scheduled command
├── Http/
│   └── Controllers/
│       ├── SmsBlastController.php           # Blast management
│       └── SmsTemplateController.php        # Template management
├── Models/
│   ├── SmsTemplate.php                       # Template model
│   ├── SmsBlast.php                          # Blast model
│   └── SmsBlastRecipient.php                 # Recipient pivot model
├── Services/
│   ├── SendSmsService.php                    # Low-level SMS sender (existing)
│   └── SmsBlastService.php                   # Blast orchestrator (new)
└── ...

database/
├── migrations/
│   ├── 2026_04_27_000000_create_sms_templates_table.php
│   ├── 2026_04_27_000001_create_sms_blasts_table.php
│   └── 2026_04_27_000002_create_sms_blast_recipients_table.php
└── ...

resources/views/pages/admin-panel/
├── sms-blast/
│   ├── index.blade.php       # Blast list
│   ├── create.blade.php      # Create form
│   └── show.blade.php        # Blast details
└── sms-templates/
    ├── index.blade.php       # Template list
    ├── create.blade.php      # Create form
    └── edit.blade.php        # Edit form

routes/
└── admin-panel.php           # Admin routes (SMS routes added)

```

---

## Database Schema

### Table: `sms_templates`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint (PK) | Auto-increment ID |
| name | varchar(255) | Template name (unique) |
| slug | varchar(255) | URL-friendly slug (unique) |
| message | text | SMS content with variables |
| description | text | Optional description |
| is_active | boolean | Enable/disable template |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** `name` (unique), `slug` (unique), `is_active`

**Sample Data:**
```sql
INSERT INTO sms_templates (name, slug, message, description, is_active) VALUES
('Birthday Greetings', 'birthday-greetings',
 'Happy Birthday {child_name}! From all of us at Mimo Play Cafe. Enjoy your special day!',
 'Sent on child''s birthday', 1),
('Time is Almost Up', 'time-is-almost-up',
 'Friendly reminder: {child_name}''s session ends in {time_remaining} minutes. Please prepare for checkout.',
 '10-minute warning before timeout', 1),
('Overtime', 'overtime',
 'Notice: {child_name} has exceeded their playtime by {minutes_over} minutes. Additional charges may apply.',
 'When child is overdue', 1),
('Check Out', 'check-out',
 'Thank you for visiting Mimo Play Cafe! {child_name}''s checkout time is {checkout_time}. Please proceed to the counter.',
 'Checkout reminder', 1);
```

---

### Table: `sms_blasts`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint (PK) | Auto-increment ID |
| title | varchar(255) | Admin-defined title |
| message | text | SMS content sent |
| status | enum | `draft`, `scheduled`, `sending`, `sent`, `failed`, `cancelled` |
| scheduled_at | timestamp | When to send (null = immediate) |
| sent_at | timestamp | When blast was actually processed |
| total_recipients | integer | Total count of recipients |
| sent_count | integer | Number successfully sent |
| failed_count | integer | Number failed |
| error_message | text | Error if status = failed |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** `status`, `scheduled_at`

---

### Table: `sms_blast_recipients`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint (PK) | Auto-increment ID |
| sms_blast_id | bigint (FK) → `sms_blasts.id` |
| recipient_type | enum | `parent` or `guardian` |
| recipient_id | varchar(50) | `d_code` (parent) or `d_code_g` (guardian) |
| mobile_number | varchar(20) | Mobile number at time of send |
| name | varchar(255) | Recipient name for display |
| status | enum | `pending`, `sent`, `failed` |
| sent_at | timestamp | When this recipient received SMS |
| error_message | text | Error if status = failed |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** `sms_blast_id`, `status` (composite: sms_blast_id + status)

**Foreign Keys:**
- `sms_blast_id` → `sms_blasts.id` ON DELETE CASCADE

---

## Setup & Installation

### Step 1: Install Dependencies

No additional composer packages required. Uses built-in Laravel components:
- Eloquent ORM
- Queue (database driver)
- Artisan Console

### Step 2: Run Migrations

```bash
php artisan migrate
```

This creates the three new tables.

### Step 3: Seed Predefined Templates

Create database seeder:

**database/seeders/SmsTemplateSeeder.php**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsTemplate;

class SmsTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Birthday Greetings',
                'slug' => 'birthday-greetings',
                'message' => "Happy Birthday {child_name}!\n\nFrom all of us at Mimo Play Cafe. Enjoy your special day!\n\n- Mimo Team",
                'description' => 'Sent on child\'s birthday',
                'is_active' => true,
            ],
            [
                'name' => 'Time is Almost Up',
                'slug' => 'time-is-almost-up',
                'message' => "FRIENDLY REMINDER FROM MIMO PLAY CAFE\n\n{child_name}'s session will end in {time_remaining} minutes.\n\nPlease prepare for checkout.",
                'description' => '10-minute warning before timeout',
                'is_active' => true,
            ],
            [
                'name' => 'Overtime',
                'slug' => 'overtime',
                'message' => "NOTICE: OVERTIME\n\n{child_name} has exceeded playtime by {minutes_over} minutes.\n\nAdditional charges may apply.\n\nPlease check out immediately.",
                'description' => 'When child is overdue',
                'is_active' => true,
            ],
            [
                'name' => 'Check Out',
                'slug' => 'check-out',
                'message' => "Thank you for visiting Mimo Play Cafe!\n\n{child_name}'s checkout time is {checkout_time}.\n\nPlease proceed to the counter.\n\nWe hope you enjoyed your visit!",
                'description' => 'Checkout reminder',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            SmsTemplate::firstOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
```

Register in `DatabaseSeeder.php`:
```php
public function run(): void
{
    $this->call([
        // ... other seeders
        SmsTemplateSeeder::class,
    ]);
}
```

Run seeder:
```bash
php artisan db:seed --class=SmsTemplateSeeder
```

### Step 4: Configure Environment Variables

Add to `.env` file:

```env
# SMS Configuration (iSMS Malaysia)
SMS_USERNAME=nlendio
SMS_PASSWORD=noister123$
SMS_SENDER_ID=JDEN SMS

# Queue Configuration (optional - defaults to database)
QUEUE_CONNECTION=database
```

### Step 5: Schedule the Command

Edit `app/Console/Kernel.php`:

```php
protected $commands = [
    \App\Console\Commands\ProcessScheduledSmsBlasts::class,
];

protected function schedule(Schedule $schedule)
{
    // Process scheduled SMS blasts every minute
    $schedule->command('sms:process-scheduled-blasts')
             ->everyMinute()
             ->withoutOverlapping();
}
```

### Step 6: Set Up Cron (Production)

On your server, add to crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

This runs every minute and triggers scheduled blasts.

---

## Configuration

### SMS Provider Settings

The system uses iSMS Malaysia gateway.Credentials in `config/services.php`:

```php
'sms' => [
    'username' => env('SMS_USERNAME', 'nlendio'),
    'password' => env('SMS_PASSWORD', 'noister123$'),
    'sender_id' => env('SMS_SENDER_ID', 'JDEN SMS'),
    'api_url' => 'https://www.isms.com.my/isms_send.php',
],
```

### SMS Formatting

Phone numbers are automatically converted to international format:
- Local: `09171234567` → International: `639171234567`
- The system strips non-numeric chars, handles 09xx and 63xxx formats

### Queue Configuration

For high-volume blasts (100+ recipients), configure queue worker:

```bash
# Start queue worker (in production)
php artisan queue:work --daemon --tries=3

# Or use supervisor for process management
```

---

## Usage Guide

### For Administrators

#### Accessing the SMS Panel

1. Log into admin panel
2. Navigate to **Admin Panel → SMS Blast** (or go to `/admin-panel/sms-blast`)

#### Creating a New Blast

**Step 1: Select Recipients**
- Choose tab: **Parents** or **Guardians**
- Check boxes next to each person you want to message
- Use "Select All" to choose all visible
- Selected count updates in real-time
- Click **Continue** (at least 1 required)

**Step 2: Compose Message**

Option A - **Use Template**:
- Select a template from dropdown
- Message automatically fills textarea
- Variables like `{child_name}` remain as-is (will be replaced if using dynamic templates)

Option B - **Custom Message**:
- Type directly in textarea
- Use variable helper buttons to insert placeholders:
  - `{child_name}` - Child's first name (if context available)
  - `{parent_name}` - Parent/guardian name
  - `{time_remaining}` - Minutes remaining
  - `{minutes_over}` - Overtime minutes
  - `{checkout_time}` - Checkout datetime

**Step 3: Schedule**
- Select **Send Immediately** (default)
- Or **Schedule for Later** → pick date & time
- Time must be in the future

**Step 4: Review & Send**
- Summary shows: recipient count, message preview, send time
- Buttons:
  - **Save as Draft** - Saves but doesn't send
  - **Schedule Blast** - Saves and schedules for later
  - **Send Now** - Sends immediately (if no schedule)

#### Managing Templates

Go to **Admin Panel → SMS Templates**:

- **View All**: See all templates with descriptions
- **Create New**: Click "Create Template" button
  - Fill: Name, Description, Message
  - Check "Active" to enable
  - Save
- **Edit**: Click edit icon next to template
  - Modify fields
  - Update
- **Delete**: Click delete icon (only if template not used in any blast)

**Template Variables Available:**

| Variable | Description | Example |
|----------|-------------|---------|
| `{child_name}` | Child's first name | `Juan` |
| `{parent_name}` | Parent/guardian full name | `Maria Santos` |
| `{time_remaining}` | Minutes until timeout | `10` |
| `{minutes_over}` | Minutes overdue | `15` |
| `{checkout_time}` | Checkout datetime | `2026-04-27 14:30` |

#### Viewing Blast History

**Blast List Page** shows:
- Recent blasts (last 20)
- Status badges: Draft, Scheduled, Sending, Sent, Failed, Cancelled
- Progress bar: sent/total percentage
- Date/time sent or scheduled
- Actions per blast

**Blast Details Page** (click on a blast):
- Overview: title, message, timestamps, statistics
- Recipient table with filters:
  - Show: All / Sent / Failed / Pending
  - Columns: Name, Mobile, Type, Status, Sent At, Error
- Resend failed (creates new draft blast with same recipients)
- Cancel (if still scheduled)
- Delete

---

## API Reference

### Endpoints

All routes require `auth` middleware.

| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | `/admin-panel/sms-blast` | index | List all blasts |
| GET | `/admin-panel/sms-blast/create` | create | Show create form |
| POST | `/admin-panel/sms-blast` | store | Create new blast |
| GET | `/admin-panel/sms-blast/{smsBlast}` | show | Blast details |
| POST | `/admin-panel/sms-blast/{smsBlast}/send` | sendNow | Send immediately |
| DELETE | `/admin-panel/sms-blast/{smsBlast}` | destroy | Delete blast |

**Template Routes:**
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | `/admin-panel/sms-templates` | index | List templates |
| GET | `/admin-panel/sms-templates/create` | create | Show form |
| POST | `/admin-panel/sms-templates` | store | Create template |
| GET | `/admin-panel/sms-templates/{smsTemplate}/edit` | edit | Edit form |
| PUT | `/admin-panel/sms-templates/{smsTemplate}` | update | Update template |
| DELETE | `/admin-panel/sms-templates/{smsTemplate}` | destroy | Delete template |

---

### Request Examples

**Create Blast (POST `/admin-panel/sms-blast`)**

```json
{
  "title": "Weekly Reminder - All Parents",
  "message": "Friendly reminder: Your child's session ends soon. Please prepare for checkout.",
  "recipients": [
    {
      "id": "M06-00001",
      "type": "parent",
      "mobile": "09171234567",
      "name": "Juan Santos"
    }
  ],
  "scheduled_at": "2026-04-28 09:00:00"
}
```

**Validation Rules:**
- `title`: required, string, max 255
- `message`: required, string
- `recipients`: required, array, min 1 item
- `recipients.*.id`: required, string
- `recipients.*.type`: required, in: parent, guardian
- `recipients.*.mobile`: required, string
- `recipients.*.name`: required, string
- `scheduled_at`: nullable, date, after:now (if provided)

---

### Response Examples

**Success Response (Redirect):**
- Redirects to `admin.sms-blast.index` with flash message:
```php
with('success', 'SMS blast scheduled successfully for 2026-04-28 09:00')
```

**Error Response:**
- Redirects back with validation errors or error message

---

## Templates

### Predefined Templates

The system ships with 4 predefined templates:

#### 1. Birthday Greetings
- **Slug**: `birthday-greetings`
- **Variables**: `{child_name}`
- **Usage**: Send on child's birthday
- **Example**: "Happy Birthday Juan! From all of us at Mimo Play Cafe..."

#### 2. Time is Almost Up
- **Slug**: `time-is-almost-up`
- **Variables**: `{child_name}`, `{time_remaining}`
- **Usage**: 10-minute warning before session ends
- **Example**: "Friendly reminder: Juan's session will end in 10 minutes..."

#### 3. Overtime
- **Slug**: `overtime`
- **Variables**: `{child_name}`, `{minutes_over}`
- **Usage**: Notify when child exceeds allocated time
- **Example**: "Notice: Juan has exceeded playtime by 15 minutes..."

#### 4. Check Out
- **Slug**: `check-out`
- **Variables**: `{child_name}`, `{checkout_time}`
- **Usage**: Reminder to proceed to checkout counter
- **Example**: "Thank you for visiting! Juan's checkout time is 2:30 PM..."

---

### Dynamic Variables

When sending from the admin UI, these placeholders are replaced:

| Placeholder | Source | Example Value |
|-------------|--------|---------------|
| `{child_name}` | Child's first name from order/child record | `Juan` |
| `{parent_name}` | Parent's full name | `Maria Santos` |
| `{time_remaining}` | Minutes until checkout (calculated) | `10` |
| `{minutes_over}` | Minutes past checkout time | `5` |
| `{checkout_time}` | Calculated checkout datetime | `2026-04-27 14:30` |

---

## Scheduling

### How Scheduling Works

1. **Admin creates blast** with `scheduled_at` timestamp
2. Blast status = `scheduled`
3. Laravel Scheduler runs every minute (`php artisan schedule:run`)
4. Command `sms:process-scheduled-blasts` executes:
   - Finds all blasts where:
     - `status = 'scheduled'`
     - `scheduled_at <= NOW()`
   - Calls `SmsBlastService::processBlast()` for each
5. processBlast():
   - Updates blast status to `sending`
   - Iterates all `pending` recipients
   - Sends SMS via `SendSmsService::sendnowsms()`
   - Updates each recipient to `sent` or `failed`
   - Updates blast counters (`sent_count`, `failed_count`)
   - Sets final blast status (`sent` or `failed`)

### Cron Setup

**Development (Windows XAMPP):**
```bash
# Run scheduler manually for testing
php artisan schedule:run

# Or use Windows Task Scheduler to run every minute
```

**Production (Linux):**
```bash
# Edit crontab
crontab -e

# Add line
* * * * * cd /var/www/playhouse && php artisan schedule:run >> /dev/null 2>&1
```

### Testing Scheduled Blasts

1. Create blast with schedule 2 minutes from now
2. Run manually: `php artisan schedule:run`
3. Check database:
   ```sql
   SELECT * FROM sms_blasts ORDER BY id DESC LIMIT 1;
   SELECT * FROM sms_blast_recipients WHERE sms_blast_id = ?;
   ```
4. Verify phone receives SMS

---

## Troubleshooting

### Common Issues

**1. SMS Not Sending**

Check:
- iSMS API credentials in `SendSmsService.php` (line 16-17)
- Internet connectivity to `https://www.isms.com.my`
- Phone number format (should be 63xxxxxxxxx)
- Check logs: `storage/logs/laravel.log`

**2. Scheduled Blasts Not Running**

Verify:
- Cron job is active: `crontab -l`
- Scheduler runs: `php artisan schedule:run` (test manually)
- Command registered in `app/Console/Kernel.php`
- No overlapping lock preventing execution

**3. Validation Errors on Create**

- Ensure at least 1 recipient selected
- Title max 255 chars
- Scheduled date must be future date
- Mobile numbers contain only digits

**4. Templates Not Appearing in Dropdown**

- Check `is_active = 1` in `sms_templates` table
- Clear cache: `php artisan cache:clear`
- Verify templates exist: `php artisan tinker` → `SmsTemplate::all()`

**5. Recipient List Empty**

- Check `m06` and `m06_guardian` tables have `mobileno` populated
- Mobile number cannot be NULL or empty string

### Logs

- Laravel logs: `storage/logs/laravel.log`
- SMS errors logged with context: `SMS Blast Error`
- Failed messages stored in `sms_blast_recipients.error_message`

### Debug Commands

```bash
# Test SMS service directly
php artisan tinker
>>> App\Services\SendSmsService::sendnowsms('09171234567', 'Test message');

# Check scheduled blasts
php artisan tinker
>>> App\Models\SmsBlast::scheduled()->get();

# Process manually (bypass cron)
php artisan sms:process-scheduled-blasts

# View queue jobs (if using queue)
php artisan queue:monitor
```

---

## Security Considerations

- All routes protected by `auth` middleware
- Admin-only (no public access)
- No API keys exposed in frontend
- Input validation on all requests
- SQL injection prevented via Eloquent ORM
- SMS content sanitized before API call

---

## Performance Notes

- Bulk insert recipients using `insert()` (not individual saves)
- Cursor iteration for memory efficiency (1000+ recipients)
- Database transactions wrap critical operations
- Indexes on foreign keys and status columns
- Queue recommended for > 100 recipients (use Laravel jobs)

---

## Future Enhancements

- [ ] Group recipients by location/branch
- [ ] Template variables with dynamic replacement (child name, parent name)
- [ ] Attach files/images (MMS)
- [ ] Delivery receipts from iSMS (webhook)
- [ ] Rate limiting controls
- [ ] Duplicate detection (same mobile numbers)
- [ ] Export blast reports as CSV
- [ ] Recurring blasts (daily/weekly/monthly)
- [ ] Template preview with sample data
- [ ] Two-way SMS replies handling

---

## Support

For issues or questions, refer to:
- Code: `app/Services/SmsBlastService.php`
- Controller: `app/Http/Controllers/SmsBlastController.php`
- Models: `app/Models/Sms*.php`

---

**Version**: 1.0  
**Last Updated**: April 27, 2026  
**Author**: PlayHouse Development Team
