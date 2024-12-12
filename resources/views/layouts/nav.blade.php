@extends('layouts.app')

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar border-end">
        <div class="position-sticky">
            <!-- Logo Section -->
            <div class="text-center py-3">
                <h2 class="logo-text">Readit</h2>
            </div>

            <!-- User Profile Section -->
            <div class="text-center py-4 profile_container mx-auto shadow-sm rounded">
                <div class="flex">
                    <img 
                        src="{{ Auth::user()->profile_picture ?? 'https://via.placeholder.com/150' }}" 
                        alt="Profile Picture" 
                        class="rounded-circle mb-3" 
                        style="width: 80px; height: 80px;"
                    >
                    <h4 class="mt-2" style="color: var(--dark_black); font-weight:bold;">Hello, {{ Auth::user()->username ?? 'User' }}</h4>
                </div>
                
                <a href="{{ route('profile.show') }}" class="btn btn-primary btn-sm mt-2">Edit Profile</a>
            </div>

            <!-- Navigation Links -->
            <ul class="nav flex-column mt-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="ic--round-home"></span> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#joinedCommunities" aria-expanded="false" aria-controls="joinedCommunities">
                        <span class="fluent--people-community-24-filled"></span> Joined Communities
                    </a>
                    <div class="collapse" id="joinedCommunities">
                        <ul class="list-unstyled ps-3 scrollable-container">
                            @forelse(Auth::user()->communities as $community)
                                <li class="joined-community-item">
                                    <a href="{{ route('community.show', $community->id) }}" class="text-decoration-none text-dark">
                                        {{ $community->name }}
                                    </a>
                                </li>
                            @empty
                                <li class="text-muted">No communities joined.</li>
                            @endforelse
                            {{-- <li class="joined-community-item">
                                <a href="#" class="text-decoration-none text-dark">
                                    Community 1
                                </a>
                            </li>
                            <li class="joined-community-item">
                                <a href="#" class="text-decoration-none text-dark">
                                    Community 2
                                </a>
                            </li>
                            <li class="joined-community-item">
                                <a href="#" class="text-decoration-none text-dark">
                                    Community 3
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">
                        <span class="material-symbols--logout"></span> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
        </div>
        @yield('main-content')
    </main>
</div>

@endsection

@push('styles')
<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }

    .d-flex {
        display: flex;
        flex: 1;
        width: 100%;
    }

    #sidebar {
        min-height: 100vh;
        min-width: 250px;
        max-width: 300px; 
        background-color: var(--white);
        color: var(--black);
        flex-shrink: 0;
        padding: 1rem;
    }

    main {
        flex-grow: 1; 
        overflow-x: auto; 
    }
    .profile_container{
        background-color: var(--tertiary_purple);
    }
    .nav-link {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .logo-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--main_purple);
        margin-bottom: 0;
    }
    .sidebar .nav-link {
        font-weight: 500;
        color: var(--black);
    }
    .sidebar .nav-link.active {
        font-weight: bold;
        color: var(--main_purple);
    }
    .sidebar .nav-link:hover {
        background-color: var(--gray);
    }
    .text-center img {
        object-fit: cover;
    }
    .btn-sm {
        font-size: 0.8rem;
        padding: 5px 10px;
    }
    .btn-primary {
        background-color: var(--main_purple);
        border-color: var(--main_purple);
    }
    .btn-primary:hover {
        background-color: var(--dark_purple);
        border-color: var(--dark_purple);
    }
    .scrollable-container {
        max-height: 200px;
        overflow-y: auto;
    }

    .joined-community-item {
        margin-left: 15px; 
        border-left: 3px solid var(--black); 
        padding: 10px;
        transition: background-color 0.3s, border-left-color 0.3s;
    }
    .joined-community-item:hover {
    background-color: var(--tertiary_purple); 
    border-left-color: var(--main_purple); 
    cursor: pointer; 
}

    .scrollable-container::-webkit-scrollbar {
        width: 8px;
    }

    .scrollable-container::-webkit-scrollbar-thumb {
        background-color: var(--gray);
        border-radius: 4px;
    }

    .scrollable-container::-webkit-scrollbar-thumb:hover {
        background-color: var(--dark_gray);
    }

    .joined-community-item a {
        display: block; 
        white-space: nowrap; 
        overflow: hidden; 
        text-overflow: ellipsis; 
        max-width: 180px; 
    }

</style>
@endpush
