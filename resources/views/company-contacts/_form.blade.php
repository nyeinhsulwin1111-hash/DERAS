@csrf

<style>
    /* ===== Green Focus ===== */
    .form-select:focus,
    .form-control:focus {
        border-color: #105c3a !important;
        box-shadow: 0 0 0 0.2rem rgba(16, 92, 58, 0.18) !important;
        outline: none !important;
    }

    /* ===== Uniform height & style for ALL fields ===== */
    .form-select,
    .form-control {
        height: 48px !important;
        font-size: 15px !important;
        border-radius: 8px !important;
        border: 1.5px solid #dee2e6 !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        padding: 0.5rem 0.75rem;
        width: 100% !important;
    }

    /* ===== Labels ===== */
    .form-label {
        font-size: 15px !important;
        font-weight: 600 !important;
        color: #105c3a !important;
        margin-bottom: 8px !important;
        display: block !important;
    }

    .form-label i {
        color: #105c3a !important;
    }

    /* ===== Status Switch ===== */
    .status-switch {
        position: relative;
        width: 74px;
        height: 36px;
        border-radius: 999px;
        background: gray;
        cursor: pointer;
        transition: .25s ease;
        user-select: none;
    }
    .status-switch.active {
        background: var(--deras-leaf);
    }
    .status-knob {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 30px;
        height: 30px;
        background: #fff;
        border-radius: 50%;
        transition: .25s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,.25);
    }
    .status-switch.active .status-knob {
        transform: translateX(38px);
    }

    /* ===== Buttons ===== */
    .btn-back {
        background: #ffffff;
        border: 1.5px solid #105c3a;
        color: #105c3a;
        border-radius: 8px;
        padding: 9px 24px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-back:hover {
        background: #051c14;
        color: #ffffff;
        text-decoration: none;
        border-color: #051c14;
    }

    .btn-save {
        background: #072a1e;
        border: none;
        color: #fff;
        border-radius: 8px;
        padding: 9px 24px;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(7, 42, 30, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    .btn-save:hover {
        background: #051c14;
        color: #fff;
        box-shadow: 0 4px 14px rgba(7, 42, 30, 0.35);
        transform: translateY(-1px);
    }

    /* ===== Form Footer ===== */
    .tb-form-footer {
        background: #f8fbf9;
        border-top: 1px solid #d1fae5;
        margin: 28px -28px -24px -28px;
        padding: 16px 28px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
</style>

{{-- Row 1: ကုမ္ပဏီအမည် & Lot --}}
<div class="row mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
        <label class="form-label"><i class="fas fa-building me-1"></i> ကုမ္ပဏီအမည် <span class="text-danger">*</span></label>
        <input type="text" name="company_name" class="form-control"
            value="{{ old('company_name', $companyContact->company_name ?? '') }}" placeholder="ကုမ္ပဏီအမည် ထည့်သွင်းပါ" required>
    </div>
    <div class="col-md-6">
        <label class="form-label"><i class="fas fa-hashtag me-1"></i> Lot <span class="text-danger">*</span></label>
        <input type="text" name="lot" class="form-control"
            value="{{ old('lot', $companyContact->lot ?? '') }}" placeholder="Lot ထည့်သွင်းပါ" required>
    </div>
</div>

{{-- Row 2: တာဝန်ခံအမည် & ဖုန်းနံပါတ် --}}
<div class="row mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
        <label class="form-label"><i class="fas fa-user-tie me-1"></i> တာဝန်ခံအမည် <span class="text-danger">*</span></label>
        <input type="text" name="responsible_name" class="form-control"
            value="{{ old('responsible_name', $companyContact->responsible_name ?? '') }}" placeholder="တာဝန်ခံအမည် ထည့်သွင်းပါ" required>
    </div>
    <div class="col-md-6">
        <label class="form-label"><i class="fas fa-phone me-1"></i> ဖုန်းနံပါတ် <span class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control"
            value="{{ old('phone', $companyContact->phone ?? '') }}" placeholder="ဖုန်းနံပါတ် ထည့်သွင်းပါ" required data-validate="phone">
    </div>
</div>

{{-- Status Toggle --}}
<div class="mb-4">
    <label class="form-label"><i class="fas fa-toggle-on me-1"></i> အခြေအနေ</label>
    <input type="hidden" name="is_active" id="is_active" value="{{ old('is_active', $companyContact->is_active ?? 1) }}">
    <div id="statusToggle" class="status-switch {{ old('is_active', $companyContact->is_active ?? 1) ? 'active' : '' }}">
        <div class="status-knob"></div>
    </div>
</div>

<div class="tb-form-footer">
    <a href="{{ route('company-contacts.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> နောက်သို့
    </a>
    <button type="submit" class="btn-save">
        <i class="fas {{ isset($companyContact) ? 'fa-pen' : 'fa-save' }}"></i>
        {{ isset($companyContact) ? 'ပြင်ဆင်ရန်' : 'သိမ်းဆည်းရန်' }}
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const t = document.getElementById('statusToggle'), i = document.getElementById('is_active');
        if (t && i) {
            t.addEventListener('click', function() {
                this.classList.toggle('active');
                i.value = this.classList.contains('active') ? 1 : 0;
            });
        }
    });
</script>
