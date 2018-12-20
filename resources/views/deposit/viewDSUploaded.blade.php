<style>
    .modal-lg{
        width:95%;
    }
</style>


<table id="viewExcel" class="table table-striped table-bordered" width="100%">
    <thead>
    <tr>
        <th>Entry No.</th>
        <th>Bank Account No.</th>
        <th>Posting Date</th>
        <th>Document Type</th>
        <th>Document No.</th>
        <th>External Document No.</th>
        <th>Description</th>
        <th>User ID</th>
        <th>Amount</th>

    </tr>
    </thead>

    <tbody>
        @foreach($excelData as $key => $data)
            <tr>
                <td>{{$data[0]}}</td>
                <td>{{$data[1]}}</td>
                <td>{{$data[2]}}</td>
                <td>{{$data[3]}}</td>
                <td>{{$data[4]}}</td>
                <td>{{$data[5]}}</td>
                <td>{{$data[6]}}</td>
                <td>{{$data[7]}}</td>
                <td style="text-align:right">{{number_format($data[8],2)}}</td>

            </tr>
        @endforeach
    </tbody>

</table>



<script>
    $("#viewExcel").DataTable();
</script>