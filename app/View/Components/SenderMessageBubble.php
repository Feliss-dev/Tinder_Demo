<?php

namespace App\View\Components;

use App\Models\Message;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SenderMessageBubble extends Component
{
    public function __construct(public Message $message)
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.sender-message-bubble');
    }
}
