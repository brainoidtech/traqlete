<x-default-layout>
    @section('pagetitle', $data['pagetitle'])

    <style>
        .common-form label {
            color: #686666;
        }

        #dept-map {
            height: 400px !important;
            width: 100% !important;
            display: block !important;
        }
    </style>

    <!--begin::Row-->
    <div class="row gx-5 gx-xl-10 pt-3">
        <!--begin::Col-->
        <div class="col-12">
            <!--begin::Chart widget 8-->
            <div class="card table-sec card-flush h-xl-100 card-bn rounded-top">
                <!-- begin card body -->
                <div class="card-body p-0-0">

                    <form method="POST" id="updateDept" class="form p-4 common-form">
                        @csrf
                        <div class="py-4">
                            <h2>Department Details</h2>
                        </div>


                        <input type="hidden" name="dept_id" value="{{ $dept->dept_id }}">
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="departmentName" class="col-form-label">Department
                                    Name*</label>
                                <input type="text" class="form-control" id="dept_name" name="dept_name"
                                    value="{{ $dept->dept_name }}" placeholder="Enter Department Name" required>
                            </div>

                            <!-- <div class="col-12 col-md-6">
                                <label class="col-form-label">Department Icon*</label>
                                <input class="form-control" id="image" accept="image/*" onchange="showImage(event)"
                                    type="file" placeholder="Book Image">
                            </div> -->

                            <div class="col-12 col-md-6">
                                <label class="col-form-label">Department Icon*</label>
                                <div class="d-flex">
                                    <div class="col-10 col-xl-11">
                                        <input class="form-control" id="dept_icon" accept="image/*" name="dept_icon"
                                            type="file" onchange="showImage(event)" placeholder="Book Image"
                                            style="color:#000 !important;">
                                    </div>
                                    <div class="col-2 col-xl-1 ps-2" id="previewContainer">
                                        <img id="imagPreview" style="width: 50px; height:45px;"
                                            src="{{ asset($dept->dept_icon) }}" alt="Book image preview" />
                                    </div>

                                </div>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="inputEmail" class="col-form-label">Department Email</label>
                                <input type="email" class="form-control" id="dept_email" name="dept_email"
                                    placeholder="Enter Email" value="{{ $dept->dept_email }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="contactNumber" class="col-form-label">Department Contact
                                    Number</label>
                                <input type="text" class="form-control" id="dept_contact_number"
                                    name="dept_contact_number" value="{{ $dept->dept_contact_number }}"
                                    placeholder="Enter Contact Number">
                            </div>
                        </div>

                        <div class="row mb-3">

                            <div class="col-12 col-md-6">
                                <label for="playerIDCode" class="col-form-label">ID For Player*</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control mx-1" id="code_for_player"
                                        name="code_for_player" value="{{ $dept->code_for_player }}"
                                        placeholder="Enter Code" required readonly>
                                    <input type="text" class="form-control mx-1" id="digit_for_player"
                                        name="digit_for_player" value="{{ $dept->digit_for_player }}"
                                        placeholder="Enter No. Of Digit" required readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="coachIDCode" class="col-form-label">ID For Coach*</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control mx-1" id="code_for_coach"
                                        name="code_for_coach" value="{{ $dept->code_for_coach }}"
                                        placeholder="Enter Code" required readonly>
                                    <input type="text" class="form-control mx-1" id="digit_for_coach"
                                        name="digit_for_coach" value="{{ $dept->digit_for_coach }}"
                                        placeholder="Enter No. Of Digit" required readonly>
                                </div>
                            </div>
                        </div>
                        <!-- Department Location / Geofence -->
                        <div class="py-4">
                            <h2>Department Location</h2>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="col-form-label">Pick Location on Map*</label>
                                <div id="dept-map"></div>
                                <small class="text-muted">📍 Search inside map or click to set geofence
                                    location</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label class="col-form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude"
                                    value="{{ $dept->latitude }}" name="latitude" readonly
                                    placeholder="Auto-filled from map">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="col-form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude"
                                    value="{{ $dept->longitude }}" name="longitude" readonly
                                    placeholder="Auto-filled from map">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="col-form-label">Geofence Radius (meters)</label>
                                <input type="number" class="form-control" id="geofence_radius"
                                    name="geofence_radius" value="{{ $dept->geofence_radius }}">
                            </div>
                        </div>

                        <div class="py-4">
                            <h2>Department Admin Details</h2>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="adminName" class="col-form-label">Primary Department
                                    Admin Name*</label>
                                <input type="text" class="form-control" id="dept_admin_name"
                                    name="dept_admin_name" placeholder="Enter Admin Name"
                                    value="{{ $userData[0]->name  ?? '-'}}" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="adminEmail" class="col-sm-2 col-md-4 col-form-label">Department Admin
                                    Email*</label>
                                <input type="email" class="form-control" id="dept_admin_email"
                                    name="dept_admin_email" placeholder="Enter Email"
                                    value="{{ $userData[0]->email ?? '-' }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="adminContact" class="col-form-label">Department Admin Contact
                                    Number*</label>
                                <input type="text" class="form-control" id="dept_admin_contact"
                                    name="dept_admin_contact" value="{{ $userData[0]->contact_number ?? '-' }}"
                                    placeholder="Enter Contact Number" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="adminPassword" class="col-form-label">Department Admin
                                    Password*</label>
                                <input type="password" class="form-control" id="dept_admin_password"
                                    name="dept_admin_password" placeholder="Enter Password">
                                <small>Leave blank if you don't want to change password</small>
                            </div>
                        </div>

                        <div class="py-4">
                            <h2>Department Manager Details</h2>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="ManagerName" class="col-form-label">Primary Department
                                    Manager Name*</label>
                                <input type="text" class="form-control" id="dept_manager_name"
                                    name="dept_manager_name" value="{{ $userData[1]->name ?? '-' }}"
                                    placeholder="Enter Manager Name" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="ManagerEmail" class="col-form-label">Department Manager
                                    Email*</label>
                                <input type="email" class="form-control" id="dept_manager_email"
                                    name="dept_manager_email" placeholder="Enter Email"
                                    value="{{ $userData[1]->email ?? '-' }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="ManagerContact" class="col-form-label">Department Manager
                                    Contact Number*</label>
                                <input type="text" class="form-control" id="dept_manager_contact"
                                    name="dept_manager_contact" value="{{ $userData[1]->contact_number  ?? '-'}}"
                                    placeholder="Enter Contact Number" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="ManagerPassword" class="col-form-label">Department Manager
                                    Password*</label>
                                <input type="password" class="form-control" id="dept_manager_password"
                                    name="dept_manager_password" placeholder="Enter Password">
                                <small>Leave blank if you don't want to change password</small>
                            </div>
                        </div>

                        <div class="row mb-3">

                            <div class="card-body p-0-0">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center py-3 px-2">
                                    <button class="btn btn-warning text-black dcg-btn" type="submit"
                                        style="padding:8px 35px;">Save</button>
                                    <button
                                        class="btn btn-outline-warning me-md-2 border border-warning dcg-btn cancelDept"
                                        type="button" style="padding:8px 35px;">Cancel</button>

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- end of card body -->
            </div>
            <!-- end of card body -->
        </div>
        <!-- end col -->
    </div>
    <!--end::Row-->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBGSRFtM1ZljmPm8PQnBGRhP53zRXdJ-uE&libraries=places,geometry&callback=initDeptMap&loading=async"
        defer></script>
    <script>
        $(document).ready(function() {
            $(document).on('submit', '#updateDept', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let dept_id = $('input[name="dept_id"]').val();

                let submitButton = $('#updateDept button[type="submit"]');
                let spinner = $(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                );

                submitButton.prop('disabled', true).append(spinner);

                $.ajax({
                    url: "{{ url('super-admin/update-department') }}/" + dept_id,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                            }).then(() => {
                                window.location.href =
                                    "{{ route('department-list') }}";
                            });

                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                            });
                        }
                    },
                    // error: function(xhr, status, error) {
                    //     Swal.fire({
                    //         title: 'Error!',
                    //         text: 'Something went wrong!',
                    //         icon: 'error',
                    //         confirmButtonText: 'Cool'
                    //     });
                    // },
                    error: function(xhr, status, error) {
                        let message = 'Something went wrong!';

                        // Case 1: Laravel Validation errors (422)
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            message = Object.values(xhr.responseJSON.errors)
                                .flat()
                                .map(e => `• ${e}`)
                                .join('<br>');
                        }else if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }
                        // Case 3: fallback, include status code
                        else {
                            message = `Error ${xhr.status}: ${xhr.statusText}`;
                        }

                        Swal.fire({
                            title: 'Error!',
                            html: message,
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).find('.spinner-border').remove();
                    }
                })
            });

            $(document).on('click', '.cancelDept', function() {
                window.location.href = "{{ route('department-list') }}";
            })

        });

        function showImage(event) {

            let file = event.target.files[0];
            const previewContainer = document.getElementById('previewContainer');
            const imgPreview = document.getElementById('imagPreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;

                }
                reader.readAsDataURL(file);
            }
        }

        // Leaflet Map for Geofence
        var deptMap = null;
        var deptMarker = null;
        var deptCircle = null;
        var autocomplete = null;

        function initDeptMap() {

            // ── Read saved lat/lng from pre-filled inputs ──────────
            var defaultLat = parseFloat(document.getElementById('latitude').value) || 18.4088;
            var defaultLng = parseFloat(document.getElementById('longitude').value) || 73.8744;
            var defaultRadius = parseInt(document.getElementById('geofence_radius').value) || 500;

            // ── Init Map ───────────────────────────────────────────
            deptMap = new google.maps.Map(document.getElementById('dept-map'), {
                center: {
                    lat: defaultLat,
                    lng: defaultLng
                },
                zoom: 15,
                mapTypeControl: true,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
            });

            // ── Saved location marker ──────────────────────────────
            deptMarker = new google.maps.Marker({
                position: {
                    lat: defaultLat,
                    lng: defaultLng
                },
                map: deptMap,
                title: 'Department Location',
                animation: google.maps.Animation.DROP,
            });

            // ── Saved location circle ──────────────────────────────
            deptCircle = new google.maps.Circle({
                map: deptMap,
                center: {
                    lat: defaultLat,
                    lng: defaultLng
                },
                radius: defaultRadius,
                strokeColor: '#f0a500',
                strokeOpacity: 0.9,
                strokeWeight: 2,
                fillColor: '#f0a500',
                fillOpacity: 0.15,
            });

            // ── Create Search Input inside map ─────────────────────
            var searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = '🔍 Search location...';
            searchInput.style.cssText = `
        width: 300px;
        height: 40px;
        padding: 0 12px;
        margin: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        outline: none;
        box-shadow: rgba(0,0,0,0.3) 0px 1px 4px -1px;
        background: white !important;
        color: #333;
    `;

            // ── Push search input into Google Map UI ───────────────
            deptMap.controls[google.maps.ControlPosition.TOP_LEFT].push(searchInput);

            // ── Attach Autocomplete ────────────────────────────────
            autocomplete = new google.maps.places.Autocomplete(searchInput, {
                fields: ['geometry', 'name', 'formatted_address'],
            });

            autocomplete.bindTo('bounds', deptMap);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();

                if (!place || !place.geometry || !place.geometry.location) {
                    alert('No location found. Please select a valid place from the dropdown.');
                    return;
                }

                if (place.geometry.viewport) {
                    deptMap.fitBounds(place.geometry.viewport);
                } else {
                    deptMap.setCenter(place.geometry.location);
                    deptMap.setZoom(15);
                }

                setLocation(
                    place.geometry.location.lat(),
                    place.geometry.location.lng(),
                    null
                );
            });

            // ── Click map to set location ──────────────────────────
            deptMap.addListener('click', function(e) {
                setLocation(e.latLng.lat(), e.latLng.lng());
            });

            // ── Radius input updates circle ────────────────────────
            document.getElementById('geofence_radius').addEventListener('input', function() {
                if (deptCircle) deptCircle.setRadius(parseInt(this.value) || 500);
            });

            // ── Manual lat/lng sync ────────────────────────────────
            document.getElementById('latitude').addEventListener('change', syncCoordsToMap);
            document.getElementById('longitude').addEventListener('change', syncCoordsToMap);
        }

        // ── Set marker + circle + fill inputs ─────────────────────
        function setLocation(lat, lng, zoom) {
            var radius = parseInt(document.getElementById('geofence_radius').value) || 500;
            var position = {
                lat: parseFloat(lat),
                lng: parseFloat(lng)
            };

            document.getElementById('latitude').value = parseFloat(lat).toFixed(8);
            document.getElementById('longitude').value = parseFloat(lng).toFixed(8);

            if (deptMarker) deptMarker.setMap(null);
            if (deptCircle) deptCircle.setMap(null);

            deptMarker = new google.maps.Marker({
                position: position,
                map: deptMap,
                title: 'Department Location',
                animation: google.maps.Animation.DROP,
            });

            deptCircle = new google.maps.Circle({
                map: deptMap,
                center: position,
                radius: radius,
                strokeColor: '#f0a500',
                strokeOpacity: 0.9,
                strokeWeight: 2,
                fillColor: '#f0a500',
                fillOpacity: 0.15,
            });

            deptMap.panTo(position);
            if (zoom) deptMap.setZoom(zoom);
        }

        // ── Sync manual lat/lng to map ─────────────────────────────
        function syncCoordsToMap() {
            var lat = parseFloat(document.getElementById('latitude').value);
            var lng = parseFloat(document.getElementById('longitude').value);

            if (isNaN(lat) || isNaN(lng)) return;
            if (lat < -90 || lat > 90) {
                alert('Latitude must be between -90 and 90.');
                return;
            }
            if (lng < -180 || lng > 180) {
                alert('Longitude must be between -180 and 180.');
                return;
            }

            setLocation(lat, lng, 15);
        }
    </script>

</x-default-layout>