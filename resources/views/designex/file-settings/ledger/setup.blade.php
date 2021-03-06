<div class="my-pnl">
    <div class="my-pnl-head">
        <div class="pull-left">
            <h6 class="">setup</h6>
        </div>
        <div class="pull-right">
            <a href="javascript:void(0)" class="btn-toggle"><i class="fa fa-chevron-down pull-right"></i></a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="my-pnl-body" style="display: block;">
        <form id="ledger" action="{{ route('ledgers.store') }}">
            {{ csrf_field() }}
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label for="">ledger code</label>
                <input type="text" class="form-control" name="row[data][ledger_code]" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="">ledger name</label>
                <input type="text" class="form-control" name="row[data][ledger_name]" autocomplete="off" required>
            </div>
            <div class="button-list">
                <button type="reset" class="btn btn-default btn-sm">clear</button>
                <button type="submit" class="btn btn-primary btn-sm">save</button>
            </div>
        </form>
    </div>
</div>