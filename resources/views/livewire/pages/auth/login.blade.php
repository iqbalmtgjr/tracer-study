<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <div class="input-group mb-3">
            <input wire:model="form.email" type="text" class="form-control @error('form.email') is-invalid @enderror"
                placeholder="Email" required autofocus autocomplete="email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('form.email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group mb-3">
            <input wire:model="form.password" type="password"
                class="form-control @error('form.password') is-invalid @enderror" placeholder="Password" required
                autocomplete="current-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('form.password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </div>
        </div>
    </form>
</div>
