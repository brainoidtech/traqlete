<x-default-layout>
	@section('pagetitle', $department . ' ' . 'Coach List' )

	<style>
		.dcg-btn {
			padding: 6px 15px !important;
		}
	</style>

	<!--begin::Row-->
	<div class="row gx-5 gx-xl-10 pt-3 py-5">

		<!--begin::Col-->
		<div class="col-12">
			<!--begin::Chart widget 8-->
			<div class="card table-sec card-flush h-xl-100"
				style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">

				<!--begin::Body-->
				<div class="card-body p-0-0">
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="coach_table">
							<thead>
								<tr class="fw-bold fs-6 text-gray-800">
									<th style="width: 10%;">Sr. No.</th>
									<th style="width: 25%;">Name</th>
									<th style="width: 25%;">Coach ID</th>
									<th style="width: 25%;">Batch</th>
									<th style="width: 10%;">Action</th>
								</tr>
							</thead>
							<tbody>


							</tbody>
						</table>
					</div>
				</div>
				<!--end::Body-->


				<!-- Modal filter start -->
				<form>
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
									<!-- Status Filter -->

									<!-- Batch Filter -->
									<div class="mb-3">
										<label for="filter_batch" class="form-label">Batch</label>
										<select class="form-select" id="filter_batch" name="batch">
											<option value="">Select Batch</option>
											@foreach ($batches as $batch)
												<option value="{{ $batch->id }}"
													{{ request('batch') == $batch->id ? 'selected' : '' }}>
													{{ $batch->batch_name }}
												</option>
											@endforeach
										</select>
									</div>

									<!-- Coach Filter -->
									<div class="mb-3">
										<label for="filter_coach" class="form-label">Coach</label>
										<select class="form-select" id="filter_coach" name="coach">
											<option value="">Select Coach</option>
											@foreach ($coaches as $coach)
												<option value="{{ $coach->id }}"
													{{ request('coach') == $coach->id ? 'selected' : '' }}>
													{{ $coach->first_name }} {{ $coach->last_name }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="modal-footer">
									<!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
									<button type="button" class="btn btn-outline-warning border border-warning text-black dcg-btn" id="resetFilter">
										Clear Filter
											</button>
									<button type="button" class="btn btn-warning text-black dcg-btn" id="applyFilter">Apply Filter</button>
	</div>
	</div>
	</div>
	</div>
	</form>
	<!-- Modal filter end -->

	</div>
	<!--end::Chart widget 8-->

	</div>
	<!--end::Col-->


	</div>
	<!--end::Row-->
	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- DataTables JS -->
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


	<script>

		$(document).ready(function () {

		function buildTable() {
			if ($.fn.DataTable.isDataTable('#coach_table')) {
				$('#coach_table').DataTable().destroy();
			}

			let $tableData = $('#coach_table').DataTable({
				processing: false,
				serverSide: true,
				ordering: true,
				searching: true,
				paging: true,
				pageLength: 25,
				lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'ALL']],
				ajax: {
					url: "{{ route('coach-list') }}",
					type: "GET",
					data: function (d) {
						d.batch = $('#filter_batch').val();
						d.coach = $('#filter_coach').val();
					}
				},
				columns: [
					{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
					{ data: 'first_name', name: 'first_name', orderable: true, searchable: true },
					{ data: 'id', name: 'id', orderable: true, searchable: true },
					{ data: 'batch_name', name: 'batch_name', orderable: true, searchable: true },
					{ data: 'action', name: 'action', orderable: false, searchable: false }
				],
				language: {
					lengthMenu: "Show _MENU_ entries",
					search: "Search:"
				},
				dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn1">>>rt<"bottom"ip><"clear">'
			});

			// Re-inject filter button every time table is built
			$('.filter-btn1').html(`
				<button id="customFilterBtn1" class="btn btn-warning text-black dcg-btn">
					Filter
				</button>
			`);

			return $tableData;
		}

		var table = buildTable();

		// ✅ Use delegated listener so it works after DOM re-injection
		$(document).on('click', '#customFilterBtn1', function () {
			$('#filterModal').modal('show');
		});

		// ✅ Apply Filter button — reload table with new filter values
		$(document).on('click', '#applyFilter', function () {
			table.ajax.reload(); // re-fires ajax.data() which picks up new select values
			$('#filterModal').modal('hide');
		});

		// ✅ Clear Filter button resets selects and reloads
		$(document).on('click', '#resetFilter', function () {
			$('#filter_batch').val('');
			$('#filter_coach').val('');
			table.ajax.reload();
			$('#filterModal').modal('hide');
		});

});

	</script>


</x-default-layout>
