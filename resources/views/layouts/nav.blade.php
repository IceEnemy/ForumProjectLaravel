@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky">
                <!-- User Profile Section -->
                <div class="text-center py-4 border-bottom">
                    <img 
                        src="{{ Auth::user()->profile_picture ?? 'https://via.placeholder.com/150' }}" 
                        alt="Profile Picture" 
                        class="img-thumbnail rounded-circle mb-3" 
                        style="width: 100px; height: 100px;"
                    >
                    <h4 class="mt-2">Hello, {{ Auth::user()->username ?? 'User' }}</h4>
                    <a href="{{ route('profile.show') }}" class="btn btn-primary btn-sm mt-2">Edit Profile</a>
                </div>

                <!-- Navigation Links -->
                <ul class="nav flex-column mt-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.show') }}">
                            <i class="bi bi-person"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#joinedCommunities" aria-expanded="false" aria-controls="joinedCommunities">
                            <i class="bi bi-people"></i> Joined Communities
                        </a>
                        <div class="collapse" id="joinedCommunities">
                            <ul class="list-unstyled ps-3">
                                @forelse(Auth::user()->communities as $community)
                                    <li>
                                        <a href="{{ route('community.show', $community->id) }}" class="text-decoration-none text-dark">
                                            {{ $community->name }}
                                        </a>
                                    </li>
                                @empty
                                    <li>No communities joined.</li>
                                @endforelse
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-gear"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
            </div>
            @yield('main-content')
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        font-family: 'Montserrat', sans-serif;
    }
    #sidebar {
        min-height: 100vh;
        background-color: var(--white);
        color: var(--black);
    }
    .sidebar .nav-link {
        font-weight: 500;
        color: #333;
    }
    .sidebar .nav-link.active {
        font-weight: bold;
        color: #007bff;
    }
    .sidebar .nav-link:hover {
        background-color: #f8f9fa;
    }
    .text-center img {
        object-fit: cover;
    }
    .btn-sm {
        font-size: 0.8rem;
        padding: 5px 10px;
    }
</style>
@endpush
