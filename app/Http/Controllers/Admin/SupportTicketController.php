<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerSupportTicket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupportTicketController extends Controller
{
    /**
     * The supported status labels.
     */
    private const STATUS_LABELS = [
        'pending' => 'Pending',
        'in_working' => 'In Working',
        'under_process' => 'Under Process',
        'completed' => 'Completed',
    ];

    /**
     * CSS classes used for status badges.
     */
    private const STATUS_STYLES = [
        'pending' => 'badge bg-warning-subtle text-warning',
        'in_working' => 'badge bg-info-subtle text-info',
        'under_process' => 'badge bg-primary-subtle text-primary',
        'completed' => 'badge bg-success-subtle text-success',
    ];

    /**
     * Display a listing of the support tickets.
     */
    public function index(Request $request): View
    {
        $ticketsQuery = SellerSupportTicket::query()
            ->with('creator:id,name,email,phone');

        $search = $request->filled('search') ? trim((string) $request->input('search')) : null;

        if ($search !== null && $search !== '') {
            $ticketsQuery->where(function ($query) use ($search) {
                $query->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($creatorQuery) use ($search) {
                        $creatorQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $status = $request->filled('status') ? trim((string) $request->input('status')) : null;

        if ($status !== null && array_key_exists($status, self::STATUS_LABELS)) {
            $ticketsQuery->where('status', $status);
        }

        $tickets = $ticketsQuery
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('ursbid-admin.help-support.index', [
            'tickets' => $tickets,
            'statusLabels' => self::STATUS_LABELS,
            'statusStyles' => self::STATUS_STYLES,
            'selectedStatus' => $status ?? null,
            'searchTerm' => $search ?? null,
        ]);
    }

    /**
     * Display the specified support ticket.
     */
    public function show(SellerSupportTicket $ticket): View
    {
        $ticket->load('creator:id,name,email,phone');

        return view('ursbid-admin.help-support.show', [
            'ticket' => $ticket,
            'statusLabels' => self::STATUS_LABELS,
            'statusStyles' => self::STATUS_STYLES,
        ]);
    }

    /**
     * Update the status for the specified support ticket.
     */
    public function updateStatus(Request $request, SellerSupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(self::STATUS_LABELS))],
        ]);

        $ticket->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('super-admin.support-tickets.show', $ticket)
            ->with('success', 'Ticket status updated successfully.');
    }
}
