<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="flex flex-col w-full h-screen overflow-hidden">
    <livewire:layout.admin_navigation />

    <!-- Main Layout -->
    <div class="w-full flex-auto overflow-hidden grid grid-cols-5" x-data="{ 'selectedTab': 'management_users' }" style="background-size: 40px 40px; background: white radial-gradient(lightgray 2px, transparent 0) -19px -19px;">
        <livewire:admin.admin-sidebar />

        <div class="col-span-4">
            <livewire:admin.admin-tab-panel />
        </div>
    </div>

{{--	<section id="content">--}}
{{--        <livewire:admin.admin-navbar />--}}

{{--		<!-- MAIN -->--}}
{{--		<main>--}}
{{--			<div class="head-title">--}}
{{--				<div class="left">--}}
{{--					<h1>Dashboard</h1>--}}
{{--					<ul class="breadcrumb">--}}
{{--						<li>--}}
{{--							<a href="#">Dashboard</a>--}}
{{--						</li>--}}
{{--						<li><i class='bx bx-chevron-right' ></i></li>--}}
{{--						<li>--}}
{{--							<a class="active" href="#">Home</a>--}}
{{--						</li>--}}
{{--					</ul>--}}
{{--				</div>--}}
{{--				<a href="#" class="btn-download">--}}
{{--					<i class='bx bxs-cloud-download' ></i>--}}
{{--					<span class="text">Download PDF</span>--}}
{{--				</a>--}}
{{--			</div>--}}
{{--            <!-- Dashboard Info -->--}}
{{--            <livewire:admin.dashboard-info />--}}




{{--			<livewire:admin.user-table />--}}


{{--		</main>--}}
{{--		<!-- MAIN -->--}}
{{--	</section>--}}
{{--	<!-- CONTENT -->--}}


{{--	<script src="{{asset('adminhub/script.js')}}"></script>--}}
</body>
</html>
