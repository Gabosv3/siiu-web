<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Category;
use App\Models\Hardware;
use App\Models\ServiceSheet;
use App\Models\Supply;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceSheetController extends Controller
{
    // Mostrar la vista de la hoja de servicio
    public function create($id)
    {
        $CategoryListhardware = Category::where('type', 'Equipo')->get();
        $CategoryListInsumo = Category::where('type', 'Insumo')->get();
        $data = Assignment::with(['technician.user', 'ticket'])->find($id);
        $supplies = Supply::all();
        return view('service_sheets.create', compact('CategoryListhardware', 'CategoryListInsumo', 'data', 'supplies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sheets' => 'required|array',
            'sheets.*.hardware_id' => 'required|exists:hardware,id',
            'sheets.*.ticket_id' => 'required|exists:tickets,id',
            'sheets.*.task' => 'required|string',
            'sheets.*.initial_date' => 'required|date',
            'sheets.*.status' => 'required|string',
            'sheets.*.description' => 'required|string',
            'sheets.*.observations' => 'nullable|string',
            'sheets.*.supplies' => 'array',
            'sheets.*.supplies.*' => 'exists:supplies,id',
        ]);

        foreach ($request->sheets as $sheetData) {
            $serviceSheet = ServiceSheet::create([
                'date' => Carbon::now(),
                'department' => $sheetData['department'],
                'ticket_id' => $sheetData['ticket_id'],
                'task' => $sheetData['task'],
                'hardware_id' => $sheetData['hardware_id'],
                'status' => $sheetData['status'],
                'description' => $sheetData['description'],
                'observations' => $sheetData['observations'] ?? null,
            ]);

            if (!empty($sheetData['supplies'])) {
                $serviceSheet->supplies()->attach($sheetData['supplies']);
            }
        }

        return redirect()->route('service_sheets.index')->with('success', 'Hojas de servicio creadas exitosamente.');
    }
    public function show(ServiceSheet $serviceSheet)
    {
        return view('service_sheets.show', compact('serviceSheet'));
    }

    public function getHardware($categoryId)
    {
        $hardware = Hardware::where('category_id', $categoryId)->get();
        return response()->json($hardware);
    }

    public function getSupplies($categoryId)
    {
        $supplies = Supply::where('category_id', $categoryId)->get();
        return response()->json($supplies);
    }

    public function getHardwareDetails($id)
    {
        $hardware = Hardware::select(
            'model_id',
            'inventory_code',
            'serial_number',
            'warranty_expiration_date',
            'barcode_path',
            'status'
        )->with('model:id,name')->find($id);

        if (!$hardware) {
            return response()->json(['error' => 'Equipo no encontrado'], 404);
        }

        return response()->json([
            'model_name' => $hardware->model->name ?? 'Sin modelo',
            'inventory_code' => $hardware->inventory_code,
            'serial_number' => $hardware->serial_number,
            'warranty_expiration_date' => $hardware->warranty_expiration_date,
            'barcode_path' => asset("storage/" . $hardware->barcode_path),
            'status' => $hardware->status
        ]);
    }
}
