<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="ledger-form" action="{{ route('ledgers.index') }}" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h5 class="modal-title" id="myModalLabel">Edit</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control" name="row[data][ledger_code]" type="text">
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="row[data][ledger_name]" type="text">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    <button id="submit" type="submit" class="btn btn-info">Update</button>
                    <input name="action" type="hidden" value="edit">
                    <input name="id" type="hidden" value="">
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>