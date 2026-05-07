
<x-default-layout>
	@section('pagetitle', $data['pagetitle'])

	<style>
		/* .custom-datatable-header {
	display: none !important;
	}

	#player_tbl_info {
		padding-left: 10px;
	} */
		.date-arrow i {
			border: 1px solid #DBD5D5;
			padding: 5px;
			margin-left: 10px;
			margin-right: 10px;
			border-radius: 5px;
		}

		.dcg-btn {
			padding: 6px 15px !important;
		}

		/* @media (min-width: 576px) {
		.filter-modal {
			max-width: var(--bs-modal-width);
			max-width: 300px;
			margin-right: 3% !important;
			margin-left: auto;
		}
		}
		.btn-close{
			border:1px solid #DBD5D5;
			border-radius:50%;

		} */
	</style>

	@php
	$activeTabShow = request('tab', 'player_tab')
	@endphp

	<div class="row gx-5 gx-xl-10">

		<div class="col-12 d-flex flex-wrap justify-content-between mb-md-5">

			<!-- Page Title Section -->
			<div class="col-xxl-4 d-flex justify-content-between mb-2">
				<div class="card-header pt-5">
					<h3 class="card-title align-items-start flex-column text-success">
						<span id="dynamic-card-title" class="fw-bold">Present Players</span> <!-- Default Title -->
					</h3>
				</div>
			</div>

			<div class="form-group d-flex align-items-center date-arrow mb-2">

				<!-- Left arrow -->
				<button type="button" class="btn btn-link" id="prev-date">
					<i class="bi bi-chevron-left"></i> <!-- Bootstrap Icon for left arrow -->
				</button>

				<!-- Date input field -->
				<div class="d-flex align-items-center">
					<input type="date" class="form-control form-control-solid bg-white border border-grey"
						name="date" id="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
						placeholder="Pick date range" />
				</div>

				<!-- Right arrow -->
				<button type="button" class="btn btn-link" id="next-date">
					<i class="bi bi-chevron-right"></i> <!-- Bootstrap Icon for right arrow -->
				</button>
			</div>

			<!-- Tab Navigation -->
			<div class="col-12 col-md-4 col-xxl-4 d-flex justify-content-md-end align-items-center mb-2">
				<div class="card-toolbar">
					<ul class="nav tablist" id="kt_chart_widget_8_tabs" role="tablist">
						<!-- Players Tab Button -->
						<li class="nav-item mx-2" role="presentation">
							<a class="btn btn-border btn-yellow {{ $activeTabShow === 'player_tab' ? 'active' : '' }}" id="kt_chart_widget_8_week_toggle"
								data-bs-toggle="tab" href="#player_tab" role="tab" aria-selected="true">
								Players
							</a>
						</li>

						<!-- Coaches Tab Button -->
						<li class="nav-item mx-0" role="presentation">
							<a class="btn btn-border btn-yellow {{ $activeTabShow === 'coach_tab' ? 'active' : ''}}" id="kt_chart_widget_8_month_toggle"
								data-bs-toggle="tab" href="#coach_tab" role="tab" aria-selected="false">
								Coaches
							</a>
						</li>
					</ul>
				</div>
			</div>

		</div>
	</div>

	<!-- Tab Content Section -->
	<div class="tab-content mb-5">

		<!-- Players Tab Content -->
		<div class="tab-pane fade {{ $activeTabShow === 'player_tab' ? 'active show' : ''}}" id="player_tab" role="tabpanel">
			<div class="card">
				<!-- <div class="card-header">
						<h5>Present Players</h5>
					</div> -->
				<div class="card-body p-0">
					<!-- Present Players Table -->
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="player_tbl">
							<thead>
								<tr class="fw-bold fs-6 text-gray-800">
									<th style="width: 9%;" class="text-center">Sr. No.</th>
									<th style="width: 10%;">Date</th>
									<th style="width: 12%;">Player</th>
									<th style="width: 12%;">Player ID</th>
									<th style="width: 12%;">Batch</th>
									<th style="width: 12%;">Coach</th>
									<th style="width: 12%;">In Time</th>
									<th style="width: 12%;">Out Time</th>
									<th style="width: 10%;" class="text-center">Action</th>
								</tr>



							</thead>
							<tbody id="player_table">
								@foreach($player_attend as $index => $players)
								<tr>
									<td  class="text-center">{{ $index + 1 }}</td>
									<td>{{ $players->attendance_date }}</td>
									<td>{{ $players->first_name }} {{ $players->middle_name }} {{ $players->last_name }}</td>
									<td>{{ $players->player_id }}</td>
									<td>{{ $players->batch_name ?? '-' }}</td>
									<td>{{ $players->coach_first_name.' '.$players->coach_middle_name.' '.$players->coach_last_name ?? '-' }}</td>
									<td>{{ $players->in_time ? \Carbon\Carbon::createFromFormat('H:i:s', $players->in_time)->format('g:i:s A') : '-' }}
									</td>
									<td>{{ $players->out_time ? \Carbon\Carbon::parse($players->out_time)->format('g:i:s A') : '-' }}
									</td>
									<td  class="text-center">
										<a href="{{ url('players-profile/'.$players->player_id ) }}">
											<i class="bi bi-eye-fill px-1"></i>
										</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					<!-- Modal filter start -->
					<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
						aria-hidden="true">
						<div class="modal-dialog filter-modal modal-dialog-centered">
							<form id="playerFilterForm">
								@csrf

								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<!-- Status Filter -->

										<!-- Batch Filter -->
										<!-- Batch Filter -->
										<div class="mb-3">
											<label for="filter_batch" class="form-label">Batch</label>
											<select class="form-select" id="selected_batch_id" name="selected_batch_id">
												<option value="" selected disabled>select Batch</option>
												@foreach($batches as $batch)
												<option value="{{ $batch->id }}">{{$batch->batch_name}}</option>
												@endforeach

											</select>
										</div>

										<!-- Coach Filter -->
										<div class="mb-3">
											<label for="filter_coach" class="form-label">Coach</label>
											<select class="form-select" id="selected_coach_id" name="selected_coach_id">
												<option value="" selected disabled>select Coach</option>
												@foreach($coaches as $coach)
												<option value="{{ $coach->id }}">{{$coach->fullName}}</option>
												@endforeach
											</select>
										</div>

										<!-- Time Range Filters -->
										<div class="mb-3">
											<label for="filter_in_time" class="form-label">In Time</label>
											<input type="time" class="form-control" id="selected_in_time" name="selected_in_time">
										</div>
										<div class="mb-3">
											<label for="filter_out_time" class="form-label">Out Time</label>
											<input type="time" class="form-control" id="selected_out_time" name="selected_out_time">
										</div>
									</div>
									<div class="modal-footer">
										<!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
										<button type="reset"
											class="btn btn-outline-warning border border-warning text-black dcg-btn"
											id="resetPlayerFilter">Clear Filter</button>
										<button type="submit" class="btn btn-warning text-black dcg-btn"
											id="applyPlayerFilter">Apply
											Fliter</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!-- Modal filter start -->

				</div>
				<!-- End of card body -->
			</div>
			<!-- End of card -->


			<div class="card-header pt-5 mt-md-10">
				<h3 class="card-title align-items-start flex-column">
					<span class="fw-bold text-danger" style="display: ;">Absent Players </span>
					<!-- <span class="fw-bold text-danger"> No Record Yet</span> -->
				</h3>
			</div>

			<!-- Absent Players Table (Static Data) -->
			<div class="card mt-4">

				<div class="card-body p-0">
					<!-- Absent Players Table (Static Data) -->
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="player_absent_tbl">
							<thead>
								<tr class="fw-bold fs-6 text-gray-800">
									<th style="width: 8%;" class="text-center">Sr. No.</th>
									<th style="width: 15%;">Date</th>
									<th style="width: 15%;">Player</th>
									<th style="width: 15%;">Player ID</th>
									<th style="width: 15%;">Batch</th>
									<th style="width: 15%;">Coach</th>
									<th style="width: 8%;" class="text-center">Action</th>
								</tr>

							</thead>
							<tbody>
								@foreach($absent_players as $index => $absentPlayer)

								<tr>
									<td class="text-center">{{ ++$index }}</td>
									<td>{{ \Carbon\Carbon::now()->format('Y-m-d') }}</td>
									<td>{{ $absentPlayer->fullName ?? '-'}}</td>
									<td>{{ $absentPlayer->player_id}}</td>
									<td>{{ $absentPlayer->batch->batch_name ?? '='}}</td>

									<td>{{ $absentPlayer->batch->loadcoach->fullName ?? '-'}}</td>
									<td class="text-center">
										<a href="{{ url('players-profile/'.$absentPlayer->player_id.'?tab=attendance_tab' ) }}">
											<i class="bi bi-eye-fill px-1"></i>
										</a>
									</td>
								</tr>
								@endforeach

								<!-- Add more static data rows as needed -->
							</tbody>
						</table>
					</div>

					<!-- Modal filter start -->
					<div class="modal fade" id="filterModal1" tabindex="-1" aria-labelledby="filterModalLabel"
						aria-hidden="true">
						<div class="modal-dialog filter-modal modal-dialog-centered">
							<form id="absentPlayersFilter">
								@csrf
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<!-- Status Filter -->

										<!-- Batch Filter -->
										<div class="mb-3">
											<label for="filter_batch" class="form-label">Batch</label>
											<select class="form-select" id="absent_player_batch_id" name="absent_player_batch_id" required>
												<option value="" selected disabled>select Batch</option>
												@foreach($batches as $batch)
												<option value="{{ $batch->id }}">{{$batch->batch_name}}</option>
												@endforeach

											</select>
										</div>

										<!-- Coach Filter -->
										<div class="mb-3">
											<label for="filter_coach" class="form-label">Coach</label>
											<select class="form-select" id="absent_player_coach_id" name="absent_player_coach_id" required>
												<option value="" selected disabled>select Coach</option>
												@foreach($coaches as $coach)
												<option value="{{ $coach->id }}">{{$coach->fullName}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="modal-footer">
										<!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
										<button type="reset"
											class="btn btn-outline-warning border border-warning text-black dcg-btn"
											id="resetAbsentPlayerFilter">Clear Filter</button>
										<button type="submit" class="btn btn-warning text-black dcg-btn "
											id="absentPlayerFilter">Apply
											Fliter</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!-- Modal filter end -->

				</div>
			</div>
			<!-- end of card -->



		</div>
		<!-- End of player tab content -->


		<!-- Coaches Tab Content -->
		<div class="tab-pane fade  {{ $activeTabShow === 'coach_tab' ? 'active show' : ''}}" id="coach_tab" role="tabpanel">
			<div class="card">

				<div class="card-body p-0">
					<!-- Present Coaches Table -->
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="coach_tbl">
							<thead>
								<tr class="fw-bold fs-6 text-gray-800">
									<th style="width: 8%;" class="text-center">Sr. No.</th>
									<th style="width: 15%;">Date</th>
									<th style="width: 15%;">Coach</th>
									<th style="width: 12%;">Coach ID</th>
									<th style="width: 12%;">Batches</th>
									<th style="width: 12%;">In Time</th>
									<th style="width: 12%;">Out Time</th>
									<th style="width: 8%;" class="text-center">Action</th>
								</tr>


							</thead>
							<tbody id="coach_table">

								@foreach($coach_attend as $index => $coach)

								<tr>
									<td class="text-center">{{ ++$index }}</td>
									<td>{{ $coach->attendance_date }} </td>
									<td>{{ $coach->first_name. ' '. $coach->middle_name. ' '. $coach->last_name ?? '' }}</td>
									<td>{{ $coach->coach_id }}</td>
									<td>{{ $coach->batch_name ?? '' }}</td>
									<td>{{ $coach->in_time ? \Carbon\Carbon::createFromFormat('H:i:s', $coach->in_time)->format('g:i:s A') : '-'}}
									</td>
									<td class="text-center">{{ $coach->out_time ? \Carbon\Carbon::parse($coach->out_time)->format('g:i:s A') : '-' }}
									</td>
									<td>
										<a href="{{ url('coach-profile/'.$coach->coach_id.'?tab=attendance_tab') }}">
											<i class="bi bi-eye-fill px-1"></i>
										</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					<!-- Modal filter start -->
					<div class="modal fade" id="filterModal2" tabindex="-1" aria-labelledby="filterModalLabel"
						aria-hidden="true">
						<div class="modal-dialog filter-modal modal-dialog-centered">
							<form id="coachFilterForm">
								@csrf
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<!-- Status Filter -->

										<!-- Batch Filter -->
										<!-- Batch Filter -->
										<div class="mb-3">
											<label for="filter_batch" class="form-label">Batch</label>
											<select class="form-select" id="filter_batch_id">
												<option value="" selected disabled>select Batch</option>
												@foreach($batches as $batch)
												<option value="{{ $batch->id }}">{{$batch->batch_name}}</option>
												@endforeach

											</select>
										</div>

										<!-- Coach Filter -->
										<div class="mb-3">
											<label for="filter_coach" class="form-label">Coach</label>
											<select class="form-select" id="filter_coach_id">
												<option value="" selected disabled>select Coach</option>
												@foreach($coaches as $coach)
												<option value="{{ $coach->id }}">{{$coach->fullName}}</option>
												@endforeach
											</select>
										</div>

										<!-- Time Range Filters -->
										<div class="mb-3">
											<label for="filter_in_time" class="form-label">In Time</label>
											<input type="time" class="form-control" id="filter_in_time">
										</div>
										<div class="mb-3">
											<label for="filter_out_time" class="form-label">Out Time</label>
											<input type="time" class="form-control" id="filter_out_time">
										</div>
									</div>
									<div class="modal-footer">
										<!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
										<button type="reset"
											class="btn btn-outline-warning border border-warning text-black dcg-btn"
											id="resetCoachFilter">Clear Filter</button>
										<button type="submit" class="btn btn-warning text-black dcg-btn "
											id="applyFilter">Apply
											Fliter</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!-- Modal filter end -->

				</div>
			</div>
			<!--end of card -->

			<div class="card-header pt-5 mt-md-10">
				<h3 class="card-title align-items-start flex-column">
					<span class="fw-bold text-danger">Absent Coaches </span>
					<!-- <span class="fw-bold text-danger"> No Record Yet</span> -->
				</h3>
			</div>

			<!-- Absent Coaches Table (Static Data) -->
			<div class="card mt-4">

				<div class="card-body p-0">
					<!-- Absent Coaches Table (Static Data) -->
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="coach_absent_tbl">
							<thead>
								<tr class="fw-bold fs-6 text-gray-800">
									<th style="width: 8%;" class="text-center">Sr. No.</th>
									<th style="width: 15%;">Date</th>
									<th style="width: 15%;">Coach</th>
									<th style="width: 15%;">Coach ID</th>
									<th style="width: 15%;">Batches</th>
									<th style="width: 8%;" class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<!-- Static Data for Absent Coaches -->
								@foreach($absent_coaches as $index => $coach)
								<tr>
									<td class="text-center">{{ ++$index }}</td>
									<td>{{ \Carbon\Carbon::now()->format('Y-m-d') }}</td>
									<td>{{ $coach->first_name . ' '. $coach->middle_name . ' '. $coach->last_name}}</td>
									<td>{{ $coach->id }}</td>
									<td>{{ $coach->batch[0]->batch_name ?? '-' }}</td>
									<td class="text-center">
										<a href="{{ url('coach-profile/'.$coach->id.'?tab=attendance_tab') }}">
											<i class="bi bi-eye-fill px-1"></i>
										</a>
									</td>
								</tr>
								@endforeach

							</tbody>
						</table>
					</div>

					<!-- Modal filter start -->
					<div class="modal fade" id="filterModal3" tabindex="-1" aria-labelledby="filterModalLabel"
						aria-hidden="true">
						<div class="modal-dialog filter-modal modal-dialog-centered">
							<form id="absentCoachFilter">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<!-- Status Filter -->

										<!-- Batch Filter -->
										<!-- Batch Filter -->
										<div class="mb-3">
											<label for="filter_batch" class="form-label">Batch</label>
											<select class="form-select" id="absent_coach_batch_id" required>
												<option value="" selected disabled>select Batch</option>
												@foreach($batches as $batch)
												<option value="{{ $batch->id }}">{{$batch->batch_name}}</option>
												@endforeach

											</select>
										</div>

										<!-- Coach Filter -->
										<div class="mb-3">
											<label for="filter_coach" class="form-label">Coach</label>
											<select class="form-select" id="absent_coach_coach_id" required>
												<option value="" selected disabled>select Coach</option>
												@foreach($coaches as $coach)
												<option value="{{ $coach->id }}">{{$coach->fullName}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="modal-footer">
										<!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
										<button type="reset"
											class="btn btn-outline-warning border border-warning text-black dcg-btn"
											id="resetAbsentCoachFilter">Clear Filter</button>
										<button type="submit" class="btn btn-warning text-black dcg-btn "
											id="applyFilter">Apply
											Fliter</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!-- Modal filter end -->

				</div>
			</div>
			<!-- End of card -->
		</div>

	</div>
	<!--end::Tab content-->

	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- DataTables JS -->
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

	<script>
		$(document).ready(function() {

			// if coach_tab is active then show cardTitle.text("Present Coaches");cardTitle.addClass("text-success");
			let activeTabs = document.getElementById('kt_chart_widget_8_tabs').querySelector('a.active').getAttribute('href').replace('#', '');

			if (activeTabs === 'coach_tab') {
				$card = $('#dynamic-card-title');
				$card.text("Present Coaches");
				$card.addClass("text-success");
			}



			var table1 = $('#player_tbl').DataTable({
				ordering: true,
				searching: true,
				paging: true,
				pageLength: 10, // default rows per page
				lengthMenu: [
					[10, 25, 50, -1],
					[10, 25, 50, "All"]
				],
				columnDefs: [{
						orderable: false,
						// targets: 6
					} // disable ordering on Action column
				],
				language: {
					lengthMenu: "Show _MENU_ entries",
					search: "Search:",

				},
				dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn1">>>rt<"bottom"ip><"clear">'
			});


			$('.filter-btn1').html(`
		<button class="btn btn-warning text-black dcg-btn customFilterBtn1">
			Filter
		</button>
			`);


			$(document).on('click', '.customFilterBtn1', function() {

				$('#filterModal').modal('show');
			});

			var absentPlayerTable = $('#player_absent_tbl').DataTable({
				ordering: true,
				searching: true,
				paging: true,
				pageLength: 10,
				lengthMenu: [
					[10, 25, 50, -1],
					[10, 25, 50, "All"]
				],
				columnDefs: [{
					orderable: false,
				}],
				language: {
					lengthMenu: "Show _MENU_ entries",
					search: "Search:",

				},

				dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn2">>>rt<"bottom"ip><"clear">'
			});

			$('.filter-btn2').html(`
		<button class=" customFilterBtn2 btn btn-warning text-black dcg-btn">
			Filter
		</button>
			`);

			$(document).on('click', '.customFilterBtn2', function(e) {
				$('#filterModal1').modal('show');
			});



			var table2 = $('#coach_tbl').DataTable({
				ordering: true,
				searching: true,
				paging: true,
				pageLength: 10,
				lengthMenu: [
					[10, 25, 50, -1],
					[10, 25, 50, "All"]
				],
				columnDefs: [{
					orderable: false,

				}],
				language: {
					lengthMenu: "Show _MENU_ entries",
					search: "Search:"
				},
				dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn3">>>rt<"bottom"ip><"clear">'
			});

			$('.filter-btn3').html(`
					<button class=" customFilterBtn3 btn btn-warning text-black dcg-btn">
						Filter
					</button>
		`);

			$(document).on('click', '.customFilterBtn3', function(e) {
				$('#filterModal2').modal('show');
			});


			var absentCoachTbl = $('#coach_absent_tbl').DataTable({
				ordering: true,
				searching: true,
				paging: true,
				pageLength: 10,
				lengthMenu: [
					[10, 25, 50, -1],
					[10, 25, 50, "All"]
				],
				columnDefs: [{
					orderable: false,

				}],
				language: {
					lengthMenu: "Show _MENU_ entries",
					search: "Search:"
				},
				dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn4">>>rt<"bottom"ip><"clear">'
			});

			$('.filter-btn4').html(`
				<button class=" customFilterBtn4 btn btn-warning text-black dcg-btn">
					Filter
				</button>
		`);

			$(document).on('click', '.customFilterBtn4', function(e) {
				$('#filterModal3').modal('show');
			});




			$(document).on('change', '#date', function() {
				loadAttendance();
			});

			$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {

				var targetTab = $(e.target).attr("href");

				if (targetTab === "#player_tab") {
					$('#date').val("{{ \Carbon\Carbon::now()->format('Y-m-d') }}");

				} else if (targetTab === "#coach_tab") {
					$('#date').val("{{ \Carbon\Carbon::now()->format('Y-m-d') }}");

				}

				// loadAttendance(); 
			});

			$(document).on('click', '#prev-date, #next-date', function() {
				loadAttendance();
			});

			function loadAttendance() {
				let date = $('#date').val();
				let activeTab = $('#kt_chart_widget_8_tabs a.active').attr('href').replace('#', '');

				$.ajax({
					url: "{{ route('attendance-list') }}",
					type: 'GET',
					data: {
						date: date,
						tab: activeTab
					},
					success: function(response) {

						if (activeTab == 'player_tab') {
							console.log(response);

							table1.destroy();
							$('#player_tbl tbody').html(response.present_html);
							table1 = $('#player_tbl').DataTable({
								ordering: true,
								searching: true,
								paging: true,
								pageLength: 10,
								dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn1">>>rt<"bottom"ip><"clear">'
							});
							$('.filter-btn1').html(`
								<button class="btn btn-warning text-black dcg-btn customFilterBtn1">
									Filter
								</button>
									`);

							//absent player tbl

							absentPlayerTable.destroy();
							$('#player_absent_tbl tbody').html(response.absent_html);
							absentPlayerTable = $('#player_absent_tbl').DataTable({
								ordering: true,
								searching: true,
								paging: true,
								pageLength: 10,
								dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn2">>>rt<"bottom"ip><"clear">'
							});
							$('.filter-btn2').html(`
								<button class="btn btn-warning text-black dcg-btn customFilterBtn2">
									Filter
								</button>
									`);

						} else if (activeTab == 'coach_tab') {
							// console.log(response);
							table2.destroy();
							$('#coach_tbl tbody').html(response.present_html);
							table2 = $('#coach_tbl').DataTable({
								ordering: true,
								searching: true,
								paging: true,
								pageLength: 10,
								dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn3">>>rt<"bottom"ip><"clear">'
							});
							$('.filter-btn3').html(`
								<button class=" customFilterBtn3 btn btn-warning text-black dcg-btn">
									Filter
								</button>
					`);

							//absent
							absentCoachTbl.destroy();
							$('#coach_absent_tbl tbody').html(response.absent_html);
							absentCoachTbl = $('#coach_absent_tbl').DataTable({
								ordering: true,
								searching: true,
								paging: true,
								pageLength: 10,
								dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn4">>>rt<"bottom"ip><"clear">'
							});
							$('.filter-btn4').html(`
							<button class="btn btn-warning text-black dcg-btn customFilterBtn4">
								Filter
							</button>
								`);

						}
					},
					error: function(xhr) {
						console.log("ERROR:", xhr.responseText);
					}
				});
			}

			document.getElementById('prev-date').addEventListener('click', function() {
				const currentDate = new Date(document.getElementById('date').value);
				currentDate.setDate(currentDate.getDate() - 1);
				document.getElementById('date').value = currentDate.toISOString().split('T')[0];
			});

			document.getElementById('next-date').addEventListener('click', function() {
				const currentDate = new Date(document.getElementById('date').value);
				currentDate.setDate(currentDate.getDate() + 1);
				document.getElementById('date').value = currentDate.toISOString().split('T')[0];
			});

			// Change the card title when the tab is clicked
			$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
				var targetTab = $(e.target).attr("href");
				var cardTitle = $('#dynamic-card-title');

				if (targetTab === "#player_tab") {

					cardTitle.text("Present Players");
					cardTitle.addClass("text-success");
				} else if (targetTab === "#coach_tab") {
					cardTitle.text("Present Coaches");
					cardTitle.addClass("text-success");
				}
			});



			$(document).on('submit', '#playerFilterForm', function(e) {
				e.preventDefault();

				let activeTab = $('#kt_chart_widget_8_tabs a.active').attr('href').replace('#', '');

				var coachId = $('#selected_coach_id').val();
				var batchId = $('#selected_batch_id').val();
				var inTime = $('#selected_in_time').val();
				var outTime = $('#selected_out_time').val();
				var date = $('#date').val();

				if (
					!coachId &&
					!batchId &&
					!inTime &&
					!outTime
				) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Please fill at least one field.',
						confirmButtonText: 'OK'
					});
					return false;
				}



				$.ajax({
					url: "{{ route('filterPlayersByAttendance') }}",
					method: 'GET',
					data: {
						'coach_id': coachId,
						'batch_id': batchId,
						'in_time': inTime,
						'out_time': outTime,
						'date': date,
						'tab': activeTab
					},
					success: function(response) {

						if (activeTab == 'player_tab') {
							console.log(response);

							table1.destroy();
							$('#player_tbl tbody').html(response.present_html);
							$('#filterModal').modal('hide');
							table1 = $('#player_tbl').DataTable({
								ordering: true,
								searching: true,
								paging: true,
								pageLength: 10,
								dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn1">>>rt<"bottom"ip><"clear">'
							});
							$('.filter-btn1').html(`
								<button class="btn btn-warning text-black dcg-btn customFilterBtn1">
									Filter
								</button>
									`);
						}
					}
				});
			});

			$(document).on('submit', '#absentPlayersFilter', function(e) {
				e.preventDefault();

				let activeTab = $('#kt_chart_widget_8_tabs a.active').attr('href').replace('#', '');

				var coachId = $('#absent_player_coach_id').val();
				var batchId = $('#absent_player_batch_id').val();
				var date = $('#date').val();

				if (!coachId && !batchId) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Please fill at least one field.',
						confirmButtonText: 'OK'
					});
					return false;
				}


				$.ajax({
					url: "{{ route('filter_absent_player') }}",
					method: 'GET',
					data: {
						'coach_id': coachId,
						'batch_id': batchId,

						'date': date,
						'tab': activeTab
					},
					success: function(response) {

						if (activeTab == 'player_tab') {
							console.log(response);


							absentPlayerTable.destroy();
							$('#player_absent_tbl tbody').html(response.absent_html);
							$('#filterModal1').modal('hide');
							absentPlayerTable = $('#player_absent_tbl').DataTable({
								ordering: true,
								searching: true,
								paging: true,
								pageLength: 10,
								dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn2">>>rt<"bottom"ip><"clear">'
							});
							$('.filter-btn2').html(`
								<button class="btn btn-warning text-black dcg-btn customFilterBtn2">
									Filter
								</button>
									`);
						}
					}
				});
			});


			//coach filter 
			$(document).on('submit', '#coachFilterForm', function(e) {
				e.preventDefault();

				let activeTab = $('#kt_chart_widget_8_tabs a.active').attr('href').replace('#', '');

				var coachId = $('#filter_coach_id').val();
				var batchId = $('#filter_batch_id').val();
				var inTime = $('#filter_in_time').val();
				var outTime = $('#filter_out_time').val();
				var date = $('#date').val();

				if (
					!coachId &&
					!batchId &&
					!inTime &&
					!outTime
				) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Please fill at least one field.',
						confirmButtonText: 'OK'
					});
					return false;
				}


				$.ajax({
					url: "{{ route('filterPlayersByAttendance') }}",
					method: 'GET',
					data: {
						'coach_id': coachId,
						'batch_id': batchId,
						'in_time': inTime,
						'out_time': outTime,
						'date': date,
						'tab': activeTab
					},
					success: function(response) {

						if (activeTab == 'coach_tab') {
							console.log(response);

							table2.destroy();
							$('#coach_tbl tbody').html(response.present_html);
							$('#filterModal2').modal('hide');
							table2 = $('#coach_tbl').DataTable({
								ordering: true,
								searching: true,
								paging: true,
								pageLength: 10,
								dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn3">>>rt<"bottom"ip><"clear">'
							});
							$('.filter-btn3').html(`
								<button class=" customFilterBtn3 btn btn-warning text-black dcg-btn">
									Filter
								</button>
					`);
						}
					}
				});
			});

			$(document).on('submit', '#absentCoachFilter', function(e) {
				e.preventDefault();

				let activeTab = $('#kt_chart_widget_8_tabs a.active').attr('href').replace('#', '');

				var coachId = $('#absent_coach_coach_id').val();
				var batchId = $('#absent_coach_batch_id').val();
				var date = $('#date').val();


				$.ajax({
					url: "{{ route('filter_absent_player') }}",
					method: 'GET',
					data: {
						'coach_id': coachId,
						'batch_id': batchId,

						'date': date,
						'tab': activeTab
					},
					success: function(response) {

						if (activeTab == 'coach_tab') {
							console.log(response);

							absentCoachTbl.destroy();
							$('#coach_absent_tbl tbody').html(response.absent_html);
							$('#filterModal3').modal('hide');
							absentCoachTbl = $('#coach_absent_tbl').DataTable({
								ordering: true,
								searching: true,
								paging: true,
								pageLength: 10,
								dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn4">>>rt<"bottom"ip><"clear">'
							});
							$('.filter-btn4').html(`
									<button class="btn btn-warning text-black dcg-btn customFilterBtn4">
										Filter
									</button>
										`);
						}
					}
				});
			});


			$(document).on('click', '#resetPlayerFilter', function() {
				$('#filterModal').modal('hide');
				$('#playerFilterForm')[0].reset();
				loadAttendance();
			});

			
			
			
			$(document).on('click', '#resetAbsentPlayerFilter', function() {

				$('#filterModal1').modal('hide');
				$('#absentPlayersFilter')[0].reset();
				loadAttendance();
			});

			$(document).on('click', '#resetCoachFilter', function() {
				$('#filterModal2').modal('hide');
				$('#coachFilterForm')[0].reset();
				loadAttendance();
			});

			$(document).on('click', '#resetAbsentCoachFilter', function() {
				$('#filterModal3').modal('hide');
				$('#absentCoachFilter')[0].reset();
				loadAttendance();
			});


		});
	</script>





</x-default-layout>