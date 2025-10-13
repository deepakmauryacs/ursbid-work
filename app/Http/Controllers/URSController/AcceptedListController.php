<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AcceptedListController extends Controller
{
    public function show(Request $request, int $dataId)
    {
        $seller = $request->session()->get('seller');

        if (!$seller) {
            abort(403, 'Seller session not found.');
        }

        $records = $this->baseAcceptedListQuery($seller->email)
            ->where('bidding_price.data_id', $dataId)
            ->orderByDesc('bidding_price.created_at')
            ->get();

        $records = $this->appendComputedTotals($records);

        return view('ursdashboard.accepted-list.show', [
            'seller'  => $seller,
            'records' => $records,
            'dataId'  => $dataId,
            'total'   => $records->count(),
        ]);
    }

    protected function baseAcceptedListQuery(string $buyerEmail)
    {
        return DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->where('bidding_price.user_email', $buyerEmail)
            ->where('bidding_price.hide', '1')
            ->where('bidding_price.action', '1')
            ->select(
                'bidding_price.id as bidding_price_id',
                'bidding_price.data_id as data_id',
                'bidding_price.rate as rate',
                'bidding_price.price as price',
                'bidding_price.product_name as bidding_price_product_name',
                'bidding_price.created_at as created_at',
                'bidding_price.seller_email as seller_email',
                'seller.name as seller_name',
                'seller.phone as seller_phone',
                'seller.hash_id as seller_hash_id',
                'product.id as product_id',
                'product.title as product_name',
                'product.description as product_description',
                'product.image as product_image',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_name as qutation_product_name',
                'qutation_form.product_img as qutation_product_img',
                'qutation_form.unit as unit',
                'qutation_form.quantity as quantity',
                'qutation_form.date_time as date_time',
                'qutation_form.city as city',
                'qutation_form.state as state',
                'qutation_form.qutation_id as qutation_id',
                'sc.id as sub_id',
                'sc.name as sub_name',
                'c.id as category_id',
                'c.name as category_name'
            );
    }

    protected function appendComputedTotals(Collection $records): Collection
    {
        return $records->map(function ($record) {
            $quantity = $record->quantity;
            $numericQuantity = 0.0;

            if (is_numeric($quantity)) {
                $numericQuantity = (float) $quantity;
            } elseif (is_string($quantity) && preg_match('/\d+(\.\d+)?/', $quantity, $matches)) {
                $numericQuantity = (float) ($matches[0] ?? 0);
            }

            $rate = is_numeric($record->rate) ? (float) $record->rate : 0.0;
            $record->calculated_total_price = round($numericQuantity * $rate, 2);

            return $record;
        });
    }
}
