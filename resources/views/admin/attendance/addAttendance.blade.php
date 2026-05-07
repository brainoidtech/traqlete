<x-default-layout>
    @section('pagetitle', $data['pagetitle'])


    <style>
        label {
            color: #686666 !important;
        }

        input[type="select"] {
            color: #000 !important;
        }
    </style>

    <form method="post" id="attendanceForm" class="pt-4">
        @csrf
        <!--begin::Row-->
        <div class="row mb-3">
            <div class="col-12 ">
                <!--begin QR-->
                <div class="card table-sec card-flush h-xl-100 p-3"
                    style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">

                    <div class="card-body p-5">


                        <div class="d-flex justify-content-between">
                            <h2>Add Attendance</h2>
                        </div>

                        <hr>
                        <!--begin::Form-->
                        <div class="common-form form">

                            <!-- Player / Coach -->
                            <div class="row g-3 py-2">
                                <label for="Name" class="col-form-label">Player / Coach*</label>
                                <div class="col-12 d-flex align-items-center">
                                    <div class="form-check mr-3 position-static">
                                        <input class="form-check-input" type="radio" name="role" id="player" checked
                                            value="Player" required>
                                        <label class="form-check-label flex-grow-1 me-4 fs-6"
                                            for="player" style="color:#000 !important;">Player</label>
                                    </div>
                                    <div class="form-check position-static">
                                        <input class="form-check-input" type="radio" name="role" id="coach"
                                            value="Coach" required>
                                        <label class="form-check-label flex-grow-1 me-4 fs-6" for="coach" style="color:#000 !important;">Coach</label>
                                    </div>
                                </div>

                            </div>

                            <!-- Name -->
                            <div class="row g-3 py-2">
                                <div class="col-12 col-md-4">
                                    <label for="Name" class="col-form-label">First Name*</label>
                                    <input type="text" class="form-control first_name" placeholder="Enter First Name" name="first_name" readonly
                                        aria-label="First name" name="first_name" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="Name" class="col-form-label">Middle Name*</label>
                                    <input type="text" class="form-control middle_name " placeholder="Enter Middle Name" name="middle_name" readonly
                                        aria-label="Middle name" name="middle_name" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="Name" class="col-form-label">Last Name*</label>
                                    <input type="text" class="form-control last_name " placeholder="Enter Last Name" name="last_name" readonly
                                        aria-label="Last name" name="last_name" required>
                                </div>
                            </div>

                            <!-- ID -->
                            <div class="row g-3 py-2">
                                <div class="col-12 col-md-6">
                                    <label for="id" class="col-form-label">ID*</label>
                                    <input type="text" class="form-control flex-grow-1 me-4" name="id"
                                        id="id" placeholder="Enter ID" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="date" class="col-form-label">Select Date*</label>
                                    <input type="date" class="form-control flex-grow-1 me-4"
                                        value="<?php echo date('Y-m-d'); ?>" name="date" required style="color:#000; font-weight:400;">
                                </div>

                            </div>

                            <!-- Select In Time -->
                            <div class="row g-3 py-2">
                                <div class="col-12 col-md-6">
                                    <label for="in_time" class="col-form-label">In Time</label>
                                    <input type="time" class="form-control flex-grow-1 me-4 in_time" name="in_time"
                                        placeholder="In time" style="color:#000; font-weight:400;">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="out_time" class="col-form-label">Out Time</label>
                                    <input type="time" class="form-control flex-grow-1 me-4 out_time" name="out_time"
                                        placeholder="Out time" style="color:#000; font-weight:400;">
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-center py-3 px-2">
                                <button class="btn btn-outline-warning me-md-2 border border-warning dcg-btn" id="cancelBtn"
                                    type="button" style="padding:8px 35px;">Cancel</button>
                                <button class="btn btn-warning text-black dcg-btn" type="submit"
                                    style="padding:8px 35px;">Save</button>
                            </div>

                        </div>
                    </div>
                    <!-- card body -->
                </div>
                <!-- end of card -->
            </div>
            <!-- end of col -->


        </div>
        <!-- end of row -->

    </form>




    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @push('scripts')
    <script>
        $(document).ready(function() {

            //clear input if radio btn change 
            $('input[name="role"]').on('change', function() {
                $('#id').val('');
                $('.first_name,  .middle_name, .last_name').val('');
                $('.in_time, .out_time').val('');

            });


            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && document.activeElement.id === 'id') {

                    e.preventDefault();

                    let id = $('#id').val();
                    let selectedRole = document.querySelector('input[name="role"]:checked').value;
                    let role = selectedRole == "Player" ? "{{ route('getPlayerData') }}" : "{{ route('getCoachName') }}";


                    $.ajax({
                        url: role,
                        method: 'GET',
                        data: {
                            'id': id,
                        },
                        success: function(response) {
                            if (response.status == 'success') {

                                let user = response.data;
                                $('.first_name').val(user.first_name);
                                $('.middle_name').val(user.middle_name);
                                $('.last_name').val(user.last_name);
                                // $('#player').prop('checked', true);

                            } else {
                                // alert('error');
                                Swal.fire({
                                    title: "Error!",
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(error, xhr, status) {
                            console.log('player data not found');
                            // alert('Error :' + error);
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    });

                }
            });



            //cancel btn
            $('#cancelBtn').click(function(e) {
                window.location.href = "{{ route('attendance-list') }}";
            });

            ///submit form 
            $('#attendanceForm').submit(function(e) {
                e.preventDefault();

                var first_name = $('input[name="first_name"]').val().trim();
                var middle_name = $('input[name="middle_name"]').val().trim();
                var last_name = $('input[name="last_name"]').val().trim();

                   let Role = $('input[name="role"]:checked').val();

                if (first_name === '' && last_name === '') {
                    let msg =  Role === 'Player' ? 'Press Enter after Player ID to view info, then Save.' : 'Press Enter after Coach ID to view info, then Save.';
                     Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: msg,

                        });
                    return false;
                }

                let formData = new FormData(this);

             
               
                let submitUrl = Role === 'Player' ? "{{ url('insert-player-attendance') }}" : "{{ url('insert-coach-attendance') }}";
                let redirect = Role === 'Player' ?  "{{ route('attendance-list') }}?tab=player_tab" : "{{ route('attendance-list') }}?tab=coach_tab";
               
                $.ajax({
                    url: submitUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 'success') {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.href = redirect;
                            });

                        } else {
                            Swal.fire({
                                title: "error!",
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status) {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: xhr.responseJSON ? xhr.responseJSON.detail : 'An unknown error occurred',

                        });
                    }
                });
            });

        });
    </script>
    @endpush
</x-default-layout>