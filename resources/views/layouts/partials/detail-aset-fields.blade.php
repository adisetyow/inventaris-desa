<div class="row g-3">
    @foreach($fields as $fieldName => $fieldConfig)
        <div class="{{ $fieldConfig['col'] ?? 'col-md-6' }}">
            <label for="{{ $fieldName }}" class="form-label">{{ $fieldConfig['label'] }}</label>

            @if($fieldConfig['type'] === 'textarea')
                <textarea class="form-control" id="{{ $fieldName }}" name="{{ $fieldName }}" rows="3"></textarea>
            @elseif($fieldConfig['type'] === 'select')
                <select class="form-select" id="{{ $fieldName }}" name="{{ $fieldName }}">
                    @foreach($fieldConfig['options'] as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            @else
                <input type="{{ $fieldConfig['type'] }}" class="form-control" id="{{ $fieldName }}" name="{{ $fieldName }}"
                    @if(isset($fieldConfig['step'])) step="{{ $fieldConfig['step'] }}" @endif
                    @if(isset($fieldConfig['required']) && $fieldConfig['required']) required @endif>
            @endif

            @if(isset($fieldConfig['help']))
                <div class="form-text">{{ $fieldConfig['help'] }}</div>
            @endif
        </div>
    @endforeach
</div>