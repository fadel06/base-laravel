@php
    $user = Auth::user();
    // Get initials (first 2 letters of name)
    $nameParts = explode(' ', $user->name);
    $initials = '';
    if (count($nameParts) >= 2) {
        // If full name, take first letter of first and last name
        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1));
    } else {
        // If single name, take first 2 letters
        $initials = strtoupper(substr($user->name, 0, 2));
    }

    // Get first name only for display
    $firstName = $nameParts[0];

    // Generate random color based on name
    $colors = [
        'bg-blue-600',
        'bg-green-600',
        'bg-purple-600',
        'bg-pink-600',
        'bg-indigo-600',
        'bg-red-600',
        'bg-orange-600',
        'bg-teal-600',
    ];
    $colorIndex = abs(crc32($user->name)) % count($colors);
    $bgColor = $colors[$colorIndex];
@endphp

<div class="relative" x-data="{
    dropdownOpen: false,
    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
    },
    closeDropdown() {
        this.dropdownOpen = false;
    }
}" @click.away="closeDropdown()">
    <!-- User Button -->
    <button class="flex items-center text-gray-700 dark:text-gray-400" @click.prevent="toggleDropdown()" type="button">
        <!-- Avatar with Initials -->
        <span
            class="mr-3 flex h-11 w-11 items-center justify-center overflow-hidden rounded-full {{ $bgColor }} text-sm font-semibold text-white">
            {{ $initials }}
        </span>

        <span class="mr-1 block font-medium text-theme-sm">{{ $firstName }}</span>

        <!-- Chevron Icon -->
        <svg class="h-5 w-5 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Start -->
    <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-50 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark"
        style="display: none;">
        <!-- User Info -->
        <div class="flex items-center gap-3 pb-3 border-b border-gray-200 dark:border-gray-800">
            <!-- Avatar in Dropdown -->
            <span
                class="flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-full {{ $bgColor }} text-base font-semibold text-white">
                {{ $initials }}
            </span>
            <div class="min-w-0 flex-1">
                <span
                    class="block truncate font-medium text-gray-700 text-theme-sm dark:text-gray-300">{{ $user->name }}</span>
                <span
                    class="mt-0.5 block truncate text-theme-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
            </div>
        </div>

        <!-- Sign Out -->
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 group text-theme-sm transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                @click="closeDropdown()">
                <span class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                </span>
                Keluar
            </button>
        </form>
    </div>
    <!-- Dropdown End -->
</div>
