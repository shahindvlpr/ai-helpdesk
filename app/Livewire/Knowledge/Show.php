<?php
// app/Livewire/Knowledge/Show.php
namespace App\Livewire\Knowledge;

use App\Models\KnowledgeBase;
use Livewire\Component;

class Show extends Component
{
    public $article;

    public function mount(KnowledgeBase $article)
    {
        $this->article = $article;
        $this->article->incrementViews();
    }

    public function markHelpful()
    {
        $this->article->markHelpful();
        session()->flash('success', 'Thank you for your feedback!');
    }

    public function markNotHelpful()
    {
        $this->article->markNotHelpful();
        session()->flash('success', 'Thank you for your feedback!');
    }

    public function render()
    {
        return view('livewire.knowledge.show');
    }
}