<x-default-layout>
    @section('pagetitle', $data['pagetitle'])

    <style>
        .bi-three-dots-vertical {
            color: #84857E !important;
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
    </style>

    <!--begin::Row-->
    <div class="row gx-5 gx-xl-10 pt-3">

        <!--begin::Col-->
        <div class="col-12">
            <!--begin::Chart widget 8-->
            <div class="card table-sec card-flush h-xl-100"
                style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">




                <!--begin::Body-->
                <div class="card-body p-0-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dept_tbl">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="width:10%;">Sr No</th>
                                    <th style="width:30%;">Department Name</th>
                                    <th style="width:30;">Department Admin</th>
                                    <th style="width:5%;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="alternating-rows">
                                @foreach($department as $index => $dept)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td><a href="{{ url('dashboard/'. $dept->dept_id) }}" class="text-decoration-none text-dark">{{ $dept->dept_name }}</a></td>
                                    <td>{{ $dept->userDepts[0]->name ?? '-' }}</td>
                                    <!-- <td>
                                       
                                        <div class="dropdown">
                                            <i class="bi bi-three-dots-vertical" role="button" data-bs-toggle="dropdown"
                                                aria-expanded="false" style="cursor:pointer;"></i>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('dashboard/'. $dept->dept_name) }}"><i class="bi bi-eye-fill"></i>
                                                        View</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('/super-admin/edit-department/'.$dept->dept_id) }}"><i class="bi bi-eye-fill"></i>
                                                        Edit</a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item delete_dept" type="button" data-id="{{ $dept->dept_id }}">
                                                        <i class="bi bi-trash-fill"></i> Delete
                                                    </button>
                                                </li>

                                            </ul>
                                        </div>
                                    </td> -->

                                    <td>
                                        <div class="d-flex gap-1">
                                            <a class="btn btn-sm" href="{{ url('dashboard/'. $dept->dept_id) }}">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>

                                            <a class="btn btn-sm" href="{{ url('/super-admin/edit-department/'.$dept->dept_id) }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>

                                            <button class="btn btn-sm delete_dept" type="button" data-id="{{ $dept->dept_id }}">
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
            <!--end::Chart widget 8-->

        </div>
        <!--end::Col-->



    </div>
    <!--end::Row-->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#dept_tbl').DataTable({
                ordering: true,
                searching: true,
                paging: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                columnDefs: [{
                    orderable: false,

                }],
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    search: "Search:"
                },
                dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn1">>>rt<"bottom"ip><"clear">'
            });


            $(document).on('click', '.delete_dept', function(e) {
                e.preventDefault();

                let id = $(this).data('id');



                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "{{ url('super-admin/destroy-department') }}/" + id,
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message,
                                        icon: 'success',
                                    }).then(() => {

                                        window.location.href = "{{ route('department-list') }}";
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.message,
                                        icon: 'error',
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong!',
                                    icon: 'error',
                                    confirmButtonText: 'Cool'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

</x-default-layout>