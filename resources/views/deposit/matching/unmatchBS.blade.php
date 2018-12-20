<table class="my-table" border="1" style="border-collapse: collapse;width:100%">
    <thead>
    <tr>
        <th class="my-table-th">Bank Date</th>
        <th class="my-table-th">Bank Description</th>
        <th class="my-table-th">Bank Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($unmatchBS as $key => $bs)
        <tr>
            <td>{{date("m/d/Y",strtotime($bs->bank_date))}}</td>
            <td>{{$bs->description}}</td>
            <td>{{number_format($bs->bank_amount)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>