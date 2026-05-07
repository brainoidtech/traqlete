<x-default-layout>
	@section('pagetitle', $data['pagetitle'])

	<style>
		.common-form label {
			color: #686666;
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


	<form method="POST" id="addCoachForm" enctype="multipart/form-data">
		@csrf

		<!--begin::Row-->
		<div class="row gx-5 gx-xl-10 py-5 pt-lg-0">
			<!--begin::Col-->
			<div class="col-12 ">
				<!--begin::Chart widget 8-->
				<div class="card table-sec card-flush h-xl-100 card-bn rounded-top"
					style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">

					<div class="card-body">

						<div class="d-flex justify-content-between">
							<h2>Register New Coach</h2>
							<h3>New Coach ID - DG{{ $coachCode }}</h3>
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
									<input type="text" class="form-control" id="document" name="document"
										placeholder="Enter Document Number" required>
								</div>

								<div class="col-12 col-md-6">
									<label class="col-form-label">Passport/ID size photo*</label>

									<div class="d-flex position-relative">

										<div class="col-10 col-xl-11">

											<input class="form-control" id="coach_image" accept="image/*"
												name="coach_image" type="file" onchange="showImage(event)"
												style="color:#000;font-weight:400;">
										</div>


										<div class="col-2 col-xl-1 ps-2">
											<img id="imagPreview" class="profile-img"
												src="{{ asset('assets/images/barcode/placeholder.jpg') }}">
										</div>
									</div>
									<input type="hidden" name="cropped_image" id="cropped_image">
								</div>

							</div>

							<input type="hidden" name="id" value="DG{{ $coachCode }}">

							<!-- Name -->
							<div class="row g-4 py-2">

								<div class="col-6 col-md-4">
									<label for="Name" class="col-form-label">First Name*</label>
									<input type="text" class="form-control" placeholder="Enter First Name"
										id="firstName" name="first_name" aria-label="First name" required>
								</div>
								<div class="col-6 col-md-4">
									<label for="Name" class="col-form-label">Middle Name*</label>
									<input type="text" class="form-control" placeholder="Enter Middle Name"
										id="middleName" name="middle_name" aria-label="Middle name" required>
								</div>
								<div class="col-6 col-md-4">
									<label for="Name" class="col-form-label">Last Name*</label>
									<input type="text" class="form-control" placeholder="Enter Last Name"
										id="lastName" name="last_name" aria-label="Last name" required>
								</div>
							</div>


							<div class="row g-4 py-2">
								<!-- DOB and Age -->
								<div class="col-12 col-md-6">
									<label for="dob" class="col-form-label">Date Of Birth*</label>
									<input type="date" class="form-control" id="dob" name="dob"
										placeholder="Select Date" required style="color:#000 !important;">
									<span id="dob_error" style="color:red;"></span>
								</div>
								<div class="col-12 col-md-6">
									<label for="age" class="col-form-label">Age*</label>
									<input type="text" class="form-control" name="age" id="age"
										placeholder="Age" required readonly>
								</div>
							</div>


							<div class="row g-4 py-2">
								<!-- Gender -->
								<div class="col-12 col-md-6">
									<label for="gender" class="col-form-label">Gender*</label>
									<select class="form-select" aria-label="Default select example" name="gender"
										id="gender" required style="color:#000 !important; font-weight:400;">
										<option selected>Select Gender</option>
										<option value="Male">Male</option>
										<option value="Female">Female</option>
										<option value="Other">Other</option>
									</select>
								</div>
							</div>


							<!-- Address -->
							<div class="row g-4 py-2">
								<div class="col-6 col-md-6">
									<label for="Address" class="col-form-label">Address Line 1*</label>
									<input type="text" class="form-control" id="address1" name="address1"
										placeholder="Address line 1" required>
								</div>
								<div class="col-6 col-md-6">
									<label for="Address" class="col-form-label">Address Line 2</label>
									<input type="text" class="form-control" id="address2" name="address2"
										placeholder="Address line 2">
								</div>
							</div>

							<div class="row g-4 py-2">
								<div class="col-6 col-md-4">
									<label for="area" class="col-form-label">Area/Locality*</label>
									<input type="text" class="form-control" id="area" name="area"
										placeholder="Enter Area/Locality" required>
								</div>
								<div class="col-6 col-md-4">
									<label for="City" class="col-form-label">City*</label>
									<input type="text" class="form-control" id="city" name="city"
										placeholder="City" required>
								</div>
								<div class="col-6 col-md-4">
									<label for="pincode" class="col-form-label">Pin Code*</label>
									<input type="number" class="form-control" id="pincode" name="pincode"
										placeholder="Enter Pin Code" required>
								</div>
							</div>

							<div class="form-inner-head">
								<h4>Credential Details</h4>
							</div>

							<div class="row g-4 py-2">

								<div class="col-12 col-md-6">
									<label for="mobile" class="mb-0 col-form-label">Contact Number*</label>
									<input type="text" class="form-control text-gray-500 flex-grow-1 me-4"
										id="coach_contact_no" name="coach_contact_no"
										placeholder="Enter Mobile Number" required>
								</div>

								<div class="col-12 col-md-6">
									<label for="Email" class="mb-0 col-form-label">Email*</label>
									<input type="text" class="form-control text-gray-500 flex-grow-1 me-4"
										id="coach_email" name="coach_email" placeholder="Enter Email" required>
								</div>



								<div class="col-12 col-md-6">
									<label for="Password" class="mb-0 col-form-label">Password*</label>

									<div style="position: relative;">
										<input type="password" id="Password" name="password"
											placeholder="Enter New Password" required class="form-control text-gray-500 flex-grow-1 me-4"
											style="padding-right: 45px;">

										<span class="togglePassword btn btn-sm btn-icon"
											style="position: absolute; top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer; display: flex; align-items: center; justify-content: center; color: #6c757d;">
											<i class="fa-solid fa-eye"></i>
										</span>
									</div>
								</div>



							</div>

							<div class="row">
								<div class="d-grid gap-2 d-flex justify-content-center py-3 px-2">
									<button class="btn btn-warning text-black border border-warning dcg-btn"
										type="submit" style="padding:8px 35px;">Save</button>
									<button class="btn btn-outline-warning me-md-2 border border-warning dcg-btn"
										id="cancelBtn" type="button" style="padding:8px 35px;">Cancel</button>

								</div>
							</div>



						</div>

					</div>

				</div>
				<!-- end widget 8 -->

			</div>
			<!-- end col -->

		</div>
		<!--end::Row-->

		<!-- Start Crop Image Modal -->
		<div id="cropModalBackdrop">

			<div id="cropPopup">
				<div class="crop-header">
					Crop Image

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

	</form>

	@push('scripts')
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

	<script>
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

			const originalFile = document.getElementById("coach_image").files[0];
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

		function replaceFileInput(blob, fileName) {
			const compressedFile = new File([blob], fileName, {
				type: "image/jpeg"
			});
			const dataTransfer = new DataTransfer();
			dataTransfer.items.add(compressedFile);
			document.getElementById("coach_image").files = dataTransfer.files;
		}

		$(document).ready(function() {

			//calculate age by dob
			// $('#dob').on('input', function(e) {

			// 	let dob = $(this).val();
			// 	let birth_year = dob.substring(0, 4);

			// 	let todays_date = new Date();
			// 	let year = todays_date.getFullYear();

			// 	const age = year - birth_year;
			// 	$('#age').val(age);

			// 	console.log(age);
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

			//cancel btn
			$('#cancelBtn').click(function(e) {
				window.location.href = "{{ route('coach-list') }}";
			});



			//Exist player data show
			$('#document').on('keydown', function(e) {
				if (e.key === "Enter") {
					e.preventDefault();
					let document_number = $(this).val().trim();
					if (document_number == '') {
						return false;
					}
					$.ajax({
						url: "{{ route('checkExistingCoach') }}",
						method: "GET",
						data: {
							"document": document_number,
						},
						success: function(response) {

							if (response) {

								let data = response.data;

								$('#firstName').val(data.first_name);
								$('#lastName').val(data.last_name);
								$('#middleName').val(data.middle_name);
								$('#dob').val(data.dob);
								$('#age').val(data.age);
								$('#address1').val(data.address1);
								$('#address2').val(data.address2);
								$('#area').val(data.area);
								$('#city').val(data.city);
								$('#pincode').val(data.pincode);
								$('#gender').val(data.gender);
								$('#coach_contact_no').val(data.coach_contact_no);
								$('#coach_email').val(data.coach_email);

								$('#imagPreview').attr('src', data.coach_image);
								// $('#previewContainer').show();

								setImgFromUrl(data.coach_image, 'coach_image')

							}

						},
						error: function(error, xhr, status) {
							console.error('Error: ', error);
						}
					});
				}
			});

			async function setImgFromUrl(imgUrl, imgInput) {
				const response = await fetch(imgUrl);
				const blob = await response.blob();
				const filename = imgUrl.split('/').pop();
				const file = new File([blob], filename, {
					type: blob.type
				});

				const dataTransfer = new DataTransfer();
				dataTransfer.items.add(file);

				document.getElementById(imgInput).files = dataTransfer.files;
			}


			//submit form 
			$('#addCoachForm').submit(function(e) {
				e.preventDefault();
				// alert('fdg');

				let formData = new FormData(this);
				Swal.fire({
					title: 'Saving ...',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: "{{ route('storeCoach') }}",
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

		});

		const toggle = document.querySelector(".togglePassword");
		const input = document.querySelector("#Password");

		toggle.addEventListener("click", function() {
			const type = input.type === "password" ? "text" : "password";
			input.type = type;

			this.querySelector("i").classList.toggle("fa-eye");
			this.querySelector("i").classList.toggle("fa-eye-slash");
		});
	</script>
	@endpush

</x-default-layout>