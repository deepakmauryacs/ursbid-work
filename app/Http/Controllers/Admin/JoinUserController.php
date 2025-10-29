<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class JoinUserController extends Controller
{
    /**
     * Mapping for supported account types.
     */
    protected array $types = [
        'vendors' => ['acc_type' => '1', 'label' => 'Vendor'],
        'buyers' => ['acc_type' => '4', 'label' => 'Buyer'],
        'contractors' => ['acc_type' => '2', 'label' => 'Contractor'],
        'clients' => ['acc_type' => '3', 'label' => 'Client'],
    ];

    /**
     * Resolve the metadata for a given account type or abort if not found.
     */
    protected function resolveType(string $type): array
    {
        if (!isset($this->types[$type])) {
            abort(404);
        }

        return $this->types[$type];
    }

    /**
     * Display the join user list for a given referrer.
     */
    public function index(Request $request, string $type, string $refCode)
    {
        $typeData = $this->resolveType($type);

        $referrer = Seller::query()
            ->where('ref_code', $refCode)
            ->whereRaw('FIND_IN_SET(?, acc_type)', [$typeData['acc_type']])
            ->firstOrFail();

        $perPage = (int) $request->input('per_page', 25);
        $perPage = in_array($perPage, [25, 50, 100], true) ? $perPage : 25;
        $keyword = $request->input('keyword');

        $users = Seller::query()
            ->where('verify', 1)
            ->where('ref_by', $refCode)
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%')
                        ->orWhere('phone', 'like', '%' . $keyword . '%')
                        ->orWhere('gst', 'like', '%' . $keyword . '%');
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('ursbid-admin.joinuse.index', [
            'type' => $type,
            'userType' => $typeData['label'],
            'refCode' => $refCode,
            'referrer' => $referrer,
            'users' => $users,
            'filters' => [
                'keyword' => $keyword,
                'per_page' => $perPage,
            ],
        ]);
    }
}
