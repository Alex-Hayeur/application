<x-guest-layout>
    <x-jet-authentication-card >
        <x-slot name="logo">
            <img src="{{ asset(config('app.logo')) }}" alt= "Csu logo">
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label value="{{ __('Email') }}" />
                <x-jet-input class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label value="{{ __('Password') }}" />
                <x-jet-input class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button class="ml-4" name="login">
                    {{ __('Login') }}
                </x-jet-button>
            </div>

        </form>
        <form method="GET" action="{{ route('login/microsoft') }}">
            <div>
                <x-jet-button class="mt-4 w-full" name="o365">
                            {{ __('Office 365') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
       <!-- Footer -->
       <footer class="bg-black text-white text-sm font-bold pb-12">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex">
                    <div class="flex flex-col flex-1">
                        <div class="flex-1 py-3 font-black text-sm">
                            CONTACT US
                        </div>
                        <div class="flex-1 py-3">
                            <a href="mailto:contactus@csu.qc.ca">contactus@csu.qc.ca</a>
                        </div>
                        <div class="flex-1 py-3">
                            <a href="tel:5148487474">514 848-7474</a>
                        </div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <div class="flex-1 py-3 font-black text-sm">
                            CSU
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://www.csu.qc.ca/about-us/our-mission/">Our Mission</a>
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://www.csu.qc.ca/about-us/contact/">Contact</a>
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://www.csu.qc.ca/about-us/jobs/">Jobs</a>
                        </div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <div class="flex-1 py-3 font-black text-sm">
                            SOCIAL MEDIA
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://www.facebook.com/csumtl/">
                                <em class="fab fa-facebook pr-2"></em>
                                Facebook
                            </a>
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://www.instagram.com/csumtl/">
                                <em class="fab fa-instagram pr-2"></em>
                                Instagram
                            </a>
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://twitter.com/csumtl">
                                <em class="fab fa-twitter pr-2"></em>
                                Twitter
                            </a>
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://www.youtube.com/channel/UC14BOYjvdSqaCoCJfede9SA">
                                <em class="fab fa-youtube pr-2"></em>
                                YouTube
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
</x-guest-layout>
