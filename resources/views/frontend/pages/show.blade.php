@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <nav class="text-sm text-gray-500 mb-4"><a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span> <span style="color: var(--muni-red-dark);">{{ $page->title }}</span></nav>
    <article class="bg-white rounded-sm shadow-sm border border-gray-200 p-8">
        <h1 class="text-3xl font-bold mb-4" style="font-family:var(--font-heading); color: var(--muni-red-dark);">{{ $page->title }}</h1>
        <div class="border-b-2 mb-6" style="border-color: var(--muni-gold); width:60px; height:3px;"></div>
        <div class="prose max-w-none text-gray-700" style="line-height:1.8;">
            {!! $page->content !!}
        </div>
    </article>
</div>
@endsection
