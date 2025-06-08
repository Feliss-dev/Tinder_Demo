<?php

namespace App\Livewire;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class Recommendations extends Component
{
    const int REQUEST_LIMIT = 10;

    public $recommendations = [];
    public $error = null;

    public function mount() {
        $this->fetchRecommendations();
    }

    #[On("recommendation-request")]
    public function requestRecommendations() {
        $this->fetchRecommendations();
    }

    public function fetchRecommendations() {
        $userId = auth()->user()->id;
        try {
            $response = Http::post(env('MATCHING_AI_REQUEST_URL'), [
                'user_id' => $userId,
                'limit' => static::REQUEST_LIMIT,
            ]);

            if ($response->ok()) {
                $this->recommendations = $response->json()['recommendations'];
                $this->error = null;
            } else {
                $this->recommendations = [];
                $this->error = __('recommendation.error.error_response');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching recommendations: ' . $e->getMessage());
            $this->recommendations = [];
            $this->error = __('recommendation.error.internal_error');
        }
    }
    public function render()
    {
        return view('livewire.recommendations');
    }
}
