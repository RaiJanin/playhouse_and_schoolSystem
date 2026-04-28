# Laravel SMS Automation Scaffold

## 1. Migration: `create_sms_templates_table.php`

```php
Schema::create('sms_templates', function (Blueprint $table) {
    $table->id();
    $table->string('type')->unique();
    $table->string('title');
    $table->text('message');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

---

## 2. Migration: `create_sms_queue_table.php`

```php
Schema::create('sms_queue', function (Blueprint $table) {
    $table->id();
    $table->string('mobile_number');
    $table->text('message');
    $table->string('type');
    $table->string('status')->default('pending');
    $table->timestamp('scheduled_at')->nullable();
    $table->timestamp('sent_at')->nullable();
    $table->text('response')->nullable();
    $table->timestamps();
});
```

---

## 3. Model: `SmsTemplate.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = ['type', 'title', 'message', 'is_active'];
}
```

---

## 4. Model: `SmsQueue.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsQueue extends Model
{
    protected $table = 'sms_queue';

    protected $fillable = [
        'mobile_number',
        'message',
        'type',
        'status',
        'scheduled_at',
        'sent_at',
        'response'
    ];
}
```

---

## 5. Service: `TemplateParserService.php`

```php
namespace App\Services;

class TemplateParserService
{
    public function parse(string $template, array $data = []): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        return $template;
    }
}
```

---

## 6. Service: `SmsService.php`

```php
namespace App\Services;

use App\Models\SmsQueue;

class SmsService
{
    public function queue(
        string $number,
        string $message,
        string $type,
        ?string $schedule = null
    ) {
        return SmsQueue::create([
            'mobile_number' => $number,
            'message' => $message,
            'type' => $type,
            'scheduled_at' => $schedule,
        ]);
    }
}
```

---

## 7. Command: `ProcessSmsQueue.php`

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SmsQueue;
use Illuminate\Support\Facades\Http;

class ProcessSmsQueue extends Command
{
    protected $signature = 'sms:process';
    protected $description = 'Process pending SMS queue';

    public function handle()
    {
        $messages = SmsQueue::where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('scheduled_at')
                  ->orWhere('scheduled_at', '<=', now());
            })
            ->limit(50)
            ->get();

        foreach ($messages as $sms) {
            try {
                // Replace with your SMS provider API
                $response = Http::post('https://sms-provider-api/send', [
                    'to' => $sms->mobile_number,
                    'message' => $sms->message,
                ]);

                $sms->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'response' => $response->body(),
                ]);
            } catch (\Exception $e) {
                $sms->update([
                    'status' => 'failed',
                    'response' => $e->getMessage(),
                ]);
            }
        }
    }
}
```

---

## 8. Scheduler Setup: `routes/console.php`

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('sms:process')->everyMinute();
```

---

## 9. Helper Function: `getTemplate()`

```php
use App\Models\SmsTemplate;

function getTemplate(string $type): ?string
{
    $template = SmsTemplate::where('type', $type)
        ->where('is_active', true)
        ->first();

    return $template?->message;
}
```

---

## 10. Example Controller Usage

```php
use App\Services\SmsService;
use App\Services\TemplateParserService;

$template = getTemplate('checkout');

$message = app(TemplateParserService::class)->parse($template, [
    'parent_name' => $parent->name,
    'child_name' => $child->name,
    'time' => now()->format('h:i A'),
]);

app(SmsService::class)->queue(
    $parent->mobile_number,
    $message,
    'checkout'
);
```

---

## 11. Server Cron

```bash
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

---

## Notes

- Replace API endpoint with your actual SMS provider.
- Add authentication and admin UI for managing templates.
- Add duplicate-prevention flags in session records.
- For high scale, migrate to Laravel Queues + Redis.
```

