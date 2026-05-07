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

		:root {
			--calendar-border: #eff2f5;
			--text-muted: #a1a5b7;
			--text-dark: #3f4254;
			--dot-in: #10b981;
			--dot-out: #3b82f6;
			--dot-absent: #f97316;
		}

		#player_attend_tbl {
			border-collapse: collapse;
			width: 100%;
			border: 1px solid var(--calendar-border);
		}

		#player_attend_tbl th {
			background-color: #ffffff;
			color: #7e8299;
			font-weight: 600;
			text-align: center;
			padding: 1.5rem 0.5rem;
			border: 1px solid var(--calendar-border);
			width: 20%;
		}

		#player_attend_tbl td {
			height: 150px;
			vertical-align: top;
			padding: 12px;
			border: 1px solid var(--calendar-border);
			position: relative;
			background-color: #ffffff;
			min-width: 125px;
		}

		/* Date Number in top right */
		.date-label {
			position: absolute;
			top: 10px;
			right: 12px;
			font-weight: 700;
			color: var(--text-dark);
			font-size: 1.1rem;
		}

		/* Attendance Entry Styling */
		.attendance-item {
			display: flex;
			justify-content: space-between;
			flex-direction: column;
			align-items: center;
			background: #ffffff;
			border: 1px solid #f1f1f4;
			border-radius: 4px;
			padding: 5px 8px;
			margin-top: 25px;
			/* Creates space for the date label */
			margin-bottom: 6px;
			font-size: 11px;
			box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.02);
		}

		.edit-attendence-item {
			display: flex;
			justify-content: space-between;
			flex-direction: row;
			align-items: center;
			background: #ffffff;
			border: 1px solid #f1f1f4;
			border-radius: 4px;
			padding: 5px 8px;
			margin-top: 25px;
			/* Creates space for the date label */
			margin-bottom: 6px;
			font-size: 11px;
			box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.02);
		}

		/* Stack multiple entries */
		.attendance-item+.attendance-item {
			margin-top: 0px;
		}

		.status-dot {
			width: 7px;
			height: 7px;
			border-radius: 50%;
			display: inline-block;
			margin-right: 5px;
		}

		.dot-in {
			background-color: var(--dot-in);
		}

		.dot-out {
			background-color: var(--dot-out);
		}

		.dot-absent {
			background-color: var(--dot-absent);
		}

		.absent-wrapper {
			margin-top: 25px;
			background: #ffffff;
			border: 1px solid #f1f1f4;
			padding: 8px 10px;
			border-radius: 4px;
			font-size: 11px;
			font-weight: 600;
			color: var(--text-dark);
		}

		.more-text {
			color: var(--text-muted);
			font-size: 11px;
			font-weight: 500;
			margin-left: 4px;
		}

		.attendance-modal {
			display: none;
			position: fixed;
			z-index: 9999;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.4);
		}

		.modal-content {
			background: #fff;
			width: 400px;
			margin: 10% auto;
			padding: 20px;
			border-radius: 8px;
		}

		.close-btn {
			float: right;
			cursor: pointer;
			font-size: 20px;
		}

		.attendance-item {
			margin-bottom: 8px;
		}

		/* Table Layout */
		.attendance-table {
			width: 100%;
			border-collapse: collapse;
		}

		.attendance-table th {
			text-align: left;
			padding: 12px 15px;
			background-color: #f8f9fa;
			color: #333;
			font-size: 0.85rem;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			border-bottom: 2px solid #dee2e6;
		}

		.attendance-table td {
			padding: 12px 15px;
			border-bottom: 1px solid #eee;
			vertical-align: middle;
			color: #444;
		}

		/* Time and Status Styling */
		.time-cell {
			font-weight: 500;
			color: #2d3436;
		}

		/* Edit Icon Styling */
		.action-btn {
			background: none;
			border: none;
			cursor: pointer;
			color: #0984e3;
			font-size: 1.1rem;
			padding: 5px;
			border-radius: 4px;
			transition: background 0.2s;
		}

		.action-btn:hover {
			background-color: #e1f5fe;
			color: #74b9ff;
		}

		/*Crop Image modal Backdrop Overlay */
		#cropModalBackdrop {
			position: fixed;
			inset: 0;
			background: rgba(0, 0, 0, 0.45);
			backdrop-filter: blur(4px);
			-webkit-backdrop-filter: blur(4px);

			display: none;
			/* keep only this */
			z-index: 9998;

			align-items: center;
			justify-content: center;
		}

		#cropPopup {
			width: 300px;
			background: #fff;
			border-radius: 10px;
			box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
			padding: 12px;
			animation: popupScale 0.25s ease;
		}

		#cropPopup img {
			width: 100%;
			max-height: 320px;
			object-fit: contain;
		}

		.crop-header {
			font-weight: 600;
			display: flex;
			justify-content: space-between;
			margin-bottom: 10px;
		}

		.crop-header span {
			cursor: pointer;
		}

		.crop-actions {
			text-align: center;
			margin-top: 10px;
		}

		/* popup animation */
		@keyframes popupScale {
			from {
				transform: scale(.9);
				opacity: 0;
			}

			to {
				transform: scale(1);
				opacity: 1;
			}
		}
	</style>

	@php
	$activeTab = request('tab', 'overview_tab');
	@endphp

	<div class="card mb-5 mb-xxl-8">
		<div class="card-body pt-9 pb-0">

			<!--begin::Details-->
			<div class="d-flex flex-wrap flex-sm-nowrap">
				<!--begin: Pic-->
				<div class="me-7 mb-4">
					<div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
						<img src="{{ asset($coachProfile->coach_image) }}" alt="image">
					</div>
				</div>
				<!--end::Pic-->
				<!--begin::Info-->
				<div class="flex-grow-1 mt-14">
					<!--begin::Title-->
					<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
						<!--begin::User-->
						<div class="d-flex flex-column">
							<!--begin::Name-->
							<div class="d-flex align-items-center mb-2">
								<a href="#"
									class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $coachProfile->fullName }}</a>
							</div>
							<!--end::Name-->
						</div>
						<!--end::User-->
					</div>
					<!--end::Title-->

					<!--begin::Stats-->
					<div class="d-flex flex-wrap flex-stack">
						<!--begin::Wrapper-->
						<div class="d-flex flex-column flex-grow-1 pe-8">
							<!--begin::Stats-->
							<div class="d-flex flex-wrap">

								<div class="pe-20 mt-5">
									<!--begin::Info-->
									<div class="text-muted me-2 fs-7">Coach ID</div>
									<!--end::Info-->
									<!--begin::Description-->
									<div class="d-flex align-items-center mt-1 fs-6">
										<!--begin::Title-->
										<div class="fs-5 fw-semibold mb-2">{{ $coachProfile->id }}</div>
										<!--end::Title-->

									</div>
									<!--end::Description-->
								</div>



							</div>
							<!--end::Stats-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Stats-->
				</div>
				<!--end::Info-->
			</div>
			<!--end::Details-->

			<!-- Tabs -->
			<ul class="nav nav-tabs mt-4 mb-4">
				<li class="nav-item">
					<a class="nav-link {{ $activeTab === 'overview_tab' ? 'active' : '' }} fw-semibold"
						data-bs-toggle="tab" href="#overview_tab">Overview</a>
				</li>
				<li class="nav-item">
					<a class="nav-link  {{ $activeTab === 'attendance_tab' ? 'active' : '' }} fw-semibold"
						data-bs-toggle="tab" href="#attendance_tab">Attendance Summary</a>
				</li>
				<li class="nav-item">
					<a class="nav-link fw-semibold" data-bs-toggle="tab" href="#batchlog_tab">Batch Logs</a>
				</li>

			</ul>
		</div>
	</div>

	<!-- TAB CONTENT -->
	<div class="tab-content pb-5">

		<!-- OVERVIEW TAB -->
		<div class="tab-pane fade {{ $activeTab === 'overview_tab' ? 'show active' : '' }}" id="overview_tab">

			<!-- Profile details tab view -->
			<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">

				<!-- <div class="card-header cursor-pointer">
					<div class="card-title m-0">
						<h3 class="fw-bold m-0">Profile Details</h3>
					</div>
					<div class="d-grid gap-2 d-md-flex justify-content-md-end py-5 px-2">
						<button class="btn btn-warning text-black dcg-btn" type="button" id="editProfileBtn">Edit Profile</button>
					</div>
				</div> -->

				<!--begin::Chart widget 8-->
				<div class="card table-sec card-flush h-xl-100" style="border:none !important;">
					<!--begin::Header-->
					<div class="card-header cursor-pointer">
						<div class="card-title m-0">
							<h3 class="fw-bold m-0">Profile Details</h3>
						</div>


						<div class="d-grid gap-2 d-md-flex justify-content-md-end py-5 px-2">
							<button class="btn btn-warning text-black dcg-btn" type="button" id="editProfileBtn">Edit
								Profile</button>
						</div>

					</div>
					<!--end::Header-->

					<!--begin::Body-->
					<div class="card-body p-0-0">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">

								<tbody class="">
									<tr>
										<td>Name</td>
										<td>{{ $coachProfile->fullName }}</td>
									</tr>

									<tr>
										<td>Document Number</td>
										<td>{{ $coachProfile->document }}</td>
									</tr>

									<tr>
										<td>Date of Birth</td>
										<td>{{ $coachProfile->dob }}</td>
									</tr>
									<tr>
										<td>Present Age</td>
										<td>{{ $coachProfile->age }}</td>
									</tr>
									<tr>
										<td>Gender</td>
										<td>{{ $coachProfile->gender }}</td>
									</tr>

									<tr>
										<td>Address</td>
										<td>{{ $coachProfile->fullAddress }}</td>
									</tr>
									<tr>
										<td>Contact Number</td>
										<td>{{ $coachProfile->coach_contact_no }}</td>
									</tr>
									<tr>
										<td>Email ID</td>
										<td>{{ $coachProfile->coach_email }}</td>
									</tr>

									<tr>
										<td>Department</td>
										<td>{{ $coachProfile->department->dept_name ?? null }}</td>
									</tr>

									<tr>
										<td> Batch</td>
										<td>{{ $coachProfile->latestBatch[0]->batch_name ?? null }}</td>

									</tr>

								</tbody>
							</table>
						</div>
					</div>
					<!--end::Body-->
				</div>
				<!--end::Chart widget 8-->


			</div>
			<!-- end of profile details tab view -->

			<!-- Edit Profile details tab section -->
			<div class="row mb-3 d-none" id="kt_profile_details_editview">
				<form method="POST" enctype="multipart/form-data" id="saveProfileBtn" action="javascript:void(0)">
					@csrf
					<div class="col-12">

						<div class="card">


							<div class="card-body">

								<div class="d-flex justify-content-between">
									<h3>Edit Details</h>
										<h3>New Coach ID - {{ $coachProfile->id }}</h3>
								</div>

								<hr>

								<div class="common-form form">

									<div class="form-head">
										<h4>Personal Details</h4>
									</div>


									<!-- Aadhar / Passport Number -->
									<div class="row g-4 py-2">
										<div class="col-12 col-md-6">
											<label for="document-number" class="col-form-label">Document Number (Aadhar /
												Passport)*</label>
											<input type="text" class="form-control" id="document" name="document" value="{{ $coachProfile->document }}"
												placeholder="Enter Document Number" required>
										</div>


										<div class="col-12 col-md-6">
											<label class="col-form-label">Passport/ID size photo*</label>

											<div class="d-flex position-relative">

												<div class="col-10 col-xl-11">

													<input class="form-control" id="image" accept="image/*"
														name="coach_image" type="file" onchange="showImage(event)"
														style="color:#000;font-weight:400;">
												</div>


												<div class="col-2 col-xl-1 ps-2">
													<img id="imagPreview" class="profile-img"
														src="{{ asset($coachProfile->coach_image) }}">
												</div>
											</div>
											<input type="hidden" name="cropped_image" id="cropped_image">
										</div>

									</div>

									<!-- Name -->
									<div class="row g-4 py-2">

										<div class="col-6 col-md-4">
											<label for="Name" class="col-form-label">First Name*</label>
											<input type="text" class="form-control" placeholder="First name"
												value="{{ $coachProfile->first_name }}" id="firstName" name="first_name"
												aria-label="First name" required>
										</div>
										<div class="col-6 col-md-4">
											<label for="Name" class="col-form-label">Middle Name*</label>
											<input type="text" class="form-control" placeholder="Middle name"
												value="{{ $coachProfile->middle_name }}" id="middleName"
												name="middle_name" aria-label="Middle name" required>
										</div>
										<div class="col-6 col-md-4">
											<label for="Name" class="col-form-label">Last Name*</label>
											<input type="text" class="form-control" placeholder="Last name"
												value="{{ $coachProfile->last_name }}" id="lastName" name="last_name"
												aria-label="Last name" required>
										</div>
									</div>


									<div class="row g-4 py-2">
										<!-- DOB and Age -->
										<div class="col-12 col-md-6">
											<label for="dob" class="col-form-label">Date Of Birth*</label>
											<input type="date" class="form-control" id="dob" name="dob"
												value="{{ $coachProfile->dob }}" placeholder="Select Date" required
												style="color:#000 !important; font-weight:400;">
											<span id="dob_error" style="color:red;"></span>
										</div>
										<div class="col-12 col-md-6">
											<label for="age" class="col-form-label">Age*</label>
											<input type="text" class="form-control" name="age" id="age"
												value="{{ $coachProfile->age }}" placeholder="Age" required readonly>
										</div>
									</div>


									<div class="row mb-3">
										<!-- Gender -->
										<div class="col-12 col-md-6">
											<label for="gender" class="col-form-label">Gender*</label>
											<select class="form-select" aria-label="Default select example"
												name="gender" id="gender" required
												style="color:#000 !important; font-weight:400;">
												<option selected>Select Gender</option>
												<option value="Male"
													{{ $coachProfile->gender == 'Male' ? 'selected' : '' }}>Male
												</option>
												<option value="Female"
													{{ $coachProfile->gender == 'Female' ? 'selected' : '' }}>Female
												</option>
												<option value="Other"
													{{ $coachProfile->gender == 'Other' ? 'selected' : '' }}>Other
												</option>
											</select>
										</div>
									</div>


									<!-- Address -->
									<div class="row g-4 py-2">

										<div class="col-6 col-md-6">
											<label for="Address" class="col-form-label">Address Line 1*</label>
											<input type="text" class="form-control" id="address1" name="address1"
												value="{{ $coachProfile->address1 }}" placeholder="Address line 1"
												required>
										</div>
										<div class="col-6 col-md-6">
											<label for="Address" class="col-form-label">Address Line 2</label>
											<input type="text" class="form-control" id="address2" name="address2"
												value="{{ $coachProfile->address2 }}" placeholder="Address line 2">

										</div>
									</div>

									<div class="row g-4 py-2">
										<div class="col-6 col-md-4">
											<label for="area" class="col-form-label">Area/Locality*</label>
											<input type="text" class="form-control" id="area" name="area"
												value="{{ $coachProfile->area }}" placeholder="Area/Locality" required>
										</div>
										<div class="col-6 col-md-4">
											<label for="City" class="col-form-label">City*</label>
											<input type="text" class="form-control" id="city" name="city"
												value="{{ $coachProfile->city }}" placeholder="City" required>
										</div>
										<div class="col-6 col-md-4">
											<label for="pincode" class="col-form-label">Pin Code*</label>
											<input type="text" class="form-control" id="pincode" name="pincode"
												value="{{ $coachProfile->pincode }}" placeholder="Pin Code" required>
										</div>
									</div>

									<div class="form-inner-head">
										<h4>Credential Details</h4>
									</div>

									<div class="row g-4 py-2">

										<div class="col-12 col-md-6">
											<label for="mobile" class="mb-0 col-form-label">Contact Number*</label>
											<input type="text" class="form-control flex-grow-1 me-4"
												value="{{ $coachProfile->coach_contact_no }}" id="mobile"
												name="coach_contact_no" placeholder="Enter Mobile Number" required>
										</div>

										<div class="col-12 col-md-6">
											<label for="Email" class="mb-0 col-form-label">Email*</label>
											<input type="text" class="form-control flex-grow-1 me-4"
												value="{{ $coachProfile->coach_email }}" id="Email" name="coach_email"
												placeholder="Enter Email" required>
										</div>

										<div class="col-12 col-md-6">
											<label for="Password" class="mb-0 col-form-label">Password</label>
											<input type="password" class="form-control flex-grow-1 me-4"
												value="" id="Password" name="password"
												placeholder="Enter New Password">
											<span>Leave blank if you don't want to change password</span>
										</div>
									</div>

									<div class="row">
										<div class="d-grid gap-2 d-flex justify-content-center py-3 px-2">
											<button
												class="btn btn-warning text-black border border-warning dcg-btn py-2"
												type="submit">Save</button>
											<button
												class="btn btn-outline-warning me-md-2 border border-warning dcg-btn py-2"
												id="cancelProfileBtn" type="button">Cancel</button>

										</div>
									</div>

								</div>

							</div>

						</div>

					</div>


					<!-- end of card body -->

					<!-- <div class="col-12 ">
                       
                        <div class="card table-sec card-flush h-xl-100 border-top-0"
                            style="border-top-left-radius:0px; border-top-right-radius:0px;">
                           
                            <div class="card-body p-0-0">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end py-3 px-2">
                                    <button class="btn btn-outline-warning me-md-2 border border-warning"
                                        type="button">Cancel</button>
                                    <button class="btn btn-warning text-black border border-warning"
                                        type="submit">Save</button>
                                </div>
                            </div>
                            

                        </div>
                       
                    </div> -->
					<!-- end btn -->

				</form>
			</div>
			<!--End of Profile details tab view -->


		</div>
		<!-- end of overview tab  -->


		<!-- ATTENDANCE TAB -->
		<div class="tab-pane fade py-5 {{ $activeTab === 'attendance_tab' ? 'show active' : '' }}" id="attendance_tab">


			<div class="card shadow-sm mb-5">
				<div class="card-header border-0 pt-6 pb-4 d-flex flex-wrap justify-content-between align-items-center">
					<div class="d-flex flex-wrap gap-3 mb-2 mb-md-0">
						<div class="border rounded px-3 py-2 bg-white d-flex align-items-center">
							<span class="status-dot dot-in"></span> <span class="fw-bold text-gray-700 fs-8">In
								Time</span>
						</div>
						<div class="border rounded px-3 py-2 bg-white d-flex align-items-center">
							<span class="status-dot dot-out"></span> <span class="fw-bold text-gray-700 fs-8">Out
								Time</span>
						</div>
						<div class="border rounded px-3 py-2 bg-white d-flex align-items-center">
							<span class="status-dot dot-absent"></span> <span class="fw-bold text-gray-700 fs-8">Absent
								/ No Record Yet</span>
						</div>
					</div>

					<div class="d-flex gap-2">
						<select class="form-select form-select-sm w-125px bg-light border" id="month_select"></select>
						<select class="form-select form-select-sm w-100px bg-light border" id="year_select"></select>
					</div>
				</div>

				<div class="card-body p-0">
					<div class="table-responsive custom-table-responsive p-2 p-md-4">
						<table class="table mb-0 table-bordered" id="player_attend_tbl">
							<thead>
								<tr>
									<th style="width: 15%;">Sun</th>
									<th style="width: 15%;">Mon</th>
									<th style="width: 15%;">Tue</th>
									<th style="width: 15%;">Wed</th>
									<th style="width: 15%;">Thu</th>
									<th style="width: 15%;">Fri</th>
									<th style="width: 15%;">Sat</th>
								</tr>
							</thead>
							<tbody id="calendar_body">
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
		<!-- end of attendence tab  -->


		<!-- Batch Log TAB -->
		<div class="tab-pane fade" id="batchlog_tab">

			<!-- Start od assement list -->
			<div id="BatchList">


				<div class="card table-sec card-flush h-xl-100 mb-5">
					<!--begin::Header-->
					<div class="card-header cursor-pointer">
						<div class="card-title m-0">
							<h3 class="fw-bold m-0">Current Batches</h3>
						</div>

					</div>


					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body p-0-0">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" id="current_batch_log">
								<thead>
									<tr class="fs-6">
										<th style="width: 15%;">Sr. No.</th>
										<th style="width: 20%;">Batch Name</th>
										<th style="width: 20%;">Start Date</th>
										<th style="width: 20%;">End Date</th>

									</tr>
								</thead>

								<tbody>
									@foreach($current_batch as $index => $batch)
									<tr>
										<td>{{ ++$index }}</td>
										<td>{{ $batch->batch_name ?? '-'}}</td>
										<td>{{ $batch->start_date ?? '-'}}</td>
										<td>{{ $batch->end_date ?? '-'}}</td>

									</tr>
									@endforeach


								</tbody>
							</table>
						</div>
					</div>
					<!--end::Body-->
				</div>

				<div class="card table-sec card-flush h-xl-100 mb-5">
					<!--begin::Header-->
					<div class="card-header cursor-pointer">
						<div class="card-title m-0">
							<h3 class="fw-bold m-0">Previous Batches</h3>
						</div>

					</div>


					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body p-0-0">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" id="previous_batch_log">
								<thead>
									<tr class="fs-6">
										<th style="width: 15%;">Sr. No.</th>
										<th style="width: 20%;">Batch Name</th>
										<th style="width: 20%;">Start Date</th>
										<th style="width: 20%;">End Date</th>

									</tr>
								</thead>

								<tbody>
									@foreach($previous_batch as $index => $batch)
									<tr>
										<td>{{ ++$index }}</td>
										<td>{{ $batch->batch_name ?? '-'}}</td>
										<td>{{ $batch->start_date ?? '-'}}</td>
										<td>{{ $batch->end_date ?? '-'}}</td>

									</tr>
									@endforeach



								</tbody>
							</table>
						</div>
					</div>
					<!--end::Body-->
				</div>



			</div>
			<!-- end of batch list -->

		</div>
		<!-- End of batch log tab -->

	</div>

	<div id="attendanceCoachModal" class="attendance-modal">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Attendance Details :-</h4>
				<span class="close-btn" onclick="closeModal()">×</span>
				<!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>  -->
			</div>

			<div id="modalBody"></div>
		</div>
	</div>
	<!-- end of main tab -->

	<!-- Start Crop Image Modal -->
	<div id="cropModalBackdrop">

		<div id="cropPopup">
			<div class="crop-header">
				Crop Image
				<!-- <span onclick="closeCrop()">✕</span> -->
			</div>
			<div class="crop-wrapper">
				<img id="cropImage">
			</div>
			<div class="crop-actions">
				<button type="button" onclick="saveCrop()" class="btn btn-warning btn-sm">
					Done
				</button>
			</div>
		</div>
	</div>
	<!-- End Crop Image Modal -->


	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

	<!-- DataTables JS -->
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

	<script>
		document.getElementById("editProfileBtn").addEventListener("click", () => {
			localStorage.setItem('showEditView', 'true');
			location.reload();
		});
		window.addEventListener('load', () => {
			if (localStorage.getItem('showEditView') === 'true') {
				document.getElementById("kt_profile_details_view").classList.add("d-none");
				document.getElementById("kt_profile_details_editview").classList.remove("d-none");
				localStorage.removeItem('showEditView');
			}
		});

		// document.getElementById("editProfileBtn").addEventListener("click", function() {

		//     document.getElementById("kt_profile_details_view").classList.add("d-none");
		//     document.getElementById("kt_profile_details_editview").classList.remove("d-none");
		// });

		document.getElementById("cancelProfileBtn").addEventListener("click", function() {

			document.getElementById("kt_profile_details_view").classList.remove("d-none");
			document.getElementById("kt_profile_details_editview").classList.add("d-none");
		});


		//calculate age by dob
		// $('#dob').on('input', function(e) {

		// 	let dob = $(this).val();
		// 	let birth_year = dob.substring(0, 4);

		// 	let todays_date = new Date();
		// 	let year = todays_date.getFullYear();

		// 	const age = year - birth_year;
		// 	$('#age').val(age);

		// });

		$('#dob').on('input', function() {

			let dob = $(this).val();

			$('#dob_error').text('');
			$('#age').val('');

			// ❌ check valid date format
			let date = new Date(dob);

			// invalid date OR wrong format
			if (!dob || isNaN(date.getTime())) {
				$('#dob_error').text('Please enter a valid date of birth.');
				return;
			}

			// ❌ extra check for unrealistic year (fixes 200000 issue)
			let year = date.getFullYear();

			if (year < 1900 || year > new Date().getFullYear()) {
				$('#dob_error').text('Please enter a valid year of birth.');
				return;
			}

			let today = new Date();
			let age = today.getFullYear() - year;

			let m = today.getMonth() - date.getMonth();
			if (m < 0 || (m === 0 && today.getDate() < date.getDate())) {
				age--;
			}

			if (age < 0 || age > 120) {
				$('#dob_error').text('Please enter a valid date of birth.');
				return;
			}

			$('#age').val(age);
		});




		// Image crop
		let cropper;


		function showImage(event) {
			const file = event.target.files[0];
			if (!file) return;

			const reader = new FileReader();
			reader.onload = function(e) {
				const image = document.getElementById("cropImage");
				image.src = e.target.result;
				document.getElementById("cropModalBackdrop").style.display = "flex";

				setTimeout(() => {
					if (cropper) cropper.destroy();

					cropper = new Cropper(image, {
						aspectRatio: 1,
						viewMode: 2,
						autoCropArea: 1,
						responsive: true,
						background: false,
						guides: true
					});
				}, 100);
			};
			reader.readAsDataURL(file);
		}


		function saveCrop() {
			const croppedCanvas = cropper.getCroppedCanvas({
				width: 1200,
				height: 1200,
				imageSmoothingEnabled: true,
				imageSmoothingQuality: 'high'
			});

			const originalFile = document.getElementById("image").files[0];
			const fileSizeMB = originalFile.size / 1024 / 1024;


			if (fileSizeMB > 2) {

				console.log("File is", fileSizeMB.toFixed(2), "MB → Compressing...");

				croppedCanvas.toBlob(function(blob) {
					const reader = new FileReader();
					reader.onload = function(e) {
						const base64 = e.target.result;
						document.getElementById("imagPreview").src = base64;
						document.getElementById("cropped_image").value = base64;

						console.log("Original :", fileSizeMB.toFixed(2), "MB");
						console.log("Compressed:", (blob.size / 1024).toFixed(1), "KB");
					};
					reader.readAsDataURL(blob);

					replaceFileInput(blob, "passport_photo.jpg");
					document.getElementById("cropModalBackdrop").style.display = "none";

				}, "image/jpeg", 0.90); // ← compress quality

			} else {
				// NO COMPRESS — use as is
				console.log("File is", fileSizeMB.toFixed(2), "MB → No compression needed");

				croppedCanvas.toBlob(function(blob) {
					const reader = new FileReader();
					reader.onload = function(e) {
						const base64 = e.target.result;
						document.getElementById("imagPreview").src = base64;
						document.getElementById("cropped_image").value = base64;
					};
					reader.readAsDataURL(blob);

					replaceFileInput(blob, "passport_photo.jpg");
					document.getElementById("cropModalBackdrop").style.display = "none";

				}, "image/jpeg", 1.0);
			}
		}



		// Replace file input with compressed file (for normal form submit)
		function replaceFileInput(blob, fileName) {
			const compressedFile = new File([blob], fileName, {
				type: "image/jpeg"
			});
			const dataTransfer = new DataTransfer();
			dataTransfer.items.add(compressedFile);
			document.getElementById("image").files = dataTransfer.files;
		}



		$(document).ready(function() {

			var table1 = $('#current_batch_log').DataTable({
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

			var table2 = $('#previous_batch_log').DataTable({
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




			$("#saveProfileBtn").on('submit', function(e) {
				e.preventDefault();

				let formData = new FormData(this);
				formData.append('mode', 'update');


				Swal.fire({
					title: 'Saving ...',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: "{{ url('update-coach-profile/' . $coachProfile->id) }}",
					method: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}',
					},
					success: function(response) {
						console.log(response);
						if (response.status === 'success') {
							Swal.fire({
								title: "success!",
								text: response.message,
								icon: "success"
							}).then(() => {
								window.location.href = "{{ route('coach-list') }}";
							});
						} else {
							Swal.fire({
								title: "error!",
								text: response.message,
								icon: "error"
							})
						}
					},
					error: function(xhr, status, error) {
						Swal.close();
						if (xhr.status === 422) {

							let errors = xhr.responseJSON.errors;
							let errorHtml = '';
							for (let key in errors) {
								errorHtml += errors[key][0] + '<br>';
							}

							Swal.fire({
								icon: 'error',
								title: 'Validation Failed',
								html: errorHtml
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Error!',
								text: 'Something went wrong!'.xhr.status
							});
						}
					}
				});

			});




			$(document).on('change', '#in_time', function() {
				let updatedInTime = $(this).val();

				if (updatedInTime) {
					$('#status').val('Present');
				} else {
					$('#status').val('Absent');
				}

			});


		});
	</script>

	<!-- Attendence Calender -->
	<script>
		const attendanceData = @json($coach_attendance);
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const monthSelect = document.getElementById('month_select');
			const yearSelect = document.getElementById('year_select');
			const calendarBody = document.getElementById('calendar_body');
			const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
			const now = new Date();
			const currentYear = now.getFullYear();
			const currentMonth = now.getMonth();
			const today = new Date();
			months.forEach((m, i) => {
				monthSelect.innerHTML +=
					`<option value="${i}" ${i === currentMonth ? 'selected' : ''}>${m}</option>`;
			});
			for (let y = currentYear - 3; y <= currentYear + 8; y++) {
				yearSelect.innerHTML += `<option value="${y}" ${y === currentYear ? 'selected' : ''}>${y}</option>`;
			}
			//Render Calender Function 
			function renderCalendar() {
				const year = parseInt(yearSelect.value);
				const month = parseInt(monthSelect.value);
				calendarBody.innerHTML = '';
				const firstDay = new Date(year, month, 1).getDay();
				const daysInMonth = new Date(year, month + 1, 0).getDate();
				let date = 1;
				for (let i = 0; i < 6; i++) {
					let row = document.createElement('tr');
					let hasDay = false;
					for (let j = 0; j < 7; j++) {
						let cell = document.createElement('td');
						if (i === 0 && j < firstDay) {
							cell.innerHTML = '';
						} else if (date > daysInMonth) {
							cell.innerHTML = '';
						} else {
							hasDay = true;
							const formattedDate = date < 10 ? '0' + date : date;
							cell.innerHTML = `<span class="date-label">${formattedDate}</span>`;
							const fullDate =
								`${year}-${String(month + 1).padStart(2,'0')}-${String(date).padStart(2,'0')}`;
							const dayOfWeek = new Date(year, month, date).getDay();
							const cellDate = new Date(year, month, date);
							if (attendanceData[fullDate]) {
								let records = attendanceData[fullDate];
								records.slice(0, 2).forEach((record) => {
									if (record.status === 0) {
										cell.innerHTML +=
											`<div class="absent-wrapper"><span class="status-dot dot-absent"></span> Absent</div>`;
									} else {
										cell.innerHTML += `<div class="attendance-item"
											id="calendar_item_${record.id}"
											style="cursor:pointer"
											onclick="editAttendance(${record.id}, '${fullDate}')">
											<span><span class="status-dot dot-in"></span> ${formatTime(record.in_time) || '--'}</span>
											<span><span class="status-dot dot-out"></span> ${formatTime(record.out_time) || '--'}</span>
											</div>`;
									}
								});
								if (records.length > 2) {
									cell.innerHTML += `<div class="more-text" style="cursor:pointer;color:#007bff" onclick="showAttendancePopup('${fullDate}')">
								+${records.length - 2} More
							</div>`;
								}
							}
							// else if (dayOfWeek !== 0 && dayOfWeek !== 6) { 
							// cell.innerHTML +=
							// `<div class="absent-wrapper"><span class="status-dot dot-absent"></span> Absent</div>`;
							// }
							else if (cellDate <= today) {
								cell.innerHTML +=
									`<div class="absent-wrapper"><span class="status-dot dot-absent"></span> Absent</div>`;
							}
							date++;
						}
						row.appendChild(cell);
					}
					if (!hasDay && i > 0) break;
					calendarBody.appendChild(row);
				}
			}

			//Show Attendance Pop-up Function
			function showAttendancePopup(date) {
				let records = attendanceData[date];
				let html = `<table class="attendance-table table table-bordered mt-3 table-striped ">
					<thead>
						<tr>
							<th>In Time</th>
							<th>Out Time</th>
							<th style="text-align: center;">Action</th>
						</tr>
					</thead>
					<tbody>`;

				records.forEach(record => {
					if (record.status === 0) {
						html += `<tr class="status-absent">
							<td colspan="2">
								<span class="status-dot dot-absent"></span> 
								<span class="absent-text">Absent</span>
							</td>
							<td style="text-align: center;">
								<button class="action-btn edit-icon-btn" title="Edit" onclick="editAttendance(${record.id}, '${date}')">
									<i class="fa-regular fa-pen-to-square"></i>
								</button>
							</td>
							</tr>`;
					} else {
						html += `
							<tr>
								<td class="time-cell">${formatTime(record.in_time) || '--'}</td>
								<td class="time-cell">${formatTime(record.out_time) || '--'}</td>
								<td style="text-align: center;">
									<button class="action-btn edit-icon-btn" title="Edit" onclick="editAttendance(${record.id}, '${date}')">
										<i class="fa-regular fa-pen-to-square"></i>
									</button>
								</td>
							</tr>`;
					}
				});
				html += `</tbody></table>`;
				document.getElementById('modalBody').innerHTML = html;
				document.getElementById('attendanceCoachModal').style.display = 'block';
			}

			//Edit Attendance Pop-up Function
			function editAttendance(id, date) {
				if (!attendanceData[date]) return;
				let record = attendanceData[date].find(r => r.id == id);
				if (!record) return;
				document.getElementById('attendanceCoachModal').style.display = 'block';
				let html = `<div class="p-3">
					<input type="hidden" id="edit_id" value="${record.id}">
					<div class="mb-3">
						<label>In Time</label>
						<input type="time" 
							id="edit_in_time" 
							class="form-control"
							value="${record.in_time}">
					</div>
					<div class="mb-3">
						<label>Out Time</label>
						<input type="time" 
							id="edit_out_time" 
							class="form-control"
							value="${record.out_time}">
					</div>
					<button class="btn btn-success"
						onclick="updateAttendance('${date}')">
						Update
					</button>
					<button class="btn btn-secondary ms-2"
						onclick="showAttendancePopup('${date}')">
						Cancel
					</button>
					</div>`;
				document.getElementById('modalBody').innerHTML = html;
			}

			//Update Attendance Function
			function updateAttendance(date) {
				const id = document.getElementById('edit_id').value;
				const in_time = document.getElementById('edit_in_time').value;
				const out_time = document.getElementById('edit_out_time').value;
				fetch(`{{ route('update.coach', ':id') }}`.replace(':id', id), {
						method: 'PUT',
						credentials: 'same-origin',
						headers: {
							'Content-Type': 'application/json',
							'Accept': 'application/json',
							'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
						},
						body: JSON.stringify({
							in_time: in_time,
							out_time: out_time,
							status: (in_time || out_time) ? 1 : 0
						})
					})
					.then(res => {
						if (!res.ok) {
							throw new Error("Network response was not ok");
						}
						return res.json();
					})
					.then(data => {
						if (data.success) {
							let record = attendanceData[date].find(r => r.id == id);
							if (record) {
								record.in_time = in_time;
								record.out_time = out_time;
								record.status = (in_time || out_time) ? 1 : 0;
							}
							closeModal();
							renderCalendar();
							Swal.fire({
								title: 'Success!',
								text: data.message,
								icon: 'success',
								confirmButtonColor: '#f9c301'
							});
						} else {
							Swal.fire({
								title: 'Error!',
								text: data.message || 'Update failed',
								icon: 'error'
							});
						}
					})
					.catch(err => {
						console.error(err);
						Swal.fire({
							title: 'Error!',
							text: 'Something went wrong. Please try again.',
							icon: 'error'
						});
					});
			}

			//close Model Function
			function closeModal() {
				document.getElementById('attendanceCoachModal').style.display = 'none';
			}

			//Format Time Function
			function formatTime(time) {
				if (!time) return '--';
				let [hour, minute] = time.split(':');
				hour = parseInt(hour);
				let ampm = hour >= 12 ? 'PM' : 'AM';
				hour = hour % 12;
				hour = hour ? hour : 12;
				return `${hour}:${minute} ${ampm}`;
			}

			window.showAttendancePopup = showAttendancePopup;
			window.editAttendance = editAttendance;
			window.updateAttendance = updateAttendance;
			window.closeModal = closeModal;
			window.formatTime = formatTime;
			monthSelect.addEventListener('change', renderCalendar);
			yearSelect.addEventListener('change', renderCalendar);
			renderCalendar();
		});
	</script>


</x-default-layout>