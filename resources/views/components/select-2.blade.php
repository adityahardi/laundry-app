@props(['name', 'opt'=>[], 'value'=>'', 'disabled'=>'', 'select'=>true, 'all'=>false])
@php
    $value = $value;
@endphp
    <select name="{{ $name }}[]" class="select2-multiple form-control form-control-sm{{ $errors->has($name) ? ' is-invalid' : ''}}" id="{{ $name }}" multiple="multiple" {{ $disabled }}>
        @if ($select)
        <option value="">Select</option>
        @endif
        @if ($all)
        <option value="all">All</option>
        @endif
        @foreach ($opt as $row)
            @if (empty($row->use))

                @if ($value)
                    @php
                        $ada = false;
                    @endphp
                    @foreach ($value as $vel)

                        @if ($row['value'] == $vel->value )
                            <option selected value="{{ $row['value'] }}">{{ $row['option'] }}</option>
                            @php
                                $ada = true;
                            @endphp
                        @endif
                    @endforeach
                    @if (!$ada)
                        <option value="{{ $row['value'] }}">{{ $row['option'] }}</option>
                    @endif
                @else
                        <option value="{{ $row['value'] }}">{{ $row['option'] }}</option>

                @endif
            @elseif (!empty($row->use) && !$row->use)
                @if ($row['value'] == $value)
                    <option selected value="{{ $row['value'] }}">{{ $row['option'] }}</option>
                @else
                    <option value="{{ $row['value'] }}">{{ $row['option'] }}</option>
                @endif
            @endif
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

@push('css')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('adminlte/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2-multiple').select2();
        })
    </script>
@endpush
