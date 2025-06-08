<?php

use App\Models\ApplicationLanguage;
use Illuminate\Support\Facades\App;
use Livewire\Volt\Component;

new class extends Component {
    public function setUserLanguage(int $languageId) {
        auth()->user()->preferences->update(['language_id' => $languageId]);
        App::setLocale(ApplicationLanguage::find($languageId)->code);
    }
}
?>

<div>
    <p class="font-bold text-xl">Languages</p>
    <p>Configurate application language here.</p>

    <hr class="my-4 border-t-gray-300 border-t-2"/>

    <div class="flex flex-col gap-3 px-10">
        @php($userLanguage = auth()->user()->preferences->language_id)

        @foreach (ApplicationLanguage::all() as $language)
            <div class="flex flex-row items-center">
                <input type="radio" id="{{$language->code}}" value="{{$language->code}}" name="language"
                       {{$language->id == $userLanguage ? 'checked=true' : ''}} class="size-5"
                       wire:click="setUserLanguage({{$language->id}})">
                <label for="{{$language->code}}" class="ml-3">{{__($language->description)}}</label>
            </div>
        @endforeach
    </div>
</div>
