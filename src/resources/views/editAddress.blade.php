@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')

    <main>
        <div class="main-title">
            <p>住所の変更</p>
        </div>

        <div class="profile-box">
            <form method="POST" action="{{ route('purchase.updateAddress', ['item' => $item->id]) }}">
                @csrf
                    <input type="hidden" name="address_id" value="{{ $address->id }}">

                    <div class="form-group">
                        <div class="form-text">
                            <label for="tel-{{ $address->id }}">郵便番号</label>
                        </div>
                        <input type="text" name="tel" id="tel-{{ $address->id }}" value="{{ $address->tel }}" class="form-input">
                        @error('tel')
                        <div class="form_error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-text">
                            <label for="address-{{ $address->id }}">住所</label>
                        </div>
                        <input type="text" name="address" id="address-{{ $address->id }}" value="{{ $address->address }}" class="form-input">
                        @error('address')
                        <div class="form_error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-text">
                            <label for="building-{{ $address->id }}">建物名</label>
                        </div>
                        <input type="text" name="building" id="building-{{ $address->id }}" value="{{ $address->building }}" class="form-input">
                    </div>

                <div class="register-button">
                    <button type="submit" class="register-button__text">更新する</button>
                </div>
            </form>
        </div>
    </main>
@endsection