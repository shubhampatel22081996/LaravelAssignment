<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Date of Birth -->
        <div class="mt-4">
            <x-input-label for="dob" :value="__('Date of Birth')" />
                <input id="dob" class="block mt-1 w-full" type="date" name="dob" :value="old('dob')" />
            <x-input-error :messages="$errors->get('dob')" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" class="block mt-1 w-full" name="gender">
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
                <!-- Add other gender options as needed -->
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Country -->
        <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" />
            <select id="country" class="block mt-1 w-full" name="country">
                <option value="">Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->country }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <!-- State -->
        <div class="mt-4">
            <x-input-label for="state" :value="__('State')" />
            <select id="state" class="block mt-1 w-full" name="state">
                <option value="">Select State</option>
                <!-- @foreach($states as $state)
                    <option value="{{ $state->id }}">{{ $state->state }}</option>
                @endforeach -->
            </select>
            <x-input-error :messages="$errors->get('state')" class="mt-2" />
        </div>

        <!-- City -->
        <div class="mt-4">
            <x-input-label for="city" :value="__('City')" />
            <select id="city" class="block mt-1 w-full" name="city">
                <option value="">Select City</option>
                <!-- @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->city }}</option>
                @endforeach -->
            </select>
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var countryDropdown = document.getElementById('country');
            var stateDropdown = document.getElementById('state');
            var cityDropdown = document.getElementById('city');


            if (countryDropdown) {
                countryDropdown.addEventListener('change', function () {
                    var selectedCountry = this.value;

                    // Make an AJAX request to fetch states based on the selected country
                    // Replace '/get-states' with the actual route that fetches states
                    fetch('/getstates?country_id=' + selectedCountry)
                        .then(response => response.json())
                        .then(data => {
                            // Clear and populate the "State" dropdown with fetched states
                            stateDropdown.innerHTML = '<option value="">Select State</option>';
                            data.forEach(state => {
                                stateDropdown.innerHTML += '<option value="' + state.id + '">' + state.state + '</option>';
                            });

                            // Clear and populate the "City" dropdown with default option
                            cityDropdown.innerHTML = '<option value="">Select City</option>';
                        })
                        .catch(error => console.error(error));
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var countryDropdown = document.getElementById('country');
            var stateDropdown = document.getElementById('state');
            var cityDropdown = document.getElementById('city');

            // Your existing code for country dropdown

            // On change event for the "State" dropdown
            stateDropdown.addEventListener('change', function () {
                var selectedState = this.value;

                // Make an AJAX request to fetch cities based on the selected state
                fetch('/getCities?state_id=' + selectedState)
                    .then(response => response.json())
                    .then(data => {
                        // Clear and populate the "City" dropdown with fetched cities
                        cityDropdown.innerHTML = '<option value="">Select City</option>';
                        data.forEach(city => {
                            cityDropdown.innerHTML += '<option value="' + city.id + '">' + city.city + '</option>';
                        });
                    })
                    .catch(error => console.error(error));
            });
        });
    </script>

</x-guest-layout>
