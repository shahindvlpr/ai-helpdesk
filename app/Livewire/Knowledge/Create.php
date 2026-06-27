<?php
// app/Livewire/Knowledge/Create.php
namespace App\Livewire\Knowledge;

use App\Models\KnowledgeBase;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $title;
    public $content;
    public $category;
    public $tags = [];
    public $is_published = true;

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|array',
            'is_published' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        $article = KnowledgeBase::create([
            'title' => $this->title,
            'content' => $this->content,
            'category' => $this->category,
            'tags' => $this->tags,
            'is_published' => $this->is_published,
            'created_by' => Auth::id(),
        ]);

        session()->flash('success', 'Article created successfully!');
        return redirect()->route('knowledge.show', $article);
    }

    public function render()
    {
        return view('livewire.knowledge.create');
    }
}