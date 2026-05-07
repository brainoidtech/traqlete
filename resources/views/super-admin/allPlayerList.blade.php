<x-default-layout>
    @section('pagetitle', 'Player List')
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

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="players-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="width: 8%;">Sr. No.</th>
                                    <th style="width: 15%;">Player Name</th>
                                    <th style="width: 15%;">Contact Number</th>
                                    <th style="width: 15%;">Department</th>
                                    <th style="width: 15%;">Player ID</th>
                                    <th style="width: 8%;">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal filter start -->
                <!-- <form method="GET">
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
                                        <select class="form-select" id="filter_coach" name="coach">
                                            <option value="">Select Department</option>
                                            <option value="Football">Football</option>
                                            <option value="Cricket">Cricket</option>
                                            <option value="Vollyball">Vollyball</option>
                                            <option value="Kabaddi">Kabaddi</option>
                                        </select>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <a type="button" href=""
                                        class="btn btn-outline-warning border border-warning text-black dcg-btn"
                                        id="resetFilter">Clear Filter</a>
                                    <button type="submit" class="btn btn-warning text-black dcg-btn "
                                        id="applyFilter">Apply
                                        Fliter</button>
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
        $('#players-table').DataTable({
            processing: false,
            serverSide: true,
            ajax: "{{ route('allPlayerList') }}",
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
                    data: 'player_name',
                    name: 'players.first_name',
                },
                {
                    data: 'player_contact_no',
                    name: 'players.player_contact_no',
                },
                {
                    data: 'department_name',
                    name: 'dept.dept_name',
                },
                {
                    data: 'player_id',
                    name: 'players.player_id',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ],
        });

        // $('.filter-btn1').html(`
		// 	<button id="customFilterBtn1" class="btn btn-warning text-black dcg-btn">
		// 		Filter
		// 	</button>
		// 		`);

        // $('#customFilterBtn1').on('click', function() {
        //     $('#filterModal').modal('show');
        // });


    });
    </script>
</x-default-layout>