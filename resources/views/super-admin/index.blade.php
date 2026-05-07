<x-default-layout>
	@section('pagetitle', $data['pagetitle'])


	<style>
	.right-btn {
		border: 1px solid #F16937;
		border-radius: 50%;
		color: #F16937;
		padding: 1px 3px;
	}

	.right-btn i {
		color: #F16937;
	}

	hr {
		width: 95%;
		margin: 0px;
		color: #DCDCDC;
	}


	.search-box {
		position: relative;
		max-width: 300px;
	}

	.search-box i {
		position: absolute;
		top: 50%;
		left: 12px;
		transform: translateY(-50%);
		color: #6c757d;
	}

	.search-box input {
		padding-left: 35px;
	}
	</style>
	

	<div class="row g-5 g-xl-10 mb-xl-0 mb-xl-5">
		<div class="col-12 my-md-5">
			<nav class="navbar">

				<form class="ms-auto" role="search">
					<div class="search-box">
						<i class="bi bi-search"></i>
						<input type="search" id="search" class="form-control" placeholder="Search..." />
					</div>
				</form>

			</nav>
		</div>
	</div>
	

	<!--begin::Row-->
	<div class="row g-5 g-xl-10 mb-xl-0 mb-sm-10" id="dept-list">

		<!--begin::Col-->
		@forelse($depts as $dept)
		<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mt-md-5">
			<a href="{{ route('dashboard', $dept->dept_id) }}">
				<!--begin::Card widget 7-->
				<div class="card db-tile card-flush ">

					<!--begin::Title-->
					<div class="card-title d-flex justify-content-between align-items-center p-3">
						<!--begin::Amount-->
						<div class="d-flex align-items-center">
							<img src="{{ asset($dept->dept_icon) }}" alt="Basketball Image" height="40px" width="40px">
							<h3 class="mx-3 mt-3">{{ $dept->dept_name }}</h3>
						</div>
						<div class="right-btn">
							<i class="bi bi-chevron-right"></i>
						</div>
						<!--end::Amount-->
					</div>
					<!--end::Title-->

					<hr class="align-self-center">

					<!--begin::Card body-->
					<div class="p-3">
						<div class="d-flex justify-content-between">
							<p>No. of Active Players</p>
							<span class="txt-blue fw-bold">{{ $dept->players_count}}</span>
						</div>
						<div class="d-flex justify-content-between">
							<p>No. of Coaches</p>
							<span class="txt-red fw-bold">{{ $dept->coach_count}}</span>
						</div>
					</div>
					<!--end::Card body-->
				</div>
				<!--end::Card widget 7-->
			</a>
		</div>
		@empty
			<div class="col-12 text-center my-5">
				<h2>No Departments Yet in Deccan Gymkhana</h2>
			</div>
		@endforelse
		<!--end::Col-->

		<!--begin::Col-->

	</div>
	<!--end::Row-->


	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function() {
		// Search dept via AJAX
		$('#search').on('input', function() {
			var dept = $(this).val().toLowerCase();
			if(dept === ''){
				dept = 'all';
			}
			$.ajax({
				url: "{{ url('/suer-admin/search-dept') }}/" + dept,
				method: "GET",
				headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}',
				},
				success: function(response) {




					if (response.dept && response.dept.length > 0) {

					$('#dept-list').html('');

						response.dept.forEach(function(department) {
							var deptHtml = `
								<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mt-md-5" id="dept-list-${department.dept_id}">
									<a href="{{ route('dashboard', '') }}/${department.dept_id}">
										<div class="card db-tile card-flush">
											<div class="card-title d-flex justify-content-between align-items-center p-3">
												<div class="d-flex align-items-center">
													<img src="{{ asset('') }}${department.dept_icon}" alt="${department.dept_name} Image" height="40px" width="40px">
													<h3 class="mx-3 mt-3">${department.dept_name}</h3>
												</div>
												<div class="right-btn">
													<i class="bi bi-chevron-right"></i>
												</div>
											</div>
											<hr class="align-self-center">
											<div class="p-3">
												<div class="d-flex justify-content-between">
													<p>No. of Active Players</p>
													<span class="txt-blue fw-bold">${department.players_count}</span>
												</div>
												<div class="d-flex justify-content-between">
													<p>No. of Coaches</p>
													<span class="txt-red fw-bold">${department.coach_count}</span>
												</div>
											</div>
										</div>
									</a>
								</div>
							`;

							$('#dept-list').append(deptHtml);
						});
					} else {

						$('#dept-list').html('<h3>No departments found</h3>');
					}
				},
				error: function(error) {
					console.error(error);
				}
			});
		});
	});
</script>

</x-default-layout>
