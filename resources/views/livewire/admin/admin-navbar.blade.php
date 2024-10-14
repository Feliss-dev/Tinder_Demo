
<nav>

        <i class='bx bx-menu' ></i>
        <a href="#" class="nav-link">Categories</a>

        {{-- <div class="form-input ">
            <p><strong>Announcement:</strong> New features have been added to enhance your experience!</p>
        </div> --}}


        <form wire:submit.prevent="submitSearch">
            <div class="relative w-full">
                <input type="text" wire:model.debounce.500ms="searchTerm" placeholder="Search users..."
                class="w-full  px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-400 focus:border-transparent">
                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-blue-500"><i class='bi bi-search text-2xl '></i></button>
            </div>
        </form>





<input type="checkbox" id="switch-mode" hidden>
    <label for="switch-mode" class="switch-mode"></label>
    <a href="#" class="notification">
        <i class='bx bxs-bell' ></i>
        <span class="num">8</span>
    </a>
    <a href="#" class="profile">
        <img src="img/people.png">
    </a>


</nav>
