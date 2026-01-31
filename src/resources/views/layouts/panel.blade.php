@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-6">
            {{-- Sidebar --}}
            <aside class="lg:min-w-56 lg:max-w-72 flex-shrink-0">
                <x-panel.sidebar />
            </aside>

            {{-- Main Content --}}
            <main class="flex-1">
                <div class="bg-white rounded-lg shadow p-6">
                    @if(session('success'))
                        <x-panel.alert type="success" :message="session('success')" />
                    @endif

                    @if(session('error'))
                        <x-panel.alert type="error" :message="session('error')" />
                    @endif

                    @if($errors->any())
                        <x-panel.alert type="error">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-panel.alert>
                    @endif

                    <h1 class="text-2xl font-bold text-gray-900 mb-6">@yield('panel-title')</h1>

                    @yield('panel-content')
                </div>
            </main>
        </div>
    </div>
@endsection
