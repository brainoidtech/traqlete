<x-default-layout>
    @section('pagetitle', $data['pagetitle'])


    <style>
        /* .pills-sm.active {
     background: #f9c301 !important;
	 padding: 7px 20px !important;
	}	

	.pills-sm{
     border: 1px solid #f9c301 !important;
	 padding: 7px 20px !important;
	 color: #000 !important;
	}	 */

        .app-main {
            margin-top: 0px !important;
        }

        .dcg-btn {
            padding: 5px 15px !important;
        }

        /* Remove bottom border of the last row */
        tbody tr:last-child td {
            border-bottom: none;
        }

        thead tr th {
            border-top: none;
        }
    </style>

    <!-- Buttons -->

    <div class="dashboard-btns">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-lg-5 px-3">
            <button class="btn btn-warning text-black dcg-btn" type="button"><a href="{{ route('add-player') }}"
                    style="text-decoration:none; color: inherit;">Add Player </a></button>
        </div>
    </div>

    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-xl-0 mb-sm-10 my-2">
        <!--begin::Col-->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mt-lg-3">
            <a href="{{ route('player-list') }}" style="text-decoration: none;">
            <!--begin::Card widget 7-->
            <div class="card db-tile card-flush ">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <div class="d-flex flex-column">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold txt-yellow me-2 lh-1 ls-n2">{{ $totalPlayer }}</span>
                        <!--end::Amount-->
                    </div>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body d-flex pe-0">
                    <img src="{{ asset('assets/images/logos/players.svg') }}" alt="players icon" class="img-fluid"
                        height="20px" width="20px">
                    <!--begin::Title-->
                    <span class="txt-yellow fs-3 fw-bold ps-2">Total Players</span>
                    <!--end::Title-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card widget 7-->
            </a>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mt-lg-3">
            <a href="{{ route('coach-list') }}" style="text-decoration: none;">
            <div class="card db-tile card-flush ">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <div class=" d-flex flex-column">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold txt-red me-2 lh-1 ls-n2">{{ $totalCoach }}</span>
                        <!--end::Amount-->
                    </div>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body d-flex pe-0">
                    <img src="{{ asset('assets/images/logos/coaches.svg') }}" alt="players icon" class="img-fluid"
                        height="20px" width="20px">
                    <!--begin::Title-->
                    <span class="txt-red fs-3 fw-bold ps-2">Total Coaches</span>
                    <!--end::Title-->
                </div>
                <!--end::Card body-->
            </div>
            </a>

        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mt-lg-3">
            <a href="{{ route('player-list') }}?tab=dormantplayers_tab" style="text-decoration: none;">
            <div class="card db-tile card-flush ">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <div class=" d-flex flex-column">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold txt-blue me-2 lh-1 ls-n2">{{ $totalDormantPlayer }}</span>
                        <!--end::Amount-->
                    </div>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body d-flex pe-0">
                    <!-- <img src="{{ asset('assets/images/logos/coaches.svg') }}" alt="players icon" class="img-fluid"
                        height="20px" width="20px"> -->
                    <!--begin::Title-->
                    <span class="txt-blue fs-3 fw-bold ps-2">Total Dormant Players</span>
                    <!--end::Title-->
                </div>
                <!--end::Card body-->
            </div>
            </a>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mt-lg-3 py-0">
            <a href="{{ route('attendance-list') }}" style="text-decoration: none;">
            <div class="card db-tile card-flush py-0">

                <!--begin::Card body-->
                <div class="card-body d-flex pe-0" style="padding-top:10px !important; padding-bottom:8px !important;">
                    <img src="{{ asset('assets/images/logos/coaches.svg') }}" alt="players icon" class="img-fluid"
                        height="20px" width="20px">
                    <!--begin::Title-->
                    <span class="txt-blue ps-2">Today's Attendance</span>
                    <!--end::Title-->
                </div>
                <!--end::Card body-->

                <!--begin::Card body-->
                <div class="card-body d-flex pe-0" style="padding-top:10px !important; padding-bottom:8px !important;">
                    <img src="{{ asset('assets/images/logos/players.svg') }}" alt="players icon" class="img-fluid"
                        height="20px" width="20px">
                    <!--begin::Title-->
                    <span class="txt-yellow ps-2">{{ $totalPlayerAttend }} (Players)</span>
                    <!--end::Title-->
                </div>
                <!--end::Card body-->

                <!--begin::Card body-->
                <div class="card-body d-flex pe-0" style="padding-top:10px !important; padding-bottom:8px !important;">
                    <img src="{{ asset('assets/images/logos/coaches.svg') }}" alt="players icon" class="img-fluid"
                        height="20px" width="20px">
                    <!--begin::Title-->
                    <span class="txt-red ps-2">{{ $totalCoachAttend }} (Coaches)</span>
                    <!--end::Title-->
                </div>
                <!--end::Card body-->

            </div>
            </a>
        </div>
        <!--end::Col-->

    </div>
    <!--end::Row-->

    <!--begin::Row-->
    <div class="row gx-5 gx-xl-10 mt-10 my-2">
        <!--begin::Col-->
        <div class="col-lg-6 mb-md-10 my-3">
            <!--begin::Chart widget 8-->
            <div class="card table-sec card-flush h-xl-100">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="txt-yellow fs-1 fw-bold">Live Player Attendance</span>
                    </h3>
                    <button class="btn btn-warning text-black dcg-btn" type="button"><a href="{{ route('attendance-list') }}?tab=player_tab"
                            style="text-decoration:none; color: inherit;">View All </a></button>

                    <!--end::Title-->
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body p-0-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="width: 8%;">No.</th>
                                    <th style="width: 30%;">Player</th>
                                    <th style="width: 18%;">Batch</th>
                                    <th style="width: 22%;">In Time</th>
                                    <th style="width: 25%;">Out Time</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @forelse($live_player_attend as $index => $player_attend)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $player_attend->first_name.' '.$player_attend->middle_name.' '.$player_attend->last_name }}
                                    </td>
                                    <td>{{ $player_attend->batch_name }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $player_attend->in_time)->format('g:i:s A') }}
                                    </td>

                                    <td>{{ $player_attend->out_time ? \Carbon\Carbon::parse($player_attend->out_time)->format('g:i:s A') : '-' }}
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No record found!</td>
                                </tr>
                                @endforelse



                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Chart widget 8-->

        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-lg-6 mb-md-10 my-3">
            <!--begin::Chart widget 8-->
            <div class="card table-sec card-flush h-xl-100">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="txt-red fs-1 fw-bold">Live Coach Attendance</span>
                    </h3>
                    <!--end::Title-->
                    <button class="btn btn-warning text-black dcg-btn" type="button"><a href="{{ route('attendance-list') }}?tab=coach_tab"
                            style="text-decoration:none; color: inherit;">View All </a></button>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body p-0-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="width: 8%;">No.</th>
                                    <th style="width: 30%;">Coach</th>
                                    <th style="width: 18%;">Batch</th>
                                    <th style="width: 22%;">In Time</th>
                                    <th style="width: 25%;">Out Time</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @forelse($live_coach_attend as $index => $coach_attend)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $coach_attend->first_name.' '.$coach_attend->middle_name.' '.$coach_attend->last_name }}
                                    </td>
                                    <td>{{ $coach_attend->batch_name }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $coach_attend->in_time)->format('g:i:s A') }}
                                    </td>

                                    <td>{{ $coach_attend->out_time ? \Carbon\Carbon::parse($coach_attend->out_time)->format('g:i:s A') : '-' }}
                                    </td>
                                </tr>
                                 @empty
                                <tr>
                                    <td colspan="5" class="text-center">No record found!</td>
                                </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Chart widget 8-->

        </div>
        <!--end::Col-->

    </div>
    <!--end::Row-->

    <div class="row mb-5 gx-5 gx-xl-10">

        <div class="col-lg-6 mb-md-10 my-3 my-lg-0">

            <div class="card table-sec card-flush h-xl-100">

                <div class="card-header pt-5">

                    <h3 class="card-title align-items-start flex-column">
                        <span class="fs-1 fw-bold">Upcoming Fees Validity Expiry</span>
                    </h3>
                    <button class="btn btn-warning text-black dcg-btn" type="button"><a href="{{ route('fee-validity') }}"
                            style="text-decoration:none; color: inherit;">View All </a></button>

                </div>

                <div class="card-body p-0-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="width: 10%;">No.</th>
                                    <th style="width: 30%;">Player</th>
                                    <th style="width: 20%;">Batch</th>
                                    <th style="width: 20%;">Validity Till</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @forelse($upcoming_fee_validity as $index => $fee_validity)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $fee_validity->first_name.' '.$fee_validity->middle_name.' '.$fee_validity->last_name}}
                                    </td>
                                    <td>{{ $fee_validity->batch_name }}</td>
                                    <td>{{ $fee_validity->valid_to }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No record found!</td>
                                </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


        </div>

        <div class="col-lg-6 mb-md-10 my-3 my-lg-0">

            <div class="card table-sec card-flush h-xl-100">

                <div class="card-header pt-5">

                    <h3 class="card-title align-items-start flex-column">
                        <span class="fs-1 fw-bold">Expired Fees Validity</span>
                    </h3>

                    <button class="btn btn-warning text-black dcg-btn" type="button"><a href="{{ route('fee-validity') }}"
                            style="text-decoration:none; color: inherit;">View All </a></button>
                </div>

                <div class="card-body p-0-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="width: 10%;">No.</th>
                                    <th style="width: 30%;">Player</th>
                                    <th style="width: 20%;">Batch</th>
                                    <th style="width: 20%;">Expired Date</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @forelse($expired_fee_validity as $index => $expired_fee)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $expired_fee->first_name.' '.$expired_fee->middle_name.' '.$expired_fee->last_name}}
                                    </td>
                                    <td>{{ $expired_fee->batch_name }}</td>
                                    <td>{{ $expired_fee->valid_to }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No record found!</td>
                                </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


        </div>

    </div>


</x-default-layout>