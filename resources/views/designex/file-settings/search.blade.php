<div class="my-pnl">
    <div class="my-pnl-head">
        <div class="pull-left">
            <h6>search</h6>
        </div>
        <div class="pull-right">
            <a href="javascript:void(0)" class="btn-toggle"><i class="fa fa-chevron-down pull-right"></i></a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="my-pnl-body">
        <form id="search-form" action="{{ route('transaction-types.index') }}">
            <input type="hidden" name="action" value="search">
            <div class="radio radio-primary">
                <input type="radio" name="plradio" id="code" value="code" checked="">
                <label for="code"> code </label>
            </div>
            <div class="radio radio-primary">
                <input type="radio" name="plradio" id="name" value="name" checked="">
                <label for="name"> name </label>
            </div>
            <div class="form-group">
                <input name="search" type="text" class="form-control" id="search-types" autocomplete="off">
            </div>
        </form>
    </div>
</div>