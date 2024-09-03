<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PruebaController extends Controller
{
// Función para manejar el registro
    public function register(Request $request)
    {
        $response = Http::post('https://candidates-exam.herokuapp.com/api/v1/usuarios', [
            'nombre' => $request->input('nombre'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
        ]);

        $data = $response->json();

        if ($response->successful()) {
            // Manejar la respuesta exitosa
            return back()->with('success', 'Registro exitoso');
        } else {
            // Manejar los errores
            return back()->withErrors($data);
        }
    }

    // Función para manejar el login
    public function login(Request $request)
    {
        $request->session()->forget('token');
        $request->session()->forget('url');

        $response = Http::post('https://candidates-exam.herokuapp.com/api/v1/auth/login', [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['tipo']) && $data['tipo'] === true) {
            // Guardar el token en la sesión
            $request->session()->put('token', $data['token']);
            $request->session()->put('url', $data['url']);

            // Redirigir al perfil
            return redirect()->route('profile');

        } else {
            // Si el login falla (tipo es false)
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas',
            ]);
        }
    }


    public function showProfile(Request $request)
    {
        $token = $request->session()->get('token');

        if (!$token) {
            return redirect()->route('login')->withErrors(['message' => 'Por favor, inicia sesión.']);
        }

        // Obtener los datos del usuario
        $response = Http::withToken($token)->get('https://candidates-exam.herokuapp.com/api/v1/usuarios/');
        $user = $response->json();

        if ($response->failed()) {
            // Manejar error en la respuesta de la API, e.g., token inválido
            $request->session()->forget('token');
            return redirect()->route('login')->withErrors(['message' => 'Sesión expirada.']);
        }

        // Asegúrate de pasar el array al view correctamente
        return view('profile', ['user' => $user]);
    }

    public function uploadCV(Request $request)
    {
        $request->validate([
            'curriculum' => 'required|file|mimes:pdf|max:5120',
        ]);

        $file = $request->file('curriculum');
        $url = $request->session()->get('url'); // Obtén el URL del usuario desde la sesión


        $response = Http::withToken($request->session()->get('token'))
            ->attach('curriculum', file_get_contents($file->path()), $file->getClientOriginalName())
            ->post("https://candidates-exam.herokuapp.com/api/v1/usuarios/{$url}/cargar_cv");

        if ($response->successful()) {
            return back()->with('success', 'CV cargado exitosamente');
        } else {
            return back()->withErrors(['upload_error' => 'Error al cargar el CV']);
        }
    }

}
