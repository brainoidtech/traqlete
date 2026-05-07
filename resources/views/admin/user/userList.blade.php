<x-default-layout>
    @section('pagetitle', $department . ' ' . 'User List')
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
            /* background-color: #ffffff; */
            border-radius: 10px;
        }
    </style>
    <div class="row gx-5 gx-xl-10 pt-3">
        <div class="col-12">
            <div class="card table-sec card-flush h-xl-100"
                style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">
                <div class="card-body p-0-0">
                    <div class="table-responsive">
                        <table id="users-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="width: 10%;">Sr No</th>
                                    <th style="width: 40%;">Name</th>
                                    <th style="width: 40%;">Role</th>
                                    <th style="display:none;">original_role_id</th> {{-- ✅ hidden --}}
                                    <th style="display:none;">role_count</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(function() {
            $('#users-table').DataTable({
                processing: false,
                serverSide: true,
                ajax: "{{ route('user-list') }}",
                dom: '<"datatable-header"l f>rt<"datatable-footer"i p>',
                lengthMenu: [10, 25, 50, 100],
                columns: [{
                        data: null,
                        name: 'sr_no',
                        orderable: false,
                        searchable: false,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role_id',
                        name: 'role_id'
                    },
                    {
                        data: 'original_role_id',
                        name: 'original_role_id',
                        visible: false
                    },
                    {
                        data: 'role_count',
                        name: 'role_count',
                        visible: false
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('edit-user-data', ':id') }}".replace(':id', row
                                .id);
                            let deleteUrl = "{{ route('delete-user-data', ':id') }}".replace(':id',
                                row.id);

                            let editBtn = `
                <a class="text-primary" href="${editUrl}" style="margin-right:10px;">
                    <i class="bi bi-pencil"></i>
                </a>`;

                            let deleteBtn = '';
                            let isRoleRestricted = (row.original_role_id == 5 || row
                                .original_role_id == 6);
                            let hasMultiple = row.role_count > 1;

                            if (!isRoleRestricted || hasMultiple) {
                                deleteBtn = `
                <form action="${deleteUrl}" method="POST" style="display:inline;"
                    onsubmit="return confirm('Are you sure you want to delete this item?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-link text-danger p-0" type="submit">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>`;
                            }

                            return `<div>${editBtn}${deleteBtn}</div>`;
                        }
                    }
                ],
                order: [
                    [1, 'asc']
                ],
            });
        });
    </script>
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
    @if (session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'error',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#3085d6'
                });
            });
        </script>
    @endif
</x-default-layout>
