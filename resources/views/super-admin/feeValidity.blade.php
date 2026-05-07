<x-default-layout>
    @section('pagetitle', $data['pagetitle'])

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

        color: #0000;
        background-color: #F9C301;
        border-radius: 10px;
    }
    </style>

    <!--begin::Row-->
    <div class="row gx-5 gx-xl-10 pt-3 py-5">

        <!--begin::Col-->
        <div class="col-12">
            <!--begin::Chart widget 8-->
            <div class="card table-sec card-flush h-xl-100"
                style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">

                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <label>Show <select name="myTable_length" aria-controls="myTable"
                            class="entries m-2 align-items-center">
                            <option value="500">500</option>
                            <option value="700">700</option>
                            <option value="1000">1000</option>
                            <option value="-1">All</option>
                        </select> Entries
                    </label>
                    <!--end::Title-->
                    <!-- Search bar -->
                    <div class="col-lg-3 pull-right">
                        <div class="input-group">
                            <span class="input-group-addon"
                                style="color: black; background-color: #f9c301;">Filter</span>
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
                                    <th style="width: 20%;">Player</th>
                                    <th style="width: 15%;">Player  ID</th>
                                    <th style="width: 15%;">Batch</th>
                                    <th style="width: 15%;">Coach</th>
                                    <th style="width: 15%;">Valid From</th>
                                    <th style="width: 15%;">Valid To</th>
                                    <th style="width: 5%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Raj Patil</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Vishal Bhatt</td>
                                    <td>DG-CH-010</td>
                                    <td>Beginner</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Vijay Shah</td>
                                    <td>DG-CH-010</td>
                                    <td>Intermediate</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Raj Patil</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Beginner</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>

                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Intermediate</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Beginner</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Intermediate</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Beginner</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Intermediate</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Beginner</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Intermediate</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Intermediate</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Intermediate</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Anirudha Sangawar</td>
                                    <td>DG-CH-010</td>
                                    <td>Advanced</td>
                                    <td>Vikram Pal</td>
                                    <td>20 Aug 2024</td>
                                    <td>20 Aug 2025</td>
                                    <td><i class="bi bi-eye-fill"></i></td>
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