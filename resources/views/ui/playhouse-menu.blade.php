<div class="p-4 transition">
    <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Menu</h2>
    <p class="text-center text-gray-600 mb-5 font-semibold">
        You may purchase or rent items for today's visit
    </p>

    <div id="items-container">
        
    </div>

    {{-- reserved, this is for dynamic items displaying --}}
    {{-- @foreach ($items as $category => $categoryItems)
        <div class="max-w-full mx-auto border border-teal-500 rounded-lg pb-4 pr-4 mb-4">
            
            <h2 class="w-60 text-xl text-gray-50 font-bold px-4 py-2 bg-teal-600 rounded-br-xl rounded-tl-lg">
                {{ ucfirst($category) }} Items
            </h2>

            <div class="ml-4 mt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                
                @foreach ($categoryItems as $item)
                    <button 
                        type="button" 
                        value="{{ $item->id }}"
                        class="item w-full p-10 bg-white rounded-lg text-lg font-semibold hover:bg-teal-100 transition">
                        {{ $item->name }}
                    </button>
                @endforeach

            </div>
        </div>
    @endforeach --}}

    <p class="text-sm text-gray-500 mt-4 text-center">You can change selections anytime before review.</p>
</div>