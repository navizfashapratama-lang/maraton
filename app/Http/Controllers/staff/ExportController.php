<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\Lomba;
use Carbon\Carbon;
use PDF;
use Excel;
use App\Exports\RegistrationsExport;

class ExportController extends Controller
{
    public function index()
    {
        return view('staff.export.index');
    }

    public function exportRegistrations(Request $request)
    {
        $format = $request->get('format', 'excel');
        $status = $request->get('status', 'all');
        $event_id = $request->get('event_id');
        
        $query = Pendaftaran::with(['user', 'lomba']);
        
        if ($status !== 'all') {
            $query->where('status_pendaftaran', $status);
        }
        
        if ($event_id) {
            $query->where('lomba_id', $event_id);
        }
        
        $registrations = $query->get();
        
        if ($format === 'pdf') {
            $pdf = PDF::loadView('staff.export.pdf.registrations', [
                'registrations' => $registrations,
                'title' => 'Data Pendaftaran'
            ]);
            
            return $pdf->download('pendaftaran-' . Carbon::now()->format('Y-m-d') . '.pdf');
        } else {
            return Excel::download(new RegistrationsExport($registrations), 'pendaftaran-' . Carbon::now()->format('Y-m-d') . '.xlsx');
        }
    }

    public function exportPayments(Request $request)
    {
        // Logika export pembayaran
        // ...
    }

    public function exportEvents(Request $request)
    {
        // Logika export event
        // ...
    }
}