<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f7fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .form-group button {
            width: 100%;
            padding: 0.75rem;
            background-color: #3182ce;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #2b6cb0;
        }
        .toggle-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #3182ce;
            cursor: pointer;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="form-container">
    <!-- Formulario de Registro -->
    <form id="register-form" class="form-group" method="POST" action="{{ route('register') }}">
        @csrf
        <h2>Registrarse</h2>
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <div class="form-group">
            <button type="submit">Registrarse</button>
        </div>
        <a class="toggle-link" onclick="toggleForms()">¿Ya tienes una cuenta?</a>
    </form>

    <!-- Formulario de Inicio de Sesión -->
    <form id="login-form" class="form-group hidden" method="POST" action="{{ route('login') }}">
        @csrf
        <h2>Iniciar Sesión</h2>
        <div class="form-group">
            <label for="login_email">Correo Electrónico</label>
            <input type="email" id="login_email" name="email" required>
        </div>
        <div class="form-group">
            <label for="login_password">Contraseña</label>
            <input type="password" id="login_password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit">Iniciar Sesión</button>
        </div>
        <p id="login-error" style="color: red;"></p>
        <a class="toggle-link" onclick="toggleForms()">¿No tienes una cuenta?</a>
    </form>
</div>

<script>
    function toggleForms() {
        const registerForm = document.getElementById('register-form');
        const loginForm = document.getElementById('login-form');
        registerForm.classList.toggle('hidden');
        loginForm.classList.toggle('hidden');
    }

    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                // Aquí verificamos si la respuesta es un JSON válido antes de procesarlo
                return response.headers.get('content-type').includes('application/json')
                    ? response.json() // Si es JSON, lo procesamos
                    : { success: true }; // Si no es JSON, asumimos que la respuesta fue exitosa
            })
            .then(data => {
                if (data.success === false) {
                    // Mostrar mensaje de error si las credenciales son incorrectas
                    document.getElementById('login-error').innerText = data.message;
                } else {
                    // Si no hay error, se asume que la redirección ocurrió correctamente
                    window.location.href = "{{ route('profile') }}";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('login-error').innerText = 'Ocurrió un error al procesar la solicitud. Por favor, intenta de nuevo.';
            });
    });
</script>
</body>
</html>

