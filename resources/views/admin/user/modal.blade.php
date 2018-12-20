<div class="modal fade" tab-index="-1" role="dialog" aria-hidden="true" id="modalTable">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-title">Generated title</h4>
            </div>
            <div class="modal-body">
                <form id="form-1437" name="" class="" action="{{ route("users.store") }}" novalidate="" style="display: inherit; width: 100%;">
                    {{ csrf_field() }}
                    <div class="custom-modal-text">
                        <div class="row" id="modal-content">
                            <div class="col-sm-12" style="display: none" id="error-wrap">
                                <div class="card m-b-20 text-white bg-danger text-xs-center">
                                    <div class="card-body">
                                        <blockquote class="card-bodyquote">
                                            <p id="error-message"></p>
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="firstname" class="control-label">Firstname</label>
                                    <input name="row[data][firstname]" type="text" class="form-control" id="firstname" placeholder="Input Firstname">
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="control-label">Lastname</label>
                                    <input name="row[data][lastname]" type="text" class="form-control" id="lastname" placeholder="Input Lastname">
                                </div>
                                <div class="form-group">
                                    <label for="username" class="control-label">Username</label>
                                    <input name="row[data][username]" type="text" class="form-control" id="username" placeholder="Input Username">
                                </div>
                                <div class="form-group" id="password-container">
                                    <label for="password" class="control-label">Password</label>
                                    <input name="row[data][password]" type="password" class="form-control" id="password" placeholder="Input Password">
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="control-label">Gender</label>
                                    <div class="radio radio-primary form-check-inline">
                                        <input type="radio" id="male" value="male" name="row[data][gender]" checked>
                                        <label for="radio1"> Male </label>
                                    </div>
                                    <div class="radio radio-pink form-check-inline">
                                        <input type="radio" id="female" value="female" name="row[data][gender]">
                                        <label for="female"> Female </label>
                                    </div>
                                </div>
                                {{--</form>--}}
                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label for="privilege" class="control-label">Privilege</label>
                                    <select class="form-control" id="privilege" title="Privilege" name="row[data][privilege]">
                                        <option value="-1">(Select Privilege)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="company" class="control-label">Company</label>
                                    <select data-url="{{ route('adminselect') }}" class="form-control" id="company" title="Company" name="row[data][company_id]">
                                        <option value="-1">(Select Company)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="businessunit" class="control-label">Business Unit</label>
                                    <select data-url="{{ route('adminselect') }}" class="form-control" id="businessunit" title="Business Unit" name="row[data][bunitid]">
                                        <option value="-1">(Select Business Unit)</option>
                                    </select>
                                </div>
                                <div id="department-wrap" class="form-group" style="display:none">
                                    <label for="department" class="control-label">Department</label>
                                    <select class="form-control" id="department" title="Department" name="row[data][dept_id]">
                                        <option value="-1">(Select Department)</option>
                                    </select>
                                </div>

                            </div>

                            <div class="col-sm-4"></div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-url="{{ route("users.store") }}" id="save" type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <input type="hidden" id="code" name="code" value="0">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>