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

		/* Backdrop */
		.qr-model {
			position: fixed;
			inset: 0;
			background: rgba(0, 0, 0, 0.35);
			display: none;
			justify-content: center;
			align-items: center;
			z-index: 1050;
		}

		/* Modal card */
		.qr-card {
			background: #fff;
			width: 220px;
			padding: 20px 20px 25px;
			border-radius: 8px;
			box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
			position: relative;
			text-align: center;
		}

		/* Close button outer circle */
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

		/* Close icon */
		.qr-close {
			font-size: 18px;
			line-height: 1;
			cursor: pointer;
			color: #333;
		}

		/* QR image container */
		.qr-code-outer {
			margin: 40px 0 15px;
			display: flex;
			justify-content: center;
		}

		#qrModelImg {
			width: 180px;
			height: 180px;
		}

		/* Button container */
		.qr-btn-outer {
			display: flex;
			justify-content: center;
			padding: 10px;
		}

		/* print */
		@media print {
			body * {
				visibility: hidden;
			}

			#qrModelImg {
				visibility: visible;
			}

			#qrModelImg {
				position: fixed;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				max-width: 300px;
				/* adjust size */
				max-height: 350px;
			}
		}

		/* //datatable delete .qr-btn-outer */
		.delete-btn i {
			font-size: 40px;
			padding: 10px;
			color: #F16937;
		}

		.tab-content {
			opacity: 0;
			transition: opacity 0.3s ease;
		}

		.tab-content.ready {
			opacity: 1;
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


		/* Modal attendence css */


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

		.absent-text {
			color: #d63031;
			font-weight: 600;
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

	<div class="card mb-5 mb-xxl-8">
		<div class="card-body pt-9 pb-0">
			<!--begin::Details-->
			<div class="d-flex flex-wrap flex-sm-nowrap">
				<!--begin: Pic-->
				<div class="me-7 mb-4">
					@if ($player->player_image)
					<div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
						<img src="{{ asset($player->player_image) }}" alt="image">
						<div>
							@if ($player->qr_code)
							<img src="{{ asset($player->qr_code) }}" alt="image" onclick="showQr(this.src)"
								class="qr_overlay">
							@endif
						</div>
					</div>
					@endif

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
									class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $player->first_name }}
									{{ $player->middle_name }} {{ $player->last_name }}
								</a>
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
									<div class="text-muted me-2 fs-7">Player ID</div>
									<!--end::Info-->
									<!--begin::Description-->
									<div class="d-flex align-items-center mt-1 fs-6">
										<!--begin::Title-->
										<div class="fs-5 fw-semibold mb-2">{{ $player->player_id }}</div>
										<!--end::Title-->

									</div>
									<!--end::Description-->
								</div>

								<div class="pe-20 mt-5">
									<!--begin::Info-->
									<div class="text-muted me-2 fs-7">HOID *</div>
									<!--end::Info-->
									<!--begin::Description-->
									<div class="d-flex align-items-center mt-1 fs-6">
										<!--begin::Title-->
										<div class="fs-5 fw-semibold mb-2">{{ $player->hoid }}</div>
										<!--end::Title-->

									</div>
									<!--end::Description-->
								</div>



								@php
								$validity = $fees[0]->valid_to ?? null;
								@endphp

								<div
									class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
									<div class="d-flex align-items-center">
										@if($player->status == 1)
										<div class="validation fs-3 fw-bold counted" data-kt-countup="true"
											data-kt-countup-value="" data-kt-countup-prefix="" data-kt-initialized="1">
											{{ $validity ? \Carbon\Carbon::parse($validity)->format('d M Y') : '-' }}
										</div>
										@else
										<div class="fs-3 fw-bold counted text-danger" data-kt-countup="true"
											data-kt-countup-value="" data-kt-countup-prefix="" data-kt-initialized="1">
											{{ $validity ? \Carbon\Carbon::parse($validity)->format('d M Y') : '-' }}
										</div>
										@endif
									</div>
									<div class="fw-semibold fs-6 text-dark-500">Validity</div>
								</div>

								<!--end::Stat-->
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


			<div class="qr-model" id="qrModel">
				<div class="qr-card">
					<div class="border qr-close-outer">
						<span class="qr-close" onclick="closeQrModel()">×</span>
					</div>
					<div class="qr-code-outer">
						<img src="" alt="Qr Code" id="qrModelImg">
					</div>
					<!-- Print button (UI only) -->
					<div class="qr-btn-outer">
						<button class="qr-print-btn btn btn-warning text-black dcg-btn mx-1" type="button"
							onclick="printQr(event)">
							Print
						</button>
						<button class="qr-print-btn btn btn-warning text-black dcg-btn mx-1" type="button"
							onclick="downloadQrBtn()" id="downloadQrBtn">
							Download
						</button>
					</div>
				</div>
			</div>




			<!-- Tabs -->
			<ul class="nav nav-tabs mt-4 mb-4">
				<li class="nav-item">
					<a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#overview_tab">Overview</a>
				</li>
				<li class="nav-item">
					<a class="nav-link fw-semibold" data-bs-toggle="tab" href="#attendance_tab">Attendance
						Summary</a>
				</li>
				<li class="nav-item">
					<a class="nav-link fw-semibold" data-bs-toggle="tab" href="#assessment_tab">Assessment</a>
				</li>
				<li class="nav-item">
					<a class="nav-link fw-semibold" data-bs-toggle="tab" href="#batchlog_tab">Batch Logs</a>
				</li>
				<li class="nav-item">
					<a class="nav-link fw-semibold" data-bs-toggle="tab" href="#feeDetails_tab">Fees Details</a>
				</li>
				<li class="nav-item">
					<a class="nav-link fw-semibold" data-bs-toggle="tab" href="#documents_tab">Documents</a>
				</li>

			</ul>
		</div>
	</div>

	<!-- TAB CONTENT -->
	<div class="tab-content">

		<!-- OVERVIEW TAB -->
		<div class="tab-pane fade show active" id="overview_tab">

			<!-- Profile details tab view -->
			<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">

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
								<tbody>
									<tr>
										<td>Document Number (Aadhar / Passport)</td>
										<td>{{ $player->aadhar_number ?? '-' }}</td>
									</tr>
									<tr>
										<td>Name</td>
										<td>{{ $player->first_name }} {{ $player->middle_name }}
											{{ $player->last_name }}
										</td>
									</tr>
									<tr>
										<td>Date of Birth</td>
										<td>{{ \Carbon\Carbon::parse($player->dob)->format('d-m-Y') }}</td>
									</tr>
									<tr>
										<td>Present Age</td>
										<td>{{ $player->age }} Years</td>
									</tr>
									<tr>
										<td>Gender</td>
										<td>{{ ucfirst($player->gender) }}</td>
									</tr>
									<tr>
										<td>School Name</td>
										<td>{{ $player->school_name ?? '-' }}</td>
									</tr>
									<tr>
										<td>Address</td>
										<td>{{ $player->address1 }}, {{ $player->address2 }}, {{ $player->area }},
											{{ $player->city }}, {{ $player->pincode }}
										</td>
									</tr>
									<tr>
										<td>Player Contact Number</td>
										<td>{{ $player->player_contact_no ?? '-' }}</td>
									</tr>
									<tr>
										<td>Player Email ID</td>
										<td>{{ $player->player_email ?? '-' }}</td>
									</tr>
									<tr>
										<td>Height</td>
										<td>{{ $player->foot ?? '' }}'{{ $player->inch ?? '' }}"</td>
									</tr>
									<tr>
										<td>weight</td>
										<td>{{ $player->weight ?? '' }}</td>
									</tr>

									{{-- Loop through all parents --}}
									@foreach ($player->parents as $parent)
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
										@if($parent['parent_profession'])
										<td>{{ $parent['parent_profession'] }}</td>
										@else
										<td> - </td>
										@endif
									</tr>
									@endforeach

									<tr>
										<td>Department</td>
										<td>{{ $player->dept_name ?? '-' }}</td>
									</tr>
									<tr>
										<td>HOID</td>
										<td>{{ $player->hoid ?? '-' }}</td>
									</tr>
									<tr>
										<td>Current Batch</td>
										<td>{{ $player->batch_name ?? '-' }}</td>
									</tr>
									<tr>
										<td>Current Assigned Coach</td>
										<td>
											{{ $player->coach_first_name ?? '' }}
											{{ $player->coach_middle_name ?? '' }}
											{{ $player->coach_last_name ?? '' }}
										</td>

										<!-- {{ $player->batch->coach[0]->last_name ?? '' }} -->
										</td>
									</tr>
									<tr>
										<td>Player Status</td>
										<td>
											@if($player->status === 1)
											Active
											@elseif($player->status === 2)
											Dormant
											@elseif($player->status === 0)
											Left
											@endif
										</td>
									</tr>
								</tbody>
							</table>


						</div>
					</div>
					<!--end::Body-->
				</div>
				<!--end::Chart widget 8-->

			</div>
			<!--End of Profile details tab view -->

			<!-- Edit Profile details tab section -->
			<div class="row mb-5 d-none py-3" id="kt_profile_details_editview">

				<!--begin Row-->
				<form enctype="multipart/form-data" id="saveProfileBtn" action="javascript:void(0)">
					@csrf
					<!--begin Col-->
					<div class="col-12 ">
						<!--begin QR-->
						<div class="card table-sec card-flush h-xl-100"
							style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">
							<!-- begin card body -->
							<div class="card-body">

								<div class="d-flex justify-content-between">
									<h2>Edit Details</h2>
									<h3>Player ID - {{ $player->player_id }}</h3>
								</div>

								<hr>

								<div class="common-form form">

									<div class="form-head">
										<h4>Personal Details</h4>
									</div>


									<!-- Aadhar / Passport Number -->
									<div class="row g-4 py-2">

										<div class="col-12 col-md-6">
											<label for="document-number" class="col-form-label">Document Number
												(Aadhar /
												Passport)*</label>
											<!-- <div class="d-flex"> -->
											<!-- <div class="col-10 col-xl-11"> -->
											<input type="text" class="form-control" id="aadharNo"
												value="{{ $player->aadhar_number }}" name="aadhar_number"
												placeholder="Document Number" required>
											<!-- </div> -->


											<!-- </div> -->
										</div>

										<div class="col-12 col-md-6">
											<label class="col-form-label">Passport/ID size photo*</label>

											<div class="d-flex position-relative">

												<div class="col-10 col-xl-11">

													<input class="form-control" id="image" accept="image/*"
														name="player_image" type="file" onchange="showImage(event)"
														style="color:#000;font-weight:400;">
												</div>


												<div class="col-2 col-xl-1 ps-2">
													<img id="imagPreview" class="profile-img"
														src="{{ asset($player->player_image) }}">
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
												id="firstName" value="{{ $player->first_name }}" name="first_name"
												aria-label="First name" required>
										</div>
										<div class="col-6 col-md-4">
											<label for="Name" class="col-form-label">Middle Name*</label>
											<input type="text" class="form-control" placeholder="Middle name"
												id="middleName" value="{{ $player->middle_name }}" name="middle_name"
												aria-label="Middle name" required>
										</div>
										<div class="col-6 col-md-4">
											<label for="Name" class="col-form-label">Last Name*</label>
											<input type="text" class="form-control" placeholder="Last name"
												id="lastName" value="{{ $player->last_name }}" name="last_name"
												aria-label="Last name" required>
										</div>
									</div>


									<div class="row g-4 py-2">
										<!-- DOB and Age -->
										<div class="col-12 col-md-6">
											<label for="dob" class="col-form-label">Date Of Birth*</label>
											<input type="date" class="form-control" id="dob" name="dob"
												value="{{ $player->dob }}" placeholder="Select Date" required
												style="color:#000; font-weight:400;">
										</div>
										<div class="col-12 col-md-6">
											<label for="age" class="col-form-label">Age*</label>
											<input type="text" class="form-control" name="age" id="age"
												value="{{ $player->age }}" placeholder="Age" required readonly>
										</div>
									</div>


									<div class="row g-4 py-2">
										<!-- Gender -->
										<div class="col-12 col-md-6">
											<label for="gender" class="col-form-label">Gender*</label>
											<select class="form-select" aria-label="Default select example"
												name="gender" id="gender" required style="color:#000; font-weight:400;">
												<option value="" selected disabled>Select Gender</option>
												<option value="Male" {{ $player->gender === 'Male' ? 'selected' : '' }}>
													Male</option>
												<option value="Female"
													{{ $player->gender === 'Female' ? 'selected' : '' }}>Female
												</option>
												<option value="Other"
													{{ $player->gender === 'Other' ? 'selected' : '' }}>Other
												</option>
											</select>
										</div>
										<!-- School name -->
										<div class="col-12 col-md-6">
											<label for="schoolName" class="col-form-label">School Name*</label>
											<input type="text" class="form-control" placeholder="Enter School Name"
												id="school_name" value="{{ $player->school_name }}" name="school_name"
												aria-label="schoolName" required>
										</div>
									</div>


									<!-- Address -->
									<div class="row g-4 py-2">

										<div class="col-6 col-md-6">
											<label for="Address" class="col-form-label">Address Line 1*</label>
											<input type="text" class="form-control" id="address1" name="address1"
												value="{{ $player->address1 }}" placeholder="Address line 1" required>
										</div>
										<div class="col-6 col-md-6">
											<label for="Address" class="col-form-label">Address Line 2</label>
											<input type="text" class="form-control" id="address2" name="address2">
										</div>
									</div>

									<div class="row g-4 py-2">
										<div class="col-6 col-md-4">
											<label for="area" class="col-form-label">Area/Locality*</label>
											<input type="text" class="form-control" id="area" name="area"
												value="{{ $player->area }}" placeholder="Area/Locality" required>
										</div>
										<div class="col-6 col-md-4">
											<label for="City" class="col-form-label">City*</label>
											<input type="text" class="form-control" id="city" name="city"
												value="{{ $player->city }}" placeholder="City" required>
										</div>
										<div class="col-6 col-md-4">
											<label for="pincode" class="col-form-label">Pin Code*</label>
											<input type="text" class="form-control" id="pincode" name="pincode"
												value="{{ $player->pincode }}" placeholder="Pin Code" required>
										</div>
									</div>


									<div class="row g-4 py-2">
										<!-- Mobile No -->
										<div class="col-12 col-md-6">
											<label for="dob" class="col-form-label">Player Contact
												Number*</label>
											<input type="text" class="form-control" placeholder="Enter Mobile Number"
												value="{{ $player->player_contact_no }}" id="player_contact_no"
												name="player_contact_no" required>
											<span id="dob_error" style="color:red;"></span>
										</div>

										<!-- Player Email ID -->
										<div class="col-12 col-md-6">
											<label for="player_email" class="col-form-label">Player Email
												ID*</label>
											<input type="text" class="form-control" name="player_email"
												value="{{ $player->player_email }}" id="player_email"
												placeholder="Enter Email" required>
										</div>
									</div>

									<div class="row g-4 py-2">
										<!-- Height -->
										<div class="col-12 col-md-6">
											<label for="height" class="col-form-label">Height*</label>
											<div class="d-flex">

												<select class="form-select me-2" aria-label="Default select example"
													id="foot" name="foot" id="foot" required
													style="color:#000; font-weight:400;">
													<option value="" selected disabled>Foot</option>
													<option value="1" {{ $player->foot === 1 ? 'selected' : '' }}>1
													</option>
													<option value="3" {{ $player->foot === 3 ? 'selected' : '' }}>3
													</option>
													<option value="2" {{ $player->foot === 2 ? 'selected' : '' }}>2
													</option>
													<option value="4" {{ $player->foot === 4 ? 'selected' : '' }}>4
													</option>
													<option value="5" {{ $player->foot === 5 ? 'selected' : '' }}>5
													</option>
													<option value="6" {{ $player->foot === 6 ? 'selected' : '' }}>6
													</option>
													<option value="7" {{ $player->foot === 7 ? 'selected' : '' }}>7
													</option>
													<option value="8" {{ $player->foot === 8 ? 'selected' : '' }}>8
													</option>


												</select>

												<select class="form-select" aria-label="Default select example"
													name="inch" id="inch" id="gender" required
													style="color:#000; font-weight:400;">
													<option value="" selected disabled>Inch</option>
													<option value="0" {{ $player->inch === 0 ? 'selected' : '' }}>0
													</option>
													<option value="1" {{ $player->inch === 1 ? 'selected' : '' }}>1
													</option>
													<option value="2" {{ $player->inch === 2 ? 'selected' : '' }}>2
													</option>
													<option value="3" {{ $player->inch === 3 ? 'selected' : '' }}>3
													</option>
													<option value="4" {{ $player->inch === 4 ? 'selected' : '' }}>4
													</option>
													<option value="5" {{ $player->inch === 5 ? 'selected' : '' }}>5
													</option>
													<option value="6" {{ $player->inch === 6 ? 'selected' : '' }}>6
													</option>
													<option value="7" {{ $player->inch === 7 ? 'selected' : '' }}>7
													</option>
													<option value="8" {{ $player->inch === 8 ? 'selected' : '' }}>8
													</option>
													<option value="9" {{ $player->inch === 9 ? 'selected' : '' }}>9
													</option>
													<option value="10" {{ $player->inch === 10 ? 'selected' : '' }}>10
													</option>
													<option value="11" {{ $player->inch === 11 ? 'selected' : '' }}>11
													</option>
												</select>
											</div>
										</div>

										<!-- Weight -->
										<div class="col-12 col-md-6">
											<label for="weight" class="col-form-label">Weight (Kg)*</label>
											<input type="text" class="form-control" placeholder="Enter Weight"
												id="weight" value="{{ $player->weight }}" name="weight"
												aria-label="weight" required>
										</div>
									</div>

									<div class="form-inner-head">
										<h4>Parent Details </h4>
									</div>


									<!-- Parent Repeater -->
									<div class="row g-4 py-2">

										<div id="parentRepeater">
											<div data-repeater-list="parent" id="repeate_list">
												@foreach ($player->parents as $index => $parent)
												<div data-repeater-item class="mb-3">
													<div class="row g-4">

														<input type="hidden" name="parent_id" value="{{ $parent['parent_id'] }}">
														<div class="col-md-6">
															<label class="col-form-label">Parent Name
																<b>{{ $index + 1 }}</b>* </label>
															<input type="text" class="form-control" name="parent_name"
																value="{{ $parent['parent_name'] }}" id="parent_name"
																placeholder="Enter Parent Name" required>
														</div>

														<div class="col-md-6">
															<label class="col-form-label">Relation*</label>
															<input type="text" class="form-control" name="relation"
																value="{{ $parent['relation'] }}" id="relation"
																placeholder="Relation" required>
														</div>


														<!-- P Email contact no -->
														<div class="col-md-6">
															<label class="col-form-label">Parent Contact
																Number*</label>
															<input type="text" class="form-control"
																value="{{ $parent['parent_contact'] }}"
																placeholder="Enter Mobile Number" name="parent_contact"
																id="parent_contact" required>
														</div>


														<!-- P Email ID -->
														<div class="col-md-6">
															<label class="col-form-label">Parent Email*</label>
															<input type="text" class="form-control" name="parent_email"
																value="{{ $parent['parent_email'] }}" id="parent_email"
																placeholder="Enter Email" required>
														</div>

														<!-- P profession -->

														<div class="col-md-6">
															<label class="col-form-label">Parent Profession</label>
															<input type="text" class="form-control"
																name="parent_profession"
																value="{{ $parent['parent_profession'] }}"
																placeholder="Enter Parent Profession">
														</div>

														<!-- Delete btn -->
														<div class="col-md-6">
															<label class="col-form-label">Delete</label>
															<div class="delete-btn">
																<i class="bi bi-file-excel" data-repeater-delete></i>
															</div>

														</div>

													</div>
												</div>
												@endforeach
											</div>

											<button data-repeater-create type="button"
												class="btn btn-outline-warning text-black border border-warning dcg-btn">
												Add Parent
											</button>

										</div>

									</div>

									<div class="form-inner-head">
										<h4>Department Details</h4>
									</div>

									<div class="row g-4 py-2">

										<div class="col-12 col-md-6">
											<label for="hoid" class="mb-0 col-form-label">HOID*</label>
											<input type="text" class="form-control text-gray-500 flex-grow-1 me-4"
												value="{{ $player->hoid }}" id="hoid" name="hoid"
												placeholder="Enter HOID" required>
										</div>

										<div class="col-12 col-md-6">
											<label for="batch" class="col-form-label">Player Status*</label>
											<select class="form-select" aria-label="Default select example"
												name="status" id="status" required style="color:#000; font-weight:400;">
												<option selected disabled>Select Status</option>


												<option value="1" {{ $player->status == 1 ? 'selected' : '' }}>
													Active
												</option>
												<option value="2" {{ $player->status == 2 ? 'selected' : '' }}>
													Dormant</option>
												<option value="0" {{ $player->status == 0 ? 'selected' : '' }}>
													Left
												</option>


											</select>
										</div>

									</div>

									<!-- Batch -->
									<div class="row g-4 py-2">

										<div class="col-12 col-md-6">
											<label for="batch" class="col-form-label">Batch*</label>
											<input type="text" class="form-control"
												value="{{ $player->batch_name ?? '-' }}" readonly>

										</div>


										<div class="col-12 col-md-6">
											<label class="col-form-label">Coach</label>
											<input type="text" class="form-control" name="coach"
												value=" {{ $player->coach_first_name ?? '' }} {{ $player->coach_middle_name ?? '' }} {{ $player->coach_last_name ?? '' }}"
												id="editCoach" readonly placeholder="Coach" required>
											<input type="hidden" class="form-control" name="assign_coach_id"
												id="assignCoach" placeholder="Coach" required>
										</div>

										<input type="text" class="form-control text-gray-500 flex-grow-1 me-4"
											style="display:none;" id="player_id" name="player_id"
											placeholder="000000000000" required readonly>

									</div>

									<div class="row">
										<div class="d-grid gap-2 d-flex justify-content-center py-3 px-2">
											<button class="btn btn-warning text-black border border-warning dcg-btn"
												type="submit" style="padding:8px 35px;">Save</button>
											<button
												class="btn btn-outline-warning me-md-2 border border-warning dcg-btn"
												id="cancelProfileBtn" type="button"
												style="padding:8px 35px;">Cancel</button>

										</div>
									</div>

								</div>

							</div>
							<!-- end of card body -->

						</div>
						<!-- end QR -->
					</div>
					<!-- end col -->
				</form>


				<!--end Row-->
			</div>
			<!--End of Profile details tab view -->

		</div>
		<!-- end of overview tab -->


		<!-- ATTENDANCE TAB -->
		<div class="tab-pane fade py-5" id="attendance_tab">

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


		<!-- ASSESSMENT TAB -->
		<div class="tab-pane fade" id="assessment_tab">

			<div class="mb-5" id="assessmentList">

				<!--begin::Chart widget 8-->
				<div class="card table-sec card-flush h-xl-100 mb-5">
					<!--begin::Header-->
					<div class="card-header cursor-pointer">
						<div class="card-title m-0">
							<h3 class="fw-bold m-0">Assessment Details</h3>
						</div>
						<div class="d-grid gap-2 d-md-flex justify-content-md-end py-5 px-2">
							<button class="btn btn-warning text-black dcg-btn" type="button" id="addAssessmentBtn">Add
								Assessment</button>
						</div>
					</div>


					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body p-0-0">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">

								<thead>
									<tr class="fs-6">
										<th style="width: 8%;">Date</th>
										<th style="width: 8%;">Height (ft/In)</th>
										<th style="width: 8%;">Weight (kg)</th>
										<th style="width: 10%;">30m Sprint (s)</th>
										<th style="width: 10%;">Standing Broad Jump (cm)</th>
										<th style="width: 10%;">Jump With Stepping (cm)</th>
										<th style="width: 10%;">Stationary Vertical Jump (cm)</th>
										<th style="width: 10%;">Measured / Approved By</th>
										<th style="width: 5%;">Action</th>
									</tr>
								</thead>


								<tbody>
									@forelse ($playerAssessment as $assessment)
									<tr>
										{{-- Date --}}
										<td>
											{{ \Carbon\Carbon::parse($assessment->assessment_date)->format('d-m-Y') }}
										</td>

										{{-- Height (ft / inch) --}}
										<td>
											{{ $assessment->foot }}' {{ $assessment->inch }}"
										</td>

										{{-- Weight --}}
										<td>
											{{ $assessment->weight }}
										</td>

										{{-- 30m Sprint --}}
										<td>
											{{ $assessment->sprint }}
										</td>

										{{-- Standing Broad Jump --}}
										<td>
											{{ $assessment->standing_broad_jump }}
										</td>

										{{-- Jump With Stepping --}}
										<td>
											{{ $assessment->jump_with_stepping }}
										</td>

										{{-- Vertical Jump --}}
										<td>
											{{ $assessment->vertical_jump }}
										</td>

										{{-- Approved By --}}
										<td>
											{{ $assessment->approved_by }}
										</td>

										{{-- Action --}}
										<td>
											<button type="button" class="editAssessmentBtn border border-0"
												data-id="{{ $assessment->id }}">
												<i class="fa fa-edit">
													<!-- <i class="fa fa-edit" data-bs-toggle="modal" data-bs-target="#editModal"> -->
												</i>
											</button>
										</td>
									</tr>
									@empty
									<tr>
										<td colspan="9" class="text-center">
											No assessment records found
										</td>
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
			<!-- end of assessment list -->

			<!-- The Edit Modal -->
			<div id="editModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">


				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="editModalLabel">Edit Assessment</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<!-- Form inside the modal -->
							<form id="updateAssessmentForm">
								@csrf
								<div class="row g-4 mb-3">

									<input type="hidden" id="assessment_id" name="assessment_id">
									<div class="col-12 col-md-6">
										<label for="date" class="form-label">Date of Assessment *</label>
										<input type="date" class="form-control" id="assessment_date"
											name="assessment_date" required>
									</div>
									<div class="col-12 col-md-6">
										<label for="height" class="form-label">Height *</label>
										<div class="d-flex">
											<select class="form-select" id="feet1" name="foot" required
												style="color:#000; font-weight:400;">
												<option value="" selected disabled>Select Feet</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
											</select>
											<!-- <span class="mx-2">ft</span> -->
											<select class="form-select" id="inch1" name="inch" required
												style="color:#000; font-weight:400;">
												<option value="" selected disabled>Select Inches</option>
												<option value="0">0</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="8">8</option>
												<option value="9">9</option>
												<option value="10">10</option>
												<option value="11">11</option>
											</select>
											<!-- <span class="mx-2">in</span> -->
										</div>
									</div>
								</div>
								<div class="row g-4 mb-3">
									<div class="col-12 col-md-6">
										<label for="weight" class="form-label">Weight (kg) *</label>
										<input type="number" step="any" class="form-control" id="weight1" name="weight" required>
									</div>
									<div class="col-12 col-md-6">
										<label for="sprint" class="form-label">30m Sprint (s) *</label>
										<input type="number" step="any" class="form-control" id="sprint" name="sprint" required>
									</div>
								</div>
								<div class="row g-4 mb-3">
									<div class="col-12 col-md-6">
										<label for="broadJump" class="form-label">Standing Broad Jump (cm)</label>
										<input type="number" step="any" class="form-control" id="standing_broad_jump"
											name="standing_broad_jump">
									</div>
									<div class="col-12 col-md-6">
										<label for="jumpWithStep" class="form-label">Jump With Stepping (cm)</label>
										<input type="number" step="any" class="form-control" id="jump_with_stepping"
											name="jump_with_stepping">
									</div>
								</div>
								<div class="row g-4 mb-3">
									<div class="col-12 col-md-6">
										<label for="stationaryJump" class="form-label">Stationary Vertical Jump
											(cm)</label>
										<input type="number" step="any" class="form-control" id="vertical_jump" name="vertical_jump">
									</div>
									<div class="col-12 col-md-6">
										<label for="measuredBy" class="form-label">Measured / Approved By *</label>
										<select class="form-select" id="approved_by" name="approved_by" required
											style="color:#000; font-weight:400;">
											<option value="">Select Measured By</option>
											@foreach ($coaches as $coach)
											<option value="{{ $coach->first_name . ' ' . $coach->last_name }}">
												{{ $coach->first_name }} {{ $coach->last_name }}
											</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-warning text-black dcg-btn"
										style="padding:8px 35px;">Save</button>
									<button type="button" class="btn btn-outline-warning text-black dcg-btn"
										style="padding:8px 35px;" data-bs-dismiss="modal">Cancel</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Assesment adding form -->
			<div class="row mb-3 d-none" id="assessmentForm">

				<div class="col-12 ">
					<!--begin QR-->
					<div class="card table-sec card-flush h-xl-100"
						style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">

						<div class="card-body p-3">

							<div class="card-title m-0 py-3">
								<h3 class="fw-bold m-0">Assessment Details</h3>
							</div>
							<!--begin::Form-->
							<form action="" method="post" id="assesmentData" class="common-form form">
								@csrf

								<input type="hidden" name="player_id" value="{{ $player->player_id }}">

								<div class="row g-4 mb-3">
									<div class="col-12 col-md-6">
										<label for="date" class="form-label">Date of Assessment *</label>
										<input type="date" class="form-control" id="date" name="assessment_date"
											required>
									</div>
									<div class="col-12 col-md-6">
										<label for="height" class="form-label">Height *</label>
										<div class="d-flex">
											<select class="form-select" id="foot" name="foot" required
												style="color:#000; font-weight:400;">
												<option value="" selected disabled>Select Feet</option>

												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
											</select>
											<!-- <span class="mx-2">ft</span> -->
											<select class="form-select" id="heightInch" name="inch" required
												style="color:#000; font-weight:400;">
												<option value="" selected disabled>Select Inches</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="8">8</option>
												<option value="9">9</option>
												<option value="10">10</option>
												<option value="11">11</option>
											</select>
											<!-- <span class="mx-2">in</span> -->
										</div>
									</div>
								</div>
								<div class="row g-4 mb-3">
									<div class="col-12 col-md-6">
										<label for="weight" class="form-label">Weight (kg) *</label>
										<input type="number" step="any" class="form-control" id="weight" name="weight" required>
									</div>
									<div class="col-12 col-md-6">
										<label for="sprint" class="form-label">30m Sprint (s) *</label>
										<input type="number" step="any" class="form-control" id="sprint" name="sprint" required>
									</div>
								</div>
								<div class="row g-4 mb-3">
									<div class="col-12 col-md-6">
										<label for="broadJump" class="form-label">Standing Broad Jump
											(cm)</label>
										<input type="number" step="any" class="form-control" id="standing_broad_jump"
											name="standing_broad_jump">
									</div>
									<div class="col-12 col-md-6">
										<label for="jumpWithStep" class="form-label">Jump With Stepping
											(cm)</label>
										<input type="number" step="any" class="form-control" id="jump_with_stepping"
											name="jump_with_stepping">
									</div>
								</div>
								<div class="row g-4 mb-3">
									<div class="col-12 col-md-6">
										<label for="stationaryJump" class="form-label">Stationary Vertical Jump
											(cm)</label>
										<input type="number" step="any" class="form-control" id="vertical_jump" name="vertical_jump">
									</div>
									<div class="col-12 col-md-6">
										<label for="measuredBy" class="form-label">Measured / Approved By
											*</label>
										<select class="form-select" id="measuredBy" name="measuredBy" value="" required
											style="color:#000; font-weight:400;">
											<option value="">Select Measured By</option>
											@foreach ($coaches as $coach)
											<option value="{{ $coach->first_name . ' ' . $coach->last_name }}">
												{{ $coach->first_name }} {{ $coach->last_name }}
											</option>
											@endforeach
										</select>

									</div>
								</div>

								<div class="row">
									<div class="col-12 ">
										<div class="card-body p-0-0">
											<div class="d-grid gap-2 d-md-flex justify-content-md-center py-3 px-2">
												<button class="btn btn-warning text-black" type="submit"
													id="assesmentForm" style="padding:8px 25px;">Save</button>
												<button class="btn btn-outline-warning me-md-2 border border-warning"
													type="button" id="cancelAssessmentBtn"
													style="padding:8px 25px;">Cancel</button>

											</div>
										</div>
									</div>
								</div>


							</form>
						</div>
						<!-- card body -->
					</div>
					<!-- end of card -->
				</div>
				<!-- end of col -->


			</div>

		</div>
		<!-- end of assessment tab -->

		<!-- Batch Log TAB -->
		<div class="tab-pane fade" id="batchlog_tab">

			<!-- Start od assement list -->
			<div id="BatchList">


				<div class="card table-sec card-flush h-xl-100 mb-5">
					<!--begin::Header-->
					<div class="card-header cursor-pointer">
						<div class="card-title m-0">
							<h3 class="fw-bold m-0">Batch Details</h3>
						</div>
						<div class="d-grid gap-2 d-md-flex justify-content-md-end py-5 px-2">
							<button class="btn btn-warning text-black dcg-btn" type="button" id="addBatchBtn">Add
								Batch Details</button>
						</div>
					</div>


					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body p-0-0">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" id="batch_log">
								<thead>
									<tr class="fs-6">
										<th style="width: 8%;">Sr. No.</th>
										<th style="width: 8%;">Batch Name</th>
										<th style="width: 8%;">Coach</th>
										<th style="width: 10%;">Start Date</th>
										<th style="width: 10%;">End Date</th>
										<th style="width: 10%;">Action</th>
									</tr>
								</thead>

								<tbody class="alternating-rows">

									@foreach ($batchlog as $index => $log)
									<tr>

										<td>{{ $index + 1 }}</td>
										<td>{{ $log->batch_name ?? 'N/A' }}</td>
										<td>{{ $log->first_name . ' ' . $log->middle_name . ' ' . $log->last_name ?? '' }}
										</td>
										<td>{{ \Carbon\Carbon::parse($log->start_date)->format('d-m-Y') }}</td>
										<td>
											@if ($log->end_date)
											{{ \Carbon\Carbon::parse($log->end_date)->format('d-m-Y') }}
											@else
											-
											@endif
										</td>
										<td>
											@if($index == 0)
											<i class="fa fa-edit edit-batch-icon" data-bs-toggle="modal"
												data-bs-target="#editBatchModal" data-log-id="{{ $log->id }}"
												data-batch-id="{{ $log->batch_id }}"
												data-coach-id="{{ $log->coach_id }}"
												data-start-date="{{ \Carbon\Carbon::parse($log->start_date)->format('Y-m-d') }}"
												data-end-date="{{ $log->end_date ? \Carbon\Carbon::parse($log->end_date)->format('Y-m-d') : '' }}"
												data-coach-name="{{ $log->first_name . ' ' . $log->middle_name . ' ' . $log->last_name }}"
												style="cursor:pointer;">
											</i>
											@else
											<i class="fa fa-edit edit-batch-icon" data-bs-toggle="modal" aria-disabled=""></i>
											@endif

										</td>
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


			<!-- The Edit Modal -->
			<!-- The Edit Modal -->
			<div id="editBatchModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="editModalLabel">Edit Batch Information</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<!-- Form inside the modal -->
							<form id="editBatchForm" method="POST">
								@csrf
								<input type="hidden" name="player_id" value="{{ $player->player_id }}">
								<input type="hidden" name="dept_player_id" value="{{ $deptPlayerId->id ?? '' }}">
								<input type="hidden" name="log_id" id="log_id" value="">

								<div class="row g-4 mb-3">
									<div class="col-12 col-md-6">
										<label for="batch_id" class="form-label">Batch*</label>
										<select class="form-select" id="batch_id" name="batch_id" required>
											<option value="">Select Batch</option>
											@foreach ($batches as $batch)
											<option value="{{ $batch->id }}" data-coach="{{ $batch->coach_id }}">
												{{ $batch->batch_name }}
											</option>
											@endforeach
										</select>
									</div>

									<div class="col-12 col-md-6">
										<label for="coach_name" class="form-label">Coach*</label>
										<input type="text" class="form-control" id="coach_name" readonly>
										<input type="hidden" id="coach_id" name="coach_id">
									</div>

								</div>
								<div class="row g-4 mb-3">
									<div class="col-12 col-md-6">
										<label for="start_date" class="form-label">Start Date*</label>
										<input type="date" class="form-control" id="start_date" name="start_date"
											required>
									</div>
									<div class="col-12 col-md-6">
										<label for="end_date" class="form-label">End Date</label>
										<input type="date" class="form-control" id="end_date" name="end_date">
									</div>
								</div>

								<div class="modal-footer">
									<button type="submit" class="btn btn-warning text-black dcg-btn"
										style="padding:8px 35px;">Save</button>
									<button type="button" class="btn btn-outline-warning text-black dcg-btn"
										data-bs-dismiss="modal" style="padding:8px 35px;">Cancel</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- batch adding form -->
			<div class="row mb-3 d-none" id="batchForm" method="post">
				<div class="col-12 ">
					<div class="card table-sec card-flush h-xl-100"
						style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">
						<div class="card-body p-3">
							<div class="card-title m-0 py-3">
								<h3 class="fw-bold m-0">Add Batch Details</h3>
							</div>

							<!-- Fixed: Added CSRF token and form ID -->
							<form action="{{ route('batch-log.store') }}" method="post" id="batchLogForm"
								class="common-form form">
								@csrf
								<input type="hidden" value="{{ $player->player_id }}" name="player_id">

								<!-- Select Date - Fixed: Different names for start and end date -->
								<div class="row g-4 mb-3">
									<div class="col-12 col-md-6">
										<label for="start_date" class="col-form-label">Start Date*</label>
										<input type="date" class="form-control flex-grow-1 me-4"
											value="<?php echo date('Y-m-d'); ?>" name="start_date" id="start_date"
											required>
									</div>
									<div class="col-12 col-md-6">
										<label for="end_date" class="col-form-label">End Date*</label>
										<input type="date" class="form-control flex-grow-1 me-4"
											value="<?php echo date('Y-m-d'); ?>" name="end_date" id="end_date" required>
									</div>
								</div>

								<!-- Batch & Coach -->
								<div class="row g-4 mb-3">
									<div class="col-md-6">
										<label class="col-form-label">Batch*</label>
										<select class="form-select" name="batch_id" id="batch_id_ofLog"
											style="color:#000; font-weight:400;" required>
											<option value="" selected disabled>Select Batch</option>
											@foreach ($batches as $batch)
											<option value="{{ $batch->id }}" data-coach="{{ $batch->coach_id }}">
												{{ $batch->batch_name }}
											</option>
											@endforeach
										</select>
									</div>

									<div class="col-md-6">
										<label class="col-form-label">Coach*</label>
										<input type="text" class="form-control" name="coach_ids" id="coach_id_ofLog"
											placeholder="Coach" readonly required>
										<input type="hidden" class="form-control" name="coach_id_for_batch_log"
											id="coach_id_for_batch_log" placeholder="Coach" readonly required>


									</div>
								</div>

								<div class="row">
									<div class="col-12 ">

										<div class="card-body p-0-0">
											<div class="d-grid gap-2 d-md-flex justify-content-md-center py-3 px-2">
												<button class="btn btn-warning text-black" type="submit"
													id="saveBatchLog" style="padding:8px 35px;">
													Save
												</button>
												<button class="btn btn-outline-warning border border-warning"
													type="button" id="cancelBatchLog" style="padding:8px 35px;">
													Cancel
												</button>

											</div>
										</div>

									</div>
								</div>

							</form>
						</div>
					</div>
				</div>


			</div>
		</div>
		<!-- End of batch log tab -->


		<!-- Fee Details TAB -->
		<div class="tab-pane fade" id="feeDetails_tab">

			<!-- Start od assement list -->
			<div id="feedetailsList">


				<div class="card table-sec card-flush h-xl-100 mb-5">
					<!--begin::Header-->
					<div class="card-header cursor-pointer">
						<div class="card-title m-0">
							<h3 class="fw-bold m-0">Fees Details</h3>
						</div>
						<div class="d-grid gap-2 d-md-flex justify-content-md-end py-5 px-2">
							<button class="btn btn-warning text-black dcg-btn" type="button" id="addfeesBtn">Add
								Fees Details</button>
						</div>
					</div>


					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body p-0-0">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" id="fee_tbl">
								<thead>
									<tr class="fs-6">
										<th style="width: 8%;">Date</th>
										<th style="width: 8%;">Fees Amount</th>
										<th style="width: 8%;">Receipt No.</th>
										<th style="width: 10%;">Valid From</th>
										<th style="width: 10%;">Valid To</th>
									</tr>
								</thead>


								<tbody class="alternating-rows">
									@foreach ($fees as $fee)
									<tr>
										<td>{{ \Carbon\Carbon::parse($fee->date)->format('d-m-Y') }}</td>
										<td>₹ {{ number_format($fee->amount) }}</td>
										<td>{{ $fee->receipt_no }}</td>
										<td>{{ \Carbon\Carbon::parse($fee->valid_from)->format('d-m-Y') }}</td>
										<td>{{ \Carbon\Carbon::parse($fee->valid_to)->format('d-m-Y') }}</td>
									</tr>
									@endforeach

								</tbody>
							</table>

						</div>
					</div>
					<!--end::Body-->
				</div>

			</div>
			<!-- end of Fee Details list -->

			<!-- Fee details form -->
			<div class="row mb-3 d-none" id="feedetailsForm">
				<div>
					<!-- Updated form action -->
					<form method="post" id="addFeeForm" action="{{ route('store-fee') }}">
						@csrf
						<div class="col-12">
							<!--begin QR-->
							<div class="card table-sec card-flush h-xl-100"
								style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">
								<div class="card-body p-3">
									<div class="card-title m-0 py-3">
										<h3 class="fw-bold m-0">Add Fee Details</h3>
									</div>
									<!--begin::Form-->
									<div class="common-form form">
										<!-- Select Date -->
										<div class="row g-4 mb-3">
											<div class="col-12 col-md-4">
												<label for="date" class="col-form-label">Date*</label>
												<input type="date" class="form-control flex-grow-1 me-4"
													value="<?php echo date('Y-m-d'); ?>" name="date" id="date" required>
											</div>
											<div class="col-12 col-md-4">
												<label for="amount" class="col-form-label">Fees Amount*</label>
												<input type="number" class="form-control flex-grow-1 me-4" name="amount"
													id="amount" placeholder="₹ 000" required>
											</div>
											<div class="col-12 col-md-4">
												<label for="receipt_no" class="col-form-label">Receipt No.*</label>
												<input type="number" class="form-control flex-grow-1 me-4"
													name="receipt_no" id="receipt_no" placeholder="Enter Receipt No."
													required>
											</div>
										</div>
										<div class="row g-4 mb-3">
											<div class="col-12 col-md-6">
												<label for="valid_from" class="col-form-label">Valid From*</label>
												<input type="date" class="form-control flex-grow-1 me-4"
													name="valid_from" id="valid_from" required>
											</div>
											<div class="col-12 col-md-6">
												<label for="valid_to" class="col-form-label">Valid To*</label>
												<input type="date" class="form-control flex-grow-1 me-4" name="valid_to"
													id="valid_to" required>
											</div>
										</div>
										<input type="hidden" name="player_id" value="{{ $player->player_id }}">

										<div class="row">
											<div class="col-12">
												<!--begin btn-->

												<!-- begin card body -->
												<div class="card-body p-0-0">
													<div
														class="d-grid gap-2 d-md-flex justify-content-md-center py-3 px-2">
														<button class="btn btn-warning text-black" type="submit"
															style="padding:8px 35px;">Save</button>
														<button
															class="btn btn-outline-warning me-md-2 border border-warning"
															type="button" id="cancelFeeBtn"
															style="padding:8px 35px;">Cancel</button>
													</div>

													<!-- end of card body -->
												</div>
												<!-- end btn -->
											</div>
										</div>

									</div>
								</div>
								<!-- card body -->
							</div>
							<!-- end of card -->
						</div>
						<!-- end of col -->


					</form>
				</div>
			</div>

		</div>
		<!-- End of Fees Details tab -->


		<!-- Document Details TAB -->
		<div class="tab-pane fade" id="documents_tab">

			<!-- Start document list -->
			<div id="documentDetailsList">


				<div class="card table-sec card-flush h-xl-100 mb-5">

					<div class="card-header cursor-pointer">
						<div class="card-title m-0">
							<h3 class="fw-bold m-0">Documents</h3>
						</div>
						<div class="d-grid gap-2 d-md-flex justify-content-md-end py-5 px-2">
							<button class="btn btn-warning text-black dcg-btn" type="button" id="addDocumentBtn">Add
								Document</button>
						</div>
					</div>



					<div class="card-body p-0-0">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" id="document_tbl">
								<thead>

									<tr class="fs-6">
										<th style="width: 8%;">Sr. No.</th>
										<th style="width: 35%;">Document Name</th>
										<th style="width: 48%;">Description</th>
										<th style="width: 8%;">Action</th>

									</tr>
								</thead>


								<tbody class="alternating-rows">
									@foreach($documents as $index => $doc)
									<tr>
										<td>{{ ++$index }}</td>
										<td>{{ $doc->document_name }}</td>
										<td>{{ $doc->description }}</td>

										@php
										$extension = strtolower(pathinfo($doc->file, PATHINFO_EXTENSION));
										@endphp
										<td>
											<div class="d-flex align-items-center gap-2">
												@if($extension == 'pdf')
												<!-- Open PDF in new tab -->
												<a href="{{ asset($doc->file) }}" target="_blank" class="text-primary">
													<i class="bi bi-eye-fill"></i>
												</a>
												@else
												<!-- Open Image in Lightbox / Zoom -->
												<a href="{{ url('viewDocumentImage/'. $doc->id) }}" target="_blank" class="text-primary">
													<i class="bi bi-eye-fill"></i>
												</a>
												@endif

												<button class="delete_doc border-0 bg-transparent text-danger p-0"
													type="button" data-id="{{ $doc->id }}">
													<i class="bi bi-trash-fill"></i>
												</button>
											</div>
										</td>

									</tr>
									@endforeach


								</tbody>
							</table>

						</div>
					</div>
					<!--end::Body-->
				</div>

			</div>
			<!-- end of document list -->

			<!-- document details form -->
			<div class="row mb-3 d-none" id="documentDetailsForm">
				<div>
					<!-- Updated form action -->
					<form method="post" id="addDocumentForm" enctype="multipart/form-data">
						@csrf
						<div class="col-12">
							<!--begin QR-->
							<div class="card table-sec card-flush h-xl-100"
								style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">
								<div class="card-body p-3">
									<div class="card-title m-0 py-3">
										<h3 class="fw-bold m-0">Add New Document</h3>
									</div>
									<!--begin::Form-->
									<div class="common-form form">
										<!-- Select Date -->
										<div class="row g-4 mb-3">
											<div class="col-12 col-md-6">
												<label for="document_name" class="col-form-label">Document Name*</label>
												<input type="text" class="form-control flex-grow-1 me-4"
													placeholder="Enter document Name" name="document_name"
													id="document_name" required>
											</div>

											<div class="col-12 col-md-6">
												<label for="file" class="col-form-label">Upload File*</label>

												<div class="d-flex">
													<!-- <div class=" col-md-10 col-lg-11"> -->
													<input type="file"
														class="form-control flex-grow-1 me-4"
														name="file" id="file"
														accept=".jpg, .jpeg, .png, application/pdf"
														required>


												</div>
												<span class="text-muted">Select a file (PDF, JPEG, PNG only)</span>
												<!-- <div class="col-2 col-md-1 px-2" id="previewContainer1"> <img
													id="imagPreview1" class="profile-img" src="" /> </div> -->
												<!-- </div> -->
											</div>

										</div>
										<div class="row g-4 mb-3">
											<div class="col-12 col-md-12">
												<label for="description" class="col-form-label">Description</label>
												<textarea class="form-control form-control-solid" name="description"
													id="description" rows="4"
													placeholder="Enter Description"></textarea>

											</div>

										</div>
										<input type="hidden" name="player_id" value="{{ $player->player_id }}">

										<div class="row">
											<div class="col-12">
												<!--begin btn-->

												<!-- begin card body -->
												<div class="card-body p-0-0">
													<div
														class="d-grid gap-2 d-md-flex justify-content-md-center py-3 px-2">
														<button class="btn btn-warning text-black" type="submit"
															style="padding:8px 35px;">Save</button>
														<button
															class="btn btn-outline-warning me-md-2 border border-warning"
															type="button" id="cancelDocumentBtn"
															style="padding:8px 35px;">Cancel</button>
													</div>

													<!-- end of card body -->
												</div>
												<!-- end btn -->
											</div>
										</div>

									</div>
								</div>
								<!-- card body -->
							</div>
							<!-- end of card -->
						</div>
						<!-- end of col -->


					</form>
				</div>
			</div>

		</div>
		<!-- End of document Details tab -->


		<!-- Attendence Modal -->
		<div id="attendanceCoachModal" class="attendance-modal">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Attendance Details :-</h4>
					<span class="close-btn" onclick="closeModal()">×</span>
					<!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
				</div>

				<div id="modalBody"></div>
			</div>
		</div>
		<!-- End Attendence Modal -->


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

	</div>
	<!-- Include jQuery first -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Then, include DataTables -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

	<!-- Include SweetAlert2 and other scripts later -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery.repeater@1.2.1/jquery.repeater.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>


	<script>
		// Edit profile btn hide and show functionality
		document.getElementById("editProfileBtn").addEventListener("click", function() {
			document.getElementById("kt_profile_details_view").classList.add("d-none");
			document.getElementById("kt_profile_details_editview").classList.remove("d-none");
		});

		// Add assessment form btn hide and show functionality
		document.getElementById("addAssessmentBtn").addEventListener("click", function() {
			document.getElementById("assessmentList").classList.add("d-none");
			document.getElementById("assessmentForm").classList.remove("d-none");
		});

		document.getElementById("cancelAssessmentBtn").addEventListener("click", function() {
			document.getElementById("assessmentList").classList.remove("d-none");
			document.getElementById("assessmentForm").classList.add("d-none");
		});


		// Add Batch form btn hide and show functionality
		document.getElementById("addBatchBtn").addEventListener("click", function() {
			document.getElementById("BatchList").classList.add("d-none");
			document.getElementById("batchForm").classList.remove("d-none");
		});


		// Add Fee details form btn hide and show functionality
		document.getElementById("addfeesBtn").addEventListener("click", function() {
			document.getElementById("feedetailsList").classList.add("d-none");
			document.getElementById("feedetailsForm").classList.remove("d-none");
		});

		// Add Document details form btn hide and show functionality
		document.getElementById("addDocumentBtn").addEventListener("click", function() {
			document.getElementById("documentDetailsList").classList.add("d-none");
			document.getElementById("documentDetailsForm").classList.remove("d-none");
		});

		//for cancel btn 
		document.getElementById("cancelProfileBtn").addEventListener("click", function() {
			document.getElementById("kt_profile_details_view").classList.remove("d-none");
			document.getElementById("kt_profile_details_editview").classList.add("d-none");
		});

		document.getElementById("cancelFeeBtn").addEventListener("click", function() {
			document.getElementById("feedetailsList").classList.remove("d-none");
			document.getElementById("feedetailsForm").classList.add("d-none");
		});

		document.getElementById("cancelDocumentBtn").addEventListener("click", function() {
			document.getElementById("documentDetailsList").classList.remove("d-none");
			document.getElementById("documentDetailsForm").classList.add("d-none");
		});

		//parents repeater
		$('#parentRepeater').repeater({
			show: function() {
				$(this).slideDown();

			},
			hide: function(deleteElement) {
				let totalItems = $('[data-repeater-item]').length;
				if (totalItems === 1) {
					if (confirm('Sorry! At least one parent details required.')) {
						return;
					}
				} else {
					if (confirm('Are you sure you want to delete this data ?')) {
						$(this).slideUp(deleteElement);

					}
				}
			}
		});

		//calculate age 
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

				}, "image/jpeg", 0.90);

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

		function replaceFileInput(blob, fileName) {
			const compressedFile = new File([blob], fileName, {
				type: "image/jpeg"
			});
			const dataTransfer = new DataTransfer();
			dataTransfer.items.add(compressedFile);
			document.getElementById("image").files = dataTransfer.files;
		}





		// Qr Modal
		function showQr(src) {
			document.getElementById('qrModelImg').src = src;
			document.getElementById('qrModel').style.display = 'flex';
		}

		function closeQrModel() {
			document.getElementById('qrModel').style.display = 'none';
		}
		// end of Qr modal

		//print qrcode 
		function printQr(e) {
			e.preventDefault();
			window.print();
		}

		//download qrcode
		function downloadQrBtn() {

			const img = document.getElementById("qrModelImg");

			// console.log(img);
			if (!img.src) {
				alert("QR image not found!");
				return;
			}

			const link = document.createElement("a");
			link.href = img.src;
			link.download = "qr-code.png"; // file name
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
		};


		$(document).ready(function() {
			// add assessment form submit
			$(document).on('click', '#assesmentForm', function(e) {

				let form = document.getElementById('assesmentData');

				if (!form.checkValidity()) {
					form.reportValidity();
					return;
				}

				e.preventDefault();
				let formData = new FormData(form);

				Swal.fire({
					title: 'Saving Assessment...',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: "{{ url('store-assesment') }}",
					method: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}',
					},
					success: function(response) {
						Swal.close();

						if (response.status === 'success') {
							Swal.fire({
								icon: 'success',
								title: 'Saved!',
								text: response.message ||
									'Assessment saved successfully!',

							}).then(() => {
								// Store tab state in localStorage before reload
								localStorage.setItem('activeTab',
									'#assessment_tab');

								// Now reload
								window.location.reload();
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Error',
								text: response.message ||
									'Failed to save assessment.'
							});
						}
					},
					error: function(xhr) {
						Swal.close();
						// ... error handling code ...
					}
				});
			});

			// edit assesment on model 
			$(document).on('click', '.editAssessmentBtn', function() {

				const id = $(this).data('id');

				$.ajax({
					url: "{{ url('editAssessment') }}/" + id,
					method: 'GET',
					dataType: 'json',
					success: function(data) {

						console.log(data.id);

						$('#assessment_id').val(data.id);
						$('#assessment_date').val(data.assessment_date);
						$('#date').val(data.assessment_date);

						$('#feet1').val(String(parseInt(data.foot)));
						$('#inch1').val(String(parseInt(data.inch)));

						// Convert weight to float
						$('#weight1').val(data.weight);
						$('#sprint').val(data.sprint);

						$('#standing_broad_jump').val(data.standing_broad_jump);
						$('#jump_with_stepping').val(data.jump_with_stepping);
						$('#vertical_jump').val(data.vertical_jump);

						$('#approved_by').val(data.approved_by);


						$('#editModal').modal('show');
					},
					error: function(xhr) {
						console.log(xhr.responseText);
					}
				});
			});

			$(document).on('submit', '#updateAssessmentForm', function(e) {
				e.preventDefault();

				let id = $('#assessment_id').val();

				let formData = new FormData(this);
				Swal.fire({
					title: 'Updating Assessment...',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: "{{ url('updateAssessment') }}/" + id,
					method: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}',
					},
					success: function(response) {
						if (response.status === 'success') {
							Swal.fire({
								title: "Success!",
								text: response.message,
								icon: "success"
							}).then(() => {
								localStorage.setItem('activeTab',
									'#assessment_tab');
								window.location.reload();
							});
						} else {
							// If status is not success
							Swal.fire({
								title: "Error!",
								text: "Something went wrong.",
								icon: "error"
							});
						}
					},
					error: function(xhr) {
						console.log(xhr.responseText);
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





			//for edit overview 
			function loadCoachData() {
				let id = $("#editBatch").find(':selected').data('coach');

				if (id) {
					$.ajax({
						url: "{{ route('getCoachByBatchId') }}",
						method: 'GET',
						data: {
							coach_id: id,
						},
						headers: {
							'X-CSRF-TOKEN': '{{ csrf_token() }}',
						},
						success: function(response) {
							if (response.status === "success") {
								let coach = response.data;
								let fullName = [
									coach.first_name,
									coach.middle_name,
									coach.last_name
								].filter(Boolean).join(' ');

								$('#editCoach').val(fullName);
								$('#assignCoach').val(coach.id);
							}
						},
						error: function(error) {
							alert(error);
						}
					});
				}
			}

			loadCoachData();
			$("#editBatch").change(function() {
				loadCoachData();
			});

			function loadCoach() {
				let id = $("#batch_id_ofLog").find(':selected').data('coach');

				if (id) {
					$.ajax({
						url: "{{ route('getCoachByBatchId') }}",
						method: 'GET',
						data: {
							coach_id: id,
						},
						headers: {
							'X-CSRF-TOKEN': '{{ csrf_token() }}',
						},
						success: function(response) {
							if (response.status === "success") {
								let coach = response.data;
								let fullName = [
									coach.first_name,
									coach.middle_name,
									coach.last_name
								].filter(Boolean).join(' ');

								$('#coach_id_ofLog').val(fullName);

								$('#coach_id_for_batch_log').val(coach.id);
							}
						},
						error: function(error) {
							alert(error);
						}
					});
				}
			}
			loadCoach();
			$("#batch_id_ofLog").change(function() {
				loadCoach();
			});


			//submit form player profile data
			$("#saveProfileBtn").on('submit', function(e) {
				e.preventDefault();

				let formData = new FormData(this);
				formData.append('mode', 'update');

				Swal.fire({
					title: 'Saving Profile...',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: "{{ url('update-player-profile/' . $player->player_id) }}",
					method: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}',
					},
					success: function(response) {
						if (response.status === 'success') {
							Swal.fire({
								title: "Success!",
								text: response.message,
								icon: "success"
							}).then(() => {
								window.location.href = "{{ route('player-list') }}";
							});
						} else {
							// If status is not success
							Swal.fire({
								title: "Error!",
								text: "Something went wrong.",
								icon: "error"
							});
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
								text: 'Something went wrong!'
							});
						}
					}
				});
			});




			// Hide tab content initially
			$('.tab-content').removeClass('ready');

			// Set active tab immediately on page load
			let activeTab = '#overview_tab'; // default

			// Check URL hash first
			if (window.location.hash) {
				activeTab = window.location.hash;
			}
			// Check localStorage for tab
			else if (localStorage.getItem('activeTab')) {
				activeTab = localStorage.getItem('activeTab');
				localStorage.removeItem('activeTab'); // Clear after use
			}

			// Set the correct tab as active immediately
			$(`.nav-link[href="${activeTab}"]`).addClass('active');
			$(activeTab).addClass('show active');

			// Remove active class from default tab
			if (activeTab !== '#overview_tab') {
				$('.nav-link[href="#overview_tab"]').removeClass('active');
				$('#overview_tab').removeClass('show active');
			}

			setTimeout(function() {
				$('.tab-content').addClass('ready');
			}, 50);


			// submit player document 
			$(document).on('submit', '#addDocumentForm', function(e) {
				e.preventDefault();

				let formData = new FormData(this);

				Swal.fire({
					title: 'Saving Document...',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});


				$.ajax({
					url: "{{ url('store-document') }}",
					type: "POST",
					data: formData,
					processData: false,
					contentType: false,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(response) {
						Swal.close();

						if (response.status === 'success') {
							Swal.fire({
								icon: 'success',
								title: 'Saved!',
								text: response.message ||
									'Document saved successfully!',
							}).then(() => {
								clearFormFields();
								$('#documentDetailsForm').addClass('d-none');
								$('#documentDetailsList').removeClass('d-none');

								// activateFeeDetailsTab();

								if (response.document) {
									addNewFeeToTable(response.document);
								} else {
									location.reload();
								}
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Error',
								text: response.message || 'Failed to save Document.'
							});
						}
					},
					error: function(xhr) {
						Swal.close();

						let errorMsg = 'An unexpected error occurred. Please try again.';
						if (xhr.status === 422) {
							let errors = xhr.responseJSON.errors;
							errorMsg = Object.values(errors).map(e => e[0]).join('\n');
						} else if (xhr.status === 403) {
							errorMsg = 'Unauthorized action.';
						} else if (xhr.responseJSON && xhr.responseJSON.message) {
							errorMsg = xhr.responseJSON.message;
						}

						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: errorMsg
						});

						console.error(xhr.responseText);
					}
				});
			});

			$(document).on('click', '.delete_doc', function() {
				let id = $(this).data('id');

				if (!id) {
					Swal.fire({
						title: "sorry!",
						text: "Id not found.",
						icon: "error!"
					});
				}

				Swal.fire({
					title: "Are you sure?",
					text: "You won't be able to revert this!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#F6C000",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, delete it!"
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							url: "{{ url('deleteDocument') }}/" + id,
							type: "POST",
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							success: function(response) {
								Swal.close();

								if (response.status === 'success') {
									Swal.fire({
										title: "Deleted!",
										text: "Your file has been deleted.",
										icon: "success"
									}).then(() => {
										window.location.reload();

									});
								} else {
									Swal.fire({
										icon: 'error',
										title: 'Error',
										text: response.message || 'Failed to save Document.'
									});
								}
							},
							error: function(xhr) {
								Swal.close();

								let errorMsg = 'An unexpected error occurred. Please try again.';
								if (xhr.status === 422) {
									let errors = xhr.responseJSON.errors;
									errorMsg = Object.values(errors).map(e => e[0]).join('\n');
								} else if (xhr.status === 403) {
									errorMsg = 'Unauthorized action.';
								} else if (xhr.responseJSON && xhr.responseJSON.message) {
									errorMsg = xhr.responseJSON.message;
								}

								Swal.fire({
									icon: 'error',
									title: 'Error',
									text: errorMsg
								});

								console.error(xhr.responseText);
							}

						});

					}
				});




			});

		});

		// Update URL when tabs are clicked
		$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
			window.location.hash = $(e.target).attr('href');
		});



		// Check URL hash on page load
		if (window.location.hash === '#batchlog_tab') {
			activateBatchLogTab();
		}

		$(document).on('click', '#addBatchBtn', function() {
			$('#batchForm').removeClass('d-none');
			$('#BatchList').addClass('d-none');
			activateBatchLogTab();
		});

		$(document).on('click', '#saveBatchLog', function(e) {
			e.preventDefault();

			if (!validateBatchForm()) return;

			let form = document.getElementById('batchLogForm');
			let formData = new FormData(form);

			Swal.fire({
				title: 'Updating...',
				allowOutsideClick: false,
				didOpen: () => {
					Swal.showLoading();
				}
			});

			$.ajax({
				url: "{{ route('batch-log.store') }}",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(response) {
					Swal.close();

					if (response.status) {
						Swal.fire({
							icon: 'success',
							title: 'Saved!',
							text: response.message ||
								'Batch log saved successfully!',

						}).then(() => {
							form.reset();
							$('#batchForm').addClass('d-none');
							$('#BatchList').removeClass('d-none');

							// Activate Batch Log tab
							activateBatchLogTab();

							// Instead of reloading, update the table with new data
							// Add the new row to the table
							if (response.batch_log) {
								addNewBatchLogToTable(response.batch_log);
							} else {
								// If no data returned, reload the page
								location.reload();
							}
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Error!',
							text: response.message,

						});
					}
				},
				error: function(xhr) {
					Swal.close();

					let errorMsg = 'An unexpected error occurred. Please try again.';
					if (xhr.status === 422) {
						let errors = xhr.responseJSON.errors;
						errorMsg = Object.values(errors).map(e => e[0]).join('\n');
					} else if (xhr.status === 403) {
						errorMsg = 'Unauthorized action.';
					}

					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: errorMsg
					});

					console.error(xhr.responseText);
				}
			});
		});

		$(document).on('click', '#cancelBatchLog', function() {
			$('#batchLogForm')[0].reset();
			$('#batchForm').addClass('d-none');
			$('#BatchList').removeClass('d-none');
			activateBatchLogTab();
		});

		function validateBatchForm() {
			let errors = [];
			let startDate = $('#start_date').val();
			let endDate = $('#end_date').val();
			let batchId = $('#batch_id').val();
			let coachId = $('#coach_id').val();

			// if (!startDate) errors.push('Start date is required.');
			// if (!endDate) errors.push('End date is required.');
			// if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
			// errors.push('End date cannot be before start date.');
			// }
			// if (!batchId) errors.push('Please select a batch.');
			// if (!coachId) errors.push('Please select a coach.');

			if (errors.length > 0) {
				Swal.fire({
					icon: 'warning',
					title: 'Validation Error',
					text: errors.join('\n')
				});
				return false;
			}
			return true;
		}

		$('#start_date').on('change', function() {
			$('#end_date').attr('min', $(this).val());
		});

		// Function to activate Batch Log tab
		function activateBatchLogTab() {
			$('.nav-link').removeClass('active');
			$('a[href="#batchlog_tab"]').addClass('active');
			$('.tab-pane').removeClass('active show');
			$('#batchlog_tab').addClass('active show');

			// Update URL hash
			if (history.pushState) {
				history.pushState(null, null, '#batchlog_tab');
			} else {
				window.location.hash = '#batchlog_tab';
			}
		}

		// Function to add new batch log to table without reloading
		function addNewBatchLogToTable(batchLog) {
			// Get current row count
			let rowCount = $('#BatchList tbody tr').length;
			let newRowNumber = rowCount + 1;

			// Format dates
			let startDate = new Date(batchLog.start_date).toLocaleDateString('en-GB');
			let endDate = batchLog.end_date ?
				new Date(batchLog.end_date).toLocaleDateString('en-GB') :
				'-';

			// Create new row HTML
			let newRow = `
	<tr>
		<td>${newRowNumber}</td>
		<td>${batchLog.batch.batch_name || 'N/A'}</td>
		<td>${batchLog.coach.first_name || ''} ${batchLog.coach.last_name || ''}</td>
		<td>${startDate}</td>
		<td>${endDate}</td>
		<td>
			<i class="fa fa-edit" data-bs-toggle="modal"
				data-bs-target="#editModal${batchLog.id}" id="edit-icon"></i>
		</td>
	</tr>
	`;

			// Append new row to table
			$('#BatchList tbody').append(newRow);
		}

		// Handle tab clicks
		$('.nav-link[data-bs-toggle="tab"]').on('click', function() {
			const target = $(this).attr('href');
			if (history.pushState) {
				history.pushState(null, null, target);
			} else {
				window.location.hash = target;
			}
		});

		// Handle browser back/forward buttons
		$(window).on('hashchange', function() {
			if (window.location.hash === '#batchlog_tab') {
				activateBatchLogTab();
			}
		});


		// Check URL hash on page load for fee tab
		if (window.location.hash === '#feeDetails_tab') {
			activateFeeDetailsTab();
		}

		// Handle button click to open add fee form
		$(document).on('click', '#addfeesBtn', function(e) {
			e.preventDefault();

			// Set default dates BEFORE showing the form
			setDefaultDates();

			// Show form and hide list
			$('#feedetailsForm').removeClass('d-none');
			$('#feedetailsList').addClass('d-none');
			activateFeeDetailsTab();
		});

		// Handle cancel button for fee form
		$(document).on('click', '#cancelFeeBtn', function() {
			// DON'T reset - just hide
			// Hide form and show list
			$('#feedetailsForm').addClass('d-none');
			$('#feedetailsList').removeClass('d-none');
			activateFeeDetailsTab();
		});

		// Function to validate fee form
		function validateFeeForm() {
			let errors = [];
			let date = $('#date').val();
			let amount = $('#amount').val();
			let receiptNo = $('#receipt_no').val();
			let validFrom = $('#valid_from').val();
			let validTo = $('#valid_to').val();

			if (!date) errors.push('Date is required.');
			if (!amount || amount <= 0) errors.push('Fees amount must be greater than 0.');
			if (!receiptNo) errors.push('Receipt number is required.');
			if (!validFrom) errors.push('Valid from date is required.');
			if (!validTo) errors.push('Valid to date is required.');

			if (validFrom && validTo && new Date(validTo) < new Date(validFrom)) {
				errors.push('Valid to date cannot be before valid from date.');
			}

			if (errors.length > 0) {
				Swal.fire({
					icon: 'warning',
					title: 'Validation Error',
					text: errors.join('\n')
				});
				return false;
			}
			return true;
		}

		// Function to set default dates
		function setDefaultDates() {
			// Get today's date in YYYY-MM-DD format
			let today = new Date().toISOString().split('T')[0];

			// Get date 30 days from now
			let thirtyDaysFromNow = new Date();
			thirtyDaysFromNow.setDate(thirtyDaysFromNow.getDate() + 30);
			let nextMonth = thirtyDaysFromNow.toISOString().split('T')[0];

			// Set the values directly (overwrites any existing values)
			$('#date').val(today);
			$('#valid_from').val(today);
			$('#valid_to').val(nextMonth);

			// Also set the min attribute for valid_to
			$('#valid_to').attr('min', today);

			// Clear amount and receipt_no fields
			$('#amount').val('');
			$('#receipt_no').val('');
		}

		// Date validation for valid_to
		$('#valid_from').on('change', function() {
			let validFrom = $(this).val();
			if (validFrom) {
				$('#valid_to').attr('min', validFrom);
			}
		});

		// Function to activate Fee Details tab
		function activateFeeDetailsTab() {
			$('.nav-link').removeClass('active');
			$('a[href="#feeDetails_tab"]').addClass('active');
			$('.tab-pane').removeClass('active show');
			$('#feeDetails_tab').addClass('active show');

			// Update URL hash
			if (history.pushState) {
				history.pushState(null, null, '#feeDetails_tab');
			} else {
				window.location.hash = '#feeDetails_tab';
			}
		}

		// Function to add new fee to table without reloading
		function addNewFeeToTable(fee) {
			// Get the tbody element
			let tbody = $('#fee_tbl tbody');

			// Check if table has "No fee records" message
			let hasNoRecords = tbody.find('tr td').text().includes('No fee records');
			let hasNoDept = tbody.find('tr td').text().includes('No department assigned');

			// Format dates to dd-mm-yyyy
			function formatDate(dateString) {
				let date = new Date(dateString);
				let day = String(date.getDate()).padStart(2, '0');
				let month = String(date.getMonth() + 1).padStart(2, '0');
				let year = date.getFullYear();
				return `${day}-${month}-${year}`;
			}

			let formattedDate = formatDate(fee.date);
			let formattedValidFrom = formatDate(fee.valid_from);
			let formattedValidTo = formatDate(fee.valid_to);

			// Format amount with Indian Rupee symbol
			let formattedAmount = '₹ ' + parseFloat(fee.amount).toLocaleString('en-IN');

			// Create new row HTML
			let newRow = `
	<tr>
		<td>${formattedDate}</td>
		<td>${formattedAmount}</td>
		<td>${fee.receipt_no}</td>
		<td>${formattedValidFrom}</td>
		<td>${formattedValidTo}</td>
	</tr>
	`;

			if (hasNoRecords || hasNoDept) {
				// Replace the entire tbody with new row
				tbody.html(newRow);
			} else {
				// Add new row at the top of the table
				tbody.prepend(newRow);
			}
		}

		// Handle tab clicks
		$('.nav-link[data-bs-toggle="tab"]').on('click', function() {
			const target = $(this).attr('href');
			if (history.pushState) {
				history.pushState(null, null, target);
			} else {
				window.location.hash = target;
			}

			// If fee tab is clicked, ensure list is visible
			if (target === '#feeDetails_tab') {
				$('#feedetailsList').removeClass('d-none');
				$('#feedetailsForm').addClass('d-none');
			}
		});

		$(window).on('hashchange', function() {
			if (window.location.hash === '#feeDetails_tab') {
				activateFeeDetailsTab();
				$('#feedetailsList').removeClass('d-none');
				$('#feedetailsForm').addClass('d-none');
			}
		});

		var table = $('#batch_log').DataTable({
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
					// targets: 5
				} // disable ordering on Action column
			],
			language: {
				lengthMenu: "Show _MENU_ entries",
				search: "Search:"
			},
			dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto"f>>rt<"bottom"ip><"clear">'
		});



		$('#fee_tbl').DataTable({
			ordering: true,

			searching: true,
			paging: true,
			pageLength: 10,
			lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			],
			language: {
				emptyTable: "No fee records found",
				lengthMenu: "Show _MENU_ entries",
				search: "Search:"
			},
			dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto"f>>rt<"bottom"ip><"clear">'
		});

		if (window.location.hash === '#feeDetails_tab') {
			activateFeeDetailsTab();
		}

		$('#document_tbl').DataTable({
			ordering: true,

			searching: true,
			paging: true,
			pageLength: 10,
			lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			],
			language: {
				emptyTable: "No fee records found",
				lengthMenu: "Show _MENU_ entries",
				search: "Search:"
			},
			dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto"f>>rt<"bottom"ip><"clear">'
		});

		$(document).on('click', '#addfeesBtn', function(e) {
			e.preventDefault();

			clearFormFields();

			$('#feedetailsForm').removeClass('d-none');
			$('#feedetailsList').addClass('d-none');
			activateFeeDetailsTab();
			$('#amount').focus();
		});
		$(document).on('submit', '#addFeeForm', function(e) {
			e.preventDefault();

			if (!validateFeeForm()) return;

			let formData = new FormData(this);

			Swal.fire({
				title: 'Saving Fee...',
				allowOutsideClick: false,
				didOpen: () => {
					Swal.showLoading();
				}
			});

			$.ajax({
				url: $(this).attr('action'),
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(response) {
					Swal.close();

					if (response.status === 'success') {
						Swal.fire({
							icon: 'success',
							title: 'Saved!',
							text: response.message || 'Fee saved successfully!',

						}).then(() => {
							clearFormFields();
							$('#feedetailsForm').addClass('d-none');
							$('#feedetailsList').removeClass('d-none');

							activateFeeDetailsTab();

							if (response.fee) {
								addNewFeeToTable(response.fee);
							} else {
								location.reload();
							}
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: response.message || 'Failed to save fee.'
						});
					}
				},
				error: function(xhr) {
					Swal.close();

					let errorMsg = 'An unexpected error occurred. Please try again.';
					if (xhr.status === 422) {
						let errors = xhr.responseJSON.errors;
						errorMsg = Object.values(errors).map(e => e[0]).join('\n');
					} else if (xhr.status === 403) {
						errorMsg = 'Unauthorized action.';
					} else if (xhr.responseJSON && xhr.responseJSON.message) {
						errorMsg = xhr.responseJSON.message;
					}

					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: errorMsg
					});

					console.error(xhr.responseText);
				}
			});
		});

		$(document).on('click', '#cancelFeeBtn', function() {
			// Clear form
			clearFormFields();

			// Hide form and show list
			$('#feedetailsForm').addClass('d-none');
			$('#feedetailsList').removeClass('d-none');
			activateFeeDetailsTab();
		});

		function clearFormFields() {
			$('#date').val('');
			$('#valid_from').val('');
			$('#valid_to').val('');

			// Clear other fields
			$('#amount').val('');
			$('#receipt_no').val('');
			$('#valid_to').removeAttr('min');
		}

		function validateFeeForm() {
			let errors = [];
			let date = $('#date').val();
			let amount = $('#amount').val();
			let receiptNo = $('#receipt_no').val();
			let validFrom = $('#valid_from').val();
			let validTo = $('#valid_to').val();

			if (!amount || amount <= 0) errors.push('Fees amount must be greater than 0.');
			if (!receiptNo) errors.push('Receipt number is required.');
			if (!validFrom) errors.push('Valid from date is required.');
			if (!validTo) errors.push('Valid to date is required.');

			if (validFrom && validTo && new Date(validTo) < new Date(validFrom)) {
				errors.push('Valid to date cannot be before valid from date.');
			}

			if (errors.length > 0) {
				Swal.fire({
					icon: 'warning',
					title: 'Validation Error',
					text: errors.join('\n')
				});
				return false;
			}
			return true;
		}

		// Date validation for valid_to
		$('#valid_from').on('change', function() {
			let validFrom = $(this).val();
			if (validFrom) {
				$('#valid_to').attr('min', validFrom);
			} else {
				$('#valid_to').removeAttr('min');
			}
		});

		function activateFeeDetailsTab() {
			$('.nav-link').removeClass('active');
			$('a[href="#feeDetails_tab"]').addClass('active');
			$('.tab-pane').removeClass('active show');
			$('#feeDetails_tab').addClass('active show');

			if (history.pushState) {
				history.pushState(null, null, '#feeDetails_tab');
			} else {
				window.location.hash = '#feeDetails_tab';
			}
		}

		function addNewFeeToTable(fee) {
			let tbody = $('#fee_tbl tbody');

			let hasNoRecords = tbody.find('tr td').text().includes('No fee records');
			let hasNoDept = tbody.find('tr td').text().includes('No department assigned');

			function formatDate(dateString) {
				let date = new Date(dateString);
				let day = String(date.getDate()).padStart(2, '0');
				let month = String(date.getMonth() + 1).padStart(2, '0');
				let year = date.getFullYear();
				return `${day}-${month}-${year}`;
			}

			let formattedDate = formatDate(fee.date);
			let formattedValidFrom = formatDate(fee.valid_from);
			let formattedValidTo = formatDate(fee.valid_to);

			let formattedAmount = '₹ ' + parseFloat(fee.amount).toLocaleString('en-IN');

			let newRow = `
	<tr>
		<td>${formattedDate}</td>
		<td>${formattedAmount}</td>
		<td>${fee.receipt_no}</td>
		<td>${formattedValidFrom}</td>
		<td>${formattedValidTo}</td>
	</tr>
		`;

			if (hasNoRecords || hasNoDept) {
				tbody.html(newRow);
			} else {
				tbody.prepend(newRow);
			}
		}

		// Handle tab clicks
		$('.nav-link[data-bs-toggle="tab"]').on('click', function() {
			const target = $(this).attr('href');
			if (history.pushState) {
				history.pushState(null, null, target);
			} else {
				window.location.hash = target;
			}

			if (target === '#feeDetails_tab') {
				$('#feedetailsList').removeClass('d-none');
				$('#feedetailsForm').addClass('d-none');
			}
		});

		$(window).on('hashchange', function() {
			if (window.location.hash === '#feeDetails_tab') {
				activateFeeDetailsTab();
				$('#feedetailsList').removeClass('d-none');
				$('#feedetailsForm').addClass('d-none');
			}
		});

		// CLICK EDIT ICON
		$('.edit-batch-icon').on('click', function() {

			let logId = $(this).data('log-id');
			let batchId = $(this).data('batch-id');
			let coachId = $(this).data('coach-id');
			let startDate = $(this).data('start-date');
			let endDate = $(this).data('end-date');
			let coachName = $(this).data('coach-name');


			// Set hidden log id
			$('#log_id').val(logId);
			$('#coach_id').val(coachId);

			// Set batch and trigger change
			$('#batch_id').val(batchId).trigger('change');

			// Set dates
			$('#start_date').val(startDate);
			$('#end_date').val(endDate);
			$('#coach_name').val(coachName);


		});

		// LOAD COACH WHEN BATCH CHANGES
		$('#batch_id').on('change', function() {

			let coachId = $(this).find(':selected').data('coach');

			$('#coach_name').val('');
			$('#coach_id').val('');

			if (!coachId) return;

			$('#coach_name').val('Loading...');

			$.ajax({
				url: "{{ route('getCoachByBatchId') }}",
				type: "GET",
				data: {
					coach_id: coachId
				},
				success: function(res) {
					if (res.status === 'success') {
						let c = res.data;
						let fullName = [c.first_name, c.middle_name, c.last_name].filter(Boolean)
							.join(' ');
						$('#coach_name').val(fullName);
						$('#coach_id').val(c.id);
					} else {
						$('#coach_name').val('No coach assigned');
					}
				},
				error: function() {
					$('#coach_name').val('Error loading coach');
				}
			});
		});

		// RESET MODAL
		$('#editBatchModal').on('hidden.bs.modal', function() {
			$('#editBatchForm')[0].reset();
			$('#coach_name').val('');
			$('#coach_id').val('');
		});

		// Listen for batch selection change
		$('#batch_id').on('change', function() {
			let selectedCoachId = $(this).find(':selected').data('coach');

			let coachNameInput = $('#coach_name');
			let coachIdInput = $('#coach_id');

			// Clear inputs initially
			coachNameInput.val('');
			coachIdInput.val('');

			if (selectedCoachId) {
				// Show loading
				coachNameInput.val('Loading...');

				// Make AJAX call to get coach data
				$.ajax({
					url: "{{ route('getCoachByBatchId') }}",
					method: 'GET',
					data: {
						coach_id: selectedCoachId,
					},
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}',
					},
					success: function(response) {
						if (response.status === "success") {
							let coach = response.data;

							// Build full name
							let fullName = [
								coach.first_name,
								coach.middle_name,
								coach.last_name
							].filter(Boolean).join(' ');

							// Set the input field and hidden ID
							coachNameInput.val(fullName);
							coachIdInput.val(coach.id);
						} else {
							// Reset if no coach found
							coachNameInput.val('No coach assigned');
							coachIdInput.val('');
						}
					},
					error: function(error) {
						console.error(error);
						coachNameInput.val('Error loading coach');
						coachIdInput.val('');
					}
				});
			} else {
				// Show message if no batch selected
				coachNameInput.val('Please select a batch first');
				coachIdInput.val('');
			}
		});

		// Optional: Clear coach when batch selection is cleared
		$('#batch_id').on('click', function() {
			if ($(this).val() === '') {
				$('#coach_name').val('');
				$('#coach_id').val('');
			}
		});

		$('#editBatchForm').on('submit', function(e) {
			e.preventDefault();

			let form = $(this);
			let formData = form.serialize();

			Swal.fire({
				title: 'Editing Batch Details...',
				allowOutsideClick: false,
				didOpen: () => {
					Swal.showLoading();
				}
			});

			$.ajax({
				url: "{{ route('update.batch') }}",
				type: "POST",
				data: formData,
				success: function(response) {

					if (response.status === 'success') {


						$('#editBatchModal').modal('hide');


						Swal.fire({
							icon: 'success',
							title: 'Success',
							text: response.message,
							confirmButtonText: 'OK'
						}).then(() => {

							// ✅ Open Batch Log tab
							$('#batch-log-tab').trigger('click');

							// ✅ Refresh the page after 500ms (tab will stay open)
							setTimeout(function() {
								location.reload();
							}, 500);

						});

					} else {
						Swal.fire('Error', 'Something went wrong!', 'error');
					}
				},
				error: function(xhr) {

					if (xhr.status === 422) {
						let errors = xhr.responseJSON.message;
						let errorMsg = '';

						$.each(errors, function(key, value) {
							errorMsg += value[0] + '<br>';
						});

						Swal.fire({
							icon: 'warning',
							title: 'Validation Error',
							html: errorMsg
						});
					} else {
						Swal.fire('Error', 'Server error occurred!', 'error');
					}
				}
			});
		});
	</script>

	<!-- Attendence Calender -->
	<script>
		const attendanceData = @json($player_attendance);
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
				fetch(`{{ route('update.player', ':id') }}`.replace(':id', id), {
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
							closeModal();
							Swal.fire({
								title: 'Error!',
								text: data.message || 'Update failed',
								icon: 'error'
							});
						}
					})
					.catch(err => {
						console.error(err);
						closeModal();
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