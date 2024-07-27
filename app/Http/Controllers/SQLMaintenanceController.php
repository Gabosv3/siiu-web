<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SQLMaintenanceController extends Controller
{

    public function index()
    {
        $tables = DB::select('SHOW TABLES');
        return view('components.sql_maintenance', compact('tables'));
    }

    public function download(Request $request)
    {
        $tables = $request->input('tables', []);

        if (empty($tables)) {
            return back()->with('error', 'No tables selected for download.');
        }

        $database = env('DB_DATABASE');
        $sqlFile = '';

        foreach ($tables as $table) {
            $createTable = DB::select("SHOW CREATE TABLE $table");
            $sqlFile .= $createTable[0]->{'Create Table'} . ";\n\n";

            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                $sqlFile .= 'INSERT INTO ' . $table . ' VALUES(';
                $sqlFile .= "'" . implode("','", array_map('addslashes', (array) $row)) . "'";
                $sqlFile .= ");\n";
            }
            $sqlFile .= "\n";
        }

        $fileName = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
        Storage::disk('local')->put($fileName, $sqlFile);


        return response()->download(storage_path('app/' . $fileName));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|mimes:sql',
        ]);
    
        $file = $request->file('sql_file');
        $filePath = $file->getRealPath();
    
        if (!$filePath) {
            return back()->with('error', 'Error finding the file path.');
        }
    
        $sql = file_get_contents($filePath);
    
       
    
        // Verificar el contenido del archivo SQL
        dd($sql);
    
        try {
            DB::unprepared($sql);
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing SQL: ' . $e->getMessage());
        }
    
        return back()->with('success', 'Data uploaded successfully.');
    }
}
