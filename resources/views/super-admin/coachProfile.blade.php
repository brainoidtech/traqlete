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
    </style>

    @php
        $activeTab = request('tab', 'tab_' . $coachRecords->first()->id);
    @endphp
    <div class="card mb-5 mb-xxl-8">
        <div class="card-body pt-0 pb-0">
            <ul class="nav nav-tabs mt-4 mb-4">
                @foreach ($coachRecords as $record)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'tab_' . $record->id ? 'active' : '' }} fw-semibold"
                            data-bs-toggle="tab" href="#tab_{{ $record->id }}">
                            {{ $record->department->dept_name ?? 'N/A' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="card mb-5 mb-xxl-8">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap">
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <img src="{{ asset($coachProfile->coach_image) }}" alt="image">
                    </div>
                </div>
                <div class="flex-grow-1 mt-14">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                                    {{ $coachProfile->fullName }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap flex-stack">
                        <div class="d-flex flex-column flex-grow-1 pe-8">
                            <div class="d-flex flex-wrap">
                                <div class="pe-20 mt-5">
                                    <div class="text-muted me-2 fs-7">Coach ID</div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="fs-5 fw-semibold mb-2">
                                            {{ $coachRecords->pluck('id')->implode(', ') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content pb-5">
        @foreach ($coachRecords as $record)
            @php
                $attendance = $allAttendance[$record->id] ?? collect();
            @endphp
            <div class="tab-pane fade {{ $activeTab === 'tab_' . $record->id ? 'show active' : '' }}"
                id="tab_{{ $record->id }}">
                <div class="card mb-5 mb-xl-10">
                    <div class="card table-sec card-flush h-xl-100" style="border:none !important;">
                        <div class="card-header cursor-pointer">
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">
                                    Profile Details - {{ $record->department->dept_name ?? 'N/A' }}
                                </h3>
                            </div>
                        </div>
                        <div class="card-body p-0-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Document Number</td>
                                            <td>{{ $record->document }}</td>
                                        </tr>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{ $record->fullName }}</td>
                                        </tr>
                                        <tr>
                                            <td>Date of Birth</td>
                                            <td>{{ $record->dob }}</td>
                                        </tr>
                                        <tr>
                                            <td>Present Age</td>
                                            <td>{{ $record->age }}</td>
                                        </tr>
                                        <tr>
                                            <td>Gender</td>
                                            <td>{{ $record->gender }}</td>
                                        </tr>
                                        <tr>
                                            <td>Document Number</td>
                                            <td>{{ $record->document }}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td>{{ $record->fullAddress }}</td>
                                        </tr>
                                        <tr>
                                            <td>Contact Number</td>
                                            <td>{{ $record->coach_contact_no }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email ID</td>
                                            <td>{{ $record->coach_email }}</td>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td>{{ $record->department->dept_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Batch</td>
                                            <td>{{ $record->latestBatch[0]->batch_name ?? 'N/A' }}</td>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</x-default-layout>
