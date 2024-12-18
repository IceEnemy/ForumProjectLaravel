@extends('layouts.nav')

@section('page-title', "Edit Community Settings")

@section('main-content')
<div class="container mt-4">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('community.show', $community->id) }}" class="btn text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
    <h1>Edit Community Settings</h1>

    <!-- Community Form -->
    <form action="{{ route('community.update', $community->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Community Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $community->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $community->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="rules" class="form-label">Rules</label>
            <textarea class="form-control" id="rules" name="rules" rows="3">{{ $community->rules }}</textarea>
        </div>
        <div class="mb-3">
            <label for="header_image" class="form-label">Header Image</label>
            <input type="file" class="form-control" id="header_image" name="header_image">
        </div>
        <div class="mb-3">
            <label for="profile_image" class="form-label">Profile Image</label>
            <input type="file" class="form-control" id="profile_image" name="profile_image">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>

    <hr class="my-4">

    <!-- Administrators Management -->
    <h2>Manage Administrators</h2>

    <!-- Search Administrators -->
    <form action="{{ route('community.settings', $community->id) }}" method="GET" class="mb-3">
        <input type="text" name="search_admins" class="form-control" placeholder="Search administrators..." value="{{ request('search_admins') }}">
        <button type="submit" class="btn btn-secondary mt-2">Search</button>
    </form>

    <!-- Current Administrators List -->
    <ul class="list-group">
        @foreach ($filteredAdmins as $admin)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $admin->username }}
                @if (auth()->id() === $community->owner_id)
                    <form action="{{ route('community.removeAdmin', [$community->id, $admin->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this administrator?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Remove</button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>

    <!-- Add Administrator -->
    <hr class="my-4">
    <h3>Community Members (Add Administrator)</h3>

    <!-- Search Members -->
    <form action="{{ route('community.settings', $community->id) }}" method="GET" class="mb-3">
        <input type="text" name="search_members" class="form-control" placeholder="Search members..." value="{{ request('search_members') }}">
        <button type="submit" class="btn btn-secondary mt-2">Search</button>
    </form>

    <!-- Search and Member List -->
    <ul class="list-group">
        @foreach ($filteredMembers as $member)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $member->username }}
                <form action="{{ route('community.addAdmin', [$community->id, $member->id]) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-primary">Add</button>
                </form>
            </li>
        @endforeach
    </ul>

    <!-- Delete Community -->
    @if (auth()->id() === $community->owner_id)
        <hr class="my-4">
        <h3>Delete Community</h3>
        <form action="{{ route('community.delete', $community->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this community? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mb-3">Delete Community</button>
        </form>
    @endif
</div>
@endsection
