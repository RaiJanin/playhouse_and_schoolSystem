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
    private $checkoutExpr = "ckin + (durationhours * interval '1 hour')";

    private function dateFilters($query, $request, $startDate, $endDate, $column)
    {
        return $query->when(
            $request->filled(['start_date', 'end_date']),
            function ($q) use ($startDate, $endDate, $column) {
                $q->whereDate($column, '>=', $startDate)
                ->whereDate($column, '<=', $endDate);
            }
        );
    }

    private function baseOrderQuery($request, $startDate, $endDate, $column)
    {
        $query = OrderItems::query();

        return $this->dateFilters($query, $request, $startDate, $endDate, $column);
    }
    
    private function allItemsQuery($request, $startDate, $endDate, $status)
    {
        $query = $this->baseOrderQuery($request, $startDate, $endDate, 'created_at');

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

        return $query->with(['child', 'order.parentPl'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(20)
                     ->withQueryString();
    }

    public function index(Request $request)
    {
        $request->merge([
            'start_date' => $request->input('start_date', now()->format('Y-m-d')),
            'end_date'   => $request->input('end_date', now()->format('Y-m-d')),
        ]);

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $now = Carbon::now();

        $inHouseGuardians = $this->baseOrderQuery($request, $startDate, $endDate, 'ckin')->whereNotNull('guardian')->whereNotNull('ckin')->count();
        $inHouseKids = $this->baseOrderQuery($request, $startDate, $endDate, 'ckin')->whereNotNull('d_code_child')->whereNotNull('ckin')->count();
        $todayReservations = $this->baseOrderQuery($request, $startDate, $endDate, 'created_at')->whereNull('ckin')->count();
        $forCheckouts = $this->baseOrderQuery($request, $startDate, $endDate, 'ckin')->whereRaw( "{$this->checkoutExpr} < ?", [ $now->copy()->addMinutes(10)] )->whereNotNull('ckin')->count();
        $overdueCount = $this->baseOrderQuery($request, $startDate, $endDate, 'ckin')->whereRaw( "{$this->checkoutExpr} < ?", [ $now->copy() ] )->whereNotNull('ckin')->count();
        $totalKids = M06Child::count();
        $totalGuardians = M06Guardian::count();
        $totalCheckouts = OrderItems::whereNotNull('ckout')->count();
        $totalSocks = OrderItems::sum('socksqty');
        
        $statusMonitor = [
            'in_house_guardians' => $inHouseGuardians,
            'in_house_kids' => $inHouseKids,
            'today_reserves' => $todayReservations,
            'total_kids' => $totalKids,
            'total_guardians' => $totalGuardians,
            'for_checkouts' => $forCheckouts,
            'total-ckouts' => $totalCheckouts,
            'socks_sold' => $totalSocks,
            'overdue' => $overdueCount,
        ];
        
        $orderItems = $this->allItemsQuery($request, $startDate, $endDate, $request->query('status'));
        
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
