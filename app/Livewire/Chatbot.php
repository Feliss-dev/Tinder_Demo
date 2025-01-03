<?php

namespace App\Livewire;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;



class Chatbot extends Component
{
    public $messages = [];
    public $userMessage = '';
    public $knowledgeBase = [];

    public function mount()
    {
        $this->knowledgeBase = json_decode(Storage::disk('public')->get('knowledge_base.json'), true);
    }

    public function sendMessage()
    {

        $this->messages[] = ['role' => 'user', 'text' => $this->userMessage];

        //Kiem tra tri thuc ban dau
        $botResponse = $this->getResponseFromKnowledgeBase($this->userMessage);

        if (!$botResponse) {
            $response = Gemini::geminiPro()->generateContent($this->userMessage);
            if (!$response->text()) {
                $botResponse = 'Error: Something went wrong';
            } else {
                $rawResponse = $response->text();


                //Xoa cac ky tu khong can thiet
                $cleanedResponse = preg_replace([
                    '/\*\*(.*?)\*\*/',         // Định dạng **bold**
                    '/__(.*?)__/',             // Định dạng __underline__
                    '/\*(.*?)\*/',             // Định dạng *italic*
                    '/```(.*?)```/s',          // Định dạng khối mã (code block)
                    '/\n{2,}/',                // Xóa các dòng trống liên tiếp
                ], [
                    '$1',               // Chuyển **bold** thành HTML <b>
                    '$1',               // Chuyển __underline__ thành HTML <u>
                    '$1',               // Chuyển *italic* thành HTML <i>
                    '$1',           // Chuyển code block thành HTML <pre>
                    "\n",                      // Giữ lại chỉ một dòng trống
                ], $rawResponse);

                $botResponse = trim($cleanedResponse);
            }
        }



        $this->messages[] = ['role' => 'bot', 'text' => $botResponse];

         // Lưu tin nhắn và phản hồi vào tệp JSON
    $chatLogs = json_decode(Storage::disk('public')->get('chat_logs.json'), true) ?? [];
    $chatLogs[] = [
        'user_message' => $this->userMessage,
        'bot_response' => $botResponse,
        'timestamp' => now(),
    ];
    Storage::disk('public')->put('chat_logs.json', json_encode($chatLogs, JSON_PRETTY_PRINT));

        $this->userMessage = '';
    }

    private function getResponseFromKnowledgeBase($question)
    {
        return $this->knowledgeBase[$question] ?? null;
    }

    public function render()
    {
        return view('livewire.chatbot')->with('messages', $this->messages);
    }
}
