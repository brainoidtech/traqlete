<x-default-layout>
    @section('pagetitle', $data['pagetitle'])

    <style>
        .common-form label {
            color: #686666;
        }

        .pagination li {
            color: #84857E;
            border: 1px solid #DCDCDC;
            border-radius: 10px;
        }

        .pagination li:first-child {
            color: #0000;
            background-color: #F9C301;
            border-radius: 10px;
        }

        .dcg-btn {
            padding: 4px 15px !important;
        }

        .qr_overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 35px;
            height: 35px;
            background-color: #fff;
            padding: 1px;
            border-radius: 2px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .qr-model {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1050;
        }

        .qr-card {
            background: #fff;
            width: 220px;
            padding: 20px 20px 25px;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            position: relative;
            text-align: center;
        }

        .qr-close-outer {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            padding: 10px;
        }

        .qr-close {
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
            color: #333;
        }

        .qr-code-outer {
            margin: 40px 0 15px;
            display: flex;
            justify-content: center;
        }

        #qrModelImg {
            width: 180px;
            height: 180px;
        }

        .qr-btn-outer {
            display: flex;
            justify-content: center;
            padding: 10px;
        }

        .delete-btn i {
            font-size: 40px;
            padding: 10px;
            color: #F16937;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #qrModelImg {
                visibility: visible;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                max-width: 300px;
                max-height: 350px;
            }
        }
    </style>

    {{-- ✅ DYNAMIC DEPARTMENT TABS --}}
    <div class="card mb-5 mb-xxl-8">
        <div class="card-body pt-0 pb-0">
            <ul class="nav nav-tabs mt-4 mb-4">
                @foreach ($deptData as $index => $dept)
                    @php
                        $heightVal = ($dept['foot'] ?? '-') . "'" . ($dept['inch'] ?? '-') . '"';
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ $index === 0 ? 'active' : '' }} fw-semibold" data-bs-toggle="tab"
                            href="#tab_{{ $dept['dept_id'] }}"
                            data-validity="{{ isset($feesData[$dept['dept_id']]) && $feesData[$dept['dept_id']]?->valid_to ? \Carbon\Carbon::parse($feesData[$dept['dept_id']]->valid_to)->format('d M Y') : '-' }}"
                            data-qr="{{ $dept['qr_code'] ? asset($dept['qr_code']) : '' }}">
                            {{ $dept['dept_name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- ✅ PLAYER HEADER (common for all tabs) --}}
    <div class="card mb-5 mb-xxl-8">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap">

                {{-- Player Image + QR --}}
                <div class="me-7 mb-4">
                    @if ($playerBase->player_image)
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ asset($playerBase->player_image) }}" alt="image">
                            <div>
                                @php
                                    $firstDeptId = $deptData[0]['dept_id'];
                                    $firstQrSrc = $deptData[0]['qr_code'] ?? null; // ✅ from deptData directly
                                @endphp

                                @if ($firstQrSrc)
                                    <img src="{{ asset($firstQrSrc) }}" alt="QR Code" id="headerQrOverlay"
                                        onclick="showQr(this.src)" class="qr_overlay">
                                @else
                                    <img src="" alt="QR Code" id="headerQrOverlay" onclick="showQr(this.src)"
                                        class="qr_overlay" style="display:none;">
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Player Info --}}
                <div class="flex-grow-1 mt-14">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                                    {{ $playerBase->first_name }} {{ $playerBase->middle_name }}
                                    {{ $playerBase->last_name }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap flex-stack">
                        <div class="d-flex flex-column flex-grow-1 pe-8">
                            <div class="d-flex flex-wrap">

                                <div class="pe-20 mt-5">
                                    <div class="text-muted me-2 fs-7">Player ID</div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="fs-5 fw-semibold mb-2">
                                            {{ collect($deptData)->pluck('player_id')->implode(', ') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="pe-20 mt-5">
                                    <div class="text-muted me-2 fs-7">HOID</div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="fs-5 fw-semibold mb-2">{{ $playerBase->hoid }}</div>
                                    </div>
                                </div>

                                <div
                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="validation fs-3 fw-bold counted" id="validityBox">
                                            @php
                                                $firstDeptId = $deptData[0]['dept_id'];
                                                $validity = $feesData[$firstDeptId]->valid_to ?? null;
                                            @endphp
                                            {{ $validity ? \Carbon\Carbon::parse($validity)->format('d M Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-dark-500">Validity</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- QR Modal --}}
            <div class="qr-model" id="qrModel">
                <div class="qr-card">
                    <div class="border qr-close-outer">
                        <span class="qr-close" onclick="closeQrModel()">×</span>
                    </div>
                    <div class="qr-code-outer">
                        <img src="" alt="Qr Code" id="qrModelImg">
                    </div>
                    <div class="qr-btn-outer">
                        <button class="qr-print-btn btn btn-warning text-black dcg-btn mx-1" type="button"
                            onclick="printQr(event)">Print</button>
                        <button class="qr-print-btn btn btn-warning text-black dcg-btn mx-1" type="button"
                            onclick="downloadQrBtn()" id="downloadQrBtn">Download</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ✅ DYNAMIC TAB CONTENT --}}
    <div class="tab-content">
        @foreach ($deptData as $index => $dept)
            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="tab_{{ $dept['dept_id'] }}">

                <div class="card mb-5 mb-xl-10">
                    <div class="card table-sec card-flush h-xl-100" style="border:none !important;">
                        <div class="card-header cursor-pointer">
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Profile Details - {{ $dept['dept_name'] }}</h3>
                            </div>
                        </div>
                        <div class="card-body p-0-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Document Number (Aadhar / Passport)</td>
                                            <td>{{ $playerBase->aadhar_number ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{ $playerBase->first_name }} {{ $playerBase->middle_name }}
                                                {{ $playerBase->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Date of Birth</td>
                                            <td>{{ \Carbon\Carbon::parse($playerBase->dob)->format('d-m-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Present Age</td>
                                            <td>{{ $playerBase->age }} Years</td>
                                        </tr>
                                        <tr>
                                            <td>Gender</td>
                                            <td>{{ ucfirst($playerBase->gender) }}</td>
                                        </tr>
                                        <tr>
                                            <td>School Name</td>
                                            <td>{{ $playerBase->school_name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td>{{ $playerBase->address1 }}, {{ $playerBase->address2 }},
                                                {{ $playerBase->area }}, {{ $playerBase->city }},
                                                {{ $playerBase->pincode }}</td>
                                        </tr>
                                        <tr>
                                            <td>Player Contact Number</td>
                                            <td>{{ $playerBase->player_contact_no ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Player Email ID</td>
                                            <td>{{ $playerBase->player_email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Height</td>
                                            <td>{{ $dept['foot'] ?? '-' }}'{{ $dept['inch'] ?? '-' }}"</td>
                                        </tr>
                                        <tr>
                                            <td>Weight</td>
                                            <td>{{ $dept['weight'] ?? '-' }}</td>
                                        </tr>
                                        {{-- Parents loop --}}
                                        @foreach ($playerBase->parents as $parent)
                                            <tr>
                                                <td>Parent {{ $loop->iteration }} Name
                                                    ({{ ucfirst($parent['relation']) }})
                                                </td>
                                                <td>{{ $parent['parent_name'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Parent {{ $loop->iteration }} Email ID</td>
                                                <td>{{ $parent['parent_email'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Parent {{ $loop->iteration }} Contact Number</td>
                                                <td>{{ $parent['parent_contact'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Parent {{ $loop->iteration }} Profession</td>
                                                @if( $parent['parent_profession'] )
                                                <td>{{ $parent['parent_profession'] }} </td>
                                                @else
                                                <td>-</td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td>Player ID</td>
                                            <td>{{ $dept['player_id'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td>{{ $dept['dept_name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>HOID</td>
                                            <td>{{ $playerBase->hoid ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Current Batch</td>
                                            <td>{{ $dept['batch_name'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Current Assigned Coach</td>
                                            <td>{{ ($dept['coach_first_name'] ?? '') . ' ' . ($dept['coach_middle_name'] ?? '') . ' ' . ($dept['coach_last_name'] ?? '') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Player Status</td>
                                            @if ($dept['player_status'] == 1)
                                                <td>Active</td>
                                            @elseif($dept['player_status'] == 2)
                                                <td>Dormant</td>
                                            @elseif($dept['player_status'] == 0)
                                                <td>Left</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        function showQr(src) {
            document.getElementById('qrModelImg').src = src;
            document.getElementById('qrModel').style.display = 'flex';
        }

        function closeQrModel() {
            document.getElementById('qrModel').style.display = 'none';
        }

        function printQr(e) {
            e.preventDefault();
            window.print();
        }

        function downloadQrBtn() {
            const img = document.getElementById("qrModelImg");
            if (!img.src) {
                alert("QR image not found!");
                return;
            }
            const link = document.createElement("a");
            link.href = img.src;
            link.download = "qr-code.png";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        $(document).ready(function() {

            // Restore active tab from hash
            if (window.location.hash) {
                $('.nav-link[href="' + window.location.hash + '"]').tab('show');
            }

            // Tab click event
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {

                // Update validity
                let validity = $(e.target).data('validity');
                $('#validityBox').text(validity);

                // Update QR overlay department-wise
                let qrSrc = $(e.target).data('qr');
                if (qrSrc) {
                    $('#headerQrOverlay').attr('src', qrSrc).show();
                } else {
                    $('#headerQrOverlay').hide();
                }

                if (history.pushState) {
                    history.pushState(null, null, $(e.target).attr('href'));
                } else {
                    window.location.hash = $(e.target).attr('href');
                }

            });

        });
    </script>

</x-default-layout>
