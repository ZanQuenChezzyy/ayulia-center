@extends('landing.layouts.master')

@section('title', 'Ayulia Training Center')

@section('content')
    @include('landing.page.partials.hero')

    @include('landing.page.partials.features')

    @include('landing.page.partials.inspector')

    @include('landing.page.partials.faq')

    @include('landing.page.partials.contact')
@endsection

@push('scripts')
    @include('landing.page.scripts.store-pesan')
@endpush
