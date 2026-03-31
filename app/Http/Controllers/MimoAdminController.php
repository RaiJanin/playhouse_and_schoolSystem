<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\M06;
use App\Models\M06Guardian;
use App\Models\M06Child;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Market;
use App\Models\DurationPrices;
use App\Models\ItemsPrices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class MimoAdminController extends Controller
{
    public function index(Request $request)
    {
        $request->merge([
            'start_date' => $request->input('start_date', now()->format('Y-m-d')),
            'end_date'   => $request->input('end_date', now()->format('Y-m-d')),
        ]);

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = OrderItems::query();

        $query->when($request->filled(['start_date', 'end_date']), 
            function ($q) use ($startDate, $endDate) 
            {
                $q->whereDate('created_at', '>=', $startDate . ' 00:00:00')
                ->whereDate('created_at', '<=', $endDate . ' 23:59:59');
            }
        );

        $inHouseGuardians = OrderItems::when($request->filled(['start_date', 'end_date']), 
            function ($q) use ($startDate, $endDate) 
            {
                $q->whereDate('ckin', '>=', $startDate)
                ->whereDate('ckin', '<=', $endDate);
            }
        )->whereNotNull('guardian')->whereNotNull('ckin')->count();

        $inHouseKids = OrderItems::when($request->filled(['start_date', 'end_date']), 
            function ($q) use ($startDate, $endDate) 
            {
                $q->whereDate('ckin', '>=', $startDate)
                ->whereDate('ckin', '<=', $endDate);
            }
        )->where('d_code_child')->whereNotNull('ckin')->count();

        $todayReservations = OrderItems::when($request->filled(['start_date', 'end_date']), 
            function ($q) use ($startDate, $endDate) 
            {
                $q->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
            }
        )->whereNull('ckin')->count();
        
        $totalKids = M06Child::count();
        $totalGuardians = M06Guardian::count();
        
        $statusMonitor = [
            'in_house_guardians' => $inHouseGuardians,
            'in_house_kids' => $inHouseKids,
            'today_reserves' => $todayReservations,
            'total_kids' => $totalKids,
            'total_guardians' => $totalGuardians
        ];
        
        $status = $request->get('status');

        switch($status)
        {
            case 'ckin':
                $query->whereNotNull('ckin')->whereNull('ckout');
                break;
            case 'ckout':
                $query->whereNotNull('ckout');
                break;
            case 'reservation':
                $query->whereNull('ckin')->whereNull('ckout');
                break;
        }
        
        $orderItems = $query->with(['child', 'order.parentPl'])->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        $columns = \DB::getSchemaBuilder()->getColumnListing('ordlne_ph');
        
        $labels = [
            'id' => 'ID',
            'ord_code_ph' => 'Order Code',
            'd_code_child' => 'Child Code',
            'guardian' => 'Guardian',
            'durationhours' => 'Duration (Hours)',
            'durationsubtotal' => 'Duration Subtotal',
            'socksqty' => 'Socks Qty',
            'socksprice' => 'Socks Price',
            'subtotal' => 'Subtotal',
            'disc_code' => 'Discount Code',
            'checked_out' => 'Checked Out',
            'lne_xtra_chrg' => 'Extra Charge',
            'notified_timeout' => 'Notified Timeout',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'ckin' => 'Checked In',
            'ckout' => 'Checked Out',
        ];
        
        return view('pages.admin-panel.dashboard', compact('orderItems', 'columns', 'labels', 'statusMonitor'));
    }
}
