@extends('voyager::master')

@section('page_title', $title)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-settings"></i> {{ $title }}
        </h1>
    </div>
@stop

@section('content')

    <div id="gradient_bg"></div>

    <div class="container-fluid">
        @include('voyager::alerts')
    </div>

    <form action="{{ route('voyager.site-settings.save', $key) }}" method="POST" enctype="multipart/form-data">

        {{ csrf_field() }}
        <div class="page-content code container-fluid">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    @php $sectionOpen = false; @endphp
                    @foreach($config->fields as $key_field => $field)

                            @php $class = isset($field->class)? $field->class : "" @endphp

                            @if(isset($field->type) && $field->type === 'section')
                                @if($sectionOpen)
                                    </div>
                                    @php $sectionOpen = false; @endphp
                                @endif
                                <div class="site-settings-section-title">
                                    <h3 class="col-md-12"><i class="{{$field->icon}}"></i><span>{{$field->label}}</span></h3>
                                </div>
                                <div class="site-settings-fields row">
                                    @php $sectionOpen = true; @endphp
                            @else
                                @if(!$sectionOpen)
                                    <div class="site-settings-fields row">
                                        @php $sectionOpen = true; @endphp
                                @endif

                                @php $help_code = !config('voyager.show_dev_tips')? "" : "<span class='config-help'><strong>site_setting</strong>(<i>'".$key.".".$key_field."'</i>)</span>"; @endphp

                                @if($field->type === 'text')
                                    <div class="form-group {{ $class }}">
                                        <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                        <input type="text" id="{{$key_field}}" class="form-control" name="{{$key_field}}" value="{{ $field->value}}">
                                    </div>
                                @elseif($field->type === 'number')
                                    <div class="form-group {{ $class }}">
                                        <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                        <input type="number" id="{{$key_field}}" class="form-control" name="{{$key_field}}" value="{{ $field->value}}">
                                    </div>
                                @elseif($field->type === 'textarea')
                                    <div class="form-group {{ $class }}">
                                        <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                        <textarea id="{{$key_field}}" class="form-control" name="{{$key_field}}">{{ $field->value}}</textarea>
                                    </div>
                                @elseif($field->type === 'checkbox')
                                    <div class="form-group {{ $class }}">
                                        <input type="checkbox" id="{{$key_field}}" name="{{$key_field}}" class="toggleswitch"
                                               data-on="{{$field->on}}" {!! $field->value === "1" ? 'checked="checked"' : '' !!}
                                               data-off="{{$field->off}}">
                                        <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                    </div>
                                @elseif($field->type === 'radio')
                                    <div class="form-group {{ $class }}">
                                        <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                        <ul class="radio">
                                            @if(isset($field->options))
                                                @foreach($field->options as $key_options => $option)
                                                    <li>
                                                        <input type="radio" id="option-{{$key_options}}"
                                                               name="{{$key_field}}"
                                                               value="{{ $key_options }}" @if($field->value == $key_options) checked @endif>
                                                        <label for="option-{{$key_options}}">{{ $option }}</label>
                                                        <div class="check"></div>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                @elseif($field->type === 'dropdown')
                                    <div class="form-group {{ $class }}">
                                        <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                        <select class="form-control select2" name="{{$key_field}}">
                                            @if(isset($field->options))
                                                @foreach($field->options as $key_options => $option)
                                                    <option value="{{ $key_options }}" @if($field->value == $key_options) selected="selected" @endif>{{ $option }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                @elseif($field->type === 'image')
                                    <div class="form-group {{ $class }}">
                                        <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                        @if(isset($field->value) && !empty($field->value))
                                            <div data-field-name="{{$key_field}}">
                                                <a
                                                    href="#"
                                                    class="voyager-x remove-single-image"
                                                    style="position:absolute;"
                                                    data-confirm-target="#confirm_delete_modal"
                                                    data-confirm-callback="Voyager.confirmCallbacks.siteSettingsRemove"
                                                    data-confirm-name="{{ $field->value }}"
                                                    data-remove-type="remove_image"></a>
                                                <img src="@if( !filter_var($field->value, FILTER_VALIDATE_URL)){{ get_file( $field->value ) }}@else{{ "" }}@endif"
                                                     style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
                                            </div>
                                        @endif
                                        <input id="{{$key_field}}" type="file" name="{{$key_field}}" accept="image/*">
                                    </div>
                                @elseif($field->type === 'media')
                                    <div class="form-group {{ $class }}">
                                        <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                        @if(isset($field->value) && !empty($field->value))
                                            @php $media = $settings->getFirstMedia($key_field); @endphp
                                            <div data-field-name="{{$key_field}}">
                                                <a
                                                    href="#"
                                                    class="voyager-x remove-single-media"
                                                    style="position:absolute;"
                                                    data-confirm-target="#confirm_delete_modal"
                                                    data-confirm-callback="Voyager.confirmCallbacks.siteSettingsRemove"
                                                    data-confirm-name="{{ $media ? $media->file_name : '' }}"
                                                    data-remove-type="remove_media"></a>
                                                @if($media)
                                                    @if(explode('/', $media->mime_type)[0] === 'image')
                                                        <img class="image-type" src="{{ $media->fullUrl() }}">
                                                    @else
                                                        <img class="file-type" src="{{ voyager_asset('icons/files/'.explode('/', $media->mime_type)[1].'.svg') }}">
                                                    @endif
                                                @endif
                                            </div>
                                            @if($media)
                                                <span class="adv-media-files-filename">{{ Str::limit($media->file_name, 100, ' (...)') }} <i class="{{ $media->size > 100000 ? 'large' : '' }}">{{ $media->sizeForHumans() }}</i></span>
                                            @endif
                                        @endif
                                        <input id="{{$key_field}}" type="file" name="{{$key_field}}">
                                    </div>
                                @elseif($field->type === 'rich_text_box')
                                    <div class="form-group {{ $class }}">
                                        <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                        <textarea class="form-control richTextBox" name="{{$key_field}}" id="richtext{{$key_field}}">
                                            {{ $field->value}}
                                        </textarea>
                                    </div>
                                @elseif($field->type === 'code_editor')
                                    <div class="form-group {{ $class }}">
                                        <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                        <div id="{{$key_field}}"
                                             @if(isset($field->theme)) data-theme="{{ $field->theme }}" @endif
                                             @if(isset($field->language)) data-language="{{ $field->language }}" @endif
                                             class="ace_editor min_height_200"
                                             name="{{$key_field}}">{{ $field->value }}</div>
                                        <textarea name="{{$key_field}}" id="{{$key_field}}_textarea" class="hidden">{{ $field->value }}</textarea>
                                    </div>
                                @elseif($field->type === 'route')
                                    <div class="form-group {{ $class }}">
                                        <a href="{{ route('voyager.' . $field->value) }}" class="btn btn-primary btn-setting-route">
                                            <i class="{{ $field->icon }}"></i> <span>{{ $field->label }}</span>
                                        </a>
                                    </div>
                                @endif
                            @endif
                    @endforeach
                    @if($sectionOpen)
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ __('voyager::settings.save') }}</button>
            </div>

        </div>
    </form>

    @include('voyager::components.modal-confirm', [
        'id' => 'confirm_delete_modal',
        'title' => __('voyager::generic.are_you_sure'),
        'message' => __('voyager::generic.are_you_sure_delete').' \'<span class="confirm_delete_name"></span>\'',
        'confirmText' => __('voyager::generic.delete_confirm'),
        'confirmClass' => 'btn-danger',
        'icon' => 'voyager-warning'
    ])

@stop

@section('javascript')
<script>
document.addEventListener('DOMContentLoaded', () => {
    window.Voyager = window.Voyager || {};
    window.Voyager.confirmCallbacks = window.Voyager.confirmCallbacks || {};
    window.Voyager.confirmCallbacks.siteSettingsRemove = ({ trigger }) => {
        if (!trigger) {
            return true;
        }
        const form = trigger.closest('form');
        if (!form) {
            return true;
        }
        const removeType = trigger.dataset.removeType;
        const holder = trigger.closest('[data-field-name]');
        const fieldName = holder ? holder.dataset.fieldName : null;
        if (!removeType || !fieldName) {
            return true;
        }
        let input = form.querySelector(`input[name="${removeType}"]`);
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = removeType;
            form.appendChild(input);
        }
        input.value = fieldName;
        form.submit();
        return true;
    };
});
</script>
@stop
