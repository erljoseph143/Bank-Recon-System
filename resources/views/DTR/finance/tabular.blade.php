@include('DTR.finance.bsTable')
<style>
    .modal .modal-dialog {
        z-index: 30051;
    }
</style>
<script>
    $(".modal-lg").addClass('modal-full');
    $(".bootstrap-dialog-title").text('{{isset($date)? 'Records for ' . date('F j, Y',strtotime($date)):'Records'}}');
</script>