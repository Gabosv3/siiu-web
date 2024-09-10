<?php

namespace App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

use Maatwebsite\Excel\Concerns\ToArray;

class CsvController extends Controller
{
    public function showForm()
    {
        return view('inventario.hardware.upload');
    }


    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            
            try {
                $data = Excel::toArray(new class implements ToArray {
                    public function array(array $array)
                    {
                        return $array;
                    }
                }, $file);

                return response()->json(['data' => $data[0]], 200); // $data[0] contiene los datos del archivo CSV
            } catch (\Exception $e) {
                Log::error('CSV upload error: ' . $e->getMessage());
                return response()->json(['error' => 'Error processing file: ' . $e->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
