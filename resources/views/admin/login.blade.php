<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Modern Auth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 20s infinite alternate',
                        'float-reverse': 'float 15s infinite alternate-reverse',
                        'pulse-slow': 'pulse 2s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%': {
                                transform: 'translate(0, 0) scale(1)'
                            },
                            '100%': {
                                transform: 'translate(50px, 50px) scale(1.1)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(50px, 50px) scale(1.1);
            }
        }

        .shine-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .shine-effect:hover::before {
            left: 100%;
        }

        .ripple-effect::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .ripple-effect:hover::before {
            width: 300px;
            height: 300px;
        }

        .input-focus:focus {
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="min-h-screen flex justify-center items-center bg-gradient-to-br from-purple-600 via-purple-700 to-purple-900 relative overflow-hidden font-sans">

    <!-- Animated Background Orbs -->
    <div class="absolute w-[500px] h-[500px] bg-gradient-to-br from-pink-400 to-red-500 rounded-full -top-36 -left-36 animate-float opacity-30"></div>
    <div class="absolute w-[400px] h-[400px] bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full -bottom-24 -right-24 animate-float-reverse opacity-30"></div>

    <!-- Main Container -->
    <div class="relative w-full max-w-6xl h-auto md:h-[700px] bg-white/15 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden flex flex-col md:flex-row m-4">

        <!-- Image/Welcome Section -->
        <div class="flex-1 bg-gradient-to-br from-purple-600/80 to-purple-900/80 flex flex-col justify-center items-center p-10 md:p-16 relative overflow-hidden">
            <!-- Decorative Circles -->
            <div class="absolute w-48 h-48 bg-white/10 rounded-full -top-12 -right-12"></div>
            <div class="absolute w-36 h-36 bg-white/10 rounded-full -bottom-8 -left-8"></div>

            <div class="relative z-10 text-center text-white">
                <i class="fas fa-shield-alt text-8xl md:text-9xl mb-8 animate-pulse-slow drop-shadow-[0_0_20px_rgba(255,255,255,0.5)]"></i>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-lg">Welcome Back!</h1>
                <p class="text-base md:text-lg opacity-90 leading-relaxed">Sign in to access your account .</p>
                <div class="mt-8">
                    <p class="text-sm opacity-80 mb-3">Don't have an account?</p>
                    <a href="/register" class="inline-block px-8 py-3 bg-white/20 backdrop-blur-sm border-2 border-white/40 text-white font-semibold rounded-xl hover:bg-white/30 hover:-translate-y-1 transition-all duration-300 shine-effect relative overflow-hidden">
                        <span class="relative z-10">Create Account</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-12 flex flex-col justify-center max-h-screen md:max-h-full overflow-y-auto">

            <div class="mb-8 flex-shrink-0">
                @if(session('success'))
                <div class="inline-flex items-center rounded px-3 py-1.5 text-base text-white">{{ session('success') }}</div>
                @endif

                @if(session('failed'))
                <div class="inline-flex items-center rounded px-3 py-1.5 text-base text-white">{{ session('failed') }}</div>
                @endif

                <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">Login</h2>
                <p class="text-white/70 text-sm md:text-base">Enter your credentials to access your account</p>
            </div>


            <form action="{{ route('login.save') }}" method="POST" class="flex-1 min-h-0">
                @csrf

                <!-- Email Input -->
                <div class="mb-5 sm:mb-6">
                    <label for="email" class="block text-white text-xs sm:text-sm font-medium mb-2 pl-1">Email Address</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-white/60 text-sm"></i>
                        <input type="email" id="email" name="user_email" value="" class="input-focus w-full py-3 sm:py-3.5 px-3 sm:px-4 pl-10 sm:pl-12 border-2 border-white/20 bg-white/10 rounded-xl text-white placeholder-white/50 transition-all duration-300 focus:outline-none focus:border-white/50 focus:bg-white/15 text-sm sm:text-base" placeholder="Enter your email" required>
                        <span class="inline-flex items-center rounded px-3 py-1.5 text-base text-white"> @error('user_email'){{ $message }}@enderror</span>

                    </div>
                </div>

                <!-- Password Input -->
                <div class="mb-5 sm:mb-6">
                    <label for="password" class="block text-white text-xs sm:text-sm font-medium mb-2 pl-1">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-white/60 text-sm"></i>
                        <input type="password" id="password" name="user_password" class="input-focus w-full py-3 sm:py-3.5 px-3 sm:px-4 pl-10 sm:pl-12 pr-10 sm:pr-12 border-2 border-white/20 bg-white/10 rounded-xl text-white placeholder-white/50 transition-all duration-300 focus:outline-none focus:border-white/50 focus:bg-white/15 text-sm sm:text-base" placeholder="Enter your password" required>
                        <i class="fas fa-eye absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 cursor-pointer text-white/60 hover:text-white transition-colors duration-300 text-sm" onclick="togglePassword()"></i>
                        <span class="inline-flex items-center rounded px-3 py-1.5 text-base text-white"> @error('user_password'){{ $message }}@enderror</span>

                    </div>
                </div>


                <!-- Submit Button -->
                <button type="submit" name="submit" class="ripple-effect w-full py-3 sm:py-4 border-none bg-gradient-to-r from-pink-500 to-red-500 text-white text-sm sm:text-base font-semibold rounded-xl cursor-pointer transition-all duration-300 shadow-[0_5px_15px_rgba(245,87,108,0.4)] hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(245,87,108,0.6)] active:translate-y-0 relative overflow-hidden">
                    <span class="relative z-10"><i class="fas fa-sign-in-alt mr-2"></i>Login Now</span>
                </button>



            </form>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = event.target;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>