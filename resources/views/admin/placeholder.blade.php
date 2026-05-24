@php $header = $section ?? 'Section' @endphp
@extends('layouts.admin')

@section('title', $section . ' - Admin - ' . config('app.name'))

@section('header', $section)

@section('content')
<div class="border border-neutral-200 bg-white p-16 text-center">
    <h3 class="text-lg font-bold tracking-tight mb-2">{{ $section }}</h3>
    <p class="text-sm text-neutral-500">This section is under development.</p>
</div>
@endsection
