

<!--begin::Header-->
<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}"
	data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}"
	data-kt-sticky-animation="false">
	<!--begin::Header container-->
	<div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
		id="kt_app_header_container">
		<!--begin::Sidebar mobile toggle-->
		<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
			<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">{!!
				getIcon('abstract-14', 'fs-2 fs-md-1') !!}</div>
		</div>
		<!--end::Sidebar mobile toggle-->
		<!--begin::Mobile logo-->
		<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
			<a href="" class="d-lg-none">
				<img alt="Logo" src="{{ asset('assets/images/logos/deccan-logo.png') }}" class="h-55px" />
			</a>
		</div>
		<!--end::Mobile logo-->
		
		<!--begin::Header wrapper-->
		<div class="d-flex align-items-center justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<!--begin::Title-->
				<h1 class="page-heading d-flex text-gray-900 fw-bold fs-1 flex-column justify-content-center my-0">
					@yield('pagetitle')
					<!-- <h1>hello</h1> -->
				</h1>
				<!--end::Title-->
			</div>
			<!--end::Page title-->

			<!--Header Navbar  -->
			<!--begin::Navbar-->
			<div class="app-navbar flex-shrink-0">

				<!--begin::User menu-->
				<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
					<!--begin::Menu wrapper-->
					<div class="cursor-pointer symbol symbol-35px"
						data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
						data-kt-menu-placement="bottom-end">
						@if(Auth::user()->profile_photo_url)
						<img src="{{ \Auth::user()->profile_photo_url }}" class="rounded-3" alt="user" />
						@else
						<div
							class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', Auth::user()->name) }}">
							{{ substr(Auth::user()->name, 0, 1) }}
						</div>
						@endif
					</div>
					<!--begin::User account menu-->
					<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
						data-kt-menu="true">
						<!--begin::Menu item-->
						<div class="menu-item px-3">
							<div class="menu-content d-flex align-items-center px-3">
								<!--begin::Avatar-->
								<div class="symbol symbol-50px me-5">
									@if(Auth::user()->profile_photo_url)
									<img alt="Logo" src="{{ Auth::user()->profile_photo_url }}" />
									@else
									<div
										class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', Auth::user()->name) }}">
										{{ substr(Auth::user()->name, 0, 1) }}
									</div>
									@endif
								</div>
								<!--end::Avatar-->
								<!--begin::Username-->
								<div class="d-flex flex-column">
									<div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name}}</div>
									<a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{
										Auth::user()->email }}</a>
								</div>
								<!--end::Username-->
							</div>
						</div>
						<!--end::Menu item-->
						<!--begin::Menu separator-->
						<div class="separator my-2"></div>
						<!--end::Menu separator-->
						<!--begin::Menu item-->
						<div class="menu-item px-5">
							<a href="{{ Route('userProfile')}}" class="menu-link px-5">My Profile</a>
						</div>
						<!--end::Menu item-->

						<!--begin::Menu separator-->
						<div class="separator my-2"></div>
						<!--end::Menu separator-->
						<!--begin::Menu item-->
						<div class="menu-item px-5">
							<a class="button-ajax menu-link px-5" href="#" data-action="{{ route('logout') }}"
								data-method="post" data-csrf="{{ csrf_token() }}" data-reload="true">
								Sign Out
							</a>
						</div>
						<!--end::Menu item-->
					</div>
					<!--end::User account menu-->
					<!--end::Menu wrapper-->
				</div>
				<!--end::User menu-->
				<!--begin::Header menu toggle-->
				<!-- <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
					<div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px"
						id="kt_app_header_menu_toggle">{!! getIcon('element-4', 'fs-1') !!}</div>
				</div> -->
				<!--end::Header menu toggle-->
				<!--begin::Aside toggle-->
				<!--end::Header menu toggle-->
			</div>
			<!--end::Navbar-->

			<!--Header Navbar  -->
		</div>
		<!--end::Header wrapper-->
	</div>
	<!--end::Header container-->
</div>
<!--end::Header-->