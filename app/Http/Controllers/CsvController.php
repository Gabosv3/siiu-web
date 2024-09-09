<?php

namespace App\Http\Controllers;

use Dotenv\Store\File\Reader;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function showForm()
    {
        return view('inventario.hardware.upload');
    }


    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        // Leer el archivo CSV
        $csvData = $this->parseCsv($filePath);
        
        return view('inventario.hardware.upload', [
            'csv_data' => $csvData
        ]);
    }

    private function parseCsv($filePath)
    {
        $csvArray = ['header' => [], 'data' => []];
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            $header = fgetcsv($handle); // Leer encabezado
            $csvArray['header'] = $header;

            while (($data = fgetcsv($handle)) !== FALSE) {
                $csvArray['data'][] = array_combine($header, $data);
            }
            fclose($handle);
        }
        return $csvArray;
    }
}
