<x-default-layout>
    @section('pagetitle', 'Add'.' '.$departmentName.' ' . 'User' )
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
                    <form method="POST" action="{{ route('add-user-data') }}" class="p-4 common-form">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="UserRole" class="col-form-label">Role *</label>
                                <select class="form-select form-control flex-grow-1 me-4" name="role_id"
                                    aria-label="Default select example" style="color:#000; font-weight:400;">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->role_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="col-form-label">Department *</label>
                                <input type="text" class="form-control" value="{{ $departmentName }}" readonly>
                                <input type="hidden" name="dept_id" value="{{ $dept_id }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="UserName" class="col-form-label">Name *</label>
                                <input type="text" name="name" class="form-control" id="ManagerName"
                                    value="{{ old('name') }}" placeholder="Enter Name">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="UserContact" class="col-form-label">Contact Number *</label>
                                <input type="text" class="form-control" id="ManagerContact" name="contact_number"
                                    value="{{ old('contact_number') }}" placeholder="Enter Contact Number">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="UserEmail" class="col-form-label">Email *</label>
                                <input type="email" class="form-control" id="ManagerEmail" placeholder="Enter Email"
                                    name="email" value="{{ old('email') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="UserPassword" class="col-form-label">Password *</label>
                                <input type="password" class="form-control" id="ManagerPassword" name="password"
                                    placeholder="Enter Password">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="card-body p-0-0">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center py-3 px-2">
                                    <button class="btn btn-warning text-black dcg-btn" type="submit"
                                        style="padding:8px 35px;">Save</button>
                                    <a class="btn btn-outline-warning me-md-2 border border-warning dcg-btn"
                                        href="{{ route('user-list') }}" type="button"
                                        style="padding:8px 35px;">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    confirmButtonColor: '#d33'
                });
            });
        </script>
    @endif
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6'
                });
            });
        </script>
    @endif
</x-default-layout>
