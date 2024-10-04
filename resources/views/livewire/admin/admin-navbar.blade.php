
<nav>

        <i class='bx bx-menu' ></i>
        <a href="#" class="nav-link">Categories</a>

        {{-- <div class="form-input ">
            <p><strong>Announcement:</strong> New features have been added to enhance your experience!</p>
        </div> --}}


        <form wire:submit.prevent="submitSearch">
            <div class="form-input">
                <input type="text" wire:model.debounce.500ms="searchTerm" placeholder="Search users...">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
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
