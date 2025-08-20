@extends('layouts.app-mobile')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50">
    <!-- Main Container -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <!-- Success Card -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:scale-105">
                <!-- Header with gradient -->
                <div class="bg-gradient-to-r from-green-400 to-emerald-500 p-8 text-center">
                    <!-- Animated Success Icon -->
                    <div class="relative inline-block">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <svg class="w-12 h-12 text-green-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <!-- Animated rings -->
                        <div class="absolute inset-0 w-24 h-24 border-4 border-green-300 rounded-full animate-ping opacity-20"></div>
                        <div class="absolute inset-0 w-24 h-24 border-2 border-green-400 rounded-full animate-pulse"></div>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-white mb-2">Payment Successful!</h1>
                    <p class="text-green-100 text-sm">Your transaction has been completed</p>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <!-- Success Message -->
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium mb-4">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Transaction Complete
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="space-y-4 text-gray-600">
                        <p class="text-center leading-relaxed">
                            Thank you! Your payment has been completed successfully. We appreciate your trust and are processing your request accordingly.
                        </p>

                        <!-- Info Card -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-800">
                                        You will receive a confirmation shortly through your registered contact details. Please keep your transaction reference safe for future use or tracking.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700 mb-2">
                                        If you have any questions or concerns, feel free to reach out to us:
                                    </p>
                                    <div class="space-y-1">
                                        <div class="flex items-center text-sm">
                                            <span class="text-gray-500 w-16">Call:</span>
                                            <span class="font-medium text-gray-900">0714335524</span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <span class="text-gray-500 w-16">WhatsApp:</span>
                                            <span class="font-medium text-gray-900">0714335524</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 space-y-3">
                        <button onclick="window.history.back()" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-green-600 hover:to-emerald-700 transform transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Continue
                        </button>
                        
                        <button onclick="window.location.href='{{ url('/') }}'" class="w-full bg-white border-2 border-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl hover:bg-gray-50 hover:border-gray-400 transform transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Go to Home
                        </button>
                    </div>
                </div>
            </div>

            <!-- Additional Info Cards -->
            <div class="mt-6 grid grid-cols-1 gap-4">
                <!-- Security Badge -->
                <div class="bg-white rounded-xl p-4 shadow-lg border border-gray-100">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Secure Payment</p>
                            <p class="text-xs text-gray-500">Your data is protected with bank-level security</p>
                        </div>
                    </div>
                </div>

                <!-- Support Badge -->
                <div class="bg-white rounded-xl p-4 shadow-lg border border-gray-100">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">24/7 Support</p>
                            <p class="text-xs text-gray-500">We're here to help anytime you need us</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for additional animations -->
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    
    .animate-bounce {
        animation: bounce 1s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0, -30px, 0);
        }
        70% {
            transform: translate3d(0, -15px, 0);
        }
        90% {
            transform: translate3d(0, -4px, 0);
        }
    }
</style>
@endsection
