@props(['label' => 'null', 'name' => null, 'value' => null])
<div class="form-group">
    <label><?= $label ?></label>
    @php
        $is_invalid = $errors->has($name) ? ' is-invalid' : '';
    @endphp
    <input name="{{ $name }}" value="{{ old($name, $value) }}"
        {{ $attributes->merge([
            'class' => 'form-control form-control-sm' . $is_invalid . ' change-background',
        ]) }} autofocus />
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>


@push('js')
    <script>
        const inputs = document.querySelectorAll('.change-background');
        inputs.forEach(input => {
            input.addEventListener('focus', event => {
                event.target.style.backgroundColor = 'pink';
            });
            input.addEventListener('blur', event => {
                if (event.target.value !== '') {
                    event.target.style.backgroundColor = 'lightblue';
                } else {
                    event.target.style.backgroundColor = '';
                }
            });
            input.addEventListener('change', event => {
                if (event.target.value !== '') {
                    event.target.style.backgroundColor = 'lightblue';
                } else {
                    event.target.style.backgroundColor = '';
                }
            });
        });
    </script>
@endpush
