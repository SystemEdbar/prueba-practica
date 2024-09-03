<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Usuario</title>
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
        .container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        h1, h2 {
            margin-top: 0;
        }
        p {
            margin: 0.5rem 0;
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
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Perfil del Usuario</h1>
    <p><strong>Correo Electrónico:</strong> {{ $user['email'] }}</p>
    <p><strong>Nombre:</strong> {{ $user['nombre'] }}</p>
    <p><strong>Estado:</strong> {{ $user['estado'] ? 'Activo' : 'Inactivo' }}</p>
    <p><strong>URL:</strong> {{ $user['url'] }}</p>

    <h2>Cargar CV</h2>
    <form action="{{ route('uploadCV') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="url" value="{{ $user['url'] }}">
        <div class="form-group">
            <label for="curriculum">Selecciona tu CV (PDF máximo 5MB)</label>
            <input type="file" id="curriculum" name="curriculum" accept=".pdf" required>
        </div>
        <div class="form-group">
            <button type="submit">Subir CV</button>
        </div>
    </form>

    <h2>Consultar CV</h2>
    <form id="view-cv-form">
        <div class="form-group">
            <button type="button" onclick="viewCV()">Mostrar CV</button>
        </div>
    </form>
</div>

<script>
    function viewCV() {
        fetch("https://candidates-exam.herokuapp.com/api/v1/usuarios/mostrar_cv", {
            method: "GET",
            headers: {
                "Authorization": "Bearer {{ session('token') }}",
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    console.log(data.url)
                    window.open(data.url, '_blank');
                } else {
                    alert('No se pudo obtener el CV. Intenta de nuevo más tarde.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al consultar el CV.');
            });
    }
</script>

</body>
</html>
