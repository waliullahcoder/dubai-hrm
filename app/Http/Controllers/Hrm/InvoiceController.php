<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    private string $table = 'hrm_invoices';

    /**
     * Invoice list + summary cards
     */
   public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = $this->filteredQuery($request, 'i')
                ->select(
                    'i.id',
                    'i.invoice_no',
                    'i.invoice_date',
                    'i.amount_before_vat',
                    'i.vat_percent',
                    'i.vat_amount',
                    'i.total_amount',
                    'i.lpo_number',
                    'i.active_status',
                    'i.payment_status',
                    'i.invoice_pdf',
                    'i.remarks'
                )
                ->orderBy('i.id', 'desc');

            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('invoice_date', fn($row) => date('d-M-Y', strtotime($row->invoice_date)))
                ->editColumn('amount_before_vat', fn($row) => number_format($row->amount_before_vat, 2))
                ->editColumn('vat_amount', fn($row) => number_format($row->vat_amount, 2))
                ->editColumn('total_amount', fn($row) => '<b>' . number_format($row->total_amount, 2) . '</b>')
                ->editColumn('active_status', function ($row) {
                    $class = $row->active_status === 'Active' ? 'bg-success' : 'bg-danger';
                    return '<span class="badge ' . $class . '">' . $row->active_status . '</span>';
                })
                ->editColumn('payment_status', function ($row) {
                    $class = $row->payment_status === 'Paid' ? 'bg-success' : 'bg-danger';
                    return '<span class="badge ' . $class . '">' . $row->payment_status . '</span>';
                })
                ->editColumn('invoice_pdf', function ($row) {
                    if (!$row->invoice_pdf) {
                        return '-';
                    }
                    return '<a href="' . asset('/' . $row->invoice_pdf) . '" target="_blank" title="View PDF">
                                <i class="fas fa-file-pdf text-danger fa-lg"></i>
                            </a>';
                })
                ->addColumn('actions', function ($row) {
                    $btn = '';

                    if (auth()->user()->can('admin.invoices.show')) {
                        $btn .= '<a href="' . route('admin.invoices.show', $row->id) . '" target="_blank"
                                    class="btn btn-sm btn-primary me-1"><i class="fas fa-eye"></i></a>';
                    }

                    if (auth()->user()->can('admin.invoices.edit')) {
                        $btn .= '<a href="' . route('admin.invoices.edit', $row->id) . '"
                                    class="btn btn-sm btn-info text-white me-1"><i class="fas fa-edit"></i></a>';
                    }

                    if (auth()->user()->can('admin.invoices.destroy')) {
                        $btn .= '<button class="btn btn-sm btn-danger link-delete"
                                    data-url="' . route('admin.invoices.destroy', $row->id) . '">
                                    <i class="fas fa-trash"></i></button>';
                    }

                    return '<div class="d-flex justify-content-center">' . $btn . '</div>';
                })
                ->rawColumns(['total_amount', 'active_status', 'payment_status', 'invoice_pdf', 'actions'])
                ->with('summary', $this->summaryData($request))
                ->make(true);
        }

        $summary = $this->summaryData($request);

        return view('hrm.invoices.index', compact('summary'));
    }

    private function filteredQuery(Request $request, string $alias = 'i')
    {
        return DB::table($this->table . ' as ' . $alias)
            ->when($request->filled('payment_status'), fn($q) => $q->where("$alias.payment_status", $request->payment_status))
            ->when($request->filled('active_status'), fn($q) => $q->where("$alias.active_status", $request->active_status))
            ->when($request->filled('from_date'), fn($q) => $q->whereDate("$alias.invoice_date", '>=', Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d')))
            ->when($request->filled('to_date'), fn($q) => $q->whereDate("$alias.invoice_date", '<=', Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d')));
    }

    private function summaryData(Request $request): array
    {
        $s = $this->filteredQuery($request, 'i')
            ->selectRaw("
                COUNT(*) as total_invoices,
                COALESCE(SUM(i.amount_before_vat), 0) as total_before_vat,
                COALESCE(SUM(i.vat_amount), 0) as total_vat,
                COALESCE(SUM(i.total_amount), 0) as total_with_vat,
                COALESCE(SUM(CASE WHEN i.payment_status = 'Paid' THEN i.total_amount ELSE 0 END), 0) as paid_amount,
                COALESCE(SUM(CASE WHEN i.payment_status = 'Unpaid' THEN i.total_amount ELSE 0 END), 0) as unpaid_amount
            ")
            ->first();

        return [
            'total_invoices'   => (int) $s->total_invoices,
            'total_before_vat' => number_format($s->total_before_vat, 2),
            'total_vat'        => number_format($s->total_vat, 2),
            'total_with_vat'   => number_format($s->total_with_vat, 2),
            'paid_amount'      => number_format($s->paid_amount, 2),
            'unpaid_amount'    => number_format($s->unpaid_amount, 2),
        ];
    }

    /**
     * Create form
     */
    public function create()
    {
        $invoice_no = $this->generateInvoiceNo();

        return view('hrm.invoices.create', compact('invoice_no'));
    }

    /**
     * Store new invoice
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_no'        => 'required|string|max:50|unique:' . $this->table . ',invoice_no',
            'invoice_date'      => 'required|date_format:d-m-Y',
            'amount_before_vat' => 'required|numeric|min:0',
            'vat_percent'       => 'required|numeric|min:0|max:100',
            'lpo_number'        => 'required|string|max:100',
            'active_status'     => 'required|in:Active,Inactive',
            'payment_status'    => 'required|in:Paid,Unpaid',
            'invoice_pdf'       => 'required|file|mimes:pdf|max:5120',
            'remarks'           => 'nullable|string|max:1000',
        ]);

        [$vat, $total] = $this->calculateVat($data['amount_before_vat'], $data['vat_percent']);
        $invoice_pdf =null;
        if ($request->hasFile('invoice_pdf')) {
                $file = $request->file('invoice_pdf');

                $filename = time() . '_' . $file->getClientOriginalName();

                $file->move(
                    public_path('backend/invoices'),
                    $filename
                );

                $invoice_pdf = 'backend/invoices/' . $filename;
            }

        DB::table($this->table)->insert([
            'invoice_no'        => $data['invoice_no'],
            'invoice_date'      => Carbon::createFromFormat('d-m-Y', $data['invoice_date'])->format('Y-m-d'),
            'amount_before_vat' => $data['amount_before_vat'],
            'vat_percent'       => $data['vat_percent'],
            'vat_amount'        => $vat,
            'total_amount'      => $total,
            'lpo_number'        => $data['lpo_number'],
            'active_status'     => $data['active_status'],
            'payment_status'    => $data['payment_status'],
            'invoice_pdf'       => $invoice_pdf,
            'remarks'           => $data['remarks'] ?? null,
            'created_by'        => auth()->id(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Save & New button
        if ($request->has('save_new')) {
            return redirect()
                ->route('admin.invoices.create')
                ->withSuccessMessage('Invoice added successfully.');
        }

        return redirect()
            ->route('admin.invoices.index')
            ->withSuccessMessage('Invoice added successfully.');
    }

    /**
     * Open invoice PDF
     */
    public function show($id)
    {
        $invoice = DB::table($this->table)->where('id', $id)->first();

        abort_if(!$invoice || !$invoice->invoice_pdf || !Storage::disk('public')->exists($invoice->invoice_pdf), 404);

        return response()->file(Storage::disk('public')->path($invoice->invoice_pdf));
    }

    /**
     * Edit form
     */
    public function edit($id)
    {
        $invoice = DB::table($this->table)->where('id', $id)->first();

        abort_if(!$invoice, 404);

        return view('hrm.invoices.edit', compact('invoice'));
    }

    /**
     * Update invoice
     */
    public function update(Request $request, $id)
    {
        $invoice = DB::table($this->table)->where('id', $id)->first();

        abort_if(!$invoice, 404);

        $data = $request->validate([
            'invoice_no'        => 'required|string|max:50|unique:' . $this->table . ',invoice_no,' . $id,
            'invoice_date'      => 'required|date_format:d-m-Y',
            'amount_before_vat' => 'required|numeric|min:0',
            'vat_percent'       => 'required|numeric|min:0|max:100',
            'lpo_number'        => 'required|string|max:100',
            'active_status'     => 'required|in:Active,Inactive',
            'payment_status'    => 'required|in:Paid,Unpaid',
            'invoice_pdf'       => 'nullable|file|mimes:pdf|max:5120',
            'remarks'           => 'nullable|string|max:1000',
        ]);

        [$vat, $total] = $this->calculateVat($data['amount_before_vat'], $data['vat_percent']);

       $update = [
                'invoice_no'        => $data['invoice_no'],
                'invoice_date'      => Carbon::createFromFormat('d-m-Y', $data['invoice_date'])->format('Y-m-d'),
                'amount_before_vat' => $data['amount_before_vat'],
                'vat_percent'       => $data['vat_percent'],
                'vat_amount'        => $vat,
                'total_amount'      => $total,
                'lpo_number'        => $data['lpo_number'],
                'active_status'     => $data['active_status'],
                'payment_status'    => $data['payment_status'],
                'remarks'           => $data['remarks'] ?? null,
                'updated_by'        => auth()->id(),
                'updated_at'        => now(),
            ];

            // New PDF upload hole purano ta delete
            if ($request->hasFile('invoice_pdf')) {

                // পুরানো PDF delete
                if ($invoice->invoice_pdf) {
                    $oldFile = public_path($invoice->invoice_pdf);

                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                // Folder path
                $folder = public_path('backend/invoices');

                // Folder না থাকলে তৈরি করবে
                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }

                // New filename
                $file = $request->file('invoice_pdf');

                $filename = time() . '_' . $file->getClientOriginalName();

                // Upload
                $file->move($folder, $filename);

                // Database path
                $update['invoice_pdf'] = 'backend/invoices/' . $filename;
            }

            DB::table($this->table)
                ->where('id', $id)
                ->update($update);
        return redirect()
            ->route('admin.invoices.index')
            ->withSuccessMessage('Invoice updated successfully.');
    }

    /**
     * Delete invoice
     */
      public function destroy($id)
        {
            $invoice = DB::table('hrm_invoices')
                ->where('id', $id)
                ->first();

            if (!$invoice) {
                return redirect()->back()->with('error', 'Invoice not found.');
            }

            // Delete PDF file
            if (!empty($invoice->invoice_pdf)) {
                $filePath = public_path($invoice->invoice_pdf);

                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete invoice record
            DB::table('hrm_invoices')
                ->where('id', $id)
                ->delete();

            return redirect()->back()->withSuccessMessage('Invoice deleted successfully.');
        }

    /**
     * Export CSV (Export button)
     */
    public function export(Request $request)
    {
        $rows = DB::table($this->table)
            ->when($request->filled('payment_status'), fn($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->filled('active_status'), fn($q) => $q->where('active_status', $request->active_status))
            ->when($request->filled('from_date'), fn($q) => $q->whereDate('invoice_date', '>=', Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d')))
            ->when($request->filled('to_date'), fn($q) => $q->whereDate('invoice_date', '<=', Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d')))
            ->orderBy('id', 'desc')
            ->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Invoice No', 'Date', 'Amount Before VAT', 'VAT %', 'VAT Amount', 'Total Amount', 'LPO Number', 'Active Status', 'Payment Status', 'Remarks']);

            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->invoice_no,
                    date('d-m-Y', strtotime($r->invoice_date)),
                    $r->amount_before_vat,
                    $r->vat_percent,
                    $r->vat_amount,
                    $r->total_amount,
                    $r->lpo_number,
                    $r->active_status,
                    $r->payment_status,
                    $r->remarks,
                ]);
            }
            fclose($out);
        }, 'invoices_' . date('Ymd_His') . '.csv', ['Content-Type' => 'text/csv']);
    }

    /**
     * Auto invoice no: INV-2026-001
     */
    private function generateInvoiceNo(): string
    {
        $year = date('Y');
        $prefix = 'INV-' . $year . '-';

        $last = DB::table($this->table)
            ->where('invoice_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->value('invoice_no');

        $next = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    /**
     * VAT & total calculation (server side)
     */
    private function calculateVat($amount, $percent): array
    {
        $vat   = round($amount * ($percent / 100), 2);
        $total = round($amount + $vat, 2);

        return [$vat, $total];
    }


  






}