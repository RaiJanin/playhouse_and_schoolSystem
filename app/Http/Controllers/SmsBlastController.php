<?php

namespace App\Http\Controllers;

use App\Models\SmsBlast;
use App\Models\SmsBlastRecipient;
use App\Models\M06;
use App\Services\SmsBlastService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmsBlastController extends Controller
{
    private $page = 'pages.admin-panel.sms-blast';

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
    public function create(SmsBlastService $smsBlastService)
    {
        $parents = M06::whereNotNull('mobileno')
            ->where('isparent', true)
            ->select('d_code as id', 'firstname', 'lastname', 'd_name as name', 'mobileno as mobile', DB::raw("'true' as isparent"))
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->firstname . ' ' . $p->lastname,
                    'mobile' => $p->mobile,
                    'type' => 'parent'
                ];
            });

        $guardians = M06::whereNotNull('mobileno')
            ->where('isguardian', true)
            ->select('d_code as id', 'firstname', 'lastname', 'd_name as name', 'mobileno as mobile', DB::raw("'true' as isguardian"))
            ->get()
            ->map(function ($g) {
                return [
                    'id' => $g->id,
                    'name' => $g->firstname . ' ' . $g->lastname,
                    'mobile' => $g->mobile,
                    'type' => 'guardian'
                ];
            });

        $templates = $smsBlastService->getDefaultTemplates();

        return view($this->page, compact('parents', 'guardians', 'templates'));
    }

    /**
     * Store new SMS blast
     */
    public function store(Request $request)
    {
        dd($request->all());
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
