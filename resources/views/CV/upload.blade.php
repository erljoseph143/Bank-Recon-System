<style>
    .bootstrap-dialog.type-primary .modal-header
    {
        background-color:rgb(96, 108, 254);
        bottom:0;
        margin-bottom:0%;
        border:0px;
    }
    .closedir{
        color:white;
    }
    .blink {
        -webkit-animation-name: blink;
        -webkit-animation-iteration-count: infinite;
        -webkit-animation-timing-function: cubic-bezier(1.0,0,0,1.0);
        -webkit-animation-duration: 1s;
    }
    .bootstrap-dialog.type-info .modal-header {
        background-color: #2196F3;
    }
    .modal-header {
        padding: 15px;
        border-bottom: none;
    }
</style>

<div style="text-align:center;    background: #2196F3;height:100px;width:100%;position:absolute;z-index:1;left:0;margin-left:0%;top:0;margin-top:0%">
    <div class="fa fa-file-text" style="top:0;margin-top:-4%;font-size:100px;left:0;margin-left:auto;right:0;margin-right:auto;color:white"></div>
    <div style="margin-top:1.2%;margin-left:auto;margin-right:auto;border:1px solid #555555;border-radius:10px;padding:5px;text-align:center;width:35%;background-color:#ffffff">
        Upload Check Voucher
    </div>
</div>
<div class="tabbable">
    <div class="tab-content ">

        <div class="tab-pane active" id="tab1excel">
            <hr>
            <div class="span6" style="padding: 5px">
                <br/>
                <br/>
                <br/>
              <br/>
                <strong>
                    <span>Note:
                        <i>
                            This is the upload field that you will fill with your extracted file.
                        </i>
                    </span>
                </strong>

                <form action="dataCVProcess" method="POST"  enctype="multipart/form-data"><br/>
                    {{csrf_field()}}
                    <div class="input-prepend input-append">
                        <span class="add-on" style="font-size:16px;">&nbsp;CV File</span>
                        <input class="input" type="file" name="mainfiles1excel" id="mainfilesexcel" style="display: none" required/>
                        <input type="text" id="mainfileexcel" class="input form-control" for="mainfilesexcel" placeholder="No file chosen" style="display:inline;width:60%;background-color: #FFF;border: 1px solid grey" disabled/>
                        <label for="mainfilesexcel" title="Click here to browse..." class="add-on" style="font-weight: bold;border:0px solid black;border-radius:0px;background-color:#ced1d1;padding:5px;cursor:pointer;" onmouseout="default_user()">
                            <img src="img/open_in_browser-26.png" width="25px"> Excel file
                        </label>
                    </div>
                    <div class="input-prepend input-append">
                        <span class="add-on" style="font-size:16px;">&nbsp;Header File</span>
                        <input class="input" type="file" name="mainfiles1excel2" id="mainfilesexcel2" style="display: none" required/>
                        <input type="text" id="mainfileexcel2" class="input form-control" for="mainfilesexcel2" placeholder="No file chosen" style="display:inline;width:60%;background-color: #FFF;border: 1px solid grey" disabled/>
                        <label for="mainfilesexcel2" title="Click here to browse..." class="add-on" style="font-weight: bold;border:0px solid black;border-radius:0px;background-color:#ced1d1;padding:5px;cursor:pointer;" onmouseout="default_user1()">
                            <img src="img/open_in_browser-26.png" width="25px"> Excel file
                        </label>
                    </div>
                    <br />

                    <button class="btn pull-left btn-info" style="font-weight: bold;border-style:none;border-radius:0px;" type="submit" id="upload" name="upload"><i class="glyphicon glyphicon-upload"></i> Upload</button>

                </form>
            </div>
        </div>
        <div class="clearfix"></div>

    </div>
</div>

<script>
    function default_user()
    {
        var default_user = '';
        //	document.getElementById('mainfile').value = document.getElementById('mainfiles').value

        document.getElementById('mainfileexcel').value = document.getElementById('mainfilesexcel').value
    }
    function default_user1()
    {
        var default_user = '';
        //	document.getElementById('mainfile').value = document.getElementById('mainfiles').value

        document.getElementById('mainfileexcel2').value = document.getElementById('mainfilesexcel2').value
    }


</script>