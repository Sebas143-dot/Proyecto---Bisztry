<x-guest-layout>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2>Bienvenido</h2>
                <p>Inicia sesión en <span class="brand">BIZSTRY</span></p>
            </div>

            <!-- Estado de la sesión -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Formulario de login -->
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <div class="form-group">
                    <div class="input-group">
                        <x-text-input 
                            id="email" 
                            class="modern-input" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="tu@email.com" 
                        />
                        <x-input-label for="email" :value="__('Correo Electrónico')" class="floating-label" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <x-text-input 
                            id="password" 
                            class="modern-input" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="••••••••" 
                        />
                        <x-input-label for="password" :value="__('Contraseña')" class="floating-label" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="error-message" />
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember" class="modern-checkbox">
                        <span class="checkmark"></span>
                        <span class="checkbox-text">Recordarme</span>
                    </label>

                </div>

                <div class="form-submit">
                    <x-primary-button class="modern-button">
                        <span>Iniciar Sesión</span>
                        <svg class="button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <style>
        * {
            box-sizing: border-box;
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .login-card {
            background: white;
            padding: 3rem 2.5rem;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(229, 231, 235, 0.5);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.025em;
        }

        .login-header p {
            font-size: 1rem;
            color: #6b7280;
            margin: 0;
        }

        .brand {
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .input-group {
            position: relative;
        }

        .modern-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            background: #fafafa;
            transition: all 0.3s ease;
            outline: none;
        }

        .modern-input:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modern-input:focus + .floating-label,
        .modern-input:not(:placeholder-shown) + .floating-label {
            transform: translateY(-2.5rem) scale(0.85);
            color: #667eea;
            font-weight: 500;
        }

        .floating-label {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
            transition: all 0.3s ease;
            pointer-events: none;
            background: white;
            padding: 0 0.5rem;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 0.9rem;
            color: #374151;
        }

        .modern-checkbox {
            display: none;
        }

        .checkmark {
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            margin-right: 0.75rem;
            position: relative;
            transition: all 0.3s ease;
            background: white;
        }

        .modern-checkbox:checked + .checkmark {
            background: #667eea;
            border-color: #667eea;
        }

        .modern-checkbox:checked + .checkmark::after {
            content: '';
            position: absolute;
            left: 6px;
            top: 2px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #5a67d8;
        }

        .modern-button {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .modern-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .modern-button:active {
            transform: translateY(0);
        }

        .button-icon {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }

        .modern-button:hover .button-icon {
            transform: translateX(4px);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: 16px;
            }

            .login-header h2 {
                font-size: 1.75rem;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>
</x-guest-layout>