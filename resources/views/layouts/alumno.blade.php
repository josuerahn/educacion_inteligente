@extends('layouts.app')

@section('title','Mi Dashboard')

@section('content')
  @livewire('student.student-dashboard')
  {{-- o <livewire:student.student-dashboard /> --}}
@endsection
