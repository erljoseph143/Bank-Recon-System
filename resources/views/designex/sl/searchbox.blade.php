<div id="searchbox" class="collapse {{ (isset($fields))?'in':'' }} aria-slide">
    <div class="row">
        <div class="col-sm-12 ">
                                <span class="tag label label-primary">
                                    subsidiary ledger
                                    <span>
                                        <i class="fa fa-close"></i>
                                    </span>
                                </span>
            <hr class="light-grey-hr">
        </div>
    </div>
    <form id="search-form" action="{{ route('sl.index') }}">
        <div class="row">
            <div class="col-sm-12 radio-error">
                <div class="form-group">

                    <div class="radio radio-primary">
                        <input type="radio" name="radio" id="docno" value="doc_no" {{ (isset($radio))?($radio=='doc_no')?"checked":"":"" }}>
                        <label for="docno"> document no </label>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" name="radio" id="account-code" value="account_code" {{ (isset($radio))?($radio=='account_code')?"checked":"":"" }}>
                        <label for="account-code"> account code </label>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" name="radio" id="ledger-code" value="ledger_code" {{ (isset($radio))?($radio=='ledger_code')?"checked":"":"" }}>
                        <label for="ledger-code"> ledger code </label>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" name="radio" id="debit" value="debit" {{ (isset($radio))?($radio=='debit')?"checked":"":"" }}>
                        <label for="debit"> debit </label>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" name="radio" id="credit" value="credit" {{ (isset($radio))?($radio=='credit')?"checked":"":"" }}>
                        <label for="credit"> credit </label>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" name="radio" id="balance" value="balance" {{ (isset($radio))?($radio=='balance')?"checked":"":"" }}>
                        <label for="balance"> balance </label>
                    </div>

                </div>
                <div class="radio-error-message"></div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="searchcheckbank">Search fields</label>
                    <div class="ui fluid search selection dropdown">
                        <input name="fields" id="search-field" data-search-url="{{ route('sl.index') }}" type="text" value="{{ (isset($fields))?$fields:'' }}" placeholder="Search fields" class="form-control" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <label for="">Search Date</label>
                <div class="input-group">
                    <input class="form-control" id="DigitalBush" type="text" class="digital-bush" />
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-xs btn-default btn-anim slide-toggle"><i class="icon-close"></i><span class="btn-text">close filter</span></button>
                <button type="button" class="btn btn-xs btn-default btn-anim clear-filter" value="clearfilter"><i class="icon-close"></i><span class="btn-text">clear filter</span></button>
                <button id="search" type="submit" name="search" class="btn btn-xs btn-primary btn-anim"><i class="icon-rocket"></i><span class="btn-text">filter</span></button>
            </div>
        </div>
    </form>
</div>