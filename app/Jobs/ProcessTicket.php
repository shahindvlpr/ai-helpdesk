// app/Jobs/ProcessTicket.php
<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\AISuggestion;
use App\Services\AIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ProcessTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function handle(AIService $aiService)
    {
        // Generate summary
        $summary = $aiService->generateSummary($this->ticket->description);
        $this->ticket->update(['ai_summary' => $summary]);

        // Auto categorize
        $categorization = $aiService->categorizeTicket($this->ticket->description);
        
        // Store AI suggestion
        AISuggestion::create([
            'ticket_id' => $this->ticket->id,
            'suggestion' => json_encode($categorization),
            'type' => 'category',
            'confidence_score' => $categorization['confidence'] ?? 0,
        ]);

        // Sentiment analysis
        $sentiment = $aiService->analyzeSentiment($this->ticket->description);
        
        // Update metadata with sentiment
        $metadata = $this->ticket->metadata ?? [];
        $metadata['sentiment'] = $sentiment;
        $this->ticket->update(['metadata' => $metadata]);
    }
}