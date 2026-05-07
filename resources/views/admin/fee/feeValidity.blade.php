<x-default-layout>
    @section('pagetitle', $data['pagetitle'])

    <style>
        .bi-eye-fill {
            color: #84857E !important;
        }

        /* .pagination li {

        color: #84857E;
        border: 1px solid #DCDCDC;
        border-radius: 10px;
    }

    .pagination li:first-child {

        color: #0000;
        background-color: #F9C301;
        border-radius: 10px;
    } */
    </style>

    <!--begin::Row-->
    <div class="row gx-5 gx-xl-10 pt-3 py-5">

        <!--begin::Col-->
        <div class="col-12">
            <!--begin::Chart widget 8-->
            <div class="card table-sec card-flush h-xl-100"
                style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">



                <!--begin::Body-->
                <div class="card-body p-0-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="fees_table">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="width: 10%;">Sr. No.</th>
                                    <th style="width: 15%;">Player</th>
                                    <th style="width: 15%;">Player ID</th>
                                    <th style="width: 15%;">Batch</th>
                                    <th style="width: 15%;">Coach</th>
                                    <th style="width: 15%;">Valid From</th>
                                    <th style="width: 15%;">Valid Till</th>
                                    <!-- <th style="width: 8%;">Action</th> -->
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($fees as $index => $fee)

                                <tr>

                                    <td>{{ ++$index }}</td>
                                    <td>{{ $fee->player_first_name . ' ' . $fee->player_middle_name . ' ' . $fee->player_last_name }}</td>


                                    <td>{{ $fee->player_id }}</td>


                                    <td>{{ $fee->batch_name ?? '-' }}</td>


                                    <td>{{ $fee->coach_first_name .' ' .  $fee->coach_middle_name . ' ' . $fee->coach_last_name }}</td>


                                    <td>
                                        {{ $fee?->valid_from 
                                            ? \Carbon\Carbon::parse($fee->valid_from)->format('d-m-Y') 
                                            : '-' 
                                        }}
                                    </td>


                                    <td>
                                        {{ $fee?->valid_to 
                                            ? \Carbon\Carbon::parse($fee->valid_to)->format('d-m-Y') 
                                            : '-' 
                                        }}
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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function() {



            var table = $('#fees_table').DataTable({
                ordering: true,
                searching: true,
                paging: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                columnDefs: [{
                    targets: [0], // specific columns
                    orderable: false
                }], 
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    search: "Search:"
                },
                dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto"f>>rt<"bottom"ip><"clear">'
                // dom: '<"top"<"d-flex align-items-center gap-3"l><"ml-auto d-flex align-items-center gap-2"f<"filter-btn1">>>rt<"bottom"ip><"clear">'
            });

        });
    </script>

</x-default-layout>