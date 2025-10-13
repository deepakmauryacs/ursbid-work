<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use App\Models\SellerSupportTicket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the seller support tickets.
     */
    public function index(Request $request): View
    {
        $seller = $request->session()->get('seller');

        $tickets = SellerSupportTicket::query()
            ->with('creator:id,name,email')
            ->when($seller, function ($query) use ($seller) {
                $query->where('user_id', $seller->id);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusStyles = [
            'pending' => 'badge bg-warning-subtle text-warning',
            'in_working' => 'badge bg-info-subtle text-info',
            'under_process' => 'badge bg-primary-subtle text-primary',
            'completed' => 'badge bg-success-subtle text-success',
        ];

        return view('ursdashboard.help-support.list', [
            'tickets' => $tickets,
            'statusStyles' => $statusStyles,
        ]);
    }

    /**
     * Show the form for creating a new support ticket.
     */
    public function create(): View
    {
        return view('ursdashboard.help-support.create');
    }

    /**
     * Store a newly created support ticket in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $seller = $request->session()->get('seller');

        if (! $seller) {
            return redirect()->route('seller-login');
        }

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:5120'],
        ]);

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $directory = public_path('uploads/support-tickets');

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $filename = uniqid('ticket_') . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $attachmentPath = 'uploads/support-tickets/' . $filename;
        }

        SellerSupportTicket::create([
            'user_id' => $seller->id,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('seller.help-support.index')
            ->with('success', 'Support ticket created successfully.');
    }
}
