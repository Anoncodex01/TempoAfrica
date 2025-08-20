@extends('layouts.auth')

@section('content')
<div class="flex min-h-screen w-full">
    <!-- Left Side - Image Slides -->
    <div class="hidden md:flex flex-1 relative overflow-hidden">
        <div 
            x-data="{
                slides: [
                    { img: '/images/pic1.jpg', title: 'Luxury Hotels', desc: 'Experience world-class accommodations' },
                    { img: '/images/pic2.jpg', title: 'Amazing Destinations', desc: 'Discover breathtaking locations' },
                    { img: '/images/pic3.jpg', title: 'Premium Service', desc: 'Unmatched hospitality experience' }
                ],
                current: 0,
                interval: null,
                start() {
                    this.interval = setInterval(() => {
                        this.current = (this.current + 1) % this.slides.length;
                    }, 5000);
                },
                stop() { clearInterval(this.interval); },
                goTo(idx) { this.current = idx; this.stop(); this.start(); }
            }"
            x-init="start()"
            class="w-full h-full relative"
        >
            <template x-for="(slide, idx) in slides" :key="idx">
                <div x-show="current === idx" class="absolute inset-0 transition-opacity duration-700" x-transition:enter="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="opacity-100" x-transition:leave-end="opacity-0">
                    <img :src="slide.img" alt="" class="object-cover w-full h-full rounded-r-2xl" />
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent text-white p-10 text-center rounded-r-2xl">
                        <h3 class="text-3xl font-bold mb-2" x-text="slide.title"></h3>
                        <p class="text-lg" x-text="slide.desc"></p>
                    </div>
                </div>
            </template>
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex space-x-3 z-10">
                <template x-for="(slide, idx) in slides" :key="idx">
                    <span @click="goTo(idx)" :class="{'bg-yellow-400 scale-125': current === idx, 'bg-yellow-100': current !== idx}" class="w-3 h-3 rounded-full cursor-pointer transition-all"></span>
                </template>
            </div>
        </div>
    </div>
    <!-- Right Side - Login Form -->
    <div class="flex-1 flex items-center justify-center bg-white px-4 py-8 md:py-0">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <img src="/images/logos/logo.png" alt="Tempo Africa Logo" class="h-10 mx-auto mb-4 rounded-lg">
                <h2 class="text-3xl font-bold mb-2">Login</h2>
                <p class="text-gray-500">Enter your log in detail please.</p>
            </div>
            <div id="login-alert"></div>
            <form id="admin-login-form" method="POST" action="{{ route('login') }}" x-data="{ show: false }">
                @csrf
                <div class="mb-5">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="Email Address"
                        class="w-full px-5 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('email') border-red-500 @enderror" />
                    @error('email')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-5 relative">
                    <input :type="show ? 'text' : 'password'" name="password" id="password" required placeholder="Password"
                        class="w-full px-5 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('password') border-red-500 @enderror" />
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" id="password-icon"></i>
                    </button>
                    @error('password')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl transition mb-4" id="login-btn">
                    Log in
                </button>
            </form>
            <div class="text-center bg-gray-100 p-3 rounded-lg border border-gray-200 text-gray-700 text-sm mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Demo: admin@tempo.com / admin123
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js for interactivity -->
<script src="//unpkg.com/alpinejs" defer></script>
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admin-login-form');
    const loginBtn = document.getElementById('login-btn');
    const alertBox = document.getElementById('login-alert');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        loginBtn.disabled = true;
        loginBtn.textContent = 'Logging in...';
        alertBox.innerHTML = '';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: new FormData(form)
        })
        .then(response => response.json())
        .then(data => {
            loginBtn.disabled = false;
            loginBtn.textContent = 'Log in';
            if (data.success && data.location) {
                window.location.href = data.location;
            } else if (data.message) {
                alertBox.innerHTML = `<div class=\"text-red-600 text-center mb-4\">${data.message}</div>`;
            }
        })
        .catch(() => {
            loginBtn.disabled = false;
            loginBtn.textContent = 'Log in';
            alertBox.innerHTML = '<div class="text-red-600 text-center mb-4">An error occurred. Please try again.</div>';
        });
    });
});
</script>
@endsection 