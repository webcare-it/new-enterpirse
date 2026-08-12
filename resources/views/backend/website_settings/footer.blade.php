@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="fw-600 mb-0">{{ translate('Footer Widget') }}</h6>
        </div>
        <div class="card-body">
            <div class="row gutters-10">
                <div class="col-lg-6">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('About Widget') }}</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label" for="signinSrEmail">{{ translate('Footer Logo') }}</label>
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="footer_logo">
                                        <input type="hidden" name="footer_logo" class="selected-files"
                                            value="{{ get_setting('footer_logo') }}">
                                    </div>
                                    <div class="file-preview"></div>
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('About description') }}</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="about_us_description">
                                    <textarea class="form-control" name="about_us_description"
                                        data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]'
                                        placeholder="Type.." data-min-height="150">{!! get_setting('about_us_description', null, $lang) !!}
                                </textarea>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Contact Info Widget') }}</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label>{{ translate('Contact phone') }}</label>
                                    <input type="hidden" name="types[]" value="contact_phone">
                                    <input type="text" class="form-control" placeholder="{{ translate('Phone') }}"
                                        name="contact_phone" value="{{ get_setting('contact_phone') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Contact email') }}</label>
                                    <input type="hidden" name="types[]" value="contact_email">
                                    <input type="text" class="form-control" placeholder="{{ translate('Email') }}"
                                        name="contact_email" value="{{ get_setting('contact_email') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Contact address') }}</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="contact_address">
                                    <input type="text" class="form-control" placeholder="{{ translate('Address') }}"
                                        name="contact_address" value="{{ get_setting('contact_address', null, $lang) }}">
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="fw-600 mb-0">{{ translate('Footer Bottom') }}</h6>
        </div>
        <div class="card-body">
            <div class="row gutters-10">
                <div class="col-lg-6">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card shadow-none bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">{{ translate('Social Link Widget') }}</h6>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label>{{ translate('Facebook') }}</label>
                                    <input type="hidden" name="types[]" value="facebook_link">
                                    <input type="text" class="form-control" placeholder="http://" name="facebook_link"
                                        value="{{ get_setting('facebook_link') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Twitter') }}</label>
                                    <input type="hidden" name="types[]" value="twitter_link">
                                    <input type="text" class="form-control" placeholder="http://" name="twitter_link"
                                        value="{{ get_setting('twitter_link') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Instagram') }}</label>
                                    <input type="hidden" name="types[]" value="instagram_link">
                                    <input type="text" class="form-control" placeholder="http://"
                                        name="instagram_link" value="{{ get_setting('instagram_link') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('YouTube') }}</label>
                                    <input type="hidden" name="types[]" value="youtube_link">
                                    <input type="text" class="form-control" placeholder="http://" name="youtube_link"
                                        value="{{ get_setting('youtube_link') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('LinkedIn') }}</label>
                                    <input type="hidden" name="types[]" value="linkedin_link">
                                    <input type="text" class="form-control" placeholder="http://"
                                        name="linkedin_link" value="{{ get_setting('linkedin_link') }}">
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card shadow-none bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">{{ translate('Copyright Widget ') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">{{ translate('Copyright text') }}</label>
                                    <input type="hidden" name="types[]" value="frontend_copyright_text">
                                    <input type="text" class="form-control"
                                        placeholder="{{ translate('Copyright text') }}" name="frontend_copyright_text"
                                        value="{{ get_setting('frontend_copyright_text') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Payment Methods') }}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="payment_method_images">
                                        <input type="hidden" name="payment_method_images" class="selected-files"
                                            value="{{ get_setting('payment_method_images') }}">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h6 class="fw-600 mb-0">{{ translate('Currency Setting') }}</h6>
        </div>
        <div class="card-body">
            <div class="row gutters-10">
                <div class="col-lg-6">
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf
                        <div class="card shadow-none bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">{{ translate('Currency Settings') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">{{ translate('Currency Code') }}</label>
                                    <input type="hidden" name="types[]" value="currency">
                                    <input type="text" class="form-control" name="currency"
                                        value="{{ get_setting('currency', 'BDT') }}">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ translate('Currency Symbol') }}</label>
                                    <input type="hidden" name="types[]" value="currency_symbol">
                                    <input type="text" class="form-control" name="currency_symbol"
                                        value="{{ get_setting('currency_symbol', '৳') }}">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ translate('Show Currency Symbol') }}</label>
                                    <input type="hidden" name="types[]" value="is_currency_symbol">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" name="is_currency_symbol" value="1"
                                                {{ get_setting('is_currency_symbol', true) ? 'checked' : '' }}>
                                            <span class="aiz-square-check"></span>
                                            {{ translate('Yes') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ translate('Currency Position') }}</label>
                                    <input type="hidden" name="types[]" value="currency_position">
                                    <select class="form-control" name="currency_position">
                                        <option value="before"
                                            {{ get_setting('currency_position', 'before') == 'before' ? 'selected' : '' }}>
                                            {{ translate('Before Amount') }} (e.g. $100)
                                        </option>
                                        <option value="after"
                                            {{ get_setting('currency_position', 'before') == 'after' ? 'selected' : '' }}>
                                            {{ translate('After Amount') }} (e.g. 100$)
                                        </option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label class="form-label">{{ translate('Enable Decimals') }}</label>
                                    <input type="hidden" name="types[]" value="is_decimal">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" name="is_decimal" value="1"
                                                {{ get_setting('is_decimal', true) ? 'checked' : '' }}>
                                            <span class="aiz-square-check"></span>
                                            {{ translate('Yes') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ translate('Decimal Digits') }}</label>
                                    <input type="hidden" name="types[]" value="decimal_digits">
                                    <input type="number" class="form-control" name="decimal_digits"
                                        value="{{ get_setting('decimal_digits', 2) }}" min="0" max="4">
                                </div>

                                <div class="text-right">
                                    <button type="submit"
                                        class="btn btn-primary">{{ translate('Save Settings') }}</button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    {{-- <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf
                        <div class="card shadow-none bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">{{ translate('Loyalty Settings') }}</h6>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="types[]" value="loyalty">

                                <div class="form-group">
                                    <label class="form-label">{{ translate('Loyalty Tiers (JSON)') }}</label>
                                    <textarea class="form-control" name="loyalty" rows="6" style="font-family: monospace;">{{ get_setting('loyalty', '{"Star":200,"Gold":900,"Diamond":4000}') }}</textarea>
                                    <small
                                        class="text-muted">{{ translate('Format: {"Star":200, "Gold":900, "Diamond":4000}') }}</small>
                                </div>

                                <div class="text-right">
                                    <button type="submit"
                                        class="btn btn-primary">{{ translate('Save Settings') }}</button>
                                </div>
                            </div>
                        </div>
                    </form> --}}
                    <form action="{{ route('business_settings.update') }}" method="POST" id="loyaltyForm">
                        @csrf
                        <div class="card shadow-none bg-light">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">{{ translate('Loyalty Settings') }}</h6>
                                <button type="button" class="btn btn-sm btn-primary" id="addTierBtn">
                                    <i class="bi bi-plus-circle"></i> {{ translate('Add Tier') }}
                                </button>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="types[]" value="loyalty">
                                <!-- Hidden field that will hold the JSON string -->
                                <input type="hidden" name="loyalty" id="loyaltyJson"
                                    value="{{ get_setting('loyalty', '{"Star":200,"Gold":900,"Diamond":4000}') }}">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="tierTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{ translate('Tier Name') }}</th>
                                                <th>{{ translate('Points Required') }}</th>
                                                <th width="80">{{ translate('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tierTableBody">
                                            <!-- Rows will be inserted here by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> {{ translate('Save Settings') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tbody = document.getElementById('tierTableBody');
            const addBtn = document.getElementById('addTierBtn');
            const jsonInput = document.getElementById('loyaltyJson');
            let tiers = {};
            try {
                const existing = jsonInput.value.trim();
                if (existing) {
                    tiers = JSON.parse(existing);
                }
            } catch (e) {
                console.warn('Invalid JSON, using default');
                tiers = {
                    Star: 200,
                    Gold: 900,
                    Diamond: 4000
                };
            }

            function renderRows() {
                tbody.innerHTML = '';
                for (const [name, points] of Object.entries(tiers)) {
                    addRow(name, points);
                }
                updateJson();
            }

            function addRow(name = '', points = '') {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                <td>
                    <input type="text" class="form-control tier-name" value="${escapeHtml(name)}" placeholder="e.g. Platinum">
                </td>
                <td>
                    <input type="number" class="form-control tier-points" value="${points}" placeholder="e.g. 1500" min="0" step="1">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-tier">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                            <path d="M10 11v6"></path>
                            <path d="M14 11v6"></path>
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                        </svg>
                    </button>
                </td>
                `;
                tbody.appendChild(tr);
                tr.querySelector('.remove-tier').addEventListener('click', function() {
                    tr.remove();
                    rebuildTiersFromRows();
                    updateJson();
                });
                tr.querySelectorAll('input').forEach(input => {
                    input.addEventListener('input', function() {
                        rebuildTiersFromRows();
                        updateJson();
                    });
                });
            }

            function escapeHtml(str) {
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }

            function rebuildTiersFromRows() {
                const rows = tbody.querySelectorAll('tr');
                const newTiers = {};
                rows.forEach(row => {
                    const nameInput = row.querySelector('.tier-name');
                    const pointsInput = row.querySelector('.tier-points');
                    const name = nameInput.value.trim();
                    const points = parseInt(pointsInput.value, 10);
                    if (name && !isNaN(points) && points >= 0) {
                        newTiers[name] = points;
                    }
                });
                tiers = newTiers;
            }

            function updateJson() {
                jsonInput.value = JSON.stringify(tiers);
            }
            addBtn.addEventListener('click', function() {
                addRow();
                const lastRow = tbody.lastElementChild;
                if (lastRow) {
                    lastRow.querySelector('.tier-name').focus();
                }
                updateJson();
            });
            renderRows();
            document.getElementById('loyaltyForm').addEventListener('submit', function() {
                rebuildTiersFromRows();
                updateJson();
            });
        });
    </script>
@endsection
