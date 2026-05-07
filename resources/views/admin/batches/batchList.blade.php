<x-default-layout>
	@section('pagetitle', $data['pagetitle'])

	<style>
		/* thead tr th {
		border-top: 1px solid #DCDCDC;
	}

	tbody tr:last-child td {
		border-bottom: 1px solid #DCDCDC;
	}

	tbody.alternating-rows tr:nth-child(2n) {
		background-color: #F5F8FB;

	} */

		.bi-three-dots-vertical {
			color: #84857E !important;
		}

		/* .pagination li {

		color: #84857E;
		border: 1px solid #DCDCDC;
		border-radius: 10px;
	}

	.pagination li:first-child {

		color: #0000;
		border-radius: 10px;
	} */
	</style>

	<!--begin::Row-->
	<div class="row gx-5 gx-xl-10 py-3">

		<div class="d-flex justify-content-end align-items-center">
			<div class="card-toolbar pb-4">
				<a href="{{ route('add-batch') }}" class="btn btn-warning text-black border border-warning py-2">
					Add Batch
				</a>
			</div>
		</div>

		<!--begin::Col-->
		<div class="col-12">
			<!--begin::Chart widget 8-->
			<div class="card table-sec card-flush h-xl-100">



				<!--begin::Body-->
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="batch">
							<thead>
								<tr class="fw-bold fs-6 text-gray-800">
									<th style="width: 5%">Sr. No.</th>
									<th style="width: 10%">Batch</th>
									<th style="width: 10%;">Head Coach</th>
									<th style="width: 10%;">Sub Coaches</th>
									<th style="width: 10%;">Time From</th>
									<th style="width: 10%;">Time To</th>
									<th style="width: 5%;">Action</th>
								</tr>
							</thead>
							<tbody class="fs-5 fw-normal">
								@php $id = 1; @endphp
								@foreach($batches as $batch)
								<?php $b = $batch['head_coach']; ?>
								<tr>
									<td>{{ $id++}}</td>
									<td>{{ $b->batch_name ?? '-' }}</td>
									<td>{{ $b->loadCoach->first_name ?? ''}} {{ $b->loadCoach->middle_name ?? ''}} {{ $b->loadCoach->last_name ?? ''}}</td>
									<td>{{ $batch['sub_coach'] ?? '-' }}</td>
									<td>
										{{ $b?->start_time 
											? \Carbon\Carbon::parse($b->start_time)->format('g:i A') 
											: 'N/A' 
										}}
									</td>
									<td>
										{{ $b?->end_time ? \Carbon\Carbon::parse($b->end_time)->format('g:i A') : 'N/A' }}
									</td>
									<td>
										<a href="#" class="edit-batch-btn" data-batch-id="{{ $b->id ?? ''}}"
											data-batch-name="{{ $b->batch_name ?? ''}}"
											data-coach-id="{{ $b->coach_id ?? '' }}"
											data-start-time="{{ $b->start_time ?? '' }}"
											data-end-time="{{ $b->end_time ?? '' }}">
											<i class="fa fa-edit"></i>
										</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>

					</div>
				</div>

				<!-- Edit Batch Modal -->
				<div class="modal fade" id="editBatchModal" tabindex="-1" aria-labelledby="editBatchModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editBatchModalLabel">Edit Batch</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal"
									aria-label="Close"></button>
							</div>
							<form id="editBatchForm" method="PUT">
								@csrf
								@method('PUT')
								<div class="modal-body">
									<input type="hidden" name="batch_id" id="edit_batch_id">

									<div class="row g-4 py-3">
										<div class="col-12">
											<div class="mb-3">
												<label for="edit_batch_name" class="form-label">Batch Name *</label>
												<input type="text" class="form-control" id="edit_batch_name"
													name="batch_name" required>

											</div>
										</div>

									</div>

									<div class="row g-4 py-2">
										<div class="col-12 col-md-6">
											<div class="mb-3">
												<label for="edit_head_coach_id" class="form-label">Coach</label>
												<select name="head_coach_id" class="form-select" id="edit_head_coach_id" required style="color:#000 !important;">
													<option value="" selected disabled>Select Coach</option>

													@foreach($coaches as $c)
													<option value="{{ $c->id }}">
														{{ trim($c->first_name.' '.$c->middle_name.' '.$c->last_name) }}
													</option>
													@endforeach
												</select>
											</div>
										</div>

										<div class="col-12 col-md-6">
											<label class="form-label">Other Coach</label>
											<select name="other_coach_ids[]" class="form-select" id="edit_subCoach"
												data-control="select2" data-placeholder="Select Sub Coach" multiple
												style="color:#000 !important;">

												@foreach($coaches as $c)
												<option value="{{ $c->id }}">
													{{ trim($c->first_name.' '.$c->middle_name.' '.$c->last_name) }}
												</option>
												@endforeach

											</select>
										</div>

									</div>

									<div class="row g-4 py-3">
										<div class="col-md-6 mb-3">
											<label for="edit_start_time" class="form-label">Time From*</label>
											<input type="time" class="form-control" id="edit_start_time"
												name="start_time" required>
										</div>

										<div class="col-md-6 mb-3">
											<label for="edit_end_time" class="form-label">Time To*</label>
											<input type="time" class="form-control" id="edit_end_time" name="end_time"
												required>
										</div>
									</div>
								</div>

								<div class="modal-footer align-items-center justify-content-center">
									<button type="submit"
										class="btn btn-warning text-black border border-warning py-2">Save</button>
									<button type="button"
										class="btn btn-outline-warning text-black border border-warning py-2"
										data-bs-dismiss="modal">Cancel</button>
								</div>

							</form>
						</div>
					</div>
				</div>
				<!--end::Body-->
			</div>
			<!--end::Chart widget 8-->

		</div>
		<!--end::Col-->



	</div>
	<!--end::Row-->

	<!-- jQuery FIRST -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Then everything else -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

	<script>
		$(document).ready(function() {

			$(document).on('click', '.edit-batch-btn', function() {
				const batchId = $(this).data('batch-id');
				const batchName = $(this).data('batch-name');
				const coachId = $(this).data('coach-id');
				const startTime = $(this).data('start-time');
				const endTime = $(this).data('end-time');

				// Fill basic fields
				$('#edit_batch_id').val(batchId);
				$('#edit_batch_name').val(batchName);
				$('#edit_head_coach_id').val(coachId);
				$('#edit_start_time').val(startTime);
				$('#edit_end_time').val(endTime);

				// Reset sub coach dropdown before loading
				$('#edit_subCoach').val(null).trigger('change');

				const subCoachUrl = "{{ url('/subCoaches') }}";

				$.ajax({
					url: `${subCoachUrl}/${batchId}`,
					method: 'GET',
					success: function(response) {
						if (response.status === 'success') {

							const selectedIds = response.sub_coaches.map(c => c.coach_id);


							// Pre-select in dropdown
							$('#edit_subCoach').val(selectedIds).trigger('change');

							// Disable head coach
							let headCoachId = $('#edit_head_coach_id').val();
							$('#edit_subCoach option[value="' + headCoachId + '"]').prop('disabled', true);

							$('#edit_subCoach').trigger('change.select2');
						}
					},
					error: function() {
						alert('Failed to load sub coaches.');
					}
				});

				$('#editBatchModal').modal('show');
			});

			//update formData
			$('#editBatchForm').on('submit', function(e) {
				e.preventDefault();

				const formData = new FormData(this);
				if (!formData.has('_method')) {
					formData.append('_method', 'PUT');
				}

				const batchId = $('#edit_batch_id').val();
				// alert(batchId);

				$.ajax({
					url: `{{ url('batches') }}/${batchId}`,
					method: 'post',
					data: formData,
					processData: false,
					contentType: false,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
						'X-Requested-With': 'XMLHttpRequest'
					},
					success: function(data) {
						if (data.success) {
							$('#editBatchModal').modal('hide');

							Swal.fire({
								icon: 'success',
								title: 'Updated!',
								text: data.message,
								timer: 1500,
								showConfirmButton: false
							}).then(() => {
								location.reload();
							});
						}
					},
					error: function(xhr,error) {
						const response = xhr.responseJSON;


						if (xhr.status === 422) {
							let message = response.message || 'Validation error';

							if (response.errors) {
								message = Object.values(response.errors)
									.flat()
									.join('\n');
							}

							Swal.fire({
								icon: 'warning',
								title: 'Validation Error',
								text: message
							});

							return;
						}

						// Other server error
						Swal.fire({
							icon: 'error',
							title: 'Error!',
							text: response?.message || 'Something went wrong.'
						});
					}
				});
			});






			// Initialize DataTable with custom options
			var table = $('#batch').DataTable({
				"ordering": true,
				"searching": true,
				"pageLength": 10,
				"lengthMenu": [
					[10, 15, 20, -1],
					[10, 15, 20, "All"]
				],
				"language": {
					"lengthMenu": "Show _MENU_ Entries"
				},
				"dom": '<"top align-items-center custom-datatable-header"<"d-flex align-items-center gap-3"l><"ml-auto"f>>rt<"bottom "ip><"clear">', // Custom layout for DataTable
			});

			// Search functionality (optional, based on custom input)
			$("#search").keyup(function() {
				table.search(this.value).draw();
			});

			// Length menu functionality (changing page length)
			$('.myTable_length').on('change', function() {
				var pageLength = $(this).val();
				table.page.len(pageLength).draw();
			});



			$('#edit_head_coach_id').on('change', function() {

				let selectedHeadCoach = $(this).val();
				$('#edit_subCoach').val(null).trigger('change');

				$('#edit_subCoach option').prop('disabled', false);

				if (selectedHeadCoach) {
					$('#edit_subCoach').prop('disabled', false);
					$('#edit_subCoach option[value="' + selectedHeadCoach + '"]')
						.prop('disabled', true);

				} else {
					$('#edit_subCoach').prop('disabled', true);
				}

				$('#edit_subCoach').trigger('change.select2');

			});



		});
	</script>



</x-default-layout>
