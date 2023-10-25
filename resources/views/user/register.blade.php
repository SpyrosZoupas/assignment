@extends('layouts.app')

@section('title', 'Registration Page')

@section('content')
<body>
                <div>{{ __('Register') }}</div>
                <div>
                    @if($errors->any())
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{$error}}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <div>
                        <label for="name">{{ ('Name') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email">{{ ('E-Mail Address') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="address">{{ ('Address') }}</label>
                        <input id="address" type="text" name="address" value="{{ old('address') }}" required>
                        @error('address')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="phone">{{ ('Phone') }}</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password">{{ ('Password') }}</label>
                        <input id="password" type="password" name="password" required>
                        @error('password')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password-confirm">{{ ('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" name="password_confirmation" required>
                    </div>

                    <div>
                        <button type="submit">{{ ('Register') }}</button>
                    </div>
                </form>
                @if(session('success'))
                    <div>{{ session('success') }}</div>
                @endif
</body>
@endsection