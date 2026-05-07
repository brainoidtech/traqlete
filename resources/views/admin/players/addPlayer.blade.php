<x-default-layout>
    @section('pagetitle', $data['pagetitle'])
    <style>
        .common-form label {
            color: #686666;
        }

        input[type="select"] {
            color: #000 !important;
        }

        .delete-btn i {
            font-size: 40px;
            padding: 10px;
            color: #F16937;
        }

        /*Crop Image modal  Backdrop Overlay */
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

    <form action="" method="POST" id="addPlayerForm" class="py-4" enctype="multipart/form-data">
        @csrf

        <!--begin Row-->
        <div class="row gx-5 gx-xl-10 py-3 ">
            <!--begin Col-->
            <div class="col-12 ">
                <!--begin QR-->
                <div class="card table-sec card-flush h-xl-100"
                    style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">
                    <!-- begin card body -->
                    <div class="card-body px-2 px-md-5">

                        <div class="d-flex justify-content-between">
                            <h2>Register New Player</h2>
                            <h3>New Player ID - DG{{ $playerCode }}</h3>
                        </div>

                        <hr>

                        <div class="common-form form">

                            <div class="form-head">
                                <h4>Personal Details</h4>
                            </div>


                            <!-- Aadhar / Passport Number -->
                            <div class="row g-4 py-2">
                                <!--  -->
                                <div class="col-12 col-md-6">
                                    <label for="document-number" class="col-form-label">Document Number (Aadhar /
                                        Passport)*</label>
                                    <input type="text" class="form-control" id="aadharNo" name="aadhar_number"
                                        placeholder="Enter Document Number" required>
                                </div>


                                <div class="col-12 col-md-6">
                                    <label class="col-form-label">Passport/ID size photo*</label>
                                    <div class="d-flex position-relative">
                                        <div class="col-10 col-xl-11">
                                            <input class="form-control" id="player_image" accept="image/*" name="player_image"
                                                type="file" onchange="showImage(event)"
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

                            <!-- Name -->
                            <div class="row g-4 py-2">

                                <div class="col-6 col-md-4">
                                    <label for="Name" class="col-form-label">First Name*</label>
                                    <input type="text" class="form-control" placeholder="First name" id="firstName"
                                        name="first_name" aria-label="Enter First name" required>
                                </div>
                                <div class="col-6 col-md-4">
                                    <label for="Name" class="col-form-label">Enter Middle Name*</label>
                                    <input type="text" class="form-control" placeholder="Middle name" id="middleName"
                                        name="middle_name" aria-label="Middle name" required>
                                </div>
                                <div class="col-6 col-md-4">
                                    <label for="Name" class="col-form-label">Enter Last Name*</label>
                                    <input type="text" class="form-control" placeholder="Last name" id="lastName"
                                        name="last_name" aria-label="Last name" required>
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
                                    <input type="text" class="form-control" name="age" id="age" placeholder="Age"
                                        required readonly>
                                </div>
                            </div>



                            <div class="row g-4 py-2">
                                <!-- Gender -->
                                <div class="col-12 col-md-6">
                                    <label for="gender" class="col-form-label">Gender*</label>
                                    <select class="form-select" aria-label="Default select example" name="gender"
                                        id="gender" required style="color:#000; font-weight:400;">
                                        <option value="" selected disabled>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <!-- School name -->
                                <div class="col-12 col-md-6">
                                    <label for="schoolName" class="col-form-label">School Name*</label>
                                    <input type="text" class="form-control" placeholder="Enter School Name"
                                        id="school_name" id="schoolName" name="school_name" aria-label="schoolName"
                                        required>
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
                                        placeholder="Enter City Name" required>
                                </div>
                                <div class="col-6 col-md-4">
                                    <label for="pincode" class="col-form-label">Pin Code*</label>
                                    <input type="number" class="form-control" id="pincode" name="pincode"
                                        placeholder="Enter Pin Code" required>
                                </div>

                            </div>


                            <div class="row g-4 py-2">
                                <!-- Mobile No -->
                                <div class="col-12 col-md-6">
                                    <label for="dob" class="col-form-label">Player Contact Number*</label>
                                    <input type="number" class="form-control" placeholder="Enter Mobile Number"
                                        id="player_contact_no" name="player_contact_no" required>
                                </div>

                                <!-- Player Email ID -->
                                <div class="col-12 col-md-6">
                                    <label for="player_email" class="col-form-label">Player Email ID*</label>
                                    <input type="email" class="form-control" name="player_email" id="player_email"
                                        placeholder="Enter Email" required>
                                </div>
                            </div>

                            <div class="row g-4 py-2">
                                <!-- Height -->
                                <div class="col-12 col-md-6">
                                    <label for="height" class="col-form-label">Height*</label>
                                    <div class="d-flex">

                                        <select class="form-select me-2" aria-label="Default select example" id="foot"
                                            name="foot" id="gender" required style="color:#000; font-weight:400;">
                                            <option value="" selected disabled>Select Foot</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>

                                        </select>

                                        <select class="form-select" aria-label="Default select example" name="inch"
                                            id="inch" id="gender" required style="color:#000; font-weight:400;">
                                            <option value="" selected disabled>Inch</option>
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
                                    </div>
                                </div>

                                <!-- Weight -->
                                <div class="col-12 col-md-6">
                                    <label for="weight" class="col-form-label">Weight (Kg)*</label>
                                    <input type="number" step="any" class="form-control" placeholder="Enter Weight" id="weight"
                                        name="weight" aria-label="weight" required>
                                </div>

                            </div>

                            <div class="form-inner-head">
                                <h4>Parent Details</h4>
                            </div>


                            <!-- Parent Repeater -->
                            <div class="row g-4 py-2">

                                <div id="parentRepeater">
                                    <div data-repeater-list="parent" id="repeate_list">
                                        <div data-repeater-item class="mb-3">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <label class="col-form-label">Parent Name*</label>
                                                    <input type="text" class="form-control" name="parent_name"
                                                        id="parent_name" placeholder="Enter Parent Name" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="col-form-label">Relation*</label>
                                                    <input type="text" class="form-control" name="relation"
                                                        id="relation" placeholder="Enter Relation with Parent" required>
                                                </div>


                                                <!-- P Email contact no -->
                                                <div class="col-md-6">
                                                    <label class="col-form-label">Parent Contact Number*</label>
                                                    <input type="number" class="form-control"
                                                        placeholder="Enter Mobile Number" name="parent_contact"
                                                        id="parent_contact" required>
                                                </div>


                                                <!-- P Email ID -->
                                                <div class="col-md-6">
                                                    <label class="col-form-label">Parent Email*</label>
                                                    <input type="email" class="form-control" name="parent_email"
                                                        id="parent_email" placeholder="Enter Email" required>
                                                </div>

                                                <!-- P profession -->
                                                <div class="col-md-6">
                                                    <label class="col-form-label">Parent Profession</label>
                                                    <input type="text" class="form-control" name="parent_profession"
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
                                    <input type="text" class="form-control text-gray-500 flex-grow-1 me-4" id="hoid"
                                        name="hoid" placeholder="Enter HOID" required>
                                </div>
                            </div>

                            <!-- Batch -->
                            <div class="row g-4 py-2">

                                <div class="col-12 col-md-6">
                                    <label for="batch" class="col-form-label">Batch*</label>
                                    <select class="form-select" aria-label="Default select example" name="batch_id"
                                        id="batch" required style="color:#000; font-weight:400;">
                                        <option selected disabled>Select Batch</option>
                                        @foreach($playersBatchData as $batch)

                                        <option value="{{ $batch->id }}" data-coach="{{ $batch->coach_id  }}">
                                            {{ $batch->batch_name}}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="col-form-label">Coach</label>
                                    <input type="text" class="form-control" name="coach" id="coach" placeholder="Coach"
                                        readonly required>
                                    <input type="hidden" class="form-control" name="assign_coach_id" id="assignCoach"
                                        placeholder="Coach" required>
                                </div>

                                <input type="text" class="form-control text-gray-500 flex-grow-1 me-4"
                                    style="display:none;" id="player_id" name="player_id" placeholder="000000000000"
                                    value="DG{{ $playerCode }}" required readonly>

                            </div>

                            <div class="row">
                                <div class="d-grid gap-4 d-flex justify-content-center py-3 px-2">
                                    <button class="btn btn-warning text-black border border-warning dcg-btn"
                                        type="submit" style="padding:8px 35px;">Save</button>
                                    <button class="btn btn-outline-warning me-md-2 border border-warning dcg-btn"
                                        id="cancelBtn" type="button" style="padding:8px 35px;">Cancel</button>

                                </div>
                            </div>





                        </div>

                    </div>
                    <!-- end of card body -->

                </div>
                <!-- end QR -->
            </div>
            <!-- end col -->




        </div>
        <!--end Row-->

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

    </form>

    @push('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.repeater@1.2.1/jquery.repeater.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        $(document).ready(function() {



            //calculate age by dob
            // $('#dob').on('input', function(e) {

            //     let dob = $(this).val();
            //     let birth_year = dob.substring(0, 4);

            //     let todays_date = new Date();
            //     let year = todays_date.getFullYear();

            //     const age = year - birth_year;
            //     $('#age').val(age);

            //     // console.log(age);
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

            //parent reapeter
            let index = 1;
            $('#parentRepeater').repeater({

                show: function() {

                    $(this).slideDown();
                    index++;
                    console.log(index);
                },
                hide: function(deleteElement) {

                    if (index === 1) {
                        if (confirm('Sorry! At least one parent details required.')) {
                            return;
                        }
                    } else {
                        if (confirm('Are You sure you want to delete this data ?')) {
                            $(this).slideUp(deleteElement);
                            index--;
                        }
                    }


                }
            });



            //Exist  player data show
            $('#aadharNo').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    let document = $(this).val().trim();
                    $.ajax({
                        url: "{{ route('checkExistingPlayer') }}",
                        method: "GET",
                        data: {
                            "document": document,
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
                                $('#player_contact_no').val(data.player_contact_no);
                                $('#player_email').val(data.player_email);

                                $('#school_name').val(data.school_name);
                                $('#hoid').val(data.hoid);
                                $('#foot').val(data.foot);
                                $('#inch').val(data.inch);
                                $('#weight').val(data.weight);

                                $('#imagPreview').attr('src', data.player_image);
                                // $('#previewContainer').show();

                                setImgFromUrl(data.player_image, 'player_image')

                                let parentData = data.parents;
                                $('#repeate_list').empty();
                                index = 0;

                                // console.log(parentData);
                                parentData.forEach((parent, index) => {

                                    index++;
                                    console.log(index);

                                    $('#repeate_list').append(` 
                                     <div data-repeater-item class="mb-3 border p-3 rounded">
                                                <div class="row g-2">


                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="parent[${index}][parent_name]"  value="${parent.parent_name}"
                                                            placeholder="Parent Name" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="parent[${index}][relation]" value="${parent.relation}"
                                                            placeholder="Relation" required>
                                                    </div>


                                                   
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" placeholder="Enter Mobile Number" value="${parent.parent_contact}"
                                                            name="parent[${index}][parent_contact]" id="parent_contact" required>
                                                    </div>


                                                  
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="parent[${index}][parent_email]"  value="${parent.parent_email}"
                                                            placeholder="Enter Email" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="col-form-label">Parent Profession</label>
                                                        <input type="text" class="form-control" name="parent[${index}][parent_profession]"  value="${parent.parent_profession}"
                                                            placeholder="Enter Parent Profession"
                                                            >
                                                     </div>


                                                   

                                                    <div class="col-md-6">
                                                    <label class="col-form-label">Delete</label>
                                                    <div class="delete-btn">
                                                        <i class="bi bi-file-excel" data-repeater-delete></i>
                                                    </div>

                                                </div>
                                                </div>
                                            </div>
                                `);
                                });


                                //parent data 



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


            $("#batch").change(function() {
                let id = $(this).find(':selected').data('coach');

                $.ajax({
                    url: "{{route('getCoachByBatchId')}}",
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


                            $('#coach').val(fullName);
                            $('#assignCoach').val(coach.id);
                        }

                    },
                    error: function(error) {
                        console.error(error)
                    }
                });


            });


            //cancel btn
            $('#cancelBtn').click(function(e) {
                window.location.href = "{{ route('player-list') }}";
            });


            //submit form

            $('#addPlayerForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                Swal.fire({
                    title: 'Saving ...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('store-player') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.close();
                        if (response.status == 'success') {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.href = "{{ route('player-list') }}";
                            });
                        }
                    },
                    error: function(xhr) {
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

            const originalFile = document.getElementById("player_image").files[0];
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
            document.getElementById("player_image").files = dataTransfer.files;
        }

        // end of image crop 
    </script>

    @endpush



</x-default-layout>