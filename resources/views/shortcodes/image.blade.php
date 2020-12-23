@if($picture) <picture> @endif
@if($lightbox) <a class="{{ $lightbox_class }}" href="{{ url($image_origin) }}"> @endif

    @if($format === 'webp')
    <source @if($lazy) data-srcset @else srcset @endif="{{ url($image_format) }}" type="image/webp">
    @endif
    <img class="{{ $class }}"
         @if($lazy) data-src="{{ url($image_src) }}" src="data:image/gif;base64,R0lGODlhAQABAAAAACwAAAAAAQABAAA=" @else src="{{ url($image_src) }}" @endif
         alt="{!! $image_alt !!}"
         title="{!! $image_alt !!}"
         {!! $width !!} {!! $height !!}/>

@if($lightbox) </a> @endif
@if($picture) </picture> @endif