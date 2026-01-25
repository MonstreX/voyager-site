@if(isset($view) && ($view === 'edit' || $view === 'add'))
    <textarea class="form-control" name="{{ $row->field }}" rows="5">{{ old($row->field, $content ?? '') }}</textarea>
@else
    @php
    $urls = explode(PHP_EOL, $content ?? '');
    foreach ($urls as $key => $url) {
        if (empty($url)) {
            unset($urls[$key]);
        }
    }
    $rulesValue = null;
    if (isset($dataTypeContent) && isset($dataTypeContent->rules)) {
        $rulesValue = $dataTypeContent->rules;
    } elseif (isset($data) && isset($data->rules)) {
        $rulesValue = $data->rules;
    }
    @endphp
    <span class="urls-block-list @if((int)$rulesValue === 0) rules-a @else rules-b @endif">
        <ul>
        @foreach($urls as $url)
            @if($loop->index < 5)
            <li>{{ $url }}</li>
            @endif
        @endforeach
        </ul>
    </span>
@endif
