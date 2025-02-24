<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class Suggestion extends Component
{
    public $suggestions = [];
    public $error = null;

    public function mount(){
        $this->fetchSuggestions();
    }

    public function fetchSuggestions(){
        $userId = auth()->user()->id; // Lấy ID của user hiện tại
        try {
            $response = Http::post('http://localhost:5000/suggestions', [
                'user_id' => $userId,
            ]);
            Log::info('Sending request to FastAPI', ['user_id' => $userId]);
            Log::info('API response: ' . $response->body());

            if ($response->ok()) {
                $data = $response->json();
                $this->suggestions = $data['suggestions'] ?? []; // Explicitly access suggestions key
                $this->error = null;
            } else {
                $this->suggestions = [];
                $this->error = 'Failed to fetch recommendations. Server returned an error.';
            }

        } catch (\Exception $e) {
            Log::error('Error fetching recommendations: ' . $e->getMessage());
            $this->suggestions = [];
            $this->error = 'An error occurred while fetching recommendations.';
        }
    }
    public function render()
    {
        return view('livewire.suggestion', [
            'suggestions' => $this->suggestions,
        ]);
    }
}
