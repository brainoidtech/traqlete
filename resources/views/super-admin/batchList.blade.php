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


        <div class="d-flex justify-content-end align-items-center">
            <div class="card-toolbar pb-4">
                <button class="btn btn-warning text-black border border-warning py-2" type="button">Add Batch</button>
            </div>
        </div>

        <!--begin::Col-->
        <div class="col-12">
            <!--begin::Chart widget 8-->
            <div class="card table-sec card-flush h-xl-100"
                style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">

                <div class="card-header py-0">
                    <!--begin::Title-->
                    <label>Show <select name="myTable_length" aria-controls="myTable"
                            class="entries m-2 align-items-center">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select> Entries
                    </label>
                    <!--end::Title-->

                    <!-- Search bar -->
                    <!-- <div class="col-lg-4 pull-right d-flex justify-content-between align-items-center">
                        <div class="searchbar border p-3 rounded-2 w-100">
                            <i class="bi bi-search"><span class="px-2">Search</span></i>
                        </div>
                        <div class="ms-4">
                            <button class="btn btn-warning text-black border border-warning py-2"
                                type="button">Filter</button>
                        </div>

                    </div> -->
                    <!-- End Search bar -->

                    <!-- Search bar -->
                    <div class="col-lg-3 pull-right">
                        <div class="input-group">
                              <button class="btn btn-warning text-black border border-warning py-2"
                                type="button">Filter</button>
                            <!-- <span class="input-group-addon"
                                style="color: black; background-color: #f9c301;">Filter</span> -->
                            <input type="text" autocomplete="off" id="search" class="form-control" placeholder="Search">
                        </div>
                    </div>
                    <!-- End Search bar -->


                </div>


                <!--begin::Body-->
                <div class="card-body p-0-0">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="width: 25%">Batch</th>
                                    <th style="width: 25%;">Coach</th>
                                    <th style="width: 20%;">Time From</th>
                                    <th style="width: 20%;">Time To</th>
                                    <th style="width: 5%;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="fs-5 fw-normal">
                                <tr>
                                    <td>Beginner</td>
                                    <td>Vikram Pal</td>
                                    <td>7:00 AM</td>
                                    <td>9:00 AM</td>
                                    <td><i class="fa fa-edit"></i></td>
                                </tr>
                                <tr>
                                    <td>Intermediate</td>
                                    <td>Meenal Shah</td>
                                    <td>9:00 AM</td>
                                    <td>11:00 AM</td>
                                    <td><i class="fa fa-edit"></i></td>
                                </tr>
                                <tr>
                                    <td>Advanced</td>
                                    <td>Prakash Rane</td>
                                    <td>4:00 PM</td>
                                    <td>6:00 PM</td>
                                    <td><i class="fa fa-edit"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>



                </div>
                <!--end::Body-->
            </div>
            <!--end::Chart widget 8-->

        </div>
        <!--end::Col-->

        <div class="col-12 ">
            <!--begin btn-->
            <div class="card table-sec card-flush h-xl-100 border-top-0"
                style="border-top-left-radius:0px; border-top-right-radius:0px;">
                <!-- begin card body -->
                <div class="card-body p-0 px-2">
                    <div class="pagination justify-content-end my-5">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end">
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item border border-0"><a class="page-link" href="#">.....</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- end of card body -->

            </div>
            <!-- end btn -->
        </div>

    </div>
    <!--end::Row-->



</x-default-layout>