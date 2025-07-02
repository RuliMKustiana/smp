@extends('layouts.app')
@section('content')
    <h1>Buat Proyek Baru</h1>
    <hr>
    <form action="{{ route('pm.projects.store') }}" method="POST">
        @csrf
        @include('pm.projects._form')
    </form>
@endsection