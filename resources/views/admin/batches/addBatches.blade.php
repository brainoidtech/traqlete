<x-default-layout>
    @section('pagetitle', $data['pagetitle'])

    <style>
        .common-form label {
            color: #686666;
        }

      
    </style>
    <form action="{{ url('/store-batch') }}" method="POST" id="batchForm" class="pt-4">
        @csrf

        <!--begin::Row-->
        <div class="row mb-3">
            <div class="col-12 ">
                <!--begin QR-->
                <div class="card table-sec card-flush h-xl-100 p-3"
                    style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">

                    <div class="card-body p-5">

                        <div class="card-title">
                            <h3>Add Batch</h3>
                        </div>
                        <!--begin::Form-->
                        <div class="common-form form">
                            <!-- Player / Coach -->

                            <!-- Name -->
                            <div class="row g-4 py-2">
                                <div class="col-12 col-md-6">
                                    <label for="Name" class="col-md-4 col-form-label">Batch Name*</label>
                                    <input type="text" name="batch_name" class="form-control" required
                                        placeholder="Batch Name" style="color:#000 !important;">
                                </div>

                            </div>
                            <div class="row g-4 py-2">
                                <div class="col-12 col-md-6">
                                    <label class="col-sm-2 col-md-4 col-form-label">
                                        Head Coach*
                                    </label>
                                    <select name="head_coach_id" class="form-select" id="headCoach" required
                                        style="color:#000 !important;">
                                        <option value="" selected disabled>Select Coach</option>

                                        @foreach($coach as $c)
                                        <option value="{{ $c->id }}">
                                            {{ trim($c->first_name.' '.$c->middle_name.' '.$c->last_name) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="col-sm-2 col-md-4 col-form-label">
                                        Other Coach
                                    </label>
                                    <select name="other_coach_ids[]" class="form-select" id="subCoach"
                                        data-control="select2" data-placeholder="Select Sub Coach" multiple disabled
                                         style="color:#000 !important;">


                                        @foreach($coach as $c)
                                        <option value="{{ $c->id }}">
                                            {{ trim($c->first_name.' '.$c->middle_name.' '.$c->last_name) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>


                            <!-- Select In Time -->
                            <div class="row g-4 py-2">
                                <div class="col-12 col-md-6">
                                    <label for="in_time" class="col-sm-2 col-md-4 col-form-label">Time From*</label>
                                    <input type="time" name="start_time" class="form-control"
                                        style="color:#000 !important;">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="out_time" class="col-sm-2 col-md-4 col-form-label">Time To*</label>
                                    <input type="time" name="end_time" class="form-control"
                                        style="color:#000 !important;">
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-center py-3 px-2">

                                <button class="btn btn-warning text-black dcg-btn" type="submit"
                                    style="padding:6px 20px;">Save</button>

                                <a href="{{ route('batch-list') }}"
                                    class="btn btn-outline-warning me-md-2 border border-warning dcg-btn" id="cancelBtn"
                                    style="padding:6px 20px;">
                                    Cancel
                                </a>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {

            $('#headCoach').on('change', function() {

                let selectedHeadCoach = $(this).val();
                $('#subCoach').val(null).trigger('change');

                $('#subCoach option').prop('disabled', false);

                if (selectedHeadCoach) {
                    $('#subCoach').prop('disabled', false);
                    $('#subCoach option[value="' + selectedHeadCoach + '"]')
                        .prop('disabled', true);

                } else {
                    $('#subCoach').prop('disabled', true);
                }

                $('#subCoach').trigger('change.select2');

            });

        })


        $(function() {

            $('#batchForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ url('/store-batch') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "json",

                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = "{{ route('batch-list') }}";
                            });
                        }
                    },
                    error: function(xhr, error) {
                        if (xhr.status === 422) {

                            let msg = '';

                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    msg += value[0] + '<br>';
                                });
                            } else {
                                msg = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: msg,
                                confirmButtonText: 'OK'
                            });

                        } else {

                            let message = xhr.responseJSON?.message || 'Something went wrong!';

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message,
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });

            //cancel btn
            $('#cancelBtn').click(function(e) {
                window.location.href = "{{ route('batch-list') }}";
            });

        });
    </script>





</x-default-layout>