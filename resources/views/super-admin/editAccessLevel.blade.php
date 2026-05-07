<x-default-layout>
    @section('pagetitle', 'Edit Access Level')

    <style>
        :root {
            --gold: #F9C301;
            --gold-dark: #d9a900;
            --gold-light: #FFF8DC;
            --text-dark: #1a1a2e;
            --text-mid: #4a4a6a;
            --text-light: #8888aa;
            --border: #e8e8f0;
            --bg-page: #f5f6fa;
            --bg-card: #ffffff;
            --radius: 14px;
            --shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        }

        body {
            background: var(--bg-page);
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .page-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .page-header .subtitle {
            font-size: 13px;
            color: var(--text-light);
            margin-top: 2px;
        }

        .btn-back-top {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text-mid);
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-back-top:hover {
            background: var(--gold-light);
            border-color: var(--gold);
            color: var(--text-dark);
        }

        .alert-custom {
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .alert-success-custom {
            background: #edfaf1;
            border-left: 4px solid #27ae60;
            color: #1e7e4a;
        }

        .alert-error-custom {
            background: #fdf0f0;
            border-left: 4px solid #e74c3c;
            color: #b03030;
        }

        .role-select-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 22px 26px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 18px;
            border: 1px solid var(--border);
        }

        .role-icon {
            width: 46px;
            height: 46px;
            background: var(--gold-light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--gold-dark);
            flex-shrink: 0;
        }

        .role-select-card label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 5px;
            display: block;
        }

        .role-select-card select {
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            background: #fafafa;
            outline: none;
            min-width: 240px;
            cursor: pointer;
            transition: border .2s;
        }

        .role-select-card select:focus {
            border-color: var(--gold);
            background: #fff;
        }

        .section-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 18px;
            overflow: hidden;
        }

        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 22px;
            border-bottom: 1px solid var(--border);
            background: #fafbff;
            cursor: pointer;
            user-select: none;
        }

        .section-head-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .icon-player {
            background: #e8f4fd;
            color: #2980b9;
        }

        .icon-coach {
            background: #eafaf1;
            color: #27ae60;
        }

        .icon-filter {
            background: #fef9e7;
            color: #d4ac0d;
        }

        .icon-other {
            background: #f5eef8;
            color: #8e44ad;
        }

        .section-head h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .section-count {
            font-size: 11px;
            color: var(--text-light);
            background: var(--border);
            padding: 2px 8px;
            border-radius: 20px;
            margin-left: 4px;
        }

        .select-all-btn {
            font-size: 12px;
            font-weight: 600;
            color: var(--gold-dark);
            background: var(--gold-light);
            border: none;
            padding: 4px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: background .2s;
        }

        .select-all-btn:hover {
            background: #ffe58a;
        }

        .section-body {
            padding: 18px 22px;
        }

        .perm-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 10px;
        }

        .perm-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 9px;
            border: 1.5px solid var(--border);
            background: #fafafa;
            cursor: pointer;
            transition: all .18s;
        }

        .perm-item:hover {
            border-color: var(--gold);
            background: var(--gold-light);
        }

        .perm-item.active {
            border-color: var(--gold);
            background: var(--gold-light);
        }

        .perm-item input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: var(--gold-dark);
            cursor: pointer;
            flex-shrink: 0;
        }

        .perm-item label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-mid);
            margin: 0;
            cursor: pointer;
            line-height: 1.3;
        }

        .perm-item.active label {
            color: var(--text-dark);
            font-weight: 600;
        }

        .form-footer {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 18px 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 6px;
        }

        .footer-summary {
            font-size: 13px;
            color: var(--text-light);
        }

        .footer-summary span {
            font-weight: 700;
            color: var(--gold-dark);
            font-size: 16px;
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gold);
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            padding: 11px 32px;
            border-radius: 9px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(249, 195, 1, .35);
            transition: all .2s;
        }

        .btn-save:hover {
            background: var(--gold-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(249, 195, 1, .45);
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: var(--text-mid);
            font-weight: 600;
            font-size: 14px;
            padding: 11px 22px;
            border-radius: 9px;
            border: 1.5px solid var(--border);
            text-decoration: none;
            transition: all .2s;
            margin-right: 10px;
        }

        .btn-cancel:hover {
            background: #f1f1f8;
            color: var(--text-dark);
        }
    </style>

    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert-custom alert-success-custom">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert-custom alert-error-custom">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('updateAccessLevels', $accessLevel->id) }}" method="POST" id="accessForm">
                @csrf
                @php
                    $sections = [
                        'player' => [
                            'label' => 'Player Permissions',
                            'icon' => 'bi-person-fill',
                            'cls' => 'icon-player',
                            'fields' => [
                                'player_view' => 'View Player',
                                'player_list' => 'Player List',
                                'player_overview' => 'Overview',
                                'player_attendance_list' => 'Attendance List',
                                'player_attendance_summury' => 'Attendance Summary',
                                'edit_player_attendance' => 'Edit Attendance',
                                'player_assesment' => 'Assessment',
                                'player_assessment_add' => 'Assessment Add',
                                'player_assessment_edit' => 'Assessment Edit',
                                'player_batch_logs' => 'Batch Logs',
                                'player_batch_log_add' => 'Batch Log Add',
                                'player_batch_log_edit' => 'Batch Log Edit',
                                'player_fees_details' => 'Fees Details',
                                'player_fees_details_add' => 'Fees Add',
                                'player_documents' => 'Documents',
                                'player_documents_add' => 'Documents Add',
                            ],
                        ],
                        'coach' => [
                            'label' => 'Coach Permissions',
                            'icon' => 'bi-person-workspace',
                            'cls' => 'icon-coach',
                            'fields' => [
                                'coach_view' => 'View Coach',
                                'coach_list' => 'Coach List',
                                'coach_attendance_list' => 'Attendance List',
                                'coach_edit_attendance' => 'Edit Coach Attendance',
                                'coach_overview' => 'Coach Overview',
                                'coach_batch_log' => 'Coach Batch Log',
                            ],
                        ],
                        'filter' => [
                            'label' => 'Filter Permissions',
                            'icon' => 'bi-funnel-fill',
                            'cls' => 'icon-filter',
                            'fields' => [
                                'batch_player_filter' => 'Batch Filter',
                                'coach_player_filter' => 'Coach Filter',
                                'in_time_player_filter' => 'In-Time Filter',
                                'out_time_player_filter' => 'Out-Time Filter',
                            ],
                        ],
                        'other' => [
                            'label' => 'Other Permissions',
                            'icon' => 'bi-shield-fill-check',
                            'cls' => 'icon-other',
                            'fields' => [
                                'login_user_attendance' => 'Login User Attendance',
                            ],
                        ],
                    ];
                @endphp

                @foreach ($sections as $sectionKey => $section)
                    @foreach ($section['fields'] as $field => $label)
                        <input type="hidden" name="{{ $field }}" value="0">
                    @endforeach
                @endforeach
                <div class="role-select-card">
                    <div class="role-icon"><i class="bi bi-person-badge-fill"></i></div>
                    <div style="flex:1">
                        <label for="role_id">Assign Role</label>
                        <select name="role_id" id="role_id" disabled
                            style="pointer-events:none; background:#f5f5f5; border:none; cursor:default;">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ $accessLevel->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->role_name }}
                                </option>
                            @endforeach
                        </select>
                        {{-- disabled fields are not submitted, so add hidden input --}}
                        <input type="hidden" name="role_id" value="{{ $accessLevel->role_id }}">
                    </div>
                </div>

                @foreach ($sections as $sectionKey => $section)
                    <div class="section-card">
                        <div class="section-head" onclick="toggleSection('{{ $sectionKey }}')">
                            <div class="section-head-left">
                                <div class="section-icon {{ $section['cls'] }}">
                                    <i class="bi {{ $section['icon'] }}"></i>
                                </div>
                                <h5>
                                    {{ $section['label'] }}
                                    <span class="section-count">{{ count($section['fields']) }}</span>
                                </h5>
                            </div>
                            <button type="button" class="select-all-btn"
                                onclick="event.stopPropagation(); selectAll('{{ $sectionKey }}', this)">
                                Select All
                            </button>
                        </div>
                        <div class="section-body" id="section-{{ $sectionKey }}">
                            <div class="perm-grid">
                                @foreach ($section['fields'] as $field => $label)
                                    @php $checked = $accessLevel->$field ?? 0; @endphp
                                    <div class="perm-item {{ $checked ? 'active' : '' }}"
                                        id="item-{{ $field }}" onclick="toggleCheck('{{ $field }}')">
                                        <input type="checkbox" id="{{ $field }}" name="{{ $field }}"
                                            value="1" data-section="{{ $sectionKey }}"
                                            {{ $checked ? 'checked' : '' }} onclick="event.stopPropagation()"
                                            onchange="syncItem('{{ $field }}', this)">
                                        <label for="{{ $field }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="form-footer">
                    <div class="footer-summary">
                        <span id="checkedCount">0</span> permissions enabled
                    </div>
                    <div>
                        <a href="{{ route('accessLevels') }}" class="btn-cancel"> Cancel
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="bi bi-floppy-fill"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function toggleCheck(field) {
            const cb = document.getElementById(field);
            cb.checked = !cb.checked;
            syncItem(field, cb);
        }

        function syncItem(field, cb) {
            const item = document.getElementById('item-' + field);
            if (cb.checked) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
            updateCount();
        }

        function selectAll(sectionKey, btn) {
            const boxes = document.querySelectorAll(`input[data-section="${sectionKey}"]`);
            const allChecked = [...boxes].every(cb => cb.checked);
            boxes.forEach(cb => {
                cb.checked = !allChecked;
                syncItem(cb.id, cb);
            });
            btn.textContent = allChecked ? 'Select All' : 'Deselect All';
        }

        function toggleSection(key) {
            const body = document.getElementById('section-' + key);
            body.style.display = (body.style.display === 'none') ? '' : 'none';
        }

        function updateCount() {
            const total = document.querySelectorAll('input[type="checkbox"]:checked').length;
            document.getElementById('checkedCount').textContent = total;
        }

        document.addEventListener('DOMContentLoaded', updateCount);
    </script>
</x-default-layout>
