<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Recommendations extends Component
{
    public $recommendations = [];
    public $error = null;

    public function viewProfile($userId) {
        $this->dispatch('viewProfile', $userId);
    }

    public function mount(){
        $this->fetchRecommendations();
    }

    public function fetchRecommendations(){
        $userId = auth()->user()->id;
        try {
            $response = Http::post("http://host.docker.internal:5000/recommend", [
                'user_id' => $userId,
            ]);

            Log::info('Sending request to FastAPI', ['user_id' => $userId]);
            Log::info('API response: ' . $response->body());

            if ($response->ok()) {
                $this->recommendations = $response->json(); // Lưu danh sách recommendations
                $this->error = null;
            } else {
                $this->recommendations = [];
                $this->error = 'Failed to fetch recommendations. Server returned an error.';
            }
        } catch (\Exception $e) {
            Log::error('Error fetching recommendations: ' . $e->getMessage());
            $this->recommendations = [];
            $this->error = 'An error occurred while fetching recommendations.';
        }
    }
    public function render()
    {
        return view('livewire.recommendations', [
            'recommendations' => $this->recommendations,
        ]);
    }
}
