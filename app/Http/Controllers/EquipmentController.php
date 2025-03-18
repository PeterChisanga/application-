<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Trip;
use App\Models\Spare;
use App\Models\EquipmentInsurance;
use App\Models\EquipmentTax;
use App\Models\MachineryUsage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;

use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;


class EquipmentController extends Controller {

    public function index() {
        try {
            $equipments = Equipment::with('trips')->get();
            return view('equipments.index', compact('equipments'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to fetch equipment.');
        }
    }

    //future update search functionality
    // public function index(Request $request) {
    //     try {
    //         $query = Equipment::query();

    //         $search = $request->input('search');

    //         if ($search) {
    //             $query->where('registration_number', 'LIKE', "%{$search}%")
    //                 ->orWhere('equipment_name', 'LIKE', "%{$search}%")
    //                 ->orWhere('asset_code', 'LIKE', "%{$search}%");
    //         }

    //         $equipments = $query->orderBy('created_at', 'desc')->get();

    //         return view('equipments.index', compact('equipments', 'search'));
    //     } catch (Exception $e) {
    //         return redirect()->back()->with('error', 'Failed to fetch equipment.');
    //     }
    // }

    public function create() {
        try {
            return view('equipments.create');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'asset_code' => 'nullable|string|max:255|unique:equipments,asset_code',
                'registration_number' => 'nullable|string|max:255',
                'chassis_number' => 'nullable|string|max:255|unique:equipments,chassis_number',
                'engine_number' => 'nullable|string|max:255|unique:equipments,engine_number',
                'type' => 'required|in:HMV,LMV,Machinery',
                'ownership' => 'required|string|max:255',
                'equipment_name' => 'required|string|max:255',
                'date_purchased' => 'required|date',
                'value' => 'required|numeric|min:0',
                'pictures.*' => 'nullable|image|max:2048', // Validate each uploaded image (max 2MB)
            ]);

            // Handle file uploads
            $pictures = [];
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('equipment_pictures', 'public');
                        $pictures[] = $path;
                    }
                }
            }

            $equipment = Equipment::create([
                'asset_code' => $request->asset_code,
                'registration_number' => $request->registration_number,
                'chassis_number' => $request->chassis_number,
                'engine_number' => $request->engine_number,
                'type' => $request->type,
                'ownership' => $request->ownership,
                'equipment_name' => $request->equipment_name,
                'date_purchased' => $request->date_purchased,
                'value' => $request->value,
                'status' => $request->status,
                'pictures' => json_encode($pictures), // Store file paths as JSON
            ]);

            return redirect()->route('equipments.index')->with('success', 'Equipment ' . ($equipment->registration_number ?? $equipment->asset_code ) . ' added successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing equipment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add equipment: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Equipment $equipment) {
        try {
            $equipment->load(['trips.driver', 'trips.fuels', 'spares', 'equipmentInsurances', 'equipmentTaxes']);
            return view('equipments.show', compact('equipment'));
        } catch (\Exception $e) {
            \Log::error('Error showing equipment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching equipment details.');
        }
    }

    public function edit(Equipment $equipment) {
        try {
            return view('equipments.edit', compact('equipment'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function update(Request $request, Equipment $equipment) {
        try {
            $request->validate([
                'asset_code' => 'nullable|string|max:255|unique:equipments,asset_code,' . $equipment->id,
                'registration_number' => 'nullable|string|max:255',
                'chassis_number' => 'nullable|string|max:255|unique:equipments,chassis_number,' . $equipment->id,
                'engine_number' => 'nullable|string|max:255|unique:equipments,engine_number,' . $equipment->id,
                'type' => 'required|in:HMV,LMV,Machinery',
                'ownership' => 'required|string|max:255',
                'equipment_name' => 'required|string|max:255',
                'date_purchased' => 'required|date',
                'value' => 'required|numeric|min:0',
                'pictures.*' => 'nullable|image|max:2048', // Validate each uploaded image
            ]);

            // Handle file uploads while preserving existing pictures
            $pictures = $equipment->pictures ? json_decode($equipment->pictures, true) : [];
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('equipment_pictures', 'public');
                        $pictures[] = $path;
                    }
                }
            }

            $equipment->update([
                'asset_code' => $request->asset_code,
                'registration_number' => $request->registration_number,
                'chassis_number' => $request->chassis_number,
                'engine_number' => $request->engine_number,
                'type' => $request->type,
                'ownership' => $request->ownership,
                'equipment_name' => $request->equipment_name,
                'date_purchased' => $request->date_purchased,
                'value' => $request->value,
                'status' => $request->status,
                'pictures' => json_encode($pictures), // Update with new picture paths
            ]);

            return redirect()->route('equipments.index')->with('success', 'Equipment ' . ($equipment->registration_number ?? $equipment->asset_code ?? '') . ' updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating equipment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update equipment: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Equipment $equipment) {
        try {
            // $equipment->delete();
            // return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete equipment.');
        }
    }

    //----------------------Bulk Equipment Upload with excel----------------------
    public function showUploadForm() {
        return view('equipments.upload');
    }

    public function upload(Request $request) {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            $import = new \App\Imports\EquipmentImport();

            Excel::import($import, $request->file('file'));

            $failures = $import->failures(); // Use failures() method for consistency

            if (!empty($failures)) {
                foreach ($failures as $failure) {
                    $rowNumber = $failure->row();
                    $attribute = $failure->attribute(); // Single string, not an array
                    $errors = implode(', ', $failure->errors()); // Errors is an array
                    \Log::warning("Row {$rowNumber} failed: Attribute [{$attribute}] - {$errors}");
                }

                return redirect()->route('equipments.index')
                    ->with('warning', 'Some rows failed to process. Ensure dates are in D/M/YYYY format (e.g., 19/4/2025), all other field are correct in your excel sheet. Check logs for details.');
            }

            return redirect()->route('equipments.index')
                ->with('success', 'File uploaded and data processed successfully.');
        } catch (\Exception $e) {
            \Log::error('Error during employee import: ' . $e->getMessage() . ' - Stack Trace: ' . $e->getTraceAsString());

            return redirect()->route('equipments.index')
                ->with('error', 'File processing failed. Ensure dates are in D/M/YYYY format (e.g., 1/1/2025), all other field are correct in your excel sheet. and check logs for details.');
        }
    }

    // -------------------Trip Methods----------------
    public function storeTrip(Request $request) {
        try {
            $tripValidated = $request->validate([
                'equipment_id' => 'required|exists:equipments,id',
                'driver_id' => 'required|exists:employees,id',
                'departure_date' => 'required|date',
                'return_date' => 'nullable|date|after_or_equal:departure_date',
                'start_kilometers' => 'required|integer|min:0',
                'end_kilometers' => 'nullable|integer|gte:start_kilometers',
                'location' => 'required|string|max:255',
                'material_delivered' => 'nullable|string|max:255',
                'quantity' => 'nullable|numeric|min:0',
                'fuels' => 'required|array|min:1', // At least one fuel entry
                'fuels.*.litres_added' => 'required|numeric|min:0',
                'fuels.*.refuel_location' => 'nullable|string|max:255',
            ]);

            // Generate unique trip number in format YYYYMMDD-RRRR e.g., 20250226-7756
            $datePrefix = now()->format('Ymd');
            do {
                $randomNumber = rand(1000, 9999);
                $tripNumber = "$datePrefix-$randomNumber";
            } while (Trip::where('trip_number', $tripNumber)->exists());

            $tripValidated['trip_number'] = $tripNumber;

            DB::transaction(function () use ($tripValidated) {
                $trip = Trip::create(Arr::except($tripValidated, ['fuels']));

                foreach ($tripValidated['fuels'] as $fuelData) {
                    $trip->fuels()->create([
                        'trip_id' => $trip->id,
                        'machinery_usage_id' => null, // Explicitly set to null for vehicle trips
                        'litres_added' => $fuelData['litres_added'],
                        'refuel_location' => $fuelData['refuel_location'],
                    ]);
                }
            });

            return redirect()->route('equipments.show', $tripValidated['equipment_id'])->with('success', 'Trip and fuel records added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing trip and fuels: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add trip and fuel records please try again');
        }
    }

    public function editTrip(Trip $trip) {
        try {
            $trip->load('fuels');

            $response = [
                'equipment_id' => $trip->equipment_id,
                'driver_id' => $trip->driver_id,
                'trip_number' => $trip->trip_number, // Optional, not used in form but included for completeness
                'departure_date' => $trip->departure_date->toDateString(), // Format as YYYY-MM-DD
                'return_date' => $trip->return_date ? $trip->return_date->toDateString() : null,
                'start_kilometers' => $trip->start_kilometers,
                'end_kilometers' => $trip->end_kilometers,
                'location' => $trip->location,
                'material_delivered' => $trip->material_delivered,
                'quantity' => $trip->quantity,
                'fuels' => $trip->fuels->map(function ($fuel) {
                    return [
                        'id' => $fuel->id,
                        'litres_added' => $fuel->litres_added,
                        'refuel_location' => $fuel->refuel_location,
                    ];
                })->toArray(),
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error fetching trip for edit: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch trip data'], 500);
        }
    }

    public function updateTrip(Request $request, Trip $trip) {
        try {
            $tripValidated = $request->validate([
                'equipment_id' => 'required|exists:equipments,id',
                'driver_id' => 'required|exists:employees,id',
                'departure_date' => 'required|date',
                'return_date' => 'nullable|date|after_or_equal:departure_date',
                'start_kilometers' => 'required|integer|min:0',
                'end_kilometers' => 'nullable|integer|gte:start_kilometers',
                'location' => 'required|string|max:255',
                'material_delivered' => 'nullable|string|max:255',
                'quantity' => 'nullable|numeric|min:0',
                'fuels' => 'required|array|min:1', // At least one fuel entry
                'fuels.*.id' => 'nullable|exists:fuels,id', // For existing fuel records
                'fuels.*.litres_added' => 'required|numeric|min:0',
                'fuels.*.refuel_location' => 'nullable|string|max:255',
            ]);

            // Use a transaction to update trip and fuels together
            DB::transaction(function () use ($trip, $tripValidated) {
                // Update the trip (trip_number remains unchanged)
                $trip->update(Arr::except($tripValidated, ['fuels']));

                // Sync fuel entries: update existing, create new, delete removed
                $existingFuelIds = $trip->fuels->pluck('id')->toArray();
                $submittedFuelIds = array_filter(array_column($tripValidated['fuels'], 'id'));

                // Delete fuels not in the submitted list
                $trip->fuels()->whereNotIn('id', $submittedFuelIds)->delete();

                // Update or create fuel entries
                foreach ($tripValidated['fuels'] as $fuelData) {
                    if (isset($fuelData['id']) && in_array($fuelData['id'], $existingFuelIds)) {
                        // Update existing fuel
                        $trip->fuels()->where('id', $fuelData['id'])->update([
                            'litres_added' => $fuelData['litres_added'],
                            'refuel_location' => $fuelData['refuel_location'],
                        ]);
                    } else {
                        // Create new fuel
                        $trip->fuels()->create([
                            'trip_id' => $trip->id,
                            'machinery_usage_id' => null,
                            'litres_added' => $fuelData['litres_added'],
                            'refuel_location' => $fuelData['refuel_location'],
                        ]);
                    }
                }
            });

            return response()->json(['success' => true, 'message' => 'Trip and fuel records updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating trip and fuels: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update trip and fuel records: ' . $e->getMessage()], 500);
        }
    }

    public function getLastTripEndKilometers($equipmentId) {
        try {
            $lastTrip = Trip::where('equipment_id', $equipmentId)
                ->orderBy('return_date', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            $response = [
                'start_kilometers' => $lastTrip ? $lastTrip->start_kilometers : 0,
                'end_kilometers' => $lastTrip ? $lastTrip->end_kilometers : null,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error fetching last trip end kilometers: ' . $e->getMessage());
            return response()->json([
                'start_kilometers' => 0,
                'end_kilometers' => null,
                'error' => 'Failed to fetch last trip details',
            ], 500);
        }
    }

    // -------------------Equipment Report Generation Methods -------------------------------
    public function generate(Request $request) {
        $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2000|max:' . date('Y'),
            'format' => 'required|in:csv,pdf',
        ]);

        try {
            $equipment = Equipment::with('trips.fuels')->findOrFail($request->equipment_id);

            $month = $request->month;
            $year = $request->year;
            $format = $request->format;

            $data = $equipment->trips()
                ->whereMonth('departure_date', $month)
                ->whereYear('departure_date', $year)
                ->orderBy('departure_date', 'asc')
                ->with('fuels') // Ensure fuels relationship is loaded
                ->get();

            if ($data->isEmpty()) {
                return back()->with('error', 'No trip information found for the selected month and year.');
            }

            if ($format === 'pdf') {
                return $this->generatePDF($data, $equipment, $month, $year);
            } elseif ($format === 'csv') {
                return $this->generateCSV($data, $equipment, $month, $year);
            }

            return back()->with('error', 'Invalid format selected.');
        } catch (\Exception $e) {
            \Log::error('Error generating report: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while generating the report: ' . $e->getMessage());
        }
    }

    private function generatePDF($data, Equipment $equipment, $month, $year) {
        $pdf = Pdf::loadView('reports.equipment_pdf', compact('data', 'equipment', 'month', 'year'));
        return $pdf->download("equipment_report_{$equipment->registration_number}_{$month}_{$year}.pdf");
    }

    // private function generateCSV($data, Equipment $equipment, $month, $year) {
    //     try {
    //         $spreadsheet = new Spreadsheet();
    //         $sheet = $spreadsheet->getActiveSheet();

    //         // Set the title
    //         $title = "{$equipment->registration_number} - {$equipment->equipment_name} - {$equipment->type} - {$month}/{$year}";
    //         $sheet->setCellValue('A1', $title);
    //         $sheet->mergeCells('A1:K1');
    //         $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //         $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

    //         // Add headers
    //         $sheet->setCellValue('A2', '#')
    //             ->setCellValue('B2', 'Departure Date')
    //             ->setCellValue('C2', 'Return Date')
    //             ->setCellValue('D2', 'Start Km')
    //             ->setCellValue('E2', 'Close Km')
    //             ->setCellValue('F2', 'Distance Travelled')
    //             ->setCellValue('G2', 'Location')
    //             ->setCellValue('H2', 'Material Delivered')
    //             ->setCellValue('I2', 'Material Qty (tonnes)')
    //             ->setCellValue('J2', 'Fuel Logs')
    //             ->setCellValue('K2', 'Total Fuel Used (Litres)');

    //         // Style headers
    //         $headerStyle = [
    //             'font' => ['bold' => true],
    //             'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    //             'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    //             'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D3D3D3']]
    //         ];
    //         $sheet->getStyle('A2:K2')->applyFromArray($headerStyle);

    //         // Add data rows
    //         $row = 3;
    //         $totalFuelUsed = 0;
    //         $totalDistanceTravelled = 0;
    //         $totalMaterialDelivered = 0;

    //         foreach ($data as $trip) {
    //             $fuelLogs = $trip->fuels->map(function ($fuel) {
    //                 return "{$fuel->litres_added}L at {$fuel->refuel_location}";
    //             })->implode(' | ');

    //             $departureDate = $trip->departure_date
    //                 ? Carbon::parse($trip->departure_date)->format('Y/m/d')
    //                 : '-';

    //             $returnDate = $trip->return_date
    //                 ? Carbon::parse($trip->return_date)->format('Y/m/d')
    //                 : '-';

    //             $distanceTravelled = ($trip->end_kilometers && $trip->start_kilometers) ? ($trip->end_kilometers - $trip->start_kilometers) : 0;
    //             $materialDelivered = $trip->quantity ?? 0;

    //             $sheet->setCellValue('A' . $row, $row - 2)
    //                 ->setCellValue('B' . $row, $departureDate)
    //                 ->setCellValue('C' . $row, $returnDate)
    //                 ->setCellValue('D' . $row, $trip->start_kilometers)
    //                 ->setCellValue('E' . $row, $trip->end_kilometers ?? 0)
    //                 ->setCellValue('F' . $row, $distanceTravelled)
    //                 ->setCellValue('G' . $row, $trip->location)
    //                 ->setCellValue('H' . $row, $trip->material_delivered ?? '-')
    //                 ->setCellValue('I' . $row, $materialDelivered)
    //                 ->setCellValue('J' . $row, $fuelLogs ?: 'No fuel data')
    //                 ->setCellValue('K' . $row, number_format($trip->fuels->sum('litres_added'), 2));

    //             // Update totals
    //             $totalFuelUsed += $trip->fuels->sum('litres_added');
    //             $totalDistanceTravelled += $distanceTravelled;
    //             $totalMaterialDelivered += $materialDelivered;

    //             $row++;
    //         }

    //         // Summary section
    //         $summaryRow = $row + 2;
    //         $sheet->setCellValue('A' . $summaryRow, 'Summary')
    //             ->setCellValue('F' . $summaryRow, 'Total Distance Travelled:')
    //             ->setCellValue('G' . $summaryRow, number_format($totalDistanceTravelled, 2) . ' Km')
    //             ->setCellValue('F' . ($summaryRow + 1), 'Total Fuel Used:')
    //             ->setCellValue('G' . ($summaryRow + 1), number_format($totalFuelUsed, 2) . ' Litres')
    //             ->setCellValue('F' . ($summaryRow + 2), 'Total Material Delivered:')
    //             ->setCellValue('G' . ($summaryRow + 2), number_format($totalMaterialDelivered, 2) . ' Tonnes');

    //         // Style summary section
    //         $summaryStyle = [
    //             'font' => ['bold' => true],
    //             'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
    //         ];
    //         $sheet->getStyle('A' . $summaryRow . ':G' . ($summaryRow + 2))->applyFromArray($summaryStyle);

    //         // Auto-size columns
    //         foreach (range('A', 'K') as $column) {
    //             $sheet->getColumnDimension($column)->setAutoSize(true);
    //         }

    //         // Save the file
    //         $filename = "equipment_report_{$equipment->registration_number}_{$month}_{$year}.xlsx";
    //         $filePath = storage_path("app/public/{$filename}");
    //         $writer = new Xlsx($spreadsheet);
    //         $writer->save($filePath);

    //         return response()->download($filePath, $filename)->deleteFileAfterSend();
    //     } catch (\Exception $e) {
    //         \Log::error('CSV Generation Error: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Failed to generate CSV file: ' . $e->getMessage())->withInput();
    //     }
    // }

    private function generateCSV($data, Equipment $equipment, $month, $year) {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set the title
            $title = "{$equipment->registration_number} - {$equipment->equipment_name} - {$equipment->type} - {$month}/{$year}";
            $sheet->setCellValue('A1', $title);
            $sheet->mergeCells('A1:K1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

            // Add headers for trips
            $sheet->setCellValue('A2', '#')
                ->setCellValue('B2', 'Departure Date')
                ->setCellValue('C2', 'Return Date')
                ->setCellValue('D2', 'Start Km')
                ->setCellValue('E2', 'Close Km')
                ->setCellValue('F2', 'Distance Travelled')
                ->setCellValue('G2', 'Location')
                ->setCellValue('H2', 'Material Delivered')
                ->setCellValue('I2', 'Material Qty (tonnes)')
                ->setCellValue('J2', 'Fuel Logs')
                ->setCellValue('K2', 'Total Fuel Used (Litres)');

            // Style headers for trips
            $headerStyle = [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D3D3D3']]
            ];
            $sheet->getStyle('A2:K2')->applyFromArray($headerStyle);

            // Add data rows for trips
            $row = 3;
            $totalFuelUsed = 0;
            $totalDistanceTravelled = 0;
            $totalMaterialDelivered = 0;

            foreach ($data as $trip) {
                $fuelLogs = $trip->fuels->map(function ($fuel) {
                    return "{$fuel->litres_added}L at {$fuel->refuel_location}";
                })->implode(' | ');

                $departureDate = $trip->departure_date ? Carbon::parse($trip->departure_date)->format('Y/m/d') : '-';
                $returnDate = $trip->return_date ? Carbon::parse($trip->return_date)->format('Y/m/d') : '-';
                $distanceTravelled = ($trip->end_kilometers && $trip->start_kilometers) ? ($trip->end_kilometers - $trip->start_kilometers) : 0;
                $materialDelivered = $trip->quantity ?? 0;

                $sheet->setCellValue('A' . $row, $row - 2)
                    ->setCellValue('B' . $row, $departureDate)
                    ->setCellValue('C' . $row, $returnDate)
                    ->setCellValue('D' . $row, $trip->start_kilometers)
                    ->setCellValue('E' . $row, $trip->end_kilometers ?? 0)
                    ->setCellValue('F' . $row, $distanceTravelled)
                    ->setCellValue('G' . $row, $trip->location)
                    ->setCellValue('H' . $row, $trip->material_delivered ?? '-')
                    ->setCellValue('I' . $row, $materialDelivered)
                    ->setCellValue('J' . $row, $fuelLogs ?: 'No fuel data')
                    ->setCellValue('K' . $row, number_format($trip->fuels->sum('litres_added'), 2));

                // Update totals
                $totalFuelUsed += $trip->fuels->sum('litres_added');
                $totalDistanceTravelled += $distanceTravelled;
                $totalMaterialDelivered += $materialDelivered;

                $row++;
            }

            // Add a separator row
            $row++;
            $sheet->setCellValue('A' . $row, 'Associated Costs');
            $sheet->mergeCells('A' . $row . ':K' . $row);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Add headers for associated costs
            $row++;
            $sheet->setCellValue('A' . $row, 'Type')
                ->setCellValue('B' . $row, 'Details')
                ->setCellValue('C' . $row, 'Amount (ZMK)')
                ->setCellValue('D' . $row, 'Expiry Date');

            // Style headers for associated costs
            $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray($headerStyle);

            // Add data rows for insurances
            $row++;
            foreach ($equipment->equipmentInsurances as $insurance) {
                $sheet->setCellValue('A' . $row, 'Insurance')
                    ->setCellValue('B' . $row, $insurance->insurance_company)
                    ->setCellValue('C' . $row, ($insurance->premium > 0) ? number_format($insurance->premium, 2) : '-')
                    ->setCellValue('D' . $row, $insurance->expiry_date->format('Y/m/d'));
                $row++;
            }

            // Add data rows for taxes
            foreach ($equipment->equipmentTaxes as $tax) {
                $sheet->setCellValue('A' . $row, 'Tax')
                    ->setCellValue('B' . $row, $tax->name)
                    ->setCellValue('C' . $row, ($tax->cost > 0) ? number_format($tax->cost, 2) : '-')
                    ->setCellValue('D' . $row, $tax->expiry_date->format('Y/m/d'));
                $row++;
            }

            // Add data rows for spares
            foreach ($equipment->spares as $spare) {
                $sheet->setCellValue('A' . $row, 'Spares')
                    ->setCellValue('B' . $row, $spare->name)
                    ->setCellValue('C' . $row, ($spare->price > 0) ? number_format($spare->price, 2) : '-' )
                    ->setCellValue('D' . $row, '-');
                $row++;
            }

            // Add a separator row before the summary
            $row++;
            $sheet->setCellValue('A' . $row, 'Summary');
            $sheet->mergeCells('A' . $row . ':K' . $row);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Fetch related costs
            $totalInsuranceCost = $equipment->equipmentInsurances()->sum('premium');
            $totalTaxCost = $equipment->equipmentTaxes()->sum('cost');
            $totalSpareCost = $equipment->spares()->sum('price'); // Adjust if needed for actual cost

            // Summary section
            $summaryRow = $row + 1;
            $sheet->setCellValue('A' . $summaryRow, 'Summary')
                ->setCellValue('F' . $summaryRow, 'Total Distance Travelled:')
                ->setCellValue('G' . $summaryRow, number_format($totalDistanceTravelled, 2) . ' Km')
                ->setCellValue('F' . ($summaryRow + 1), 'Total Fuel Used:')
                ->setCellValue('G' . ($summaryRow + 1), number_format($totalFuelUsed, 2) . ' Litres')
                ->setCellValue('F' . ($summaryRow + 2), 'Total Material Delivered:')
                ->setCellValue('G' . ($summaryRow + 2), number_format($totalMaterialDelivered, 2) . ' Tonnes')
                ->setCellValue('F' . ($summaryRow + 3), 'Total Insurance Cost:')
                ->setCellValue('G' . ($summaryRow + 3), ($totalInsuranceCost > 0) ? number_format($totalInsuranceCost, 2). ' ZMK' : '- ZMK')
                ->setCellValue('F' . ($summaryRow + 4), 'Total Tax Cost:')
                ->setCellValue('G' . ($summaryRow + 4), ($totalTaxCost > 0) ? number_format($totalTaxCost, 2) . ' ZMK' : '- ZMK')
                ->setCellValue('F' . ($summaryRow + 5), 'Total Spare Cost:')
                ->setCellValue('G' . ($summaryRow + 5), ($totalSpareCost > 0) ? number_format($totalSpareCost, 2) . ' ZMK' : '- ZMK');

            // Style summary section
            $summaryStyle = [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ];
            $sheet->getStyle('A' . $summaryRow . ':G' . ($summaryRow + 5))->applyFromArray($summaryStyle);

            // Auto-size columns
            foreach (range('A', 'K') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Save the file
            $filename = "equipment_report_{$equipment->registration_number}_{$month}_{$year}.xlsx";
            $filePath = storage_path("app/public/{$filename}");
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            return response()->download($filePath, $filename)->deleteFileAfterSend();
        } catch (\Exception $e) {
            \Log::error('CSV Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate CSV file: ' . $e->getMessage())->withInput();
        }
    }

    // ------------------Machinery Usage Methods --------------------------------
    public function storeMachineryUsage(Request $request) {
        try {
            $validated = $request->validate([
                'equipment_id' => 'required|exists:equipments,id',
                'operator_id' => 'required|exists:employees,id',
                'date' => 'required|date',
                'start_hours' => 'required|numeric|min:0',
                'closing_hours' => 'nullable|numeric|gt:start_hours',
                'location' => 'required|string|max:255',
                'fuels' => 'required|array|min:1',
                'fuels.*.litres_added' => 'required|numeric|min:0',
                'fuels.*.refuel_location' => 'nullable|string|max:255',
            ]);

            \DB::transaction(function () use ($validated) {
                $usage = MachineryUsage::create([
                    'equipment_id' => $validated['equipment_id'],
                    'operator_id' => $validated['operator_id'],
                    'date' => $validated['date'],
                    'start_hours' => $validated['start_hours'],
                    'closing_hours' => $validated['closing_hours'],
                    'location' => $validated['location'],
                ]);

                foreach ($validated['fuels'] as $fuelData) {
                    $usage->fuels()->create([
                        'trip_id' => null,
                        'machinery_usage_id' => $usage->id,
                        'litres_added' => $fuelData['litres_added'],
                        'refuel_location' => $fuelData['refuel_location'],
                    ]);
                }
            });

            return redirect()->route('equipments.show', $validated['equipment_id'])->with('success', 'Machinery usage and fuel records added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing machinery usage: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add machinery usage: ' . $e->getMessage());
        }
    }

    public function lastMachineryUsage($equipment_id) {
        $lastUsage = MachineryUsage::where('equipment_id', $equipment_id)
            ->orderBy('date', 'desc')
            ->first();

        return response()->json([
            'start_hours' => $lastUsage ? $lastUsage->start_hours : 0,
            'closing_hours' => $lastUsage ? $lastUsage->closing_hours : null,
        ]);
    }

    // ---------------------------Spares Methods-------------------------
    public function createSpare(Equipment $equipment) {
        return view('spares.create', compact('equipment'));
    }

    public function storeSpare(Request $request) {
        try {
            $validated = $request->validate([
                'equipment_id' => 'required|exists:equipments,id',
                'name' => 'required|string|max:255',
                'quantity' => 'required|numeric|min:0',
            ]);

            Spare::create($validated);

            return redirect()->route('equipments.show', $validated['equipment_id'])
                             ->with('success', 'Spare part registered successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing spare: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to register spare part: ' . $e->getMessage())->withInput();
        }
    }

    // ---------------------------Insurance Methods-------------------------
    public function createInsurance(Equipment $equipment) {
        return view('equipment-insurances.create', compact('equipment'));
    }

    public function storeInsurance(Request $request) {
        try {
            $validated = $request->validate([
                'equipment_id' => 'required|exists:equipments,id',
                'insurance_company' => 'required|string|max:255',
                'phone_number' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'premium' => 'required|numeric|min:0',
                'expiry_date' => 'required|date',
            ]);

            EquipmentInsurance::create($validated);

            return redirect()->route('equipments.show', $validated['equipment_id'])
                             ->with('success', 'Insurance registered successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing insurance: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to register insurance: ' . $e->getMessage())->withInput();
        }
    }

    // ---------------------------Tax Methods------------------------
    public function createTax(Equipment $equipment) {
        return view('taxes.create', compact('equipment'));
    }

    public function storeTax(Request $request) {
        try {
            $validated = $request->validate([
                'equipment_id' => 'required|exists:equipments,id',
                'name' => 'required|string|max:255',
                'cost' => 'required|numeric|min:0',
                'expiry_date' => 'required|date',
            ]);

            EquipmentTax::create($validated);

            return redirect()->route('equipments.show', $validated['equipment_id'])
                             ->with('success', 'Tax registered successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing tax: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to register tax: ' . $e->getMessage())->withInput();
        }
    }
}
