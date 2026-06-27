<?php
// app/Livewire/Knowledge/Index.php
namespace App\Livewire\Knowledge;

use App\Models\KnowledgeBase;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
    ];

    public function render()
    {
        $articles = KnowledgeBase::published()
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->when($this->category, function ($query) {
                $query->byCategory($this->category);
            })
            ->latest()
            ->paginate($this->perPage);

        $categories = KnowledgeBase::published()
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('livewire.knowledge.index', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category']);
    }
}