<table class="my-table" border="1" style="border-collapse: collapse;width: 100%;">
    <thead>
    <tr>
        <th class="my-table-th">Entry No</th>
        <th class="my-table-th">Book Date</th>
        <th class="my-table-th">Doc No</th>
        <th class="my-table-th">Ext Doc No</th>
        <th class="my-table-th">Book Amount</th>
        <th class="my-table-th">User ID</th>
        <th class="my-table-th">Description</th>

    </tr>
    </thead>
    <tbody>
    @foreach($unmatchBK as $key => $bk)
        <tr>
            <td>{{$bk->entry_no}}</td>
            <td>{{date('m/d/Y',strtotime($bk->posting_date))}}</td>
            <td>{{$bk->doc_no}}</td>
            <td>{{$bk->ext_doc_no}}</td>
            <td>{{number_format($bk->amount,2)}}</td>
            <td>{{$bk->users}}</td>
            <td>{{$bk->description}}</td>
        </tr>
    @endforeach
    </tbody>
</table>