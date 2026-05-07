<x-default-layout>
	@section('pagetitle', $data['pagetitle'])
	<style>
		.app-container {
			padding-top: 10px !important;
		}

		.app-main {
			margin-top: 0px !important;
		}

		.bi-eye-fill {
			color: #84857E !important;
		}

		.pagination li {

			color: #84857E;
			border: 1px solid #DCDCDC;
			border-radius: 10px;
		}

		.pagination li:first-child {

			color: #0000;
			border-radius: 10px;
		}

		#player_tbl_info {
			padding-left: 10px;
		}

		.dcg-btn {
			padding: 6px 15px !important;
		}

		/* thead tr th {
		border-top: 1px solid #DCDCDC;
	}

	tbody tr:last-child td {
		border-bottom: 1px solid #DCDCDC;
	} */

		/* print qr */

		.print-only {
			display: none;
		}

		@media print {
			body * {
				visibility: hidden;
			}

			#print-area,
			#print-area * {
				visibility: visible;
			}

			#print-area {
				display: flex;
				justify-content: center;
				align-items: center;

				position: fixed;
				top: 0;
				left: 0;

				width: 100vw;
				height: 100vh;
			}

			#print-area img {
				max-width: 300px;
				max-height: 300px;
			}
		}

		/* @media (max-width:500px) {
	.top {
		display: block !important;
	}
} */
	</style>

	@php
	$activePlayertab = request('tab', 'allplayers_tab');
	@endphp

	<div id="playerListContainer">
		<div class="row gx-5 gx-xl-10 pb-3">

			<!-- <div class="col-12 d-flex flex-wrap justify-content-between mb-md-5"> -->

			<!-- Tab Navigation -->
			<div class="col-12 d-flex justify-content-md-end align-items-center mb-2">
				<div class="card-toolbar">
					<ul class="nav tablist" id="kt_chart_widget_8_tabs" role="tablist">
						<!-- All Players Tab Button -->
						<li class="nav-item me-2 my-2" role="presentation">
							<a class="btn btn-border btn-yellow {{ $activePlayertab === 'allplayers_tab' ? 'active' : '' }}" id="kt_chart_widget_8_week_toggle"
								data-bs-toggle="tab" href="#allplayers_tab" role="tab" aria-selected="true">
								All Players
							</a>
						</li>

						<!-- Dormant Players Tab Button -->
						<li class="nav-item me-2 my-2" role="presentation">
							<a class="btn btn-border btn-yellow  {{ $activePlayertab === 'dormantplayers_tab' ? 'active' : '' }}" id="kt_chart_widget_8_month_toggle" data-bs-toggle="tab"
								href="#dormantplayers_tab" role="tab" aria-selected="false">
								Dormant Players
							</a>
						</li>

						<!-- Dormant Players Tab Button -->
						<li class="nav-item me-0 my-2" role="presentation">
							<a class="btn btn-border btn-yellow {{ $activePlayertab === 'leaveplayers_tab' ? 'active' : '' }}" id="kt_chart_widget_8_month_toggle" data-bs-toggle="tab"
								href="#leaveplayers_tab" role="tab" aria-selected="false">
								Leave Players
							</a>
						</li>
					</ul>
				</div>
			</div>

			<!-- </div> -->
		</div>

		<!-- Tab Content Section -->
		<div class="tab-content mb-5">

			<!-- Players Tab Content -->
			<div class="tab-pane fade  {{ $activePlayertab === 'allplayers_tab' ? 'active show' : '' }}" id="allplayers_tab" role="tabpanel">
				<div class="card">
					<!--begin::Body-->
					<div class="card-body p-0-0">
						<div class="table-responsive custom-table-responsive">
							<table class="table table-bordered m-5 table-striped" id="player_tbl">
								<thead>
									<tr class="fs-6">
										<th style="width: 8%;">Sr No</th>
										<th style="width: 15%;">Player ID</th>
										<th style="width: 15%;">Name</th>
										<th style="width: 12%;">Batch</th>
										<th style="width: 15%;">Coach</th>
										<th style="width: 14%;">Validity</th>
										<th style="width: 12%;">Status</th>
										<th style="width: 12%;">Action</th>
									</tr>
								</thead>
								<tbody class="">

								</tbody>

							</table>
						</div>
					</div>
					<!--end::Body-->

					<!-- Modal filter start -->
					<form id="AllPlayerData" method="GET">
						@csrf
						<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
							aria-hidden="true">
							<div class="modal-dialog filter-modal modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"></button>
									</div>
									<div class="modal-body">


										<div class="mb-3">
											<label for="filter_batch" class="form-label">Batch</label>
											<select class="form-select" aria-label="Default select example" name="batch_id"
												id="filter_batch" required style="color:#000; font-weight:400;">
												<option selected disabled>Select Batch</option>
												@foreach($batches as $batch)

												<option value="{{ $batch->id }}" data-all-player-coach="{{ $batch->coach_id  }}">
													{{ $batch->batch_name}}
												</option>
												@endforeach

											</select>

										</div>

										<div class="mb-3">
											<label for="filter_coach" class="form-label">Coach</label>
											<input type="text" class="form-control" name="coach" id="all_player_filter" placeholder="Coach"
												readonly required>
										</div>

										<div class="mb-3">
											<label for="filter_coach" class="form-label">Status</label>
											<select class="form-select" id="filter_player_status" name="status">
												<option value="">Select Status</option>
												<option value="1">Active</option>
												<option value="2">Dormant</option>
												<option value="0">Left</option>


											</select>
										</div>
										<div class="mb-3">
											<label for="filter_coach" class="form-label">Validity</label>
											<select class="form-select" id="filter_player_valididty" name="validity">
												<option value="In Validity">Select Validity</option>
												<option value="valid">
													In Validity
												</option>
												<option value="expired">
													Expired
												</option>
											</select>
										</div>
									</div>
									<div class="modal-footer">
										<button type="reset"
											class="btn btn-outline-warning border border-warning text-black dcg-btn"
											id="resetPlayerFilter">Clear Filter</button>
										<button type="submit" class="btn btn-warning text-black dcg-btn">Apply Fliter</button>


									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<!-- End of card -->
			</div>
			<!-- End of player tab content -->


			<!-- Dormant players Tab Content -->
			<div class="tab-pane fade  {{ $activePlayertab === 'dormantplayers_tab' ? 'active show' : '' }}" id="dormantplayers_tab" role="tabpanel">
				<div class="card">

					<!--begin::Body-->
					<div class="card-body p-0-0">
						<div class="table-responsive custom-table-responsive">
							<table class="table table-bordered m-5 table-striped" id="dormant_player_tbl">
								<thead>


									<tr class="fs-6">
										<th style="width: 8%;">Sr No</th>
										<th style="width: 15%;">Player ID</th>
										<th style="width: 15%;">Name</th>
										<th style="width: 12%;">Batch</th>
										<th style="width: 15%;">Coach</th>
										<th style="width: 14%;">Validity</th>
										<th style="width: 12%;">Status</th>
										<th style="width: 12%;">Action</th>
									</tr>

								</thead>
								<tbody class="">

								</tbody>

							</table>
						</div>
					</div>
					<!--end::Body-->

					<!-- Modal filter start -->
					<form id="dormantPlayerData" method="GET">
						@csrf
						<div class="modal fade" id="filterModal2" tabindex="-1" aria-labelledby="filterModalLabel"
							aria-hidden="true">
							<div class="modal-dialog filter-modal modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div class="mb-3">
											<label for="filter_batch" class="form-label">Batch</label>
											<select class="form-select" aria-label="Default select example" name="batch_id"
												id="batch" required style="color:#000; font-weight:400;">
												<option selected disabled>Select Batch</option>
												@foreach($batches as $batch)

												<option value="{{ $batch->id }}" data-coach="{{ $batch->coach_id  }}">
													{{ $batch->batch_name}}
												</option>
												@endforeach

											</select>
										</div>
										<!-- coach -->
										<div class="mb-3">
											<label for="filter_coach" class="form-label">Coach</label>
											<input type="text" class="form-control" name="coach" id="coach" placeholder="Coach"
												readonly required>
										</div>

										<input type="hidden" name="filter_status" id="filter_status" value="2">

										<div class="mb-3">
											<label for="" class="form-label">Validity</label>
											<select class="form-select" id="filter_validity" name="filter_validity">
												<option value="In Validity">Select Validity</option>
												<option value="valid"
													{{ request('validity') == 'valid' ? 'selected' : '' }}>
													In Validity
												</option>
												<option value="expired"
													{{ request('validity') == 'expired' ? 'selected' : '' }}>
													Expired
												</option>
											</select>
										</div>
									</div>
									<div class="modal-footer">
										<button type="reset"
											class="btn btn-outline-warning border border-warning text-black dcg-btn"
											id="resetDormantFilter">Clear Filter</button>
										<button type="submit" class="btn btn-warning text-black dcg-btn">Apply Fliter</button>

									</div>
								</div>
							</div>
						</div>
					</form>

				</div>
				<!--end of card -->


			</div>
			<!-- End of Dormant players tab content -->

			<!-- Leave players Tab Content -->
			<div class="tab-pane fade {{ $activePlayertab === 'leaveplayers_tab' ? 'active show' : '' }}" id="leaveplayers_tab" role="tabpanel">
				<div class="card">

					<!--begin::Body-->
					<div class="card-body p-0-0">
						<div class="table-responsive custom-table-responsive">
							<table class="table table-bordered m-5 table-striped" id="leave_player_tbl">
								<thead>

									<tr class="fs-6">
										<th style="width: 8%;">Sr No</th>
										<th style="width: 15%;">Player ID</th>
										<th style="width: 15%;">Name</th>
										<th style="width: 12%;">Batch</th>
										<th style="width: 15%;">Coach</th>
										<th style="width: 14%;">Validity</th>
										<th style="width: 12%;">Status</th>
										<th style="width: 12%;">Action</th>
									</tr>
								</thead>
								<tbody class="">

								</tbody>

							</table>
						</div>
					</div>
					<!--end::Body-->

					<!-- Modal filter start -->
					<form id="leftPlayerData">
						@csrf
						<div class="modal fade" id="filterModal3" tabindex="-1" aria-labelledby="filterModalLabel"
							aria-hidden="true">
							<div class="modal-dialog filter-modal modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div class="mb-3">
											<label for="filter_batch" class="form-label">Batch</label>
											<select class="form-select" aria-label="Default select example" name="batch_id"
												id="left_player_batch" required style="color:#000; font-weight:400;">
												<option selected disabled>Select Batch</option>
												@foreach($batches as $batch)

												<option value="{{ $batch->id }}" data-left-player-coach="{{ $batch->coach_id  }}">
													{{ $batch->batch_name}}
												</option>
												@endforeach

											</select>
										</div>
										<div class="mb-3">
											<label for="filter_coach" class="form-label">Coach</label>
											<div class="mb-3">
												<input type="text" class="form-control" name="coach" id="left_player_coach" placeholder="Coach"
													readonly required>
											</div>
										</div>

										<input type="hidden" id="left_player_status" value="0">

										<div class="mb-3">
											<label for="" class="form-label">Validity</label>
											<select class="form-select" id="left_player_validity" name="validity">
												<option value="In Validity">Select Validity</option>
												<option value="valid"
													{{ request('validity') == 'valid' ? 'selected' : '' }}>
													In Validity
												</option>
												<option value="expired"
													{{ request('validity') == 'expired' ? 'selected' : '' }}>
													Expired
												</option>
											</select>
										</div>
									</div>
									<div class="modal-footer">
										<button type="reset"
											class="btn btn-outline-warning border border-warning text-black dcg-btn"
											id="resetLeaveFilter">Clear Filter</button>
										<button type="submit" class="btn btn-warning text-black dcg-btn "
											id="applyFilter">Apply
											Fliter</button>
									</div>
								</div>
							</div>
						</div>
					</form>

				</div>
				<!--end of card -->

			</div>
			<!-- End of Leave players Tab Content -->

		</div>
		<!--end::Tab content-->
	</div>



	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

	<script>
		$(document).ready(function() {

			//  Helper: build DataTable for a given tab 
			function buildTable(tableId, filterBtnClass, modalId, tab, extraParams = {}) {
				if ($.fn.DataTable.isDataTable('#' + tableId)) {
					$('#' + tableId).DataTable().destroy();
				}

				var dt = $('#' + tableId).DataTable({
					processing: false,
					serverSide: true,
					ordering: true,
					searching: true,
					paging: true,
					pageLength: 25,
					lengthMenu: [
						[10, 25, 50, -1],
						[10, 25, 50, "All"]
					],
					ajax: {
						url: "{{ route('player-list') }}",
						type: "GET",
						data: function(d) {
							d.tab = tab;
							$.extend(d, extraParams);
						}
					},
					columns: [{
							data: 'DT_RowIndex',
							name: 'DT_RowIndex',
							orderable: false,
							searchable: false
						},
						{
							data: 'player_id',
							name: 'players.player_id'
						},
						{
							data: 'name',
							name: 'players.first_name',
							orderable: true
						},
						{
							data: 'batch_name',
							name: 'batches.batch_name'
						},
						{
							data: 'coach_name',
							name: 'coach_name',
							orderable: true
						},
						{
							data: 'validity',
							name: 'validity',
							orderable: true
						},
						{
							data: 'status_label',
							name: 'status_label',
							orderable: true
						},
						{
							data: 'action',
							name: 'action',
							orderable: false,
							searchable: false
						}
					],
					language: {
						lengthMenu: "Show _MENU_ entries",
						search: "Search:"
					},
					dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"' + filterBtnClass + '">>>rt<"bottom"ip><"clear">'
				});

				// Inject Filter button into DataTable toolbar
				$('.' + filterBtnClass).html(`
						<button class="btn btn-warning text-black dcg-btn custom-filter-trigger" data-modal="${modalId}">
							Filter
						</button>
					`);

				return dt;
			}

			//  Initialize all 3 tables 
			buildTable('player_tbl', 'filter-btn1', 'filterModal', 'allplayers_tab');
			buildTable('dormant_player_tbl', 'filter-btn2', 'filterModal2', 'dormantplayers_tab');
			buildTable('leave_player_tbl', 'filter-btn3', 'filterModal3', 'leaveplayers_tab');

			//  Open filter modal on button click (delegated)
			$(document).on('click', '.custom-filter-trigger', function() {
				let modalId = $(this).data('modal');
				$('#' + modalId).modal('show');
			});

			//  Coach auto-fill on batch change 
			$(document).on('change', '#filter_batch, #batch, #left_player_batch', function() {
				let coachId, inputId;

				if (this.id === 'filter_batch') {
					coachId = $(this).find(':selected').data('all-player-coach');
					inputId = '#all_player_filter';
				} else if (this.id === 'batch') {
					coachId = $(this).find(':selected').data('coach');
					inputId = '#coach';
				} else {
					coachId = $(this).find(':selected').data('left-player-coach');
					inputId = '#left_player_coach';
				}

				$.ajax({
					url: "{{ route('getCoachByBatchId') }}",
					method: 'GET',
					data: {
						coach_id: coachId
					},
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}'
					},
					success: function(response) {
						if (response.status === 'success') {
							let c = response.data;
							$(inputId).val([c.first_name, c.middle_name, c.last_name].filter(Boolean).join(' '));
						}
					}
				});
			});

			//  Apply filter: All Players 
			$(document).on('submit', '#AllPlayerData', function(e) {
				e.preventDefault();
				let params = {
					batch: $('#filter_batch').val(),
					coach: $('#filter_batch').find(':selected').data('all-player-coach'),
					status: $('#filter_player_status').val(),
					validity: $('#filter_player_valididty').val(),
				};
				buildTable('player_tbl', 'filter-btn1', 'filterModal', 'allplayers_tab', params);
				$('#filterModal').modal('hide');
			});

			//  Apply filter: Dormant Players 
			$(document).on('submit', '#dormantPlayerData', function(e) {
				e.preventDefault();
				let params = {
					batch: $('#batch').val(),
					coach: $('#batch').find(':selected').data('coach'),
					validity: $('#filter_validity').val(),
				};
				buildTable('dormant_player_tbl', 'filter-btn2', 'filterModal2', 'dormantplayers_tab', params);
				$('#filterModal2').modal('hide');
			});

			//  Apply filter: Leave Players 
			$(document).on('submit', '#leftPlayerData', function(e) {
				e.preventDefault();
				let params = {
					batch: $('#left_player_batch').val(),
					coach: $('#left_player_batch').find(':selected').data('left-player-coach'),
					validity: $('#left_player_validity').val(),
				};
				buildTable('leave_player_tbl', 'filter-btn3', 'filterModal3', 'leaveplayers_tab', params);
				$('#filterModal3').modal('hide');
			});


			$(document).on('click', '#resetDormantFilter', function() {

				$('#dormantPlayerData')[0].reset();

				buildTable('dormant_player_tbl', 'filter-btn2', 'filterModal2', 'dormantplayers_tab');

				$('#filterModal2').modal('hide');
			});

			$(document).on('click', '#resetLeaveFilter', function() {

				$('#leftPlayerData')[0].reset();

				buildTable('leave_player_tbl', 'filter-btn3', 'filterModal3', 'leaveplayers_tab');

				$('#filterModal3').modal('hide');
			});

			$(document).on('click', '#resetPlayerFilter', function() {

				$('#AllPlayerData')[0].reset();

				buildTable('player_tbl', 'filter-btn1', 'filterModal', 'allplayers_tab');

				$('#filterModal').modal('hide');
			});

		});






		function printQr(e) {
			e.preventDefault();
			window.print();
		}
	</script>

</x-default-layout>