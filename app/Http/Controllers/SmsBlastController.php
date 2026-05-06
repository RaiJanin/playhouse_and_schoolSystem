<?php

namespace App\Http\Controllers;

use App\Models\SmsBlast;
use App\Models\SmsBlastRecipient;
use App\Models\M06;
use App\Services\SmsBlastService;
use App\Http\Requests\SmsBlastRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmsBlastController extends Controller
{
    private $page = 'pages.admin-panel.sms-blast';
    private $smsBlastService;

    public function __construct(SmsBlastService $smsBlastService)
    {
        $this->smsBlastService = $smsBlastService;
    }

    /**
     * Display SMS blast history
     */
    public function index(Request $request)
    {
        return view($this->page);
    }

    /**
     * Show create SMS blast form
     */
    public function create()
    {
        $templates = $this->smsBlastService->getDefaultTemplates();

        return view($this->page, compact('templates'));
    }

    /**
     * Store new SMS blast
     */
    public function store(SmsBlastRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try
        {
            $dateTimeEx = $data['scheduled_date'] . ' ' . $data['scheduled_time'];
            $recipients = $request->validated('recipient_ids', []) ? count($data['recipient_ids']) : 0;
            
            $blast = SmsBlast::create([
                'title' => $data['title'],
                'message' => $data['message'],
                'status' => SmsBlast::STATUS_DRAFT,
                'slug' => $data['slug'],
                'total_recipients' => $recipients,
                'type' => $data['type'],
                'send_mode' => $data['send_mode'],
                'schedule_at' => $dateTimeEx,
            ]);

            $result = [
                'success' => false,
                'message' => 'Unkown error'
            ];

            switch($blast->send_mode)
            {
                case 'scheduled':
                    $result = $this->smsBlastService->scheduleBlast($blast, $data['recipient_ids'], $dateTimeEx);
                    break;
                case 'now':
                    $result = $this->smsBlastService->sendBlast($blast, $data['recipient_ids']);
                    break;
                case 'alltimes':
                    $result = ['success' => true];
                    break;
            }

            if ($result['success']) {
                DB::commit();
                return redirect()->route('sms_blast.index')
                    ->with('success', 'SMS blast ' . ($blast->status === 'scheduled' ? 'scheduled' : 'sent') . ' successfully!');
            } else {
                DB::rollBack();
                return back()->withErrors([
                    'error' => 'Failed to send SMS blast: ' . ($result['message'] ?? 'Unknown error'),
                ]);
            }
            
        } catch(\Exception $e) {
            DB::rollback();
            return back()->withErrors([
                'error' => 'Error: '. $e->getMessage(),
            ]);
        }
    }

    /**
     * Show blast details
     */
    public function show(SmsBlast $smsBlast)
    {
       
    }

    /**
     * Show templates page
     */
    public function templates()
    {
        
    }

    /**
     * Resend to failed recipients
     */
    public function resendFailed(SmsBlast $smsBlast)
    {
        
    }

    /**
     * Delete blast
     */
    public function destroy(SmsBlast $smsBlast)
    {
        
    }
}
