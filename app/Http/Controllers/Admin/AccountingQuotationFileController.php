<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AccountingQuotationFileController extends Controller
{
    public function show($id)
    {
        $quotation = DB::table('qutation_form')->where('id', $id)->first();

        if (!$quotation) {
            abort(404);
        }

        $files = [];
        if (!empty($quotation->image)) {
            $files = array_filter(array_map('trim', explode(',', $quotation->image)));
        }

        return view('ursbid-admin.accounting.quotation-files', [
            'quotation' => $quotation,
            'files' => $files,
        ]);
    }
}
