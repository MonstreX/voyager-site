@if(isset($view) && ($view === 'edit' || $view === 'add'))
    @include('voyager::formfields.relationship', ['view' => $view, 'options' => $row->details])
@else
    @php
    $positionColor = null;
    if (isset($data) && isset($data->positionId) && isset($data->positionId->color)) {
        $positionColor = $data->positionId->color;
    }
    @endphp
    @if($positionColor && ($row->type == 'relationship' || (isset($data) && !empty($data->{$row->field}))))
        <span class="badge badge-md region-block-badge" style="background-color: {{ $positionColor }};">
            @if ($row->type == 'relationship')
                @include('voyager::formfields.relationship', ['view' => 'browse','options' => $row->details])
            @else
                {{ $data->{$row->field} }}
            @endif
        </span>
    @endif
@endif
