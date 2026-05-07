<x-default-layout>
    @section('pagetitle', 'Coach List')

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

        /* Make Search label and input appear side-by-side */
        .right-tools>div>div {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Remove label bottom margin */
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
                                    <th style="width: 8%;">Sr. No.</th>
                                    <th style="width: 20%;">Coach</th>
                                    <th style="width: 20%;">Department</th>
                                    <th style="width: 20%;">Coach ID</th>
                                    <th style="width: 8%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal filter start -->
                <!-- <form id="applyFilter">
                    @csrf
                    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog filter-modal modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label for="filter_coach" class="form-label">Departments</label>
                                        <select class="form-select" id="filter_dept" name="dept_name">
                                            <option value="" selected disabled> Select Department </option>
                                           

                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-outline-warning border border-warning text-black dcg-btn"
                                        id="resetFilter">
                                        Clear Filter</button>
                                    <button type="submit" class="btn btn-warning text-black dcg-btn ">
                                        Apply Fliter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form> -->

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(function() {
            $('#coaches-table').DataTable({
                processing: false,
                serverSide: true,
                ajax: "{{ route('allCoachList') }}",
                // dom: '<"datatable-header"l f>rt<"datatable-footer"i p>',
                // dom: '<"datatable-header"<"d-flex align-items-center gap-2"l> f>rt<"datatable-footer"i p>',
                dom: '<"datatable-header d-flex justify-content-between align-items-center"<"d-flex gap-2"l><"right-tools d-flex align-items-center gap-2"<f><"filter-btn1">>>rt<"datatable-footer"i p>',
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
                        data: 'coach_full_name',
                        name: 'coaches.first_name'
                    },
                    {
                        data: 'department_name', 
                        name: 'department_name'
                    },
                    {
                        data: 'coach_ids', 
                        name: 'coach_ids',
                        orderable: false
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