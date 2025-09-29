@push('scripts')
<script type="text/javascript">
    (function ($) {
        const $form = $('#buyerOrderFiltersForm');
        const $recordsPerPage = $('#buyerOrderRecords');
        const $resetButton = $('#resetBuyerOrderFilters');

        $recordsPerPage.on('change', function () {
            $form.trigger('submit');
        });

        $resetButton.on('click', function () {
            $form.find('input[type="text"]').val('');
            $form.find('select').prop('selectedIndex', 0);
            $form.trigger('submit');
        });

        $(document).on('click', '.mdl_btn', function () {
            const $button = $(this);
            $('.product_id').val($button.data('product_id'));
            $('.product_name').val($button.data('product_name'));
            $('.user_email').val($button.data('user_email'));
            $('.data_id').val($button.data('data_id'));
        });
    })(jQuery);
</script>
@endpush
