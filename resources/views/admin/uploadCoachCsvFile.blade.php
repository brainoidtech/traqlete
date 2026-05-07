<x-default-layout>
    @section('pagetitle', $data['pagetitle'])

    
    <style>
        input#zbook-image {
            border-radius: 0px !important;
        }

        .btn-border-green {
            background: #ffffff !important;
            border-color: #00bdcb;
            color: #00bdcb;
        }

        .tab-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding-bottom: 20px !important;
        }

        .title-btn-sec {
            padding: 0px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .title-btn-sec h4,
        .modal-header h4 {
            margin: 0px !important;
        }

        .background-grey {
            background: #f8f8f8;
            padding: 30px 30px;
        }

        .padding-0 {
            padding: 0px !important;
        }

        .p-40-20 {
            padding-top: 40px;
        }

        .justify-content-end {
            display: flex;
            justify-content: flex-end;
        }

        .d-flex {
            display: flex;
            padding: 5px 0px;
        }

        .checkbox-padding {
            padding: 6px 15px 6px 0px !important
        }

        .mt-5 {
            margin-top: 20px;
        }

        #conceptTabs li.active h2 {
            background: #00BDCB !important;
            color: #fff;
        }

        #conceptTabs {
            padding-bottom: 20px;
        }

        /* .submit-row {
        padding: 40px 0 70px 0;
    } */

        /* .submit-row button {
        margin-right: 20px;
    } */




        /* Modal CSS */

        .modal-dialog {
            padding-top: 50px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            flex-direction: column;
        }

        .modal-title-sec .col-md-6 {
            display: flex;
            align-items: center;
        }

        #existingBooks .modal-dialog {
            max-width: 40%;
            /* Adjust width as needed */
        }

        #existingBooks .modal-body {
            max-height: 500px;
            /* You can adjust this based on your design */
            overflow-y: auto;
            /* Enables vertical scrolling */
            overflow-x: hidden;
            /* Prevents horizontal scroll */
        }
    </style>

    <div class="main-content">
        <div class="wrap-content container" id="container">



            <div class="row" style="margin-top: 8px;">
                <div class="card card-flush ">
                    <form id="csvUploadForm" class="form-horizontal" enctype="multipart/form-data">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="col-md-12">
                            <fieldset class="module aligned wide">

                                <div class="form-group row align-items-center mt-7 ms-5">
                                    <div class="form-group row align-items-center">
                                    <label for="csv-file" class="col-sm-2 col-form-label fs-5">Upload CSV</label>

                                    <div class="col-sm-6">
                                        <input type="file" class="form-control" id="csv-file" name="csv_file" accept=".csv">
                                    </div>
                                </div>

                            </fieldset>

                            <button type="submit" class="btn btn-yellow my-7 ms-7">
                                Upload
                            </button>


                            
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            
            $('#csvUploadForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $.ajax({
                    url: "{{ url('import-coach-csv') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    beforeSend: function() {
                        Swal.fire({
                            title: 'Uploading...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },

                    success: function(response) {
                        console.log('response');
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: response.message || "CSV uploaded successfully"
                        });
                         $('#csvUploadForm')[0].reset();
                    },

                    error: function(xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Upload Failed",
                            text: xhr.responseJSON?.message || "Something went wrong!"
                        });
                         $('#csvUploadForm')[0].reset();
                    }
                });
            });
        });
    </script>


</x-default-layout>