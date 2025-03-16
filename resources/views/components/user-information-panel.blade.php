<div {{ $attributes }}>
    <div class="flex flex-row">
        <strong>Email:</strong>
        <p class="ml-2">{{$user->email}}</p>
    </div>

    <div class="flex flex-row">
        <strong>Birthdate:</strong>
        <p class="ml-2">{{$user->birth_date}}</p>
    </div>

    <div class="flex flex-row">
        <strong class="mr-2">Gender:</strong>

        @if ($user->genders->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                @foreach ($user->genders as $gender)
                    <span class="inline-block bg-blue-100 text-blue-700 border border-blue-300 rounded-full px-3 py-1 text-sm font-semibold">
                        {{ $gender->name }}
                    </span>
                @endforeach
            </div>
        @else
            <p>No data available</p>
        @endif
    </div>

    <strong>Bio:</strong>
    <p>{{$user->bio}}</p>

    <div class="flex flex-row">
        <strong class="mr-2">Interests:</strong>

        @if ($user->interests->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                @foreach ($user->interests as $interest)
                    <span class="inline-block bg-green-100 text-green-700 border border-green-300 rounded-full px-3 py-1 text-sm font-semibold">
                        {{ $interest->name }}
                    </span>
                @endforeach
            </div>
        @else
            <p>No data available</p>
        @endif
    </div>

    <div class="flex flex-row">
        <strong class="mr-2">Desired Gender:</strong>
        @if ($user->desiredGenders->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                @foreach ($user->desiredGenders as $desiredGender)
                    <span class="inline-block bg-pink-100 text-pink-700 border border-pink-300 rounded-full px-3 py-1 text-sm font-semibold">
                        {{ $desiredGender->name }}
                    </span>
                @endforeach
            </div>
        @else
            <p>No data available</p>
        @endif
    </div>

    <div class="flex flex-row">
        <strong class="mr-2">Dating Goal:</strong>
        @if ($user->datingGoals->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                @foreach ($user->datingGoals as $datingGoal)
                    <span class="text-xl text-green-700 font-medium capitalize">
                        {{ $datingGoal->name }} 👋
                    </span>
                @endforeach
            </div>
        @else
            <p>No data available</p>
        @endif
    </div>

    <div class="flex flex-row">
        <strong class="mr-2">Known languages:</strong>
        @if ($user->languages->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                @foreach ($user->languages as $language)
                    <span class="inline-block bg-purple-100 text-purple-700 border border-purple-300 rounded-full px-3 py-1 text-sm font-semibold">
                        {{ $language->name }}
                    </span>
                @endforeach
            </div>
        @else
            <p>No data available</p>
        @endif
    </div>
</div>
