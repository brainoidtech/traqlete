<x-default-layout>
    @section('pagetitle', 'User Profile' )

    <style>
        .common-form label {
            color: #686666;
        }

        input[type="select"] {
            color: #000 !important;
        }
    </style>
    <div class="row gx-5 gx-xl-10 py-3">
        <div class="col-12">
            <div class="card table-sec card-flush h-xl-100"
                style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">
                <div class="card-body p-0-0">
                    <form  id="updateProfile" class="p-4 common-form">
                        @csrf

                        <input type="hidden" name="user_id" value="{{ $user['id'] }}">

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="UserName" class="col-form-label">Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ $user['name'] }}"
                                     placeholder="Enter Name" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="UserContact" class="col-form-label">Contact Number *</label>
                                <input type="text" class="form-control" name="contact_number" value="{{ $user['contact_number'] }}"
                                     placeholder="Enter Contact Number" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="UserEmail" class="col-form-label">Email *</label>
                                <input type="email" class="form-control" placeholder="Enter Email" value="{{ $user['email'] }}"
                                    name="email"  readonly required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="UserPassword" class="col-form-label">Password </label>
                                <input type="password" class="form-control" id="ManagerPassword" name="password"
                                    placeholder="Enter Password">
                                <span>Leave blank if you don't want to change password</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="card-body p-0-0">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center py-3 px-2">
                                    <button class="btn btn-warning text-black dcg-btn" type="submit"
                                        style="padding:8px 35px;">Save</button>
                                    <button class="btn btn-outline-warning me-md-2 border border-warning dcg-btn"
                                        type="reset"
                                        style="padding:8px 35px;">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('submit', '#updateProfile', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            alert('fggf');

            Swal.fire({
                title: 'Saving ...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('updateUserProfile') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: "success!",
                            text: response.message,
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "error!",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function(error) {
                    console.log('error!');
                }
            });
        });
    </script>


</x-default-layout>