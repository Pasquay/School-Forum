@props(['name', 'id', 'value' => '1', 'checked' => false, 'label' => 'Toggle'])

<div class="switch-container">
    <label for="{{ $id }}" class="switch-label">{{ $label }}</label>
    <label class="switch">
        <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}" {{ $checked ? 'checked' : '' }}>
        <span class="slider"></span>
    </label>
</div>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #4a90e2;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #4a90e2;
    }

    input:checked + .slider:before {
        transform: translateX(20px);
    }

    .switch-label {
        font-size: 0.9rem;
        color: #333;
        cursor: pointer;
    }

    .switch-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }
</style>