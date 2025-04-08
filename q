[1mdiff --git a/app/Livewire/Chat/ConversationContainer.php b/app/Livewire/Chat/ConversationContainer.php[m
[1mindex 31b7bdb..04fc77e 100644[m
[1m--- a/app/Livewire/Chat/ConversationContainer.php[m
[1m+++ b/app/Livewire/Chat/ConversationContainer.php[m
[36m@@ -16,6 +16,8 @@[m [mclass ConversationContainer extends Component[m
     public $loadedMessages;[m
     public int $loadAmount = self::PAGINATE_STEP;[m
 [m
[32m+[m[32m    public array $uploadingMessages = [];[m
[32m+[m
     #[On('echo-private:conversation.{conversation.id},.conversation-sent')][m
     function listenBroadcastedMessage($event) {[m
         $this->dispatch('scroll-bottom');[m
