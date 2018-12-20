<table id="bank-acct-list" class="table table-condensed table-hover">
    <thead>
    <tr>
        <th>Bank Name</th>
        <th>Account No</th>
        <th>Account Name</th>
        <th>Action</th>

    </tr>
    </thead>
    <tbody>
    @foreach($banks as $key => $b)

        <tr>
            <td>{{$b->bank}}</td>
            <td>{{$b->accountno}}</td>
            <td>
                {{$b->accountname}}
            </td>
            <td>
                <button class="btn btn-default btn-xs view-file" data-bank="{{$b->id}}" >
                    <i class="glyphicon glyphicon-zoom-in"></i>
                    View
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>


@push('scripts')
    <script>
        $(".view-file").click(function(){
            var bankID = $(this).data('bank');
            $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
            $.ajax({
                type:'get',
                url:'{{url('deposit/fileList')}}/'+bankID,
                success:function(data)
                {
                    $("#content").html(data);
                }
            });
        });

        $("#bank-acct-list").DataTable({'aaSorting':[]});
    </script>
@endpush