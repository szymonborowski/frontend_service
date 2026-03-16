@props(['slides'])

<div
    x-data
    x-init="new Splide($refs.slider, {
        type: 'fade',
        rewind: true,
        autoplay: true,
        interval: 5000,
        pauseOnHover: true,
        arrows: {{ count($slides) > 1 ? 'true' : 'false' }},
        pagination: {{ count($slides) > 1 ? 'true' : 'false' }},
        heightRatio: 0.4,
    }).mount()"
    class="mb-8"
>
    <div x-ref="slider" class="splide rounded-lg shadow overflow-hidden">
        <div class="splide__track">
            <ul class="splide__list">
                @foreach($slides as $slide)
                    <li class="splide__slide">
                        @if($slide['type'] === 'image')
                            <div class="relative w-full h-full">
                                <img
                                    src="{{ $slide['image_url'] }}"
                                    alt="{{ $slide['title'] }}"
                                    class="w-full h-full object-cover"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                    <h2 class="text-2xl font-bold mb-2">{{ $slide['title'] }}</h2>
                                    @if($slide['link_url'])
                                        <a
                                            href="{{ $slide['link_url'] }}"
                                            class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg text-sm font-medium hover:bg-white/30 transition"
                                        >
                                            {{ $slide['link_text'] ?? 'Learn more' }}
                                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="w-full h-full">
                                {!! $slide['html_content'] !!}
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
