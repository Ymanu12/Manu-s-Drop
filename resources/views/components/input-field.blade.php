@props([
    'name',
    'label',
    'type' => 'text',
    'as' => 'input',
    'id' => null,
    'value' => null,
    'autocomplete' => null,
    'required' => false,
    'autofocus' => false,
    'placeholder' => ' ',
    'wrapperClass' => 'form-floating mb-3 theme-form-field',
    'inputClass' => '',
    'rows' => 6,
])

@php
    $fieldId = $id ?? $name;
    $fieldValue = old($name, $value);
    $isTextarea = $as === 'textarea';
    $hasValueAttribute = ! $isTextarea && ! in_array($type, ['password', 'file'], true);
    $inputClasses = trim('form-control form-control_gray theme-form-control ' . $inputClass . ($errors->has($name) ? ' is-invalid' : ''));
    $labelText = $required ? $label . ' *' : $label;
@endphp

<div class="{{ $wrapperClass }}">
    @if ($isTextarea)
        <textarea
            id="{{ $fieldId }}"
            name="{{ $name }}"
            class="{{ $inputClasses }}"
            placeholder=" "
            rows="{{ $rows }}"
            @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if($required) required @endif
            @if($autofocus) autofocus @endif
            {{ $attributes->except(['class']) }}
        >{{ $fieldValue }}</textarea>
    @else
        <input
            type="{{ $type }}"
            id="{{ $fieldId }}"
            name="{{ $name }}"
            class="{{ $inputClasses }}"
            placeholder=" "
            @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if($required) required @endif
            @if($autofocus) autofocus @endif
            @if($hasValueAttribute) value="{{ $fieldValue }}" @endif
            {{ $attributes->except(['class']) }}
        >
    @endif

    <label for="{{ $fieldId }}">{{ $labelText }}</label>

    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>