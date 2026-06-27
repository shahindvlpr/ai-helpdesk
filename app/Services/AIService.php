// app/Services/AIService.php
<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use App\Models\Ticket;
use App\Models\KnowledgeBase;

class AIService
{
    /**
     * Generate ticket summary using AI
     */
    public function generateSummary(string $content): string
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful support assistant. Summarize this ticket in 2-3 sentences. Keep it concise and professional.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $content
                    ]
                ],
                'max_tokens' => 150,
                'temperature' => 0.7,
            ]);

            return $response->choices[0]->message->content ?? '';
        } catch (\Exception $e) {
            \Log::error('AI Summary Generation Failed: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Auto-categorize ticket using AI
     */
    public function categorizeTicket(string $description): array
    {
        try {
            $categories = ['Technical', 'Billing', 'Account', 'General', 'Feature Request', 'Bug Report', 'Support'];
            
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Categorize this support ticket into one of: " . implode(', ', $categories) . 
                                    ". Also provide a confidence score between 0-1. Return as JSON."
                    ],
                    [
                        'role' => 'user',
                        'content' => $description
                    ]
                ],
                'max_tokens' => 100,
                'temperature' => 0.3,
            ]);

            $result = json_decode($response->choices[0]->message->content ?? '{}', true);
            
            return [
                'category' => $result['category'] ?? 'General',
                'confidence' => $result['confidence'] ?? 0.5
            ];
        } catch (\Exception $e) {
            \Log::error('AI Categorization Failed: ' . $e->getMessage());
            return ['category' => 'General', 'confidence' => 0];
        }
    }

    /**
     * Suggest reply for ticket
     */
    public function suggestReply(string $ticketContent, string $messageHistory): string
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional support agent. Suggest a helpful and professional reply for this ticket. Be empathetic and solution-oriented.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Ticket: {$ticketContent}\n\nPrevious Messages: {$messageHistory}\n\nProvide a suggested reply:"
                    ]
                ],
                'max_tokens' => 300,
                'temperature' => 0.7,
            ]);

            return $response->choices[0]->message->content ?? '';
        } catch (\Exception $e) {
            \Log::error('AI Suggestion Failed: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Search knowledge base using AI
     */
    public function searchKnowledgeBase(string $query): array
    {
        try {
            // First, try to find relevant articles in knowledge base
            $articles = KnowledgeBase::published()
                ->search($query)
                ->take(5)
                ->get();

            if ($articles->isNotEmpty()) {
                return $articles->toArray();
            }

            // If no articles found, use AI to generate a suggestion
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a knowledge base expert. Provide a brief solution or guidance for this customer query.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $query
                    ]
                ],
                'max_tokens' => 200,
                'temperature' => 0.7,
            ]);

            return [
                'ai_generated' => true,
                'content' => $response->choices[0]->message->content ?? ''
            ];
        } catch (\Exception $e) {
            \Log::error('Knowledge Base Search Failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Sentiment analysis of ticket
     */
    public function analyzeSentiment(string $text): array
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Analyze the sentiment of this text. Return JSON with sentiment (positive, neutral, negative) and score (0-1).'
                    ],
                    [
                        'role' => 'user',
                        'content' => $text
                    ]
                ],
                'max_tokens' => 100,
                'temperature' => 0.3,
            ]);

            return json_decode($response->choices[0]->message->content ?? '{}', true);
        } catch (\Exception $e) {
            \Log::error('Sentiment Analysis Failed: ' . $e->getMessage());
            return ['sentiment' => 'neutral', 'score' => 0.5];
        }
    }

    /**
     * Generate knowledge base article from ticket
     */
    public function generateKnowledgeArticle(Ticket $ticket): string
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Based on this support ticket, create a knowledge base article. Include problem description, solution steps, and prevention tips.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Ticket Subject: {$ticket->subject}\nDescription: {$ticket->description}\nResolution: {$ticket->ai_summary}"
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            return $response->choices[0]->message->content ?? '';
        } catch (\Exception $e) {
            \Log::error('Knowledge Article Generation Failed: ' . $e->getMessage());
            return '';
        }
    }
}