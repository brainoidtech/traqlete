<x-default-layout>
    @section('pagetitle', 'Access Level List')

    <style>
        .bi-eye-fill {
            color: #84857E !important;
        }

        .pagination li {
            color: #84857E;
            border: 1px solid #DCDCDC;
            border-radius: 10px;
        }

        .pagination li:first-child {
            color: transparent;
            background-color: #F9C301;
            border-radius: 10px;
        }

        .dcg-btn {
            padding: 6px 15px !important;
        }

        .right-tools>div>div {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .right-tools label {
            margin-bottom: 0;
        }
    </style>

    <div class="row gx-5 gx-xl-10 pt-3 py-5">
        <div class="col-12">
            <div class="card table-sec card-flush h-xl-100"
                style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">
                <div class="card-body p-0-0">
                    <div class="table-responsive">
                        <table id="coaches-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="width: 10%;">Sr. No.</th>
                                    <th style="width: 80%;">Role Name</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#F9C301',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            @endif
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#F9C301',
                    confirmButtonText: 'OK',
                });
            @endif
            $('#coaches-table').DataTable({
                processing: false,
                serverSide: true,
                ajax: '{{ route('accessLevels') }}',
                dom: '<"datatable-header"<"d-flex align-items-center gap-2"l> f>rt<"datatable-footer"i p>',
                pageLength: 20,
                lengthMenu: [20, 50, 100],
                language: {
                    lengthMenu: "Show _MENU_ Entries",
                },
                columns: [{
                        data: null,
                        name: 'sr_no',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1 + meta.settings._iDisplayStart;
                        }
                    },
                    {
                        data: 'role_id',
                        name: 'role_id'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'asc']
                ]
            });

        });
    </script>
</x-default-layout>
