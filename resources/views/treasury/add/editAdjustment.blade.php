<div class="row">
    <form id="edit-adjustment" method="post">
        {{csrf_field()}}
        <div class="col-md-12">
            <div class="input-group form-group">
                <input type="hidden" name="id-adj" id="id-adj" value="{{$adj->id}}">
                <input type="hidden" name="date_adj" id="date-adj" value="{{$adj->sales_date}}">
                <select name="description" id="description" class="form-control">
                    <option value="PDC">PDC</option>
                    <option value="Due Checks">Due Checks</option>
                    <option value="Forex">Forex</option>
                    <option value="Western Union">Western Union</option>
                </select>
            </div>
            <div class="input-group form-group">
                <input type="text" name="amount" id="amt-data" class="form-control" value="{{$adj->amount_edited}}">
            </div>
        </div>
    </form>
</div>


<script>

    $(document).on("focus", "#amt-data", function () {
        $(this).maskMoney();
        $(this).css('text-align','right');
    });

    $('#description').val('{{$adj->description}}').trigger('change');

</script>