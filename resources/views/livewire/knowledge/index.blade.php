<!-- resources/views/livewire/knowledge/index.blade.php -->
<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Knowledge Base</h2>
        @auth
            @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
                <a href="{{ route('knowledge.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Article
                </a>
            @endif
        @endauth
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Search articles..." 
                       class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select wire:model.live="category" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="perPage" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>
        <div class="mt-3 text-right">
            <button wire:click="resetFilters" class="text-sm text-blue-600 hover:text-blue-800">
                Reset Filters
            </button>
        </div>
    </div>

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <a href="{{ route('knowledge.show', $article) }}" class="hover:text-blue-600">
                                {{ $article->title }}
                            </a>
                        </h3>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                            {{ $article->category }}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 text-sm mt-2 line-clamp-3">
                        {{ Str::limit(strip_tags($article->content), 150) }}
                    </p>
                    
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                        <div class="flex items-center space-x-2">
                            <span>👁️ {{ $article->views }}</span>
                            <span>👍 {{ $article->helpful_count }}</span>
                        </div>
                        <div>
                            <a href="{{ route('knowledge.show', $article) }}" class="text-blue-600 hover:text-blue-800">
                                Read More →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                <svg class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p>No articles found</p>
                <p class="text-sm mt-1">Try adjusting your search or filters</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $articles->links() }}
    </div>
</div>