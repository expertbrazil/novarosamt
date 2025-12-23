@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nova Rosa MT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
        
        .logo-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .glass-effect {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .input-field {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            background: rgba(30, 41, 59, 0.8);
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .input-field::placeholder {
            color: #64748b;
        }
        
        .input-field::-webkit-input-placeholder { color: #64748b; }
        .input-field::-moz-placeholder { color: #64748b; }
        .input-field:-ms-input-placeholder { color: #64748b; }
        .input-field:-moz-placeholder { color: #64748b; }
        
        input[type="email"], input[type="password"] {
            color: #ffffff !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo -->
            <div class="text-center mb-6">
                <div class="logo-animation inline-block">
                    @if(isset($settings['logo']) && $settings['logo'])
                        <img src="{{ Storage::url($settings['logo']) }}" 
                             alt="Logo da Empresa" 
                             class="h-24 w-auto mx-auto object-contain">
                    @else
                        <svg class="w-16 h-16 mx-auto text-indigo-400" fill="currentColor" viewBox="0 0 100 100">
                            <path d="M20 30 Q50 10 80 30 Q50 50 20 30 Z" opacity="0.8"/>
                            <path d="M20 50 Q50 30 80 50 Q50 70 20 50 Z" opacity="0.6"/>
                        </svg>
                    @endif
                </div>
            </div>

            <!-- Title -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-2">
                    Acesse sua conta
                </h1>
            </div>

            <!-- Login Form -->
            <div class="glass-effect rounded-2xl p-8 space-y-6">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               required 
                               value="{{ old('email') }}"
                               class="input-field w-full px-4 py-4 rounded-xl border-0 focus:outline-none focus:ring-0 text-lg placeholder-gray-400 text-white"
                               placeholder="Endereço de email">
                    </div>

                    <!-- Password Field -->
                    <div>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required 
                               class="input-field w-full px-4 py-4 rounded-xl border-0 focus:outline-none focus:ring-0 text-lg placeholder-gray-400 text-white"
                               placeholder="Senha">
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" 
                                   name="remember" 
                                   type="checkbox" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-600 rounded bg-gray-700">
                            <label for="remember" class="ml-2 text-sm text-gray-300">
                                Lembrar de mim
                            </label>
                        </div>
                        <div>
                            <a href="#" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                                Esqueceu a senha?
                            </a>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl">
                            @foreach($errors->all() as $error)
                                <p class="text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Sign In Button -->
                    <div>
                        <button type="submit" 
                                class="btn-primary w-full py-4 px-4 rounded-xl text-white font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                            Entrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add subtle animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.glass-effect');
            form.style.opacity = '0';
            form.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                form.style.transition = 'all 0.6s ease';
                form.style.opacity = '1';
                form.style.transform = 'translateY(0)';
            }, 200);
        });
    </script>
</body>
</html>

