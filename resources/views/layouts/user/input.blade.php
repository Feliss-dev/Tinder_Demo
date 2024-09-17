<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Profile</title>
</head>
<body>
    <form action="{{ route('info.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
        </div>

        <!-- Date of Birth -->
        <div>
            <label for="birth_date">Date of Birth</label>
            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', Auth::user()->birth_date) }}" required>
        </div>

        <!-- Gender -->
        <div>
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="male" {{ old('gender', Auth::user()->gender) === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', Auth::user()->gender) === 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender', Auth::user()->gender) === 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <!-- Bio -->
        <div>
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio">{{ old('bio', Auth::user()->bio) }}</textarea>
        </div>

        <!-- Images -->
        <div>
            <label for="images">Upload Images</label>
            <input type="file" id="images" name="images[]" multiple>
        </div>

        <!-- Interests -->
        <div>
            <label for="interests">Interests</label>
            <select id="interests" name="interests" required>
                <option value="sports" {{ old('interests') === 'sports' ? 'selected' : '' }}>Sports</option>
                <option value="music" {{ old('interests') === 'music' ? 'selected' : '' }}>Music</option>
                <option value="travel" {{ old('interests') === 'travel' ? 'selected' : '' }}>Travel</option>
                <!-- Add more options -->
            </select>
        </div>

        <!-- Desired Gender -->
        <div>
            <label for="desired_gender">Preferred Gender</label>
            <select id="desired_gender" name="desired_gender" required>
                <option value="male" {{ old('desired_gender') === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('desired_gender') === 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('desired_gender') === 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <!-- Dating Goal -->
        <div>
            <label for="dating_goal">Dating Goal</label>
            <select id="dating_goal" name="dating_goal" required>
                <option value="love" {{ old('dating_goal') === 'love' ? 'selected' : '' }}>Love</option>
                <option value="friend" {{ old('dating_goal') === 'friend' ? 'selected' : '' }}>Friend</option>
                <option value="other" {{ old('dating_goal') === 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
