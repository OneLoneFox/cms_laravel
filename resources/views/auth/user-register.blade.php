<x-user-auth-layout>
    <x-user-auth-card>

        <div class="mt-5 mb-5">
            <h1 class="font-serif text-4xl font-bold">Registrate</h1>
            <p class="mt-2">Empieza registrandote para poder participar en tus congresos favoritos</p>
        </div>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('user_register') }}" class="flex-grow grid grid-cols-1 lg:grid-cols-2 gap-4">
            @csrf
            <div>
                <x-label>Soy un participante</x-label>
                <input type="radio" name="user_type" value="{{ User::PARTICIPANT }}"/>
            </div>
            <div>
                <x-label>Soy un autor/ponente</x-label>
                <input type="radio" name="user_type" value="{{ User::AUTHOR }}"/>
            </div>
            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Cellphone Number -->
            <div>
                {{-- cellphone has no translation lol --}}
                <x-label for="cellphone" :value="'Número celular'" />

                <x-input id="cellphone" class="block mt-1 w-full" type="text" name="cellphone" :value="old('cellphone')" required />
            </div>

            <!-- Sex -->
            <div>
                <x-label for="sex" :value="'Sexo'"/>

                <x-select id="sex" name="sex" class="block mt-1 w-full" required>
                    <x-slot name="options">
                        <option value="{{ User::MALE }}">Masculino</option>
                        <option value="{{ User::FEMALE }}">Femenino</option>
                        <option value="{{ User::NONE }}">Prefiero no decirlo</option>
                    </x-slot>
                </x-select>
            </div>

            <!-- Password -->
            <div>
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end lg:col-span-2">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-user-auth-card>
</x-user-auth-layout>
