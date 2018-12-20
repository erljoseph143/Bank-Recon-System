@foreach($months as $month)
    <tr>
        <td>{{ $month->bank_date->format('M Y') }}</td>
        <td>
            <span class="backup-status">
                <a data-url="{{ url('admin/backup/extract-json') }}" href="#download" class="on-default open-modal download-json" title="download" data-month="{{ $month->bank_date->format('m') }}" data-year="{{ $month->bank_date->format('Y') }}"><i class="fa fa-cloud-download"></i></a>
            </span>
            <label for="uploaddb" data-url="" href="#upload" class="on-default open-modal" title="upload" data-id=""><i class="fa fa-cloud-upload"></i></label>
            <input id="uploaddb" type="file">
        </td>
    </tr>
@endforeach