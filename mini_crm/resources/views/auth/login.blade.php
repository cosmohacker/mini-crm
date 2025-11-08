<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    <title>Login - {{ config('app.name', 'Mini CRM') }}</title>
</head>

<body class="DEFAULT_THEME">
    <main>
        <!-- Main Content -->
        <div
            class="flex flex-col w-full overflow-hidden relative min-h-screen radial-gradient items-center justify-center gap-6 g-0 px-4 py-8">
            <!-- Login Card -->
            <div class="justify-center items-center w-full card flex max-w-md">
                <div class="w-full card-body">
                    <a href="{{ route('home') }}" class="py-4 block">
                        <img src="{{ asset('images/logos/logo-light.svg') }}" alt="" class="mx-auto" />
                    </a>
                    <p class="mb-4 text-gray-400 text-sm text-center">Mini CRM</p>

                    <!-- form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- username -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm mb-2 text-gray-400">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="py-3 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('email') border-red-500 @enderror" aria-describedby="email-error">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500" id="email-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- password -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm mb-2 text-gray-400">Password</label>
                            <input type="password" id="password" name="password" required
                                class="py-3 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('password') border-red-500 @enderror" aria-describedby="password-error">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500" id="password-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- button -->
                        <div class="grid my-5">
                            <button type="submit" class="btn py-[10px] text-base text-white font-medium hover:bg-blue-700">Sign In</button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Widgets Card -->
            <div class="justify-center items-center w-full card flex max-w-md">
                <div class="w-full card-body">
                    <h2 class="text-3xl mb-4 text-gray-400 text-center">Widgets</h2>
                    <div class="grid my-5">
                        <a href="{{ route('widgets.index') }}" class="btn py-[10px] text-base text-white font-medium hover:bg-blue-700 text-center">
                            Redirect to Widget
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--end of project-->
    </main>

    @include('partials.scripts')
</body>

</html>
