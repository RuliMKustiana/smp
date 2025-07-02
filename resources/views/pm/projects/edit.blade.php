@extends('layouts.app')
@section('content')
    <h1>Edit Proyek: {{ $project->name }}</h1>
    <hr>
    <form action="{{ route('pm.projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')
        @include('pm.projects._form')
    </form>
@endsection