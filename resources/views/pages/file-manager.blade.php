@extends('layout.basic')

@section('title', 'File Manager')

@section('styles')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        .grid > div { animation: fadeIn 0.5s ease forwards; }
        .grid > div:nth-child(1) { animation-delay: 0.1s; }
        .grid > div:nth-child(2) { animation-delay: 0.2s; }
        .grid > div:nth-child(3) { animation-delay: 0.3s; }
        .grid > div:nth-child(4) { animation-delay: 0.4s; }
        .grid > div:nth-child(5) { animation-delay: 0.5s; }
        .grid > div:nth-child(6) { animation-delay: 0.6s; }
    </style>
@endsection

@section('contents')
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold mb-2" style="color: #2C5530;"><i class="fa-regular fa-folder-open text-yellow-600 mr-2"></i>File Manager</h1>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl border-2 flex items-center gap-3 animate-fade-in" style="background: linear-gradient(135deg, #7FFFD4, #5FD3B3); border-color: #2C5530;">
            <span class="text-2xl">✅</span>
            <p class="font-semibold" style="color: #2C5530;">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl border-2 flex items-center gap-3 animate-fade-in" style="background: linear-gradient(135deg, #FF6B6B, #EE5A5A); border-color: #8B0000;">
            <span class="text-2xl">❌</span>
            <p class="font-semibold text-white">{{ session('error') }}</p>
        </div>
    @endif

    <div class="rounded-2xl p-8 bg-white/70 backdrop-blur-sm border-2" style="border-color: rgba(255, 191, 0, 0.4); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
        <h2 class="text-xl font-semibold mb-6 flex items-center gap-2" style="color: #2C5530;">
            <i class="fa-solid fa-images text-amber-600 mr-2"></i> Your Files ({{ count($files) }})
        </h2>

        @if(count($files) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($files as $file)
                    <div 
                        class="rounded-xl p-4 text-center border-2 border-transparent transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                        style="background: linear-gradient(145deg, #FFFFFF, rgba(255, 191, 0, 0.1)); animation: fadeIn 0.5s ease forwards;"
                    >
                        <img 
                            src="{{ Storage::url($file) }}" 
                            alt="{{ basename($file) }}"
                            class="w-full h-36 object-cover rounded-lg mb-3"
                            style="border: 2px solid rgba(127, 255, 212, 0.3);"
                        >
                        <p class="text-sm font-medium mb-3 break-all" style="color: #5D4E37;">{{$file}}</p>
                        <form method="POST" action="/admin/files/delete/{{ urlencode($file) }}">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                onclick="return confirm('Are you sure you want to delete this file?')"
                                class="px-5 py-2 rounded-lg font-semibold transition-all duration-300 hover:scale-105"
                                style="background: linear-gradient(135deg, #FFBF00, #E6A800); color: #5D4E37;"
                            >
                                <i class="fa-solid fa-trash-can text-gray-500 mr-2"></i> Delete
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12" style="color: #5D4E37;">
                <div class="text-6xl mb-4">📭</div>
                <p class="text-lg">No files uploaded yet. Start by uploading your first file!</p>
            </div>
        @endif
    </div>
@endsection