@extends('decora.portal.layout')

@section('content')
    <section class="detail-hero" aria-labelledby="page-title">
        <div class="detail-intro">
            <h1 id="page-title">@yield('title')</h1>
            <p>@yield('intro')</p>
            @yield('actions')
        </div>
        <img src="{{ asset('images/decora/'.trim($__env->yieldContent('photo')).'.webp') }}" alt="" fetchpriority="high">
    </section>

    <section class="detail-steps" aria-labelledby="steps-title">
        <h2 id="steps-title">@yield('steps-title')</h2>
        <ol class="workflow-steps">
            @yield('steps')
        </ol>
    </section>
@endsection
