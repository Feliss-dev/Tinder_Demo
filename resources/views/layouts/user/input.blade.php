<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Profile</title>

</head>
<body>
    <div class="form-container">
        <h2> Account Information</h2>
    <form action="{{ route('info.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
        <!-- Name -->
        <div  class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
        </div>

        <!-- Date of Birth -->
        <div class="form-group">
            <label for="birth_date">Date of Birth</label>
            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', Auth::user()->birth_date) }}" required>
        </div>

        <!-- Gender -->
        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="male" {{ old('gender', Auth::user()->gender) === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', Auth::user()->gender) === 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender', Auth::user()->gender) === 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <!-- Bio -->
        <div class="form-group full-width">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio">{{ old('bio', Auth::user()->bio) }}</textarea>
        </div>

        <!-- Images -->
        <div class="form-group">
            <label for="images">Upload Images</label>
            <input type="file" id="images" name="images[]" multiple>
        </div>

        <!-- Interests -->
        <div class="form-group">
            <label for="interests">Interests</label>
            <select id="interests" name="interests" required>
                <option value="sports" {{ old('interests') === 'sports' ? 'selected' : '' }}>Sports</option>
                <option value="music" {{ old('interests') === 'music' ? 'selected' : '' }}>Music</option>
                <option value="travel" {{ old('interests') === 'travel' ? 'selected' : '' }}>Travel</option>
                <!-- Add more options -->
            </select>
        </div>

        <!-- Desired Gender -->
        <div class="form-group">
            <label for="desired_gender">Preferred Gender</label>
            <select id="desired_gender" name="desired_gender" required>
                <option value="male" {{ old('desired_gender') === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('desired_gender') === 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('desired_gender') === 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <!-- Dating Goal -->
        <div class="form-group">
            <label for="dating_goal">Dating Goal</label>
            <select id="dating_goal" name="dating_goal" required>
                <option value="love" {{ old('dating_goal') === 'love' ? 'selected' : '' }}>Love</option>
                <option value="friend" {{ old('dating_goal') === 'friend' ? 'selected' : '' }}>Friend</option>
                <option value="other" {{ old('dating_goal') === 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
    </div>
        <button type="submit" class="submit-button">Update Profile</button>
    </form>
    </div>
</body>

<style>
    body {
  font-family: Arial, sans-serif;
  background-color: #f9e0e0d4;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}

.form-container {
  background-color: #fff;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 1px 1px 15px rgba(0, 0, 0, 0.1);
  max-width: 60%;
  width: 100%;
  text-align: center;
}

h2 {
  margin-bottom: 20px;
  font-size: 24px;
  font-weight: bold;
  font-style: italic;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  margin-bottom: 5px;
  font-weight: bold;
  text-align: left;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"],
.form-group input[type="date"],
.form-group input[type="file"] {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.gender-options {
  display: flex;
  justify-content: space-between;
  gap: 5px;
}

.gender-option {
  flex: 1;
  border: 1px solid #ccc;
  border-radius: 5px;
  text-align: center;
  padding: 10px;
  cursor: pointer;
  background-color: #f9f9f9;
}

.gender-option input {
  display: none;
}

.gender-option span {
  display: block;
}

.gender-option input:checked + span {
  background-color: #ff5656;
  color: white;
  border-radius: 5px;
  padding: 5px;
}

.submit-button {
  margin-top: 20px;
  width: 100%;
  padding: 10px;
  background-color: #ff5656;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.submit-button:hover {
  background-color: #ff4545;
}

.form-group.full-width {
  grid-column: 1 / span 2;
}

textarea {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  resize: vertical;
}

</style>
</html>
