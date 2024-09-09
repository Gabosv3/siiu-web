<?php

namespace App\Http\Controllers;

use Dotenv\Store\File\Reader;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CsvController extends Controller
{
    public function showForm()
    {
        return view('inventario.hardware.upload');
    }


    public function upload(Request $request)
    {
        // Validar el archivo CSV
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($request->hasFile('csv_file')) {
            // Obtener el archivo
            $file = $request->file('csv_file');
            $path = $file->getRealPath(); // Obtener la ruta real del archivo

            try {
                // Crear el lector CSV
                $csv = Reader::createFromPath($path, 'r');
                $csv->setHeaderOffset(0); // Asume que la primera fila contiene encabezados

                // Leer las filas del CSV
                $records = $csv->getRecords(); // Esto devuelve un iterable de registros

                // Procesar los datos del CSV
                $data = [];
                foreach ($records as $record) {
                    $data[] = $record; // Puedes procesar y almacenar estos datos como desees
                }

                return response()->json(['data' => $data], 200);
            } catch (\Exception $e) {
                Log::error('CSV upload error: ' . $e->getMessage());
                return response()->json(['error' => 'Error processing file: ' . $e->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
