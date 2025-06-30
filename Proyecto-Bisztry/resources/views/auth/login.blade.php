<x-guest-layout>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h2>Iniciar Sesión</h2>
                <p>Accede al sistema de gestión de <strong>BIZSTRY</strong></p>
            </div>

            <!-- Estado de la sesión -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Formulario de login -->
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <div class="form-group">
                    <x-input-label for="email" :value="__('Correo Electrónico')" />
                    <x-text-input id="email" class="input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="form-group">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="input" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="form-footer">
                    <label class="remember">
                        <input type="checkbox" name="remember" class="checkbox">
                        <span>Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                <div class="form-submit">
                    <x-primary-button class="btn btn-primary w-full">
                        Iniciar Sesión
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f3f4f6;
        }

        .login-card {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
        }

        .login-header p {
            font-size: 0.95rem;
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            background-color: white;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .checkbox {
            margin-right: 0.5rem;
        }

        .forgot-link {
            color: #3b82f6;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn.btn-primary {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease;
        }

        .btn.btn-primary:hover {
            background-color: #2563eb;
        }
    </style>
</x-guest-layout>
