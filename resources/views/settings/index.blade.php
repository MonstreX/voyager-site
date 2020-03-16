@extends('voyager::master')

@section('page_header')
    <h1 class="page-title code-editor">
        <i class="voyager-code"></i>
        <p>{{ $title }}</p>
        <span class="page-description">Управление настройками сайта</span>
    </h1>
@stop

@section('content')

    <div id="gradient_bg"></div>

    <div class="container-fluid">
        @include('voyager::alerts')
    </div>

    <form action="{{ route('voyager.site-settings.save', $key) }}" method="POST" enctype="multipart/form-data">

        {{ csrf_field() }}

        <div class="page-content code container-fluid">
            <div class="panel-body">
                <div class="row">
                    @foreach($config->fields as $key_field => $field)

                        @php $class = isset($field->class)? $field->class : "" @endphp

                        @if(isset($field->type) && $field->type === 'section')
                            <h3 class="col-md-12"><i class="{{$field->icon}}"></i><span>{{$field->label}}</span></h3>
                        @else

                            @php $help_code = !config('voyager.show_dev_tips')? "" : "<span class='config-help'><strong>site_setting</strong>(<i>'".$key.".".$key_field."'</i>)</span>"; @endphp

                            @if($field->type === 'text')
                                <div class="form-group {{ $class }}">
                                    <label for="{{$key_field}}">{{$field->label}}</label> {!! $help_code !!}
                                    <input type="text" id="{{$key_field}}" class="form-control" name="{{$key_field}}" value="{{ $field->value}}">
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
                                            <a href="#" class="voyager-x remove-single-image" style="position:absolute;"></a>
                                            <img src="@if( !filter_var($field->value, FILTER_VALIDATE_URL)){{ get_file( $field->value ) }}@else{{ "" }}@endif"
                                                 style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
                                        </div>
                                    @endif
                                    <input id="{{$key_field}}" type="file" name="{{$key_field}}" accept="image/*">
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
                            @endif
                        @endif
                    @endforeach
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('voyager::settings.save') }}</button>
                </div>

            </div>
        </div>
    </form>

@stop

@section('javascript')
<script>
var ace_editor_element = document.getElementsByClassName("ace_editor");

// For each ace editor element on the page
for(var i = 0; i < ace_editor_element.length; i++)
{
    // Create an ace editor instance
    var ace_editor = ace.edit(ace_editor_element[i].id);
    if(ace_editor_element[i].getAttribute('data-theme')){
        ace_editor.setTheme("ace/theme/" + ace_editor_element[i].getAttribute('data-theme'));
    }

    if(ace_editor_element[i].getAttribute('data-language')){
        ace_editor.getSession().setMode("ace/mode/" + ace_editor_element[i].getAttribute('data-language'));
    }

    ace_editor.on('change', function(event, el) {
        ace_editor_id = el.container.id;
        ace_editor_textarea = document.getElementById(ace_editor_id + '_textarea');
        ace_editor_instance = ace.edit(ace_editor_id);
        ace_editor_textarea.value = ace_editor_instance.getValue();
    });

    // Set auto height of window
    ace_editor.setOptions({
        maxLines: Infinity
    });
}

window.ace.require = window.ace.acequire;

function tinymce_setup_callback(editor)
{
    console.log('setup tinyMCE...');

    editor.settings.external_plugins = {
        'ace': '{{ voyager_extension_asset('js/ace/plugin.js') }}'
    }

    editor.settings.plugins = 'link, image, -ace, textcolor, lists';
    editor.settings.toolbar = 'removeformat | styleselect bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist outdent indent | link image table | ace';

    editor.settings.cleanup = false;
    editor.settings.verify_html = false;
    editor.settings.min_height = 200;
}


$('document').ready(function(){
    $('.toggleswitch').bootstrapToggle();

    $('.page-content').on('click', '.remove-single-image', function () {

        vext.dialogActionRequest({
            'title': '<i class="voyager-trash"></i> {{ __("voyager::generic.delete_question") }}',
            'message': '{{ __("voyager::generic.delete_question") }}',
            'fields': '<input type="hidden" name="remove_image" value="' + $(this).parent().data('field-name') +'">',
            'class': 'vext-dialog-warning',
            'yes': '{{ __('voyager-extension::bread.dialog_button_remove') }}',
            'url': '{{ route('voyager.site-settings.save', $key) }}',
            'method': 'POST',
            'method_field': '',
            'csrf_field': '{{ csrf_field() }}'
        });


    });
});
</script>
@stop
