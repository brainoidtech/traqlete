<x-default-layout>
    @section('pagetitle', $data['pagetitle'])

    <style>
    .common-form label {
        color: #686666;
    }
    </style>


    <!--begin::Row-->
    <div class="row mb-3">
        <div class="col-12 ">
            <!--begin QR-->
            <div class="card table-sec card-flush h-xl-100"
                style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;">

                <div class="card-body p-3">
                    <!--begin::Form-->
                    <form action="" method="post" id="attendanceForm" class="common-form form">
                        <!-- Player / Coach -->

                        <!-- Name -->
                        <div class="row g-3 py-2">
                            <label for="Name" class="col-md-4 col-form-label">Batch Name*</label>
                            <div class="col-sm-10 col-md-8">
                                <input type="text" class="form-control" placeholder="Batch Name" aria-label="First name"  name="first_name"
                                    required>
                            </div>
                        </div>

                        <!-- Select In Time -->
                        <div class="row mb-3">
                            <label for="in_time" class="col-sm-2 col-md-4 col-form-label">Time From*</label>
                            <div class="col-sm-10 col-md-8">
                                <input type="time" class="form-control flex-grow-1 me-4" name="in_time" placeholder="In time" required>
                            </div>
                        </div>

                        <!-- Select Out Time -->

                        <div class="row mb-3">
                            <label for="out_time" class="col-sm-2 col-md-4 col-form-label">Time To*</label>
                            <div class="col-sm-10 col-md-8">
                                <input type="time" class="form-control flex-grow-1 me-4" name="out_time" placeholder="Out time" required>
                            </div>
                        </div>

                        <!-- Select batch -->
                         <div class="row mb-3">
                                <label for="batch" class="col-sm-2 col-md-4 col-form-label">Assign coach*</label>
                                <div class="col-sm-10 col-md-8">
                                    <select class="form-select" aria-label="Default select example" required>
                                        <option selected>Select Batch</option>
                                        <option value="1">Morning</option>
                                        <option value="2">Afternoon</option>
                                        <option value="3">Evening</option>
                                    </select>
                                </div>
                            </div>

                    </form>
                </div>
                <!-- card body -->
            </div>
            <!-- end of card -->
        </div>
        <!-- end of col -->

        <div class="col-12 ">
            <!--begin btn-->
            <div class="card table-sec card-flush h-xl-100 border-top-0"
                style="border-top-left-radius:0px; border-top-right-radius:0px;">
                <!-- begin card body -->
                <div class="card-body p-0-0">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end py-3 px-2">
                        <button class="btn btn-outline-warning me-md-2 border border-warning dcg-btn" type="button"
                            style="padding:8px 35px;">Cancel</button>
                        <button class="btn btn-warning text-black dcg-btn" type="button"
                            style="padding:8px 35px;">Save</button>
                    </div>
                </div>
                <!-- end of card body -->

            </div>
            <!-- end btn -->
        </div>
    </div>
    <!-- end of row -->
   
</x-default-layout>