<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class RecommendationInfoModal extends Component
{
    public ?User $user;
    public ?array $infos;

    #[On("recommendation-info-modal-open")]
    public function open(User $user, array $infos) {
        $this->user = $user;
        $this->infos = $infos;
    }

    #[On("recommendation-info-modal-close")]
    public function close() {
        $this->user = null;
        $this->infos = null;
    }

    public function render()
    {
        return view('livewire.recommendation-info-modal');
    }
}
