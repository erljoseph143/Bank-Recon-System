<table class="table table-striped">
    <thead>
        <tr>
            @foreach($header as $head)
                <th>{{$head}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($result as $key => $data)
            <tr>
                @foreach($data as $key2 => $d)
                    <td>{{ $d}}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>