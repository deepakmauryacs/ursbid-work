@php
    $vendorBids = collect($vendorBids ?? [])->values();
    $products = collect($products ?? [])->values();
    $auction = $auction ?? (object) [];
    $status = $status ?? 'N/A';
    $currencySymbol = $currencySymbol ?? '₹';

    if (! function_exists('get_currency_symbol_now')) {
        function get_currency_symbol_now($currency)
        {
            $currency_symbols = [
                'INR' => '₹',
                '₹'   => '₹',
                'USD' => '$',
                '$'   => '$',
                'NPR' => 'NPR',
            ];

            return $currency_symbols[$currency] ?? ($currency_symbols[strtoupper($currency)] ?? $currency);
        }
    }

    $currencyLabel = get_currency_symbol_now($currencyCode ?? $auction->currency ?? 'INR');

    $displayDate = $auction->date_label ?? '-';
    $displayStartTime = $auction->start_time_label ?? '-';
    $displayEndTime = $auction->end_time_label ?? '-';

    $auctionId = $auction->auction_id ?? '-';
    $branchName = $auction->branch_name ?? '-';
    $remarks = $auction->remarks ?? '-';
    $priceBasis = $auction->price_basis ?? '-';
    $paymentTerms = $auction->payment_terms ?? '-';
    $deliveryPeriod = $auction->delivery_period ?? '-';
@endphp

<style>
    .cis-border { border: 2px solid #222 !important; }
</style>

<table border="0" cellpadding="0" cellspacing="0" id="sheet0" class="sheet0 gridlines" style="border-collapse: collapse;page-break-after: always;">
    <tbody>
        <tr class="row0" style="height: 80.8pt;">
            <td class="cis-border" colspan="{{ $vendorBids->count() + 4 }}" style="text-align: center; vertical-align: middle; font-weight: bold; color: #27405E; font-family: 'Calibri'; font-size: 20pt; background-color: white;">
                <img src="{{ asset('public/assets/images/logo-sm/rfq-logo.jpg') }}" border="0"><br>
                Forward Auction - Comparative Information Statement
            </td>
        </tr>

        <tr class="row1" style="height: 31.2pt;">
            <td class="cis-border" style="text-align: left; font-weight: bold;">
                &nbsp; Auction ID: {{ $auctionId }}
            </td>
            <td class="cis-border" style="text-align: left; font-weight: bold;">
                Date: {{ $displayDate }}
            </td>
            <td class="cis-border" style="text-align: left; font-weight: bold;">
                Time: {{ $displayStartTime }} TO {{ $displayEndTime }}
            </td>
            <td class="cis-border" style="text-align: left; font-weight: bold;">
                Branch/Unit: {{ $branchName ?: '-' }}
            </td>
            <td class="cis-border" colspan="{{ $vendorBids->count() }}" style="text-align: left; font-weight: bold;">
                Status: {{ $status }}
            </td>
        </tr>

        <tr class="row2" style="height: 48.6pt;">
            <td class="cis-border" rowspan="2" style="text-align: center; font-weight: bold;">
                Product Details
            </td>
            <td class="cis-border" rowspan="2" style="text-align: center; font-weight: bold;">
                Specs
            </td>
            <td class="cis-border" rowspan="2" style="text-align: center; font-weight: bold;">
                Qty/UOM
            </td>
            <td class="cis-border" rowspan="2" style="text-align: center; font-weight: bold;">
                Start Price
            </td>
            @foreach ($vendorBids as $vendor)
                <td class="cis-border" colspan="1" style="text-align: center; font-weight: bold; color: #1F497D;">
                    {{ $vendor['name'] ?? 'Vendor' }}
                    <br>
                    @php
                        $country = $vendor['country_code'] ?? null;
                        $mobile = $vendor['mobile'] ?? null;
                        $mobileLabel = '-';
                        if ($mobile) {
                            $prefix = $country ? '+' . ltrim((string) $country, '+') . ' ' : '';
                            $mobileLabel = $prefix . $mobile;
                        }
                    @endphp
                    (M: {{ $mobileLabel }})
                </td>
            @endforeach
        </tr>

        <tr class="row3" style="height: 31.2pt;">
            @foreach ($vendorBids as $vendor)
                <td class="cis-border" style="text-align: center; font-weight: bold; color: #1F497D;">
                    Rate Per Unit ({{ $currencyLabel }})
                </td>
            @endforeach
        </tr>

        @foreach ($products as $product)
            @php
                $productId = $product->id ?? null;
                $maxPrice = 0;
                foreach ($vendorBids as $vendor) {
                    $price = $vendor['prices'][$productId] ?? null;
                    if (is_numeric($price) && $price > $maxPrice) {
                        $maxPrice = $price;
                    }
                }
            @endphp
            <tr class="row4" style="height: 19.2pt;">
                <td class="cis-border" style="text-align: center; font-weight: bold; color: #1F497D; background-color: #DBE5F1;">
                    {{ $product->product_name ?? '-' }}
                </td>
                <td class="cis-border" style="text-align: center;">
                    {{ $product->specs ?? '-' }}
                </td>
                <td class="cis-border" style="text-align: center;">
                    @php
                        $quantity = $product->quantity ?? '-';
                        $uom = $product->uom_name ?? '';
                        $quantityLabel = trim((string) $quantity . ' ' . (string) $uom);
                    @endphp
                    {{ $quantityLabel !== '' ? $quantityLabel : '-' }}
                </td>
                <td class="cis-border" style="text-align: center;">
                    @php
                        $startPrice = $product->start_price ?? null;
                    @endphp
                    {{ is_numeric($startPrice) ? number_format((float) $startPrice, 2) : '-' }}
                </td>
                @foreach ($vendorBids as $vendor)
                    @php
                        $price = $vendor['prices'][$productId] ?? null;
                        $isMax = is_numeric($price) && (float) $price === (float) $maxPrice && (float) $price !== 0.0;
                        $bgColor = $isMax ? 'background-color: yellow;' : '';
                    @endphp
                    <td class="cis-border" style="text-align: center; {{ $bgColor }}">
                        {{ is_numeric($price) ? number_format((float) $price, 2) : '-' }}
                    </td>
                @endforeach
            </tr>
        @endforeach

        <tr class="row5" style="height: 16.36pt;">
            <td class="cis-border" colspan="4" style="text-align: right; font-weight: bold; background-color: #F2DBDB;">
                Total
            </td>
            @foreach ($vendorBids as $vendor)
                @php
                    $total = $vendor['total'] ?? null;
                @endphp
                <td class="cis-border" style="text-align: center; background-color: #F2DBDB;">
                    {{ is_numeric($total) ? ($currencySymbol . ' ' . number_format((float) $total, 2)) : ($currencySymbol . ' -') }}
                </td>
            @endforeach
        </tr>

        <tr class="row6" style="height: 16.36pt;">
            <td class="cis-border" style="text-align: center; font-weight: bold;">
                Remarks
            </td>
            <td class="cis-border" colspan="{{ $vendorBids->count() + 3 }}">
                {{ trim((string) $remarks) !== '' ? $remarks : '-' }}
            </td>
        </tr>

        <tr class="row7" style="height: 16.36pt;">
            <td class="cis-border" style="text-align: center; font-weight: bold;">
                Price Basis
            </td>
            <td class="cis-border" colspan="{{ $vendorBids->count() + 3 }}">
                {{ trim((string) $priceBasis) !== '' ? $priceBasis : '-' }}
            </td>
        </tr>

        <tr class="row8" style="height: 16.36pt;">
            <td class="cis-border" style="text-align: center; font-weight: bold; background-color: #F2F2F2;">
                Payment Terms
            </td>
            <td class="cis-border" colspan="{{ $vendorBids->count() + 3 }}" style="background-color: #F2F2F2;">
                {{ trim((string) $paymentTerms) !== '' ? $paymentTerms : '-' }}
            </td>
        </tr>

        <tr class="row9" style="height: 16.36pt;">
            <td class="cis-border" style="text-align: center; font-weight: bold;">
                Delivery Period (Days)
            </td>
            <td class="cis-border" colspan="{{ $vendorBids->count() + 3 }}">
                {{ trim((string) $deliveryPeriod) !== '' ? $deliveryPeriod : '-' }}
            </td>
        </tr>
    </tbody>
</table>
