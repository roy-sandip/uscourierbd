<div class="form-group @if($row) row @endif">
    @if($label && !in_array($type, ['checkbox','radio']))
        <label for="{{ $id }}" class="@if($inline) col-sm-4 @endif" >{{ $label }}</label>
    @endif

    @if(in_array($type, ['checkbox','radio']))
        <div class="form-check">
            <input
                id="{{ $id }}"
                name="{{ $arrayName() }}"
                type="{{ $type }}"
                value="{{ $value ?? 1 }}"
                class="form-check-input {{ $class }} @error($name) is-invalid @enderror"
                @checked(old($name, $value))
                {{ $attributes }}
            >
            @if($label)
                <label class="form-check-label" for="{{ $id }}">
                    {{ $label }}
                </label>
            @endif
        </div>
    @elseif($prepend || $append)
        <div class="input-group input-group-{{ $size }} @if($inline) col-sm-8 @endif">
            {{-- Prepend --}}
            @if($prepend)
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        @if(Str::startsWith($prepend, 'icon:'))
                            <i class="{{ Str::after($prepend, 'icon:') }}"></i>
                        @else
                            {!! $prepend !!}
                        @endif
                    </span>
                </div>
            @endif

            <input 
                id="{{ $id }}"
                name="{{ $arrayName() }}" 
                type="{{ $type }}" 
                placeholder="{{ $placeholder }}" 
                value="{{ old($name, $value) }}"
                class="form-control form-control-{{ $size }} {{ $class }} @error($name) is-invalid @enderror"
                {{ $attributes }}
            >

            {{-- Append --}}
            @if($append)
                <div class="input-group-append">
                    <span class="input-group-text">
                        @if(Str::startsWith($append, 'icon:'))
                            <i class="{{ Str::after($append, 'icon:') }}"></i>
                        @else
                            {!! $append !!}
                        @endif
                    </span>
                </div>
            @endif
        </div>
    @else
        <input 
            id="{{ $id }}"
            name="{{ $arrayName() }}" 
            type="{{ $type }}" 
            placeholder="{{ $placeholder }}" 
            value="{{ old($name, $value) }}"
            class="form-control form-control-{{ $size }} @if($inline) col-sm-4 @endif {{ $class }} @error($name) is-invalid @enderror"
            {{ $attributes }}
        >
    @endif

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
