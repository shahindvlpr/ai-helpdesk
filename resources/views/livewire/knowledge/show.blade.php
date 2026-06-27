<!-- resources/views/livewire/knowledge/show.blade.php -->
<div>
    <div class="mb-4">
        <a href="{{ route('knowledge.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Knowledge Base
        </a>
    </div>

    @if(session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $article->title }}</h1>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                        {{ $article->category }}
                    </span>
                    @if($article->tags)
                        @foreach($article->tags as $tag)
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="text-sm text-gray-500">
                👁️ {{ $article->views }} views
            </div>
        </div>

        <div class="prose max-w-none mt-4">
            {!! nl2br(e($article->content)) !!}
        </div>

        <div class="mt-6 pt-6 border-t">
            <p class="text-sm text-gray-500 mb-3">Was this article helpful?</p>
            <div class="flex space-x-2">
                <button wire:click="markHelpful" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    👍 Yes ({{ $article->helpful_count }})
                </button>
                <button wire:click="markNotHelpful" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    👎 No ({{ $article->not_helpful_count }})
                </button>
            </div>
        </div>
    </div>
</div>