<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\HardwareFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class HardwareFileController extends Controller
{

    /**
     * Stores a new file related to a hardware record.
     *
     * Validates the incoming request data, including file upload.
     * Saves the uploaded file to the storage and records the file information
     * in the database associated with a specific hardware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'name' => 'required|string|max:255', // El nombre es obligatorio
            'description' => 'required|string', // La descripción es obligatoria
            'file-upload' => 'required|file|mimes:pdf,docx|max:10240', // Aceptamos PDF, DOCX, y imágenes
            'hardware_id' => 'required|exists:hardware,id' // Asegurarse de que el hardware_id sea válido
        ], [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no debe superar los 255 caracteres',
            'description.required' => 'La descripción es obligatoria',
            'description.max' => 'La descripción no debe superar los 65535 caracteres',
            'file-upload.required' => 'El archivo es obligatorio',
            'file-upload.file' => 'El archivo debe ser un archivo',
            'file-upload.mimes' => 'El archivo debe ser un archivo PDF, DOCX o imagen',
            'file-upload.max' => 'El archivo no debe superar los 10 MB',
            'hardware_id.required' => 'El hardware es obligatorio',
            'hardware_id.exists' => 'El hardware no existe',
        ]);

        // Obtener el hardware_id desde el formulario
        $hardware_id = $request->input('hardware_id');

        // Guardar el archivo
        $file = $request->file('file-upload');
        $filePath = $file->store('public/files'); // Guardar en la carpeta storage/app/public/files

        // Aquí generamos la ubicación automáticamente
        $location = $filePath; // O puedes usar cualquier lógica que necesites

        // Guardar los detalles del archivo en la base de datos
        $fileData = [
            'hardware_id' => $hardware_id,  // Asignar el hardware_id recibido
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'location' => $location,
        ];

        // Guardar en la base de datos (ejemplo de modelo HardwareFile)
        $savedFile = HardwareFile::create($fileData);

        return response()->json([
            'success' => true,
            'message' => 'Archivo subido exitosamente.',
            'file' => $savedFile,
        ]);
    }

    /**
     * Descarga un archivo asociado a un hardware específico.
     *
     * Busca y verifica la existencia del archivo por su ID en la base de datos.
     * Comprueba que el archivo exista físicamente en el almacenamiento.
     * Valida que el tipo de archivo sea permitido (.pdf, .doc, .docx).
     * Inicia la descarga del archivo con el nombre y extensión adecuados.
     *
     * @param  int  $id  El ID del archivo a descargar.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */

    public function downloadFile($id)
    {
        // Obtener el archivo por ID
        $file = HardwareFile::find($id);

        // Verificar si el archivo existe
        if (!$file) {
            return abort(404, 'Archivo no encontrado');
        }

        // Obtener la ruta completa del archivo
        $filePath = storage_path('app/' . $file->location);

        // Verificar que el archivo realmente exista en la ruta
        if (!file_exists($filePath)) {
            return abort(404, 'Archivo no encontrado');
        }

        // Obtener la extensión del archivo
        $extension = strtolower(pathinfo($file->location, PATHINFO_EXTENSION));

        // Validar si la extensión es válida (solo .pdf, .doc, .docx)
        if (!in_array($extension, ['pdf', 'doc', 'docx'])) {
            return abort(403, 'Tipo de archivo no permitido');
        }

        // Nombre del archivo con la extensión
        $fileNameWithExtension = $file->name . '.' . $extension;

        // Descargar el archivo con el nombre y la extensión correcta
        return response()->download($filePath, $fileNameWithExtension, [
            'Content-Type' => $extension == 'pdf' ? 'application/pdf' : 'application/msword',
        ]);
    }

    /**
     * Elimina un archivo asociado a un hardware.
     *
     * Busca el archivo por su ID, lo elimina físicamente del almacenamiento
     * y luego lo elimina de la base de datos. Redirige a la vista del hardware
     * correspondiente con un mensaje de éxito.
     *
     * @param int $id El ID del archivo a eliminar.
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        // Encuentra el archivo por su ID
        $file = HardwareFile::findOrFail($id);

        // Elimina el archivo físicamente
        if (Storage::exists($file->location)) {
            Storage::delete($file->location);
        }

        // Elimina el archivo de la base de datos
        $file->delete();

        // Redirige con mensaje de éxito
        return redirect()->route('hardwares.show', $file->hardware_id)->with('success')->with('success', 'Archivo eliminado correctamente');
    }
}
