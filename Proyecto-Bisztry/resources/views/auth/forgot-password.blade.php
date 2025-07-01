<x-guest-layout>
    <div class="forgot-password-container">
        <div class="forgot-password-card">
            <div class="forgot-password-header">
                <div class="icon-container">
                    <svg class="forgot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-3a1 1 0 011-1h2.586l6.243-6.243A6 6 0 0121 9z"/>
                    </svg>
                </div>
                <h2>¿Olvidaste tu contraseña jesus gay?</h2>
                <p class="description">
                    No te preocupes. Solo proporciona tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña que te permitirá elegir una nueva.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="status-message" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="forgot-password-form">
                @csrf

                <!-- Email Address -->
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
                            placeholder="tu@email.com" 
                        />
                        <x-input-label for="email" :value="__('Correo Electrónico')" class="floating-label" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                </div>

                <div class="form-submit">
                    <x-primary-button class="modern-button">
                        <svg class="button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Enviar Enlace de Restablecimiento</span>
                    </x-primary-button>
                </div>

                <div class="back-to-login">
                    <a href="{{ route('login') }}" class="back-link">
                        <svg class="back-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        * {
            box-sizing: border-box;
        }

        .forgot-password-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .forgot-password-card {
            background: white;
            padding: 3rem 2.5rem;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(229, 231, 235, 0.5);
        }

        .forgot-password-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .icon-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .forgot-icon {
            width: 48px;
            height: 48px;
            color: #667eea;
            padding: 12px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 12px;
        }

        .forgot-password-header h2 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 1rem 0;
            letter-spacing: -0.025em;
        }

        .description {
            font-size: 0.95rem;
            color: #6b7280;
            line-height: 1.6;
            margin: 0;
        }

        .status-message {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            color: #047857;
            font-size: 0.9rem;
        }

        .forgot-password-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 2rem;
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

        .form-submit {
            margin-bottom: 1.5rem;
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
            gap: 0.75rem;
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
            transform: scale(1.1);
        }

        .back-to-login {
            text-align: center;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }

        .back-link:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .back-icon {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
        }

        .back-link:hover .back-icon {
            transform: translateX(-3px);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .forgot-password-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: 16px;
            }

            .forgot-password-header h2 {
                font-size: 1.5rem;
            }

            .description {
                font-size: 0.9rem;
            }

            .modern-button {
                padding: 0.875rem 1.5rem;
            }
        }
    </style>
</x-guest-layout>