@extends('statamic::layout')
@section('title', 'Instagram Login')

@section('content')
    <div class="no-results md:pt-8 max-w-2xl mx-auto">
        <div class="w-full">
            <h1 class="mb-4">Instagram Connection</h1>

            @if($status === 'CONNECTED')
                <p class="mb-1">Connected with account : <strong>{{ $userProfile->username }}</strong></p>

                <p class="mb-4">Expiration date : {{ $tokenExpireDate->toIso8601String() }}</p>

                <a href='{{ $logoutUrl }}' class="btn-danger btn">Logout</a>
            @elseif($status === 'NOT_CONNECTED')
                <a href='{{ $loginUrl }}' class="btn-primary btn-lg">Login with Instagram</a>

                <div class="rounded p-3 lg:px-7 lg:py-5 shadow bg-white mt-4">
                    <label for="name" class="font-bold text-base mb-sm">OAuth Redirect URI</label>
                    <div class="flex">
                        <!-- Target -->
                        <input id="foo"  class="input-text" value="{{ route('statamic.cp.nineteen-ig.callback') }}" readonly>
                        <!-- Trigger -->
                        <button class="btn" data-clipboard-target="#foo">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="clipboard-copy w-6 h-6">
                                <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z"></path>
                                <path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

            @else
                <p class="mt-1">Not configured</p>
                <p>Please set the INSTAGRAM_APP_ID and INSTAGRAM_APP_SECRET in .env file</p>
            @endif
        </div>

    </div>

    <script src="https://unpkg.com/clipboard@2/dist/clipboard.min.js"></script>
    <script>
        new ClipboardJS('.btn');
    </script>


@endsection
