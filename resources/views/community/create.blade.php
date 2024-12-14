@extends('layouts.app')

@section('title', 'Create Community')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-header text-center">
                    <h3>Create Community</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Community Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="header_image" class="form-label">Header Image</label>
                            <input type="file" name="header_image" id="header_image" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Create Community</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
