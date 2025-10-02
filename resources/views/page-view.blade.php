<x-landing-layout>
    <h1 class="text-3xl font-bold text-center my-8 pt-16"> {{ $page->title }} </h1>
    <div class="max-w-4xl mx-auto p-4">
        {!! $page->content !!}
    </div>
    <x-footer-section />
</x-landing-layout>