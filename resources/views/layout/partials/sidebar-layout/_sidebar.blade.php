<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
	data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
	data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
	<!--begin::Logo-->
	<div class="app-sidebar-logo px-6 py-5" id="kt_app_sidebar_logo">
		<!--begin::Logo image-->

		<?php
			$session_dept_id = Session::get('department_id');
			if ($session_dept_id == '' || $session_dept_id === null) {
				$session_dept_id = session('deptId');
			}
		?>

		@if (
		(auth()->check() && auth()->user()->userRole->role_name === 'departmentAdmin') || $session_dept_id )
		<a href="{{ route('dashboard', $session_dept_id) }}">
			<img alt="Logo" src="{{ asset('assets/images/logos/deccan-logo.png') }}"
				class="app-sidebar-logo-default" />
			<img alt="Logo" src="{{ asset('assets/images/logos/deccan-logo.png') }}"
				class="app-sidebar-logo-minimize" />
		</a>
		@elseif(auth()->check() && auth()->user()->userRole->role_name === 'superAdmin')
		<a href="{{ route('super-admin') }}">
			<img alt="Logo" src="{{ asset('assets/images/logos/deccan-logo.png') }}"
				class="app-sidebar-logo-default" />
			<img alt="Logo" src="{{ asset('assets/images/logos/deccan-logo.png') }}"
				class="app-sidebar-logo-minimize" />
		</a>
		@endif

		<!--end::Logo image-->
		<!--begin::Sidebar toggle-->

		<div id="kt_app_sidebar_toggle"
			class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-20 start-100 translate-middle rotate"
			data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
			data-kt-toggle-name="app-sidebar-minimize">{!! getIcon('black-left-line', 'fs-3 rotate-180 ms-1') !!}
		</div>
		<script type="text/javascript">
			var sidebar_toggle = document.getElementById(
				"kt_app_sidebar_toggle"); // Get the sidebar toggle button element
			@if(isset($_COOKIE['sidebar_minimize_state']) && $_COOKIE['sidebar_minimize_state'] === 'on')
			document.body.setAttribute("data-kt-app-sidebar-minimize",
				"on"); // Set the 'data-kt-app-sidebar-minimize' attribute for the body tag
			sidebar_toggle.setAttribute("data-kt-toggle-state",
				"active"); // Set the 'data-kt-toggle-state' attribute for the sidebar toggle button
			sidebar_toggle.classList.add("active"); // Add the 'active' class to the sidebar toggle button
			@endif
		</script>
		<!--end::Sidebar toggle-->
	</div>
	<!--end::Logo-->

	@if (auth::check())

	<!-- only super-admin can access both admin/super-admin based on auth and URL -->
	@if ( auth::user()->userRole->role_name === 'departmentAdmin' ||
		(!request()->is('super-admin/*') && auth::user()->userRole->role_name === 'superAdmin'))
	<!--begin::sidebar menu-->
	<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
		<!--begin::Menu wrapper-->
		<div id="kt_app_sidebar_menu_wrapper"
			class="app-sidebar-wrapper hover-scroll-overlay-y my-5 overflow-hidden" data-kt-scroll="true"
			data-kt-scroll-activate="true" data-kt-scroll-height="auto"
			data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
			data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
			data-kt-scroll-save-state="true">
			<!--begin::Menu-->
			<div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6"
				id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">

				<!--begin:Menu item-->

				<div class="menu-item menu-accordion {{ request()->routeIs('dashboard') ? 'here show' : '' }}">
					<!--begin:Menu link-->
					@if (
					(auth()->check() && auth()->user()->userRole->role_name === 'departmentAdmin') || $session_dept_id )
					<a class="menu-link" href="{{ route('dashboard',  $session_dept_id ) }}" target="">
						<!-- <span class="menu-icon">{!! getIcon('rocket', 'fs-2') !!}</span> -->
						<!-- <span class="menu-icon"><img href="{{ asset('assets/images/logos/dashboard-20px.svg') }}" alt=""></span> -->
						<span class="menu-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20">
								<path id="dashboard-20px"
									d="M11.224,4.742V8.722a2.5,2.5,0,0,1-2.514,2.5H4.749a2.5,2.5,0,0,1-2.494-2.5V4.753a2.5,2.5,0,0,1,2.494-2.5H8.72a2.522,2.522,0,0,1,2.5,2.492m11.031.01V8.722a2.524,2.524,0,0,1-2.494,2.5H15.78A2.566,2.566,0,0,1,14,10.5a2.5,2.5,0,0,1-.729-1.774V4.753a2.522,2.522,0,0,1,2.5-2.5h3.971a2.524,2.524,0,0,1,2.5,2.5m0,11.026v3.969a2.524,2.524,0,0,1-2.494,2.5H15.78a2.566,2.566,0,0,1-1.8-.708,2.481,2.481,0,0,1-.729-1.774V15.8a2.522,2.522,0,0,1,2.5-2.5h3.971a2.524,2.524,0,0,1,2.5,2.5Zm-11.031.01v3.969A2.524,2.524,0,0,1,8.71,22.25H4.749a2.483,2.483,0,0,1-2.494-2.492V15.788a2.524,2.524,0,0,1,2.494-2.513H8.72a2.566,2.566,0,0,1,1.775.738,2.512,2.512,0,0,1,.729,1.774"
									transform="translate(-2.256 -2.25)" fill="#84857e" />
							</svg>

						</span>

						<span class="menu-title">Dashboard</span>
					</a>
					@elseif(auth()->check() && auth()->user()->userRole->role_name === 'superAdmin')
					<a class="menu-link" href="{{ route('super-admin') }}" target="">
						<!-- <span class="menu-icon">{!! getIcon('rocket', 'fs-2') !!}</span> -->
						<!-- <span class="menu-icon"><img href="{{ asset('assets/images/logos/dashboard-20px.svg') }}" alt=""></span> -->
						<span class="menu-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20">
								<path id="dashboard-20px"
									d="M11.224,4.742V8.722a2.5,2.5,0,0,1-2.514,2.5H4.749a2.5,2.5,0,0,1-2.494-2.5V4.753a2.5,2.5,0,0,1,2.494-2.5H8.72a2.522,2.522,0,0,1,2.5,2.492m11.031.01V8.722a2.524,2.524,0,0,1-2.494,2.5H15.78A2.566,2.566,0,0,1,14,10.5a2.5,2.5,0,0,1-.729-1.774V4.753a2.522,2.522,0,0,1,2.5-2.5h3.971a2.524,2.524,0,0,1,2.5,2.5m0,11.026v3.969a2.524,2.524,0,0,1-2.494,2.5H15.78a2.566,2.566,0,0,1-1.8-.708,2.481,2.481,0,0,1-.729-1.774V15.8a2.522,2.522,0,0,1,2.5-2.5h3.971a2.524,2.524,0,0,1,2.5,2.5Zm-11.031.01v3.969A2.524,2.524,0,0,1,8.71,22.25H4.749a2.483,2.483,0,0,1-2.494-2.492V15.788a2.524,2.524,0,0,1,2.494-2.513H8.72a2.566,2.566,0,0,1,1.775.738,2.512,2.512,0,0,1,.729,1.774"
									transform="translate(-2.256 -2.25)" fill="#84857e" />
							</svg>

						</span>

						<span class="menu-title">Dashboard</span>
					</a>
					@endif
					<!--end:Menu link-->
				</div>
				<!--end:Menu item-->

				<hr class="sidebar-menu-divider">

				<!--begin:Menu item-->
				<div data-kt-menu-trigger="click"
					class="menu-item menu-accordion {{ request()->routeIs('attendance-list', 'add-attendance') ? 'here show' : '' }}">
					<!--begin:Menu link-->
					<span class="menu-link">
						<span class="menu-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20">
								<g fill="#84857e">
									<path d="M14,4 a4,4,0,1,1,-4,-4 a4,4,0,0,1,4,4" transform="translate(-2, 0)" />

									<path
										d="M13.678,11.5 a11.032,11.032,0,0,0,-1.3,0.074 a3.008,3.008,0,0,0,-2.8,2.8 a16.29,16.29,0,0,0,-0.078,2.034 v0.172 a16.29,16.29,0,0,0,0.078,2.034 A3.579,3.579,0,0,0,10.025,20 H10 c-8,0-8,-2.015-8,-4.5 S5.582,11,10,11 a13.191,13.191,0,0,1,3.678,0.5"
										transform="translate(-2, 0)" />

									<path fill-rule="evenodd"
										d="M14.5,20 c-1.65,0-2.475,0-2.987,-0.513 S11,18.15,11,16.5 s0,-2.475,0.513,-2.987 S12.85,13,14.5,13 s2.475,0,2.987,0.513 S18,14.85,18,16.5 s0,2.475,-0.513,2.987 S16.15,20,14.5,20 m1.968,-4.254 a0.583,0.583,0,0,0,-0.825,-0.825 l-1.92,1.92 l-0.366,-0.365 a0.583,0.583,0,1,0,-0.825,0.825 l0.778,0.778 a0.583,0.583,0,0,0,0.825,0 Z"
										transform="translate(0, -1)" />
								</g>
							</svg>

						</span>
						<span class="menu-title">Attendance</span>
						<span class="">
							<i class="bi bi-plus"></i>
						</span>
					</span>
					<!--end:Menu link-->
					<!--begin:Menu sub-->
					<div class="menu-sub menu-sub-accordion">
						<!--begin:Menu item-->
						<div class="menu-item">
							<!--begin:Menu link-->
							<a class="menu-link {{ request()->routeIs('attendance-list') ? 'active' : '' }}"
								href="{{ route('attendance-list') }}">
								<!-- <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span> -->
								<span class="menu-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15"
										height="15" fill="none">

										<!-- Outer document -->
										<path d="M18.75 3.75V18.75C18.75 19.44 18.19 20 17.5 20H2.5
            C1.81 20 1.25 19.44 1.25 18.75V3.75
            C1.25 3.06 1.81 2.5 2.5 2.5H3.75V1.25
            C3.75 0.56 4.31 0 5 0C5.69 0 6.25 0.56 6.25 1.25V2.5H8.75V1.25
            C8.75 0.56 9.31 0 10 0C10.69 0 11.25 0.56 11.25 1.25V2.5H13.75V1.25
            C13.75 0.56 14.31 0 15 0C15.69 0 16.25 0.56 16.25 1.25V2.5H17.5
            C18.19 2.5 18.75 3.06 18.75 3.75Z" fill="#84857e" />

										<!-- List lines -->
										<path d="M3.75 7.5H16.25V8.75H3.75Z" fill="#f7f7f7" />
										<path d="M3.75 11.25H16.25V12.5H3.75Z" fill="#f7f7f7" />
										<path d="M3.75 15H16.25V16.25H3.75Z" fill="#f7f7f7" />

									</svg>

								</span>

								<span class="menu-title">Attendance List</span>
							</a>
							<!--end:Menu link-->
						</div>
						<!--end:Menu item-->
						<!--begin:Menu item-->
						<div class="menu-item">
							<!--begin:Menu link-->
							<a class="menu-link {{ request()->routeIs('add-attendance') ? 'active' : '' }}"
								href="{{ route('add-attendance') }}">
								<!-- <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span> -->
								<span class="menu-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15"
										height="15" fill="none">

										<!-- Back page -->
										<path
											d="M3.333 5H1.667V16.667C1.667 17.583 2.417 18.333 3.333 18.333H15V16.667H3.333V5Z"
											fill="#84857e" />

										<!-- Front page -->
										<path d="M16.667 1.667H6.667C5.75 1.667 5 2.417 5 3.333V13.333C5 14.25 5.75 15 6.667 15H16.667
            C17.583 15 18.333 14.25 18.333 13.333V3.333
            C18.333 2.417 17.583 1.667 16.667 1.667Z" fill="#84857e" />

										<!-- Plus icon -->
										<path
											d="M12.5 4.167V7.5H15.833V9.167H12.5V12.5H10.833V9.167H7.5V7.5H10.833V4.167H12.5Z"
											fill="#ffffff" />

									</svg>

								</span>
								<span class="menu-title">Add Attendance</span>
							</a>
							<!--end:Menu link-->
						</div>
						<!--end:Menu item-->
					</div>
					<!--end:Menu sub-->
				</div>
				<!--end:Menu item-->

				<hr class="sidebar-menu-divider">


				<!--begin:Menu item-->
				<div data-kt-menu-trigger="click"
					class="menu-item menu-accordion {{ request()->routeIs('player-list', 'add-player') ? 'here show' : '' }}">
					<!--begin:Menu link-->
					<span class="menu-link">
						<span class="menu-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20">
								<g fill="#84857e">
									<path
										d="M6.6,1.4 C6.4,1.2 6.4,1 6.4,0.8 C4.2,1.2 3.5,1.8 3.0,2.5 C2.5,3.2 2.3,4.1 2.2,5.5 C2.1,9.2 1.8,12.9 1.5,16.6 L3.8,16.5 C3.9,15.5 3.9,14.6 4.0,13.7 C4.3,12.3 4.7,10.9 4.7,10.9 C4.7,10.9 4.5,10.3 4.4,9.1 C4.4,7.9 3.8,2.9 6.6,1.4 Z"
										opacity="0.7" />
									<path
										d="M17.7,4.7 C17.3,2.7 16.2,1.8 15.0,1.2 C14.2,0.8 13.5,0.6 12.8,0.4 C12.7,0.5 12.6,0.7 12.6,0.8 C16.2,2.9 15.6,7.9 15.6,8.1 C15.6,8.1 15.1,9.5 15.4,11.2 C15.6,12.6 16.1,14.0 16.1,14.0 C16.2,15.0 16.3,15.9 16.3,16.8 L18.7,16.9 C18.3,12.8 18.0,8.7 17.7,4.7 Z"
										opacity="0.7" />

									<path
										d="M13.1,1.7 C11.3,2.6 9.1,2.6 7.3,1.7 C4.7,3.1 5.3,8.1 5.3,8.1 C5.3,8.1 6.0,9.5 5.3,20 L14.7,20 C14.1,9.5 14.8,8.1 14.8,8.1 C14.8,8.1 15.4,3.1 13.1,1.7 Z M10.4,4.4 C12.2,4.4 13.7,5.9 13.7,7.7 L13.2,7.7 C13.2,6.2 12.0,5.0 10.4,5.0 C8.8,5.0 7.6,6.2 7.6,7.7 L7.1,7.7 C7.1,5.9 8.6,4.4 10.4,4.4 Z" />

									<path
										d="M13.0,0.5 C12.6,0.2 11.5,0.6 10.0,0.6 C8.4,0.6 7.3,0.2 7.0,0.5 C6.9,0.6 6.9,0.7 6.9,0.8 C6.9,1.4 8.3,2.6 10.0,2.6 C11.7,2.6 13.1,1.4 13.1,0.8 C13.1,0.7 13.1,0.6 13.0,0.5 Z" />
								</g>
							</svg>

						</span>

						<span class="menu-title">Players</span>
						<!-- <span class="menu-arrow"></span> -->
						<span class="">
							<i class="bi bi-plus"></i>
						</span>
					</span>
					<!--end:Menu link-->
					<!--begin:Menu sub-->
					<div class="menu-sub menu-sub-accordion">
						<!--begin:Menu item-->
						<div class="menu-item">
							<!--begin:Menu link-->
							<a class="menu-link {{ request()->routeIs('player-list') ? 'active' : '' }}"
								href="{{ route('player-list') }}">
								<span class="menu-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15"
										height="15" fill="none">

										<!-- Outer document -->
										<path d="M18.75 3.75V18.75C18.75 19.44 18.19 20 17.5 20H2.5
            C1.81 20 1.25 19.44 1.25 18.75V3.75
            C1.25 3.06 1.81 2.5 2.5 2.5H3.75V1.25
            C3.75 0.56 4.31 0 5 0C5.69 0 6.25 0.56 6.25 1.25V2.5H8.75V1.25
            C8.75 0.56 9.31 0 10 0C10.69 0 11.25 0.56 11.25 1.25V2.5H13.75V1.25
            C13.75 0.56 14.31 0 15 0C15.69 0 16.25 0.56 16.25 1.25V2.5H17.5
            C18.19 2.5 18.75 3.06 18.75 3.75Z" fill="#84857e" />

										<!-- List lines -->
										<path d="M3.75 7.5H16.25V8.75H3.75Z" fill="#f7f7f7" />
										<path d="M3.75 11.25H16.25V12.5H3.75Z" fill="#f7f7f7" />
										<path d="M3.75 15H16.25V16.25H3.75Z" fill="#f7f7f7" />

									</svg>

								</span>
								<span class="menu-title">Players List</span>
							</a>
							<!--end:Menu link-->
						</div>
						<!--end:Menu item-->
						<!--begin:Menu item-->
						<div class="menu-item">
							<!--begin:Menu link-->
							<a class="menu-link {{ request()->routeIs('add-player') ? 'active' : '' }}"
								href="{{ route('add-player') }}">
								<span class="menu-icon"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
									width="15" height="15" fill="none">

									<!-- Back page -->
									<path
										d="M3.333 5H1.667V16.667C1.667 17.583 2.417 18.333 3.333 18.333H15V16.667H3.333V5Z"
										fill="#84857e" />

									<!-- Front page -->
									<path d="M16.667 1.667H6.667C5.75 1.667 5 2.417 5 3.333V13.333C5 14.25 5.75 15 6.667 15H16.667
            C17.583 15 18.333 14.25 18.333 13.333V3.333
            C18.333 2.417 17.583 1.667 16.667 1.667Z" fill="#84857e" />

									<!-- Plus icon -->
									<path
										d="M12.5 4.167V7.5H15.833V9.167H12.5V12.5H10.833V9.167H7.5V7.5H10.833V4.167H12.5Z"
										fill="#ffff" />

									</svg></span>
								<span class="menu-title">Add player</span>
							</a>
							<!--end:Menu link-->
						</div>
						<!--end:Menu item-->

					</div>
					<!--end:Menu sub-->
				</div>
				<!--end:Menu item-->

				<hr class="sidebar-menu-divider">


				<!--begin:Menu item-->
				<div data-kt-menu-trigger="click"
					class="menu-item menu-accordion {{ request()->routeIs('coach-list', 'add-coach') ? 'here show' : '' }}">
					<!--begin:Menu link-->
					<span class="menu-link">
						<span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
							viewBox="0 0 20 20">
							<g fill="#84857e">
								<path
									d="M7.666,8.334 h0.353 a3,3,0,0,0,1.647,2.35 V11.667 H8.343 l0.513,0.667 l2.143,2.787 l2.143,-2.787 L13.656,11.667 H12.333 v-0.983 a3,3,0,0,0,1.647,-2.35 h0.667 a1.333,1.333,0,0,0,1.333,-1.333 v-2 A4.667,4.667,0,0,0,6.333,5.001 v2 A1.333,1.333,0,0,0,7.666,8.334 Z M13.999,6.334 H14.333 a0.667,0.667,0,1,1,0,1.333 H13.999 Z M11.666,12.001 a0.667,0.667,0,0,1,-1.333,0 v-0.744 a2.937,2.937,0,0,0,1.333,0 Z M8.666,6.334 h2 a1.334,1.334,0,0,0,1.29,-1 h1.043 a0.334,0.334,0,0,1,0.334,0.333 V8 a2.333,2.333,0,1,1,-4.667,0 Z M7.666,6.334 h0.333 V7.667 H7.666 a0.667,0.667,0,1,1,0,-1.333 Z" />

								<path
									d="M14.666,11.667 h-0.17 l-0.513,0.667 l-2.617,3.4 A1,1,0,0,1,11,17.667 v1 h-1 v-2 a1,1,0,0,1,0.633,-0.93 l-2.617,-3.4 l-0.513,-0.667 h-0.17 a2.67,2.67,0,0,0,-2.667,2.667 v5.667 a0.334,0.334,0,0,0,0.333,0.333 h2 v-3 a0.333,0.333,0,1,1,0.667,0 v3.333 h6.666 v-3 a0.333,0.333,0,1,1,0.667,0 v3.333 h2 a0.334,0.334,0,0,0,0.333,-0.333 v-5.667 a2.67,2.67,0,0,0,-2.667,-2.667 Z" />
							</g>
							</svg></span>
						<span class="menu-title">Coaches</span>
						<!-- <span class="menu-arrow"></span> -->
						<span class="">
							<i class="bi bi-plus"></i>
						</span>
					</span>
					<!--end:Menu link-->
					<!--begin:Menu sub-->
					<div class="menu-sub menu-sub-accordion">
						<!--begin:Menu item-->
						<div class="menu-item">
							<!--begin:Menu link-->
							<a class="menu-link {{ request()->routeIs('coach-list') ? 'active' : '' }}"
								href="{{ route('coach-list') }}">
								<span class="menu-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15"
										height="15" fill="none">

										<!-- Outer document -->
										<path d="M18.75 3.75V18.75C18.75 19.44 18.19 20 17.5 20H2.5
            C1.81 20 1.25 19.44 1.25 18.75V3.75
            C1.25 3.06 1.81 2.5 2.5 2.5H3.75V1.25
            C3.75 0.56 4.31 0 5 0C5.69 0 6.25 0.56 6.25 1.25V2.5H8.75V1.25
            C8.75 0.56 9.31 0 10 0C10.69 0 11.25 0.56 11.25 1.25V2.5H13.75V1.25
            C13.75 0.56 14.31 0 15 0C15.69 0 16.25 0.56 16.25 1.25V2.5H17.5
            C18.19 2.5 18.75 3.06 18.75 3.75Z" fill="#84857e" />

										<!-- List lines -->
										<path d="M3.75 7.5H16.25V8.75H3.75Z" fill="#f7f7f7" />
										<path d="M3.75 11.25H16.25V12.5H3.75Z" fill="#f7f7f7" />
										<path d="M3.75 15H16.25V16.25H3.75Z" fill="#f7f7f7" />

									</svg>

								</span>
								<span class="menu-title">Coaches List</span>
							</a>
							<!--end:Menu link-->
						</div>
						<!--end:Menu item-->
						<!--begin:Menu item-->
						<div class="menu-item">
							<!--begin:Menu link-->
							<a class="menu-link {{ request()->routeIs('add-coach') ? 'active' : '' }}"
								href="{{ route('add-coach') }}">
								<span class="menu-icon"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
									width="15" height="15" fill="none">

									<!-- Back page -->
									<path
										d="M3.333 5H1.667V16.667C1.667 17.583 2.417 18.333 3.333 18.333H15V16.667H3.333V5Z"
										fill="#84857e" />

									<!-- Front page -->
									<path d="M16.667 1.667H6.667C5.75 1.667 5 2.417 5 3.333V13.333C5 14.25 5.75 15 6.667 15H16.667
            C17.583 15 18.333 14.25 18.333 13.333V3.333
            C18.333 2.417 17.583 1.667 16.667 1.667Z" fill="#84857e" />

									<!-- Plus icon -->
									<path
										d="M12.5 4.167V7.5H15.833V9.167H12.5V12.5H10.833V9.167H7.5V7.5H10.833V4.167H12.5Z"
										fill="#ffff" />

									</svg></span>
								<span class="menu-title">Add Coach</span>
							</a>
							<!--end:Menu link-->
						</div>
						<!--end:Menu item-->

					</div>
					<!--end:Menu sub-->
				</div>
				<!--end:Menu item-->

				<hr class="sidebar-menu-divider">


				<!--begin:Menu item-->
				<div class="menu-item   menu-accordion {{ request()->routeIs('fee-validity') ? 'here show' : '' }}">
					<!--begin:Menu link-->
					<a class="menu-link" href="{{ route('fee-validity') }}" target="">
						<span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
							viewBox="0 0 20 20">
							<g fill="none" fill-rule="evenodd">
								<rect width="15" height="15" rx="4.227" fill="#84857e" />

								<path fill="#ffffff"
									d="M4.375,6.667a.208.208,0,0,0-.147.356l.478.478-.478.478a.208.208,0,0,0,0,.295l.478.478-.478.478a.208.208,0,0,0,0,.295L4.705,10l-.478.478a.208.208,0,0,0,0,.295l.478.478-.478.478a.208.208,0,0,0,0,.295l.478.478-.478.478a.209.209,0,0,0,.147.356h11.25a.208.208,0,0,0,.147-.356l-.478-.478.478-.478a.208.208,0,0,0,0-.295l-.478-.478.478-.478a.208.208,0,0,0,0-.295L15.295,10l.478-.478a.208.208,0,0,0,0-.295l-.478-.478.478-.478a.208.208,0,0,0,0-.295L15.295,7.5l.478-.478a.209.209,0,0,0-.147-.356Zm.5.417H15.122l-.269.269a.208.208,0,0,0,0,.295l.478.477-.478.478a.208.208,0,0,0,0,.295l.478.478-.478.479a.208.208,0,0,0,0,.295l.478.477-.478.478a.208.208,0,0,0,0,.295l.478.478-.478.478a.208.208,0,0,0,0,.295l.269.27H4.878l.269-.269a.208.208,0,0,0,0-.295l-.478-.478.478-.478a.208.208,0,0,0,0-.295l-.478-.478.478-.478a.208.208,0,0,0,0-.295L4.67,9.375,5.147,8.9a.208.208,0,0,0,0-.295L4.67,8.125l.478-.478a.208.208,0,0,0,0-.295l-.269-.269Zm1.58.417a.208.208,0,0,0-.208.208.626.626,0,0,1-.625.625.208.208,0,0,0-.208.208v2.917a.208.208,0,0,0,.208.208.626.626,0,0,1,.625.625.208.208,0,0,0,.208.208h7.083a.208.208,0,0,0,.208-.208.626.626,0,0,1,.625-.625.208.208,0,0,0,.208-.208V8.542a.208.208,0,0,0-.208-.208.626.626,0,0,1-.625-.625.208.208,0,0,0-.208-.208Zm.187.417h6.709a1.045,1.045,0,0,0,.812.812v2.542a1.045,1.045,0,0,0-.812.812H6.645a1.045,1.045,0,0,0-.812-.812V8.729a1.045,1.045,0,0,0,.812-.812Zm2.52.413a.208.208,0,0,0,0,.417h.417a.623.623,0,0,1,.588.423H9.166a.208.208,0,0,0,0,.417h1a.624.624,0,0,1-.584.41H9.166a.208.208,0,0,0-.208.208.009.009,0,0,0,0,0,.206.206,0,0,0,.06.151l1.25,1.25a.207.207,0,0,0,.147.061.211.211,0,0,0,.148-.061.208.208,0,0,0,0-.295l-.908-.908a1.039,1.039,0,0,0,.946-.82h.231a.208.208,0,1,0,0-.417H10.6a1.037,1.037,0,0,0-.193-.424h.422a.208.208,0,0,0,0-.417H9.166ZM7.083,9.375A.625.625,0,1,0,7.708,10,.626.626,0,0,0,7.083,9.375Zm5.833,0a.625.625,0,1,0,.625.625A.626.626,0,0,0,12.917,9.375Zm-5.833.417A.208.208,0,1,1,6.875,10,.208.208,0,0,1,7.083,9.792Zm5.833,0a.208.208,0,1,1-.208.208A.208.208,0,0,1,12.917,9.792Z" />
							</g>
							</svg></span>
						<span class="menu-title">Fees Validity</span>
					</a>
					<!--end:Menu link-->
				</div>

				<hr class="sidebar-menu-divider">


				<!--end:Menu item-->
				<!--begin:Menu item-->
				<!-- <div class="menu-item  menu-accordion {{ request()->routeIs('report') ? 'here show' : '' }}">
					<a class="menu-link" href="#" target="_blank">
						<span class="menu-icon">{!! getIcon('code', 'fs-2') !!}</span>
						<span class="menu-title">Report</span>
					</a>
				</div> -->
				<!--end:Menu item-->
				<!--begin:Menu item-->
				<!-- <div class="menu-item   menu-accordion {{ request()->routeIs('setting') ? 'here show' : '' }}">
					<a class="menu-link" href="#" target="_blank">
						<span class="menu-icon">{!! getIcon('setting-2', 'fs-2') !!}</span>
						<span class="menu-title">Settings</span>
					</a>
				</div> -->
				<!--end:Menu item-->



				<!--begin:Menu item for Master-->
				<div data-kt-menu-trigger="click"
					class="menu-item menu-accordion {{ request()->routeIs('batch-list', 'add-batch', 'add-batch', 'batch-list', 'add-user', 'user-list') ? 'here show' : '' }}">
					<!-- level1 -->
					<span class="menu-link">
						<span class="menu-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20">
								<g fill="none" fill-rule="evenodd">
									<rect width="15" height="15" fill="none" />

									<path fill="#84857e"
										d="M12.5,1.25 A6.255,6.255,0,0,0,6.556,9.436 l-4.5,4.5 a.625,.625,0,0,0-.183,.442 V17.5 a.625,.625,0,0,0,.625,.625 H5.625 a.625,.625,0,0,0,.625-.625 V16.25 H7.5 a.625,.625,0,0,0,.625-.625 v-1.25 h1.25 a.625,.625,0,0,0,.442-.183 l.747-.748 A6.251,6.251,0,1,0,12.5,1.25 Z M14.062,7.188 a1.25,1.25,0,1,1,1.25-1.25 A1.25,1.25,0,0,1,14.062,7.188 Z" />
								</g>
							</svg></span>
						<span class="menu-title">Master</span>
						<!-- <span class="menu-arrow"></span> -->
						<span class="">
							<i class="bi bi-plus"></i>
						</span>
					</span>
					<div class="menu-sub menu-sub-accordion">
						<!-- level 2 for batches-->
						<div data-kt-menu-trigger="click"
							class="menu-item menu-accordion {{ request()->routeIs('batch-list', 'add-batch') ? 'here show' : '' }}">
							<span class="menu-link">
								<span class="menu-icon"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
									width="15" height="15" fill="none">

									<!-- Outer document -->
									<path d="M18.75 3.75V18.75C18.75 19.44 18.19 20 17.5 20H2.5
            C1.81 20 1.25 19.44 1.25 18.75V3.75
            C1.25 3.06 1.81 2.5 2.5 2.5H3.75V1.25
            C3.75 0.56 4.31 0 5 0C5.69 0 6.25 0.56 6.25 1.25V2.5H8.75V1.25
            C8.75 0.56 9.31 0 10 0C10.69 0 11.25 0.56 11.25 1.25V2.5H13.75V1.25
            C13.75 0.56 14.31 0 15 0C15.69 0 16.25 0.56 16.25 1.25V2.5H17.5
            C18.19 2.5 18.75 3.06 18.75 3.75Z" fill="#84857e" />

									<!-- List lines -->
									<path d="M3.75 7.5H16.25V8.75H3.75Z" fill="#f7f7f7" />
									<path d="M3.75 11.25H16.25V12.5H3.75Z" fill="#f7f7f7" />
									<path d="M3.75 15H16.25V16.25H3.75Z" fill="#f7f7f7" />

									</svg>
								</span>
								<span class="menu-title">Batches</span>
								<!-- <span class="menu-arrow"></span> -->
								<span class="">
									<i class="bi bi-plus"></i>
								</span>
							</span>

							<!-- level 3 submenu container -->
							<div class="menu-sub menu-sub-accordion">
								<div class="menu-items">
									<a class="menu-link {{ request()->routeIs('batch-list') ? 'active' : '' }}"
										href="{{ route('batch-list') }}">
										<span class="menu-icon">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15"
												height="15" fill="none">

												<!-- Outer document -->
												<path d="M18.75 3.75V18.75C18.75 19.44 18.19 20 17.5 20H2.5
            C1.81 20 1.25 19.44 1.25 18.75V3.75
            C1.25 3.06 1.81 2.5 2.5 2.5H3.75V1.25
            C3.75 0.56 4.31 0 5 0C5.69 0 6.25 0.56 6.25 1.25V2.5H8.75V1.25
            C8.75 0.56 9.31 0 10 0C10.69 0 11.25 0.56 11.25 1.25V2.5H13.75V1.25
            C13.75 0.56 14.31 0 15 0C15.69 0 16.25 0.56 16.25 1.25V2.5H17.5
            C18.19 2.5 18.75 3.06 18.75 3.75Z" fill="#84857e" />

												<!-- List lines -->
												<path d="M3.75 7.5H16.25V8.75H3.75Z" fill="#f7f7f7" />
												<path d="M3.75 11.25H16.25V12.5H3.75Z" fill="#f7f7f7" />
												<path d="M3.75 15H16.25V16.25H3.75Z" fill="#f7f7f7" />

											</svg>
										</span>
										<span class="menu-title">Batch List</span>
									</a>
								</div>
								<div class="menu-items">
									<a class="menu-link {{ request()->routeIs('add-batch') ? 'active' : '' }}"
										href="{{ route('add-batch') }}">
										<span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg"
											viewBox="0 0 20 20" width="15" height="15" fill="none">

											<!-- Back page -->
											<path
												d="M3.333 5H1.667V16.667C1.667 17.583 2.417 18.333 3.333 18.333H15V16.667H3.333V5Z"
												fill="#84857e" />

											<!-- Front page -->
											<path d="M16.667 1.667H6.667C5.75 1.667 5 2.417 5 3.333V13.333C5 14.25 5.75 15 6.667 15H16.667
            C17.583 15 18.333 14.25 18.333 13.333V3.333
            C18.333 2.417 17.583 1.667 16.667 1.667Z" fill="#84857e" />

											<!-- Plus icon -->
											<path
												d="M12.5 4.167V7.5H15.833V9.167H12.5V12.5H10.833V9.167H7.5V7.5H10.833V4.167H12.5Z"
												fill="#ffff" />

											</svg></span>
										<span class="menu-title">Add Batch</span>
									</a>
								</div>
							</div>

						</div>

						<!-- level 2 for Users-->
						<div data-kt-menu-trigger="click"
							class="menu-item menu-accordion {{ request()->routeIs('user-list', 'add-user') ? 'here show' : '' }}">
							<span class="menu-link">
								<span class="menu-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="15"
									height="15" viewBox="0 0 20 20">
									<g id="users-updated">
										<path
											d="M10,10a5,5,0,1,0-5-5A5.015,5.015,0,0,0,10,10Zm0,2.5c-3.312,0-10,1.688-10,5v2.5h20v-2.5C20,14.188,13.312,12.5,10,12.5Z"
											fill="#84857e" />
									</g>
									</svg>
								</span>
								<span class="menu-title">Users</span>
								<span class="">
									<i class="bi bi-plus"></i>
								</span>
							</span>

							<div class="menu-sub menu-sub-accordion">
								<div class="menu-items">
									<a class="menu-link {{ request()->routeIs('user-list') ? 'active' : '' }}"
										href="{{ route('user-list') }}">
										<span class="menu-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
												viewBox="0 0 20 20">
												<g id="user-list-refined">
													<path
														d="M9.469,12.34a4.687,4.687,0,1,0-5.187,0A7.517,7.517,0,0,0,.739,15.062a.625.625,0,0,0,.511.985H12.5a.625.625,0,0,0,.511-.985A7.517,7.517,0,0,0,9.469,12.34Z"
														fill="#84857e" />
													<path
														d="M19.38,15.061a7.517,7.517,0,0,0-3.542-2.722,4.688,4.688,0,0,0-3.865-8.415.625.625,0,0,0-.332.975,5.924,5.924,0,0,1,.3,6.632.625.625,0,0,0,.16.828,8.844,8.844,0,0,1,.683.564c.011.012.023.024.035.036a8.753,8.753,0,0,1,1.991,2.727.625.625,0,0,0,.566.361h3.494a.625.625,0,0,0,.511-.985Z"
														fill="#84857e" />
												</g>
											</svg>
										</span>
										<span class="menu-title">User List</span>
									</a>
								</div>
								<div class="menu-items">
									<a class="menu-link {{ request()->routeIs('add-user') ? 'active' : '' }}"
										href="{{ route('add-user') }}">
										<span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg"
											viewBox="0 0 20 20" width="15" height="15" fill="none">

											<!-- Back page -->
											<path
												d="M3.333 5H1.667V16.667C1.667 17.583 2.417 18.333 3.333 18.333H15V16.667H3.333V5Z"
												fill="#84857e" />

											<!-- Front page -->
											<path d="M16.667 1.667H6.667C5.75 1.667 5 2.417 5 3.333V13.333C5 14.25 5.75 15 6.667 15H16.667
            C17.583 15 18.333 14.25 18.333 13.333V3.333
            C18.333 2.417 17.583 1.667 16.667 1.667Z" fill="#84857e" />

											<!-- Plus icon -->
											<path
												d="M12.5 4.167V7.5H15.833V9.167H12.5V12.5H10.833V9.167H7.5V7.5H10.833V4.167H12.5Z"
												fill="#ffff" />

											</svg></span>
										<span class="menu-title">Add User</span>
									</a>
								</div>
							</div>

						</div>

						<!-- level 2 for Assesment parameter -->
						<!-- <div data-kt-menu-trigger="click"
							class="menu-item menu-accordion {{ request()->routeIs('assessment-list', 'add-assessment') ? 'here show' : '' }}">
							<span class="menu-link">
								<span class="menu-bullet">{!! getIcon('abstract-28', 'fs-2') !!}</span>
								<span class="menu-title">Assesment Parameters</span>
								<span class="menu-arrow"></span>
							</span>

							<div class="menu-sub menu-sub-accordion">
								<div class="menu-items">
									<a class="menu-link {{ request()->routeIs('assesment-list') ? 'active' : '' }}" href="#">
										<span class="menu-bullet"><span
											class="bullet bullet-dot"></span></span>
										<span class="menu-title"> Assesment List</span>
									</a>
								</div>
								<div class="menu-items">
									<a class="menu-link {{ request()->routeIs('add-assesment') ? 'active' : '' }}" href="#">
										<span class="menu-bullet"><span
											class="bullet bullet-dot"></span></span>
										<span class="menu-title">Add Assesment</span>
									</a>
								</div>
							</div>

						</div> -->

					</div>
				</div>
				<!--end::Menu-->

				<hr class="sidebar-menu-divider">

				@if( Session::get('department_id') )
					<div class="menu-item   menu-accordion">
						<!--begin:Menu link-->
						<a class="menu-link" href="{{ route('super-admin') }}" target="">
							<span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
								viewBox="0 0 20 20">
								<g fill="none" fill-rule="evenodd">
									<rect width="15" height="15" rx="4.227" fill="#84857e" />

									<path fill="#ffffff"
										d="M4.375,6.667a.208.208,0,0,0-.147.356l.478.478-.478.478a.208.208,0,0,0,0,.295l.478.478-.478.478a.208.208,0,0,0,0,.295L4.705,10l-.478.478a.208.208,0,0,0,0,.295l.478.478-.478.478a.208.208,0,0,0,0,.295l.478.478-.478.478a.209.209,0,0,0,.147.356h11.25a.208.208,0,0,0,.147-.356l-.478-.478.478-.478a.208.208,0,0,0,0-.295l-.478-.478.478-.478a.208.208,0,0,0,0-.295L15.295,10l.478-.478a.208.208,0,0,0,0-.295l-.478-.478.478-.478a.208.208,0,0,0,0-.295L15.295,7.5l.478-.478a.209.209,0,0,0-.147-.356Zm.5.417H15.122l-.269.269a.208.208,0,0,0,0,.295l.478.477-.478.478a.208.208,0,0,0,0,.295l.478.478-.478.479a.208.208,0,0,0,0,.295l.478.477-.478.478a.208.208,0,0,0,0,.295l.478.478-.478.478a.208.208,0,0,0,0,.295l.269.27H4.878l.269-.269a.208.208,0,0,0,0-.295l-.478-.478.478-.478a.208.208,0,0,0,0-.295l-.478-.478.478-.478a.208.208,0,0,0,0-.295L4.67,9.375,5.147,8.9a.208.208,0,0,0,0-.295L4.67,8.125l.478-.478a.208.208,0,0,0,0-.295l-.269-.269Zm1.58.417a.208.208,0,0,0-.208.208.626.626,0,0,1-.625.625.208.208,0,0,0-.208.208v2.917a.208.208,0,0,0,.208.208.626.626,0,0,1,.625.625.208.208,0,0,0,.208.208h7.083a.208.208,0,0,0,.208-.208.626.626,0,0,1,.625-.625.208.208,0,0,0,.208-.208V8.542a.208.208,0,0,0-.208-.208.626.626,0,0,1-.625-.625.208.208,0,0,0-.208-.208Zm.187.417h6.709a1.045,1.045,0,0,0,.812.812v2.542a1.045,1.045,0,0,0-.812.812H6.645a1.045,1.045,0,0,0-.812-.812V8.729a1.045,1.045,0,0,0,.812-.812Zm2.52.413a.208.208,0,0,0,0,.417h.417a.623.623,0,0,1,.588.423H9.166a.208.208,0,0,0,0,.417h1a.624.624,0,0,1-.584.41H9.166a.208.208,0,0,0-.208.208.009.009,0,0,0,0,0,.206.206,0,0,0,.06.151l1.25,1.25a.207.207,0,0,0,.147.061.211.211,0,0,0,.148-.061.208.208,0,0,0,0-.295l-.908-.908a1.039,1.039,0,0,0,.946-.82h.231a.208.208,0,1,0,0-.417H10.6a1.037,1.037,0,0,0-.193-.424h.422a.208.208,0,0,0,0-.417H9.166ZM7.083,9.375A.625.625,0,1,0,7.708,10,.626.626,0,0,0,7.083,9.375Zm5.833,0a.625.625,0,1,0,.625.625A.626.626,0,0,0,12.917,9.375Zm-5.833.417A.208.208,0,1,1,6.875,10,.208.208,0,0,1,7.083,9.792Zm5.833,0a.208.208,0,1,1-.208.208A.208.208,0,0,1,12.917,9.792Z" />
								</g>
								</svg>
									</span>
							<span class="menu-title">Main Dashboard</span>
						</a>
						<!--end:Menu link-->
					</div>

					<hr class="sidebar-menu-divider">
				@endif


			</div>
			<!--end::Menu wrapper-->
		</div>
	</div>
	<!--end::sidebar menu-->


	@elseif(auth::user()->userRole->role_name === 'superAdmin' && request()->is('super-admin/*'))
	<!--begin::sidebar menu-->
	<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
		<!--begin::Menu wrapper-->
		<div id="kt_app_sidebar_menu_wrapper"
			class="app-sidebar-wrapper hover-scroll-overlay-y my-5 overflow-hidden" data-kt-scroll="true"
			data-kt-scroll-activate="true" data-kt-scroll-height="auto"
			data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
			data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
			data-kt-scroll-save-state="true">
			<!--begin::Menu-->
			<div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6"
				id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
				<!--begin:Menu item-->
				<div class="menu-item menu-accordion {{ request()->routeIs('dashboard') ? 'here show' : '' }}">
					<!--begin:Menu link-->
					<a class="menu-link" href="{{ route('super-admin') }}" target="">
						<span class="menu-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
							viewBox="0 0 20 20">
							<path id="dashboard-20px"
								d="M11.224,4.742V8.722a2.5,2.5,0,0,1-2.514,2.5H4.749a2.5,2.5,0,0,1-2.494-2.5V4.753a2.5,2.5,0,0,1,2.494-2.5H8.72a2.522,2.522,0,0,1,2.5,2.492m11.031.01V8.722a2.524,2.524,0,0,1-2.494,2.5H15.78A2.566,2.566,0,0,1,14,10.5a2.5,2.5,0,0,1-.729-1.774V4.753a2.522,2.522,0,0,1,2.5-2.5h3.971a2.524,2.524,0,0,1,2.5,2.5m0,11.026v3.969a2.524,2.524,0,0,1-2.494,2.5H15.78a2.566,2.566,0,0,1-1.8-.708,2.481,2.481,0,0,1-.729-1.774V15.8a2.522,2.522,0,0,1,2.5-2.5h3.971a2.524,2.524,0,0,1,2.5,2.5Zm-11.031.01v3.969A2.524,2.524,0,0,1,8.71,22.25H4.749a2.483,2.483,0,0,1-2.494-2.492V15.788a2.524,2.524,0,0,1,2.494-2.513H8.72a2.566,2.566,0,0,1,1.775.738,2.512,2.512,0,0,1,.729,1.774"
								transform="translate(-2.256 -2.25)" fill="#84857e" />
							</svg></span>
						<span class="menu-title">Dashboard</span>
					</a>
					<!--end:Menu link-->
				</div>
				<!--end:Menu item-->

				<hr class="sidebar-menu-divider">

				<!--begin:Menu item-->
				<div data-kt-menu-trigger="click"
					class="menu-item menu-accordion {{ request()->routeIs('department-list' , 'add-department') ? 'here show' : '' }}">
					<!--begin:Menu link-->
					<span class="menu-link">
						<span class="menu-icon"> <svg xmlns="http://www.w3.org/2000/svg"
							xmlns:xlink="http://www.w3.org/1999/xlink" width="15" height="15"
							viewBox="0 0 20 20">
							<defs>
								<clipPath id="clip-path">
									<rect id="Rectangle_243" data-name="Rectangle 243" width="20" height="20"
										transform="translate(-93 99)" fill="#84857e" stroke="#707070"
										stroke-width="1" />
								</clipPath>
							</defs>
							<g id="Departments" transform="translate(93 -99)" clip-path="url(#clip-path)">
								<path id="_5ed3cfd6d7d03f5d13cdee6609ac64f1"
									data-name="5ed3cfd6d7d03f5d13cdee6609ac64f1"
									d="M19.828,19.608H17.614V3.835a.164.164,0,0,1-.044,0H3.246a.164.164,0,0,1-.044,0V19.608H.989a.4.4,0,1,0,0,.8H19.828a.4.4,0,1,0,0-.8ZM14.015,9.956h1.437v2.5H14.015v-2.5Zm1.437-4.372v2.5H14.015v-2.5h1.437Zm-3.671,0h1.433v2.5H11.781Zm0,4.372h1.433v2.5H11.781ZM9.035,5.585v2.5H7.6v-2.5H9.035ZM7.6,14.328H9.035v2.5H7.6v-2.5Zm0-1.87v-2.5H9.035v2.5ZM5.368,5.585H6.8v2.5H5.368v-2.5Zm0,4.372H6.8v2.5H5.368v-2.5Zm0,4.372H6.8v2.5H5.368v-2.5Zm5.5,0h4.58v5.28h-4.58ZM3.246,3.039H17.57a1.315,1.315,0,1,0,0-2.63H3.246a1.315,1.315,0,0,0,0,2.63Z"
									transform="translate(-93.408 98.592)" fill="#84857e" />
							</g>
							</svg>
						</span>
						<span class="menu-title">Departments</span>
						<!-- <span class="menu-arrow"></span> -->
						<span class="">
							<i class="bi bi-plus"></i>
						</span>
					</span>
					<!--end:Menu link-->
					<!--begin:Menu sub-->
					<div class="menu-sub menu-sub-accordion">
						<!--begin:Menu item-->
						<div class="menu-item">
							<!--begin:Menu link-->
							<a class="menu-link {{ request()->routeIs('department-list') ? 'active' : '' }}"
								href="{{ route('department-list') }}">
								<span class="menu-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
										viewBox="0 0 20 20" style="display: inline-block; vertical-align: middle;">
										<g transform="translate(0, 0)">
											<rect width="5.307" height="5.013" transform="translate(7.347 1.027)"
												fill="#84857e" />
											<rect width="5.307" height="5.013" transform="translate(0 13.961)"
												fill="#84857e" />
											<rect width="5.307" height="5.013" transform="translate(7.347 13.961)"
												fill="#84857e" />
											<rect width="5.307" height="5.013" transform="translate(14.694 13.961)"
												fill="#84857e" />
											<path
												d="M3.32,13.294V10.667H9.333V13.294h1.333V10.667H16.68V13.294H18.013V9.334H10.666V6.707H9.333V9.334H1.986v3.96Z"
												fill="#84857e" />
										</g>
									</svg>
								</span>
								<span class="menu-title">Department List</span>
							</a>
							<!--end:Menu link-->
						</div>
						<!--end:Menu item-->
						<!--begin:Menu item-->
						<div class="menu-item">
							<!--begin:Menu link-->
							<a class="menu-link {{ request()->routeIs('add-department') ? 'active' : '' }}"
								href="{{ route('add-department') }}">
								<span class="menu-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15"
										height="15" fill="none">

										<!-- Back page -->
										<path
											d="M3.333 5H1.667V16.667C1.667 17.583 2.417 18.333 3.333 18.333H15V16.667H3.333V5Z"
											fill="#84857e" />

										<!-- Front page -->
										<path d="M16.667 1.667H6.667C5.75 1.667 5 2.417 5 3.333V13.333C5 14.25 5.75 15 6.667 15H16.667
                                                        C17.583 15 18.333 14.25 18.333 13.333V3.333
                                                        C18.333 2.417 17.583 1.667 16.667 1.667Z" fill="#84857e" />

										<!-- Plus icon -->
										<path
											d="M12.5 4.167V7.5H15.833V9.167H12.5V12.5H10.833V9.167H7.5V7.5H10.833V4.167H12.5Z"
											fill="#ffffff" />

									</svg>

								</span>
								<span class="menu-title">Add Department</span>
							</a>
							<!--end:Menu link-->
						</div>
						<!--end:Menu item-->
					</div>
					<!--end:Menu sub-->
				</div>
				<!--end:Menu item-->

				<hr class="sidebar-menu-divider">

				<!--begin:Menu item-->
				<div class="menu-item menu-accordion {{ request()->routeIs('allPlayerList') ? 'here show' : '' }}">
					<!--begin:Menu link-->
					<a class="menu-link" href="{{ route('allPlayerList') }}" target="">
						<span class="menu-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20">
								<g fill="#84857e">
									<path
										d="M6.6,1.4 C6.4,1.2 6.4,1 6.4,0.8 C4.2,1.2 3.5,1.8 3.0,2.5 C2.5,3.2 2.3,4.1 2.2,5.5 C2.1,9.2 1.8,12.9 1.5,16.6 L3.8,16.5 C3.9,15.5 3.9,14.6 4.0,13.7 C4.3,12.3 4.7,10.9 4.7,10.9 C4.7,10.9 4.5,10.3 4.4,9.1 C4.4,7.9 3.8,2.9 6.6,1.4 Z"
										opacity="0.7" />
									<path
										d="M17.7,4.7 C17.3,2.7 16.2,1.8 15.0,1.2 C14.2,0.8 13.5,0.6 12.8,0.4 C12.7,0.5 12.6,0.7 12.6,0.8 C16.2,2.9 15.6,7.9 15.6,8.1 C15.6,8.1 15.1,9.5 15.4,11.2 C15.6,12.6 16.1,14.0 16.1,14.0 C16.2,15.0 16.3,15.9 16.3,16.8 L18.7,16.9 C18.3,12.8 18.0,8.7 17.7,4.7 Z"
										opacity="0.7" />

									<path
										d="M13.1,1.7 C11.3,2.6 9.1,2.6 7.3,1.7 C4.7,3.1 5.3,8.1 5.3,8.1 C5.3,8.1 6.0,9.5 5.3,20 L14.7,20 C14.1,9.5 14.8,8.1 14.8,8.1 C14.8,8.1 15.4,3.1 13.1,1.7 Z M10.4,4.4 C12.2,4.4 13.7,5.9 13.7,7.7 L13.2,7.7 C13.2,6.2 12.0,5.0 10.4,5.0 C8.8,5.0 7.6,6.2 7.6,7.7 L7.1,7.7 C7.1,5.9 8.6,4.4 10.4,4.4 Z" />

									<path
										d="M13.0,0.5 C12.6,0.2 11.5,0.6 10.0,0.6 C8.4,0.6 7.3,0.2 7.0,0.5 C6.9,0.6 6.9,0.7 6.9,0.8 C6.9,1.4 8.3,2.6 10.0,2.6 C11.7,2.6 13.1,1.4 13.1,0.8 C13.1,0.7 13.1,0.6 13.0,0.5 Z" />
								</g>
							</svg>

						</span>
						<span class="menu-title">Players</span>
					</a>
					<!--end:Menu link-->
				</div>
				<!--end:Menu item-->

				<!--begin:Menu item-->
				<div class="menu-item menu-accordion {{ request()->routeIs('allCoachList') ? 'here show' : '' }}">
					<!--begin:Menu link-->
					<a class="menu-link" href="{{ route('allCoachList') }}" target="">
						<span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
							viewBox="0 0 20 20">
							<g fill="#84857e">
								<path
									d="M7.666,8.334 h0.353 a3,3,0,0,0,1.647,2.35 V11.667 H8.343 l0.513,0.667 l2.143,2.787 l2.143,-2.787 L13.656,11.667 H12.333 v-0.983 a3,3,0,0,0,1.647,-2.35 h0.667 a1.333,1.333,0,0,0,1.333,-1.333 v-2 A4.667,4.667,0,0,0,6.333,5.001 v2 A1.333,1.333,0,0,0,7.666,8.334 Z M13.999,6.334 H14.333 a0.667,0.667,0,1,1,0,1.333 H13.999 Z M11.666,12.001 a0.667,0.667,0,0,1,-1.333,0 v-0.744 a2.937,2.937,0,0,0,1.333,0 Z M8.666,6.334 h2 a1.334,1.334,0,0,0,1.29,-1 h1.043 a0.334,0.334,0,0,1,0.334,0.333 V8 a2.333,2.333,0,1,1,-4.667,0 Z M7.666,6.334 h0.333 V7.667 H7.666 a0.667,0.667,0,1,1,0,-1.333 Z" />

								<path
									d="M14.666,11.667 h-0.17 l-0.513,0.667 l-2.617,3.4 A1,1,0,0,1,11,17.667 v1 h-1 v-2 a1,1,0,0,1,0.633,-0.93 l-2.617,-3.4 l-0.513,-0.667 h-0.17 a2.67,2.67,0,0,0,-2.667,2.667 v5.667 a0.334,0.334,0,0,0,0.333,0.333 h2 v-3 a0.333,0.333,0,1,1,0.667,0 v3.333 h6.666 v-3 a0.333,0.333,0,1,1,0.667,0 v3.333 h2 a0.334,0.334,0,0,0,0.333,-0.333 v-5.667 a2.67,2.67,0,0,0,-2.667,-2.667 Z" />
							</g>
							</svg></span>
						<span class="menu-title">Coaches</span>
					</a>
					<!--end:Menu link-->
				</div>
				<!--end:Menu item-->

				<hr class="sidebar-menu-divider">

				<!--begin:Menu item-->
				<div data-kt-menu-trigger="click"
					class="menu-item menu-accordion {{ request()->routeIs('userList', 'addUser', 'accessLevels') ? 'here show' : '' }}">
					<!--begin:Menu link-->
					<span class="menu-link">
						<span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
							viewBox="0 0 20 20" style="display: inline-block; vertical-align: middle;">
							<g transform="translate(-1, -1)">
								<path
									d="M11,11a5,5,0,1,0-5-5A5.015,5.015,0,0,0,11,11Zm0,2.5c-3.312,0-10,1.688-10,5v2.5h20v-2.5C21,15.188,14.313,13.5,11,13.5Z"
									fill="#84857e" />
							</g>
							</svg></span>
						<span class="menu-title">Users</span>
						<!-- <span class="menu-arrow"></span> -->
						<span class="">
							<i class="bi bi-plus"></i>
						</span>
					</span>
					<!--end:Menu link-->
					<!--begin:Menu sub-->
					<div class="menu-sub menu-sub-accordion">
						<!--begin:Menu item-->
						<div class="menu-item">
							<!--begin:Menu link-->
							<a class="menu-link {{ request()->routeIs('userList') ? 'active' : '' }}"
								href="{{ route('userList') }}">
								<span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15"
									height="15" viewBox="0 0 20 20">
									<g id="user-list-updated">
										<path
											d="M9.469,12.34a4.687,4.687,0,1,0-5.187,0A7.517,7.517,0,0,0,.739,15.062a.625.625,0,0,0,.511.985H12.5a.625.625,0,0,0,.511-.985A7.517,7.517,0,0,0,9.469,12.34Z"
											fill="#84857e" />
										<path
											d="M19.38,15.061a7.517,7.517,0,0,0-3.542-2.722,4.688,4.688,0,0,0-3.865-8.415.625.625,0,0,0-.332.975,5.924,5.924,0,0,1,.3,6.632.625.625,0,0,0,.16.828,8.844,8.844,0,0,1,.683.564.625.625,0,0,1,.035.036,8.753,8.753,0,0,1,1.991,2.727.625.625,0,0,0,.566.361h3.494a.625.625,0,0,0,.511-.985Z"
											fill="#84857e" />
									</g>
									</svg></span>
								<span class="menu-title">User List</span>
							</a>
							<!--end:Menu link-->
						</div>
						<!--end:Menu item-->

						<!--begin:Menu item-->
						<div class="menu-item">
							<!--begin:Menu link-->
							<a class="menu-link {{ request()->routeIs('addUser') ? 'active' : '' }}"
								href="{{ route('addUser') }}">
								<span class="menu-icon"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
									width="15" height="15" fill="none">

									<!-- Back page -->
									<path
										d="M3.333 5H1.667V16.667C1.667 17.583 2.417 18.333 3.333 18.333H15V16.667H3.333V5Z"
										fill="#84857e" />

									<!-- Front page -->
									<path d="M16.667 1.667H6.667C5.75 1.667 5 2.417 5 3.333V13.333C5 14.25 5.75 15 6.667 15H16.667
                                                            C17.583 15 18.333 14.25 18.333 13.333V3.333
                                                            C18.333 2.417 17.583 1.667 16.667 1.667Z" fill="#84857e" />
									<!-- Plus icon -->
									<path
										d="M12.5 4.167V7.5H15.833V9.167H12.5V12.5H10.833V9.167H7.5V7.5H10.833V4.167H12.5Z"
										fill="#ffffff" />

									</svg></span>
								<span class="menu-title">Add User</span>
							</a>
							<!--end:Menu link-->
						</div>

						<div class="menu-item">
							<a class="menu-link {{ request()->routeIs('accessLevels') ? 'active' : '' }}"
								href="{{ route('accessLevels') }}">
								<span class="menu-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15"
										height="15" fill="none">
										<!-- Shield body -->
										<path
											d="M12 2L4 5.5V11C4 15.418 7.582 19.617 12 21C16.418 19.617 20 15.418 20 11V5.5L12 2Z"
											fill="#84857e" />
										<!-- Lock body -->
										<rect x="9" y="11" width="6" height="5" rx="1" fill="#ffffff" />
										<!-- Lock shackle -->
										<path
											d="M10 11V9.5C10 8.672 10.672 8 11.5 8H12.5C13.328 8 14 8.672 14 9.5V11"
											stroke="#ffffff" stroke-width="1.5" fill="none" />
										<!-- Keyhole dot -->
										<circle cx="12" cy="13.5" r="0.8" fill="#84857e" />
									</svg>
								</span>
								<span class="menu-title">Access Levels</span>
							</a>
						</div>
						<!--end:Menu item-->
					</div>
					<!--end:Menu sub-->

				</div>
				<!--end:Menu item-->

				<hr class="sidebar-menu-divider">
				<!--begin:Menu item for setting -->

				<!-- <div class="menu-item"> -->
					<!--begin:Menu link-->
					<!-- <a class="menu-link" href="#">
						<span class="menu-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20">
								<g id="settings-updated">
									<path id="settings-path"
										d="M16.192,10.817A6.494,6.494,0,0,0,16.25,10a6.494,6.494,0,0,0-.058-.817L17.95,7.808a.42.42,0,0,0,.1-.533L16.383,4.392a.419.419,0,0,0-.508-.183L13.8,5.042a6.088,6.088,0,0,0-1.408-.817l-.317-2.208a.406.406,0,0,0-.408-.35H8.333a.406.406,0,0,0-.408.35L7.608,4.225A6.4,6.4,0,0,0,6.2,5.042L4.125,4.208a.406.406,0,0,0-.508.183L1.95,7.275a.411.411,0,0,0,.1.533L3.808,9.183A6.609,6.609,0,0,0,3.75,10a6.609,6.609,0,0,0,.058.817L2.05,12.192a.42.42,0,0,0-.1.533l1.667,2.883a.419.419,0,0,0,.508.183L6.2,14.958a6.088,6.088,0,0,0,1.408.817l.317,2.208a.406.406,0,0,0,.408.35h3.333a.406.406,0,0,0,.408-.35l.317-2.208a6.4,6.4,0,0,0,1.408-.817l2.075.833a.407.407,0,0,0,.508-.183l1.667-2.883a.42.42,0,0,0-.1-.533ZM10,12.917A2.917,2.917,0,1,1,12.917,10,2.92,2.92,0,0,1,10,12.917Z"
										fill="#84857e" />
								</g>
							</svg>
						</span>
						<span class="menu-title">Settings</span>
					</a> -->
					<!--end:Menu link-->
				<!-- </div> -->
				<!--end:Menu item-->

				<!-- <hr class="sidebar-menu-divider"> -->
			</div>
			<!--end::Menu-->

		</div>
		<!--end::Menu wrapper-->
	</div>
	<!--end::sidebar menu-->
	@endif
	@endif



	<!--begin::Footer-->


	<!-- <div class="app-sidebar-footer flex-column-auto px-6" id="kt_app_sidebar_footer">
		<a href="{{ route('logout') }}" data-method="post"
			class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden logout-btn text-nowrap px-3 h-40px w-100">
			{!! getIcon('exit-left', 'fs-2') !!} <span class="btn-label">Logout</span></a>
	</div> -->

	 <form action="{{ route('logout') }}" method="post">
		@csrf
		<div class="app-sidebar-footer flex-column-auto px-6" id="kt_app_sidebar_footer">
			<!-- Use a button with type="submit" -->
			<button type="submit"
				class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden logout-btn text-nowrap px-3 h-40px w-100">
				{!! getIcon('exit-left', 'fs-2') !!}
				<span class="btn-label">Logout</span>
			</button>
		</div>
	</form> 



	<!--end::Footer-->

</div>

<!--end::Sidebar-->
