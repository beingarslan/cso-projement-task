@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
    @include('users.includes.page-header')
    @include('users.includes.search-filters')
    @include('users.includes.users-table')
    @include('users.includes.user-modal')
    @include('users.includes.delete-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/user-manager.js') }}"></script>
@endpush
