@if(session('success'))
    <div class="mb-6 p-4 rounded-xl border-2 flex items-center gap-3 animate-fade-in bg-green-200 border-green-400">
        <span class="text-2xl text-[var(--color-primary-mid-dark)]"><i class="fa-regular fa-trash-can"></i></span>
        <p class="font-semibold text-[var(--color-primary-mid-dark)]">{{ session('success') ?? 'Success! File Deleted' }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 rounded-xl border-2 flex items-center gap-3 animate-fade-in bg-red-100 border-red-500">
        <span class="text-2xl text-red-700"><i class="fa-solid fa-file-circle-xmark"></i></span>
        <p class="font-semibold text-red-600">{{ session('error') ?? 'Error deleting file' }}</p>
    </div>
@endif

<div class="rounded-2xl p-8 bg-gray-50/50 backdrop-blur-sm border-2 border-[var(--color-third-light)]">
    <!-- Pagination Controls -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold flex items-center gap-2 text-[var(--color-primary-mid-dark)]">
            <i class="fa-solid fa-images text-[var(--color-primary-full-dark)] mr-2"></i> Your Files ({{ $totalFiles }})
        </h2>
        
        <form method="GET" action="{{ route('files.index') }}" class="flex items-center gap-2">
            <label class="text-sm font-medium" style="color: #5D4E37;">Show:</label>
            <select 
                name="per_page" 
                onchange="this.form.submit()"
                class="px-3 py-2 rounded-lg border-2 border-[var(--color-primary-light)] text-sm"
            >
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                <option value="40" {{ $perPage == 40 ? 'selected' : '' }}>40</option>
                <option value="60" {{ $perPage == 60 ? 'selected' : '' }}>60</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-sm" style="color: #5D4E37;">files per page</span>
        </form>
    </div>

    @if(count($files) > 0)
        @foreach($files as $date => $dateFiles)
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-[var(--color-primary-mid-dark)]">
                    <i class="fa-regular fa-calendar text-amber-600"></i>
                    {{ $date === 'Unknown' ? 'Unknown Date' : \Carbon\Carbon::parse($date)->format('F j, Y') }}
                    <span class="text-sm font-normal text-[var(--color-primary-full-dark)]">({{ count($dateFiles) }} files)</span>
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($dateFiles as $file)
                        <div 
                            class="rounded-xl p-4 text-center backdrop-blur-lg bg-white/40 border-2 border-white shadow-lg transition-all duration-300"
                        >
                            <img 
                                src="{{ Storage::url($file) }}" 
                                alt="{{ basename($file) }}"
                                class="w-full h-36 object-cover rounded-lg mb-3"
                                style="border: 2px solid rgba(127, 255, 212, 0.3);"
                            >
                            <p class="text-sm font-medium mb-3 break-all" style="color: #5D4E37;">{{ basename($file) }}</p>
                            <form method="POST" action="/admin/files/delete/{{ urlencode($file) }}">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit"
                                    onclick="return confirm('Are you sure you want to delete this file?')"
                                    class="px-5 py-2 rounded-lg font-semibold bg-[var(--color-primary)] text-gray-50 transition-all duration-300 hover:scale-105"
                                >
                                    <i class="fa-solid fa-trash-can text-gray-50 mr-2"></i> Delete
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Pagination Links -->
        @if($paginatedFiles->hasPages())
            <div class="mt-8 flex justify-center">
                <nav class="flex justify-between items-center gap-2">
                    {{-- Previous Page Link --}}
                    @if($paginatedFiles->onFirstPage())
                        <span class="px-4 py-2 rounded-lg bg-[var(--color-primary-full-dark)] text-gray-50 transition-all duration-300 hover:bg[var(--color-primary-light)] cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left"></i> Previous
                        </span>
                    @else
                        <a 
                            href="{{ $paginatedFiles->previousPageUrl() }}&per_page={{ $perPage }}"
                            class="px-4 py-2 rounded-lg bg-[var(--color-primary)] text-gray-50 transition-all duration-300 hover:bg[var(--color-primary-light)]"
                        >
                            <i class="fa-solid fa-chevron-left"></i> Previous
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    <div class="hidden sm:flex justify-between gap-2">
                        @foreach($paginatedFiles->getUrlRange(max(1, $paginatedFiles->currentPage() - 2), min($paginatedFiles->lastPage(), $paginatedFiles->currentPage() + 2)) as $page => $url)
                            @if($page == $paginatedFiles->currentPage())
                                <span 
                                    class="px-4 py-2 rounded-lg bg-[var(--color-third)] text-white font-semibold"
                                >
                                    {{ $page }}
                                </span>
                            @else
                                <a 
                                    href="{{ $url }}&per_page={{ $perPage }}"
                                    class="px-4 py-2 rounded-lg transition-all duration-300 bg-[var(--color-accent-secondary)] hover:scale-105"
                                >
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                    
                    {{-- Next Page Link --}}
                    @if($paginatedFiles->hasMorePages())
                        <a 
                            href="{{ $paginatedFiles->nextPageUrl() }}&per_page={{ $perPage }}"
                            class="px-4 py-2 rounded-lg bg-[var(--color-primary)] text-gray-50 transition-all duration-300 hover:bg[var(--color-primary-light)]"
                        >
                            Next <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 rounded-lg bg-[var(--color-primary-full-dark)] text-gray-50 transition-all duration-300 hover:bg[var(--color-primary-light)] cursor-not-allowed">
                            Next <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    @endif
                </nav>
            </div>
        @endif

        {{-- Showing X to Y of Z results --}}
        <div class="mt-4 text-center text-sm" style="color: #5D4E37;">
            Showing {{ ($paginatedFiles->currentPage() - 1) * $perPage + 1 }} to {{ min($paginatedFiles->currentPage() * $perPage, $totalFiles) }} of {{ $totalFiles }} files
        </div>
    @else
        <div class="text-center py-12" style="color: #5D4E37;">
            <div class="text-6xl mb-4">📭</div>
            <p class="text-lg">No files uploaded yet. Start by uploading your first file!</p>
        </div>
    @endif
</div>

