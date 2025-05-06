<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;

class FacialController extends Controller
{
    public function run(Request $request)
    {
        // Validar el ID del estudiante
        $validated = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id'
        ]);

        // Buscar el estudiante
        $estudiante = Estudiante::find($validated['estudiante_id']);

        // Asegurarse de que el nombre del estudiante esté en UTF-8
        $nombreEstudiante = utf8_encode($estudiante->nombre);

        // Ruta completa al ejecutable de Python del entorno virtual
        $python = 'C:\\Users\\Kevin Joel\\Downloads\\Reconocimiento_facial\\.venv\\Scripts\\python.exe';

        // Ruta al script Python
        $script = 'C:\\Users\\Kevin Joel\\Downloads\\Reconocimiento_facial\\Reconocimiento_facial\\FaceRecognition.py';

        // Ruta al directorio con imágenes
        $imagesPath = storage_path('app/public/parentescos');

        // Escapar parámetros con comillas y asegurarse de que los nombres sean correctos
        $command = "\"{$python}\" \"{$script}\" \"{$imagesPath}\" \"{$nombreEstudiante}\" 2>&1";

        // Ejecutar el script y capturar salida
        $output = shell_exec($command);

        // Manejar error si no hay salida
        if ($output === null) {
            \Log::error("Error al ejecutar script. Comando: $command");
            return response()->json(['error' => 'No se pudo ejecutar el script'], 500);
        }

        // Devolver la salida
        return response()->json([
            'success' => strpos($output, 'ERROR') === false,
            'output' => $output,
            'command' => $command
        ]);
    }
}
