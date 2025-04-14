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
            $equipments = Equipment::with(['trips', 'machineryUsages'])
                ->orderBy('registration_number', 'asc') // Sort by equipment_name alphabetically
                ->get();

            return view('equipments.index', compact('equipments'));
        } catch (Exception $e) {
            \Log::error('Error fetching equipment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to fetch equipment.');
        }
    }

    // public function index() {
    //     try {
    //         $equipments = Equipment::with(['trips', 'machineryUsages', 'equipmentInsurances', 'equipmentTaxes'])
    //             ->orderBy('registration_number', 'asc')
    //             ->get()
    //             ->map(function ($equipment) {
    //                 $today = Carbon::today(); // April 11, 2025
    //                 $oneMonthFromNow = $today->copy()->addMonth(); // May 11, 2025

    //                 // Get latest insurance expiration date
    //                 $latestInsurance = $equipment->equipmentInsurances->sortByDesc('expiry_date')->first();
    //                 $latestInsuranceDate = $latestInsurance ? Carbon::parse($latestInsurance->expiration_date) : null;

    //                 // Get latest expiration dates for each tax type
    //                 $roadTax = $equipment->equipmentTaxes->where('name', 'ROAD TAX')->sortByDesc('expiry_date')->first();
    //                 $fitnessTax = $equipment->equipmentTaxes->where('name', 'FITNESS TAX')->sortByDesc('expiry_date')->first();
    //                 $identityTax = $equipment->equipmentTaxes->where('name', 'IDENTITY TAX')->sortByDesc('expiry_date')->first();

    //                 $roadTaxDate = $roadTax ? Carbon::parse($roadTax->expiration_date) : null;
    //                 $fitnessTaxDate = $fitnessTax ? Carbon::parse($fitnessTax->expiration_date) : null;
    //                 $identityTaxDate = $identityTax ? Carbon::parse($identityTax->expiration_date) : null;

    //                 // Check for expired (red)
    //                 $isExpired = ($latestInsuranceDate && $latestInsuranceDate->lt($today)) ||
    //                             ($roadTaxDate && $roadTaxDate->lt($today)) ||
    //                             ($fitnessTaxDate && $fitnessTaxDate->lt($today)) ||
    //                             ($identityTaxDate && $identityTaxDate->lt($today));

    //                 // Check for expiring within 1 month (yellow)
    //                 $isExpiringSoon = (!$isExpired && (
    //                     ($latestInsuranceDate && $latestInsuranceDate->lte($oneMonthFromNow)) ||
    //                     ($roadTaxDate && $roadTaxDate->lte($oneMonthFromNow)) ||
    //                     ($fitnessTaxDate && $fitnessTaxDate->lte($oneMonthFromNow)) ||
    //                     ($identityTaxDate && $identityTaxDate->lte($oneMonthFromNow))
    //                 ));

    //                 // Assign class based on conditions
    //                 if ($isExpired) {
    //                     $equipment->expiration_class = 'table-danger'; // Red for expired
    //                 } elseif ($isExpiringSoon) {
    //                     $equipment->expiration_class = 'table-warning'; // Yellow for expiring within 1 month
    //                 } else {
    //                     $equipment->expiration_class = ''; // No special class
    //                 }

    //                 return $equipment;
    //             });

    //         return view('equipments.index', compact('equipments'));
    //     } catch (Exception $e) {
    //         \Log::error('Error fetching equipment: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Failed to fetch equipment.');
    //     }
    // }

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
            $equipment->load([
                'trips' => function ($query) {
                    $query->orderBy('departure_date', 'asc');
                },
                'trips.driver',
                'trips.fuels',
                'machineryUsages' => function ($query) {
                    $query->orderBy('date', 'asc');
                },
                'machineryUsages.operator',
                'machineryUsages.fuels',
                'spares',
                'equipmentInsurances',
                'equipmentTaxes'
            ]);

            return view('equipments.show', compact('equipment'));
        } catch (\Exception $e) {
            Log::error('Error showing equipment: ' . $e->getMessage());
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
                'start_kilometers' => 'required|numeric|min:0',
                'end_kilometers' => 'nullable|numeric',// this should be added later on |gte:start_kilometers
                'location' => 'required|string|max:255',
                'material_delivered' => 'nullable|string|max:255',
                'quantity' => 'nullable|numeric|min:0',
                'fuels' => 'required|array|min:1', // At least one fuel entry
                'fuels.*.litres_added' => 'required|numeric|min:0',
                'fuels.*.cost' => 'nullable|numeric',
                'fuels.*.refuel_location' => 'nullable|string|max:255',
                'loading' => 'nullable|numeric',
                'council_fee' => 'nullable|numeric',
                'weighbridge' => 'nullable|numeric',
                'toll_gate' => 'nullable|numeric',
                'other_expenses' => 'nullable|numeric',
                'supplier_name' => 'nullable|string|max:255',
                'gross_weight' => 'nullable|numeric',
                'net_weight' => 'nullable|numeric',
                'tare_weight' => 'nullable|numeric',
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
                        'cost' => $fuelData['cost'] ?? null,
                        'refuel_location' => $fuelData['refuel_location'] ?? null,
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
                'departure_date' => $trip->departure_date->toDateString(), // Format as YYYY-MM-DD
                'return_date' => $trip->return_date ? $trip->return_date->toDateString() : null,
                'start_kilometers' => $trip->start_kilometers,
                'end_kilometers' => $trip->end_kilometers,
                'location' => $trip->location,
                'material_delivered' => $trip->material_delivered,
                'quantity' => $trip->quantity,
                'loading' => $trip->loading,
                'council_fee' => $trip->council_fee,
                'weighbridge' => $trip->weighbridge,
                'toll_gate' => $trip->toll_gate,
                'other_expenses' => $trip->other_expenses,
                'supplier_name' => $trip->supplier_name,
                'gross_weight' => $trip->gross_weight,
                'net_weight' => $trip->net_weight,
                'tare_weight' => $trip->tare_weight,

                'fuels' => $trip->fuels->map(function ($fuel) {
                    return [
                        'id' => $fuel->id,
                        'litres_added' => $fuel->litres_added,
                        'cost' => $fuel->cost,
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
                'start_kilometers' => 'required|numeric|min:0',
                'end_kilometers' => 'nullable|numeric', // should be added later on |gte:start_kilometers
                'location' => 'required|string|max:255',
                'material_delivered' => 'nullable|string|max:255',
                'quantity' => 'nullable|numeric|min:0',
                'fuels' => 'required|array|min:1', // At least one fuel entry
                'fuels.*.id' => 'nullable|exists:fuels,id', // For existing fuel records
                'fuels.*.litres_added' => 'required|numeric|min:0',
                'fuels.*.cost' => 'nullable|numeric',
                'fuels.*.refuel_location' => 'nullable|string|max:255',
                'loading' => 'nullable|numeric',
                'council_fee' => 'nullable|numeric',
                'weighbridge' => 'nullable|numeric',
                'toll_gate' => 'nullable|numeric',
                'other_expenses' => 'nullable|numeric',
                'supplier_name' => 'nullable|string|max:255',
                'gross_weight' => 'nullable|numeric',
                'net_weight' => 'nullable|numeric',
                'tare_weight' => 'nullable|numeric',
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
                            'cost' => $fuelData['cost'] ?? null,
                            'refuel_location' => $fuelData['refuel_location'] ?? null,
                        ]);
                    } else {
                        // Create new fuel
                        $trip->fuels()->create([
                            'trip_id' => $trip->id,
                            'machinery_usage_id' => null,
                            'litres_added' => $fuelData['litres_added'],
                            'cost' => $fuelData['cost'] ?? null,
                            'refuel_location' => $fuelData['refuel_location'] ?? null,
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

    // ------------------Machinery Usage Methods --------------------------------
    public function storeMachineryUsage(Request $request) {
        try {
            $validated = $request->validate([
                'equipment_id' => 'required|exists:equipments,id',
                'operator_id' => 'required|exists:employees,id',
                'date' => 'required|date',
                'start_hours' => 'required|numeric|min:0',
                'closing_hours' => 'nullable|numeric',// this should be added later |gt:start_hours
                'location' => 'required|string|max:255',
                'fuels' => 'required|array|min:1',
                'fuels.*.litres_added' => 'required|numeric|min:0',
                'fuels.*.cost' => 'nullable|numeric',
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
                        'cost' => $fuelData['cost'],
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

    // -------------------Equipment Report Generation Methods -------------------------------
    public function generate(Request $request) {
        $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:csv,pdf',
        ]);

        try {
            $equipment = Equipment::with(['trips.fuels', 'machineryUsages.fuels'])->findOrFail($request->equipment_id);

            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $format = $request->format;

            if ($equipment->type === 'Machinery') {
                $data = $equipment->machineryUsages()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date', 'asc')
                    ->with('fuels')
                    ->get();
                $dataType = 'machinery';
            } else {
                $data = $equipment->trips()
                    ->whereBetween('departure_date', [$startDate, $endDate])
                    ->orderBy('departure_date', 'asc')
                    ->with('fuels')
                    ->get();
                $dataType = 'trips';
            }

            if ($data->isEmpty()) {
                return back()->with('error', 'No ' . ($dataType === 'machinery' ? 'machinery usage' : 'trip') . ' information found for the selected date range.');
            }

            if ($format === 'pdf') {
                return $this->generatePDF($data, $equipment, $startDate, $endDate, $dataType);
            } elseif ($format === 'csv') {
                return $this->generateCSV($data, $equipment, $startDate, $endDate, $dataType);
            }

            return back()->with('error', 'Invalid format selected.');
        } catch (\Exception $e) {
            \Log::error('Error generating report: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while generating the report: ' . $e->getMessage());
        }
    }

    private function generatePDF($data, Equipment $equipment, $startDate, $endDate, $dataType = 'trips') {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.equipment_pdf', compact('data', 'equipment', 'startDate', 'endDate', 'dataType'));
        return $pdf->download("equipment_report_{$equipment->registration_number}_{$startDate}_to_{$endDate}.pdf");
    }

    private function generateCSV($data, Equipment $equipment, $startDate, $endDate, $dataType = 'trips') {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set the title
            $title = "{$equipment->registration_number} - {$equipment->equipment_name} - {$equipment->type} - {$startDate} to {$endDate}";
            $sheet->setCellValue('A1', $title);
            $sheet->mergeCells('A1:V1'); // Extended for new column
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

            // Add headers
            if ($dataType === 'machinery') {
                $sheet->setCellValue('A2', '#')
                    ->setCellValue('B2', 'Date')
                    ->setCellValue('C2', 'Start Hours')
                    ->setCellValue('D2', 'Closing Hours')
                    ->setCellValue('E2', 'Hours Used')
                    ->setCellValue('F2', 'Location')
                    ->setCellValue('G2', 'Operator')
                    ->setCellValue('H2', 'Fuel Logs')
                    ->setCellValue('I2', 'Total Fuel Used (Litres)')
                    ->setCellValue('J2', 'Total Fuel Cost (ZMW)');
            } else {
                $sheet->setCellValue('A2', '#')
                    ->setCellValue('B2', 'Departure Date')
                    ->setCellValue('C2', 'Return Date')
                    ->setCellValue('D2', 'Start Km')
                    ->setCellValue('E2', 'Close Km')
                    ->setCellValue('F2', 'Distance Travelled')
                    ->setCellValue('G2', 'Location')
                    ->setCellValue('H2', 'Driver')
                    ->setCellValue('I2', 'Material Delivered')
                    ->setCellValue('J2', 'Supplier Name')
                    ->setCellValue('K2', 'Gross Weight (tonnes)')
                    ->setCellValue('L2', 'Tare Weight (tonnes)')
                    ->setCellValue('M2', 'Net Weight (tonnes)')
                    ->setCellValue('N2', 'Loading Cost (ZMW)')
                    ->setCellValue('O2', 'Council Fee (ZMW)')
                    ->setCellValue('P2', 'Weighbridge Fee (ZMW)')
                    ->setCellValue('Q2', 'Toll Gate Fee (ZMW)')
                    ->setCellValue('R2', 'Other Expenses (ZMW)')
                    ->setCellValue('S2', 'Fuel Logs')
                    ->setCellValue('T2', 'Total Fuel Used (Litres)')
                    ->setCellValue('U2', 'Total Fuel Cost (ZMW)');
            }

            // Style headers
            $headerStyle = [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D3D3D3']]
            ];
            $sheet->getStyle('A2:' . ($dataType === 'machinery' ? 'K2' : 'V2'))->applyFromArray($headerStyle);

            // Add data rows
            $row = 3;
            $totalFuelUsed = 0;
            $totalFuelCost = 0; // Initialize
            $totalDistanceOrHours = 0;
            $totalLoadingCost = 0;
            $totalCouncilFee = 0;
            $totalWeighbridgeFee = 0;
            $totalTollGateFee = 0;
            $totalOtherExpenses = 0;

            foreach ($data as $item) {
                $fuelLogs = $item->fuels->map(function ($fuel) {
                    return number_format($fuel->litres_added, 2) . "L at " . ($fuel->refuel_location ?? 'Unknown') . " / " . ($fuel->cost !== null && $fuel->cost !== 0 ? number_format($fuel->cost, 2) : '-') . " ZMW/L";
                })->implode(' | ');

                $itemFuelCost = $item->fuels->sum(function ($fuel) {
                    return $fuel->cost !== null ? $fuel->litres_added * $fuel->cost : 0;
                });

                if ($dataType === 'machinery') {
                    $date = $item->date ? Carbon::parse($item->date)->format('Y/m/d') : '-';
                    $hoursUsed = ($item->closing_hours && $item->start_hours) ? ($item->closing_hours - $item->start_hours) : 0;

                    $sheet->setCellValue('A' . $row, $row - 2)
                        ->setCellValue('B' . $row, $date)
                        ->setCellValue('C' . $row, ($item->start_hours === null || $item->start_hours == 0) ? '-' : $item->start_hours)
                        ->setCellValue('D' . $row, ($item->closing_hours === null || $item->closing_hours == 0) ? '-' : $item->closing_hours)
                        ->setCellValue('E' . $row, ($hoursUsed === null || $hoursUsed == 0) ? '-' : $hoursUsed)
                        ->setCellValue('F' . $row, $item->location)
                        ->setCellValue('G' . $row, $item->operator->employee_full_name ?? '-')
                        ->setCellValue('H' . $row, $fuelLogs ?: 'No fuel data')
                        ->setCellValue('I' . $row, number_format($item->fuels->sum('litres_added'), 2))
                        ->setCellValue('J' . $row, $itemFuelCost > 0 ? number_format($itemFuelCost, 2) : '-');

                    $totalDistanceOrHours += $hoursUsed;
                } else {
                    $departureDate = $item->departure_date ? Carbon::parse($item->departure_date)->format('Y/m/d') : '-';
                    $returnDate = $item->return_date ? Carbon::parse($item->return_date)->format('Y/m/d') : '-';
                    $distanceTravelled = ($item->end_kilometers && $item->start_kilometers) ? ($item->end_kilometers - $item->start_kilometers) : 0;

                    $sheet->setCellValue('A' . $row, $row - 2)
                        ->setCellValue('B' . $row, $departureDate)
                        ->setCellValue('C' . $row, $returnDate)
                        ->setCellValue('D' . $row, ($item->start_kilometers === null || $item->start_kilometers == 0) ? '-' : $item->start_kilometers)
                        ->setCellValue('E' . $row, ($item->end_kilometers === null || $item->end_kilometers == 0) ? '-' : $item->end_kilometers)
                        ->setCellValue('F' . $row, ($distanceTravelled === null || $distanceTravelled === 0) ? '-' : $distanceTravelled)
                        ->setCellValue('G' . $row, $item->location)
                        ->setCellValue('H' . $row, $item->driver->employee_full_name ?? '-')
                        ->setCellValue('I' . $row, $item->material_delivered ?? '-')
                        ->setCellValue('J' . $row, $item->supplier_name ?? '-')
                        ->setCellValue('K' . $row, $item->gross_weight ? number_format($item->gross_weight, 2) : '-')
                        ->setCellValue('L' . $row, $item->tare_weight ? number_format($item->tare_weight, 2) : '-')
                        ->setCellValue('M' . $row, $item->net_weight ? number_format($item->net_weight, 2) : '-')
                        ->setCellValue('N' . $row, $item->loading ? number_format($item->loading, 2) : '-')
                        ->setCellValue('O' . $row, $item->council_fee ? number_format($item->council_fee, 2) : '-')
                        ->setCellValue('P' . $row, $item->weighbridge ? number_format($item->weighbridge, 2) : '-')
                        ->setCellValue('Q' . $row, $item->toll_gate ? number_format($item->toll_gate, 2) : '-')
                        ->setCellValue('R' . $row, $item->other_expenses ? number_format($item->other_expenses, 2) : '-')
                        ->setCellValue('S' . $row, $fuelLogs ?: 'No fuel data')
                        ->setCellValue('T' . $row, number_format($item->fuels->sum('litres_added'), 2))
                        ->setCellValue('U' . $row, $itemFuelCost > 0 ? number_format($itemFuelCost, 2) : '-');

                    $totalDistanceOrHours += $distanceTravelled;
                    $totalLoadingCost += $item->loading ?? 0;
                    $totalCouncilFee += $item->council_fee ?? 0;
                    $totalWeighbridgeFee += $item->weighbridge ?? 0;
                    $totalTollGateFee += $item->toll_gate ?? 0;
                    $totalOtherExpenses += $item->other_expenses ?? 0;
                }

                $totalFuelUsed += $item->fuels->sum('litres_added');
                $totalFuelCost += $itemFuelCost;
                $row++;
            }

            // Add associated costs section
            $row++;
            $sheet->setCellValue('A' . $row, 'Associated Costs');
            $sheet->mergeCells('A' . $row . ':' . ($dataType === 'machinery' ? 'K' : 'V') . $row);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            $row++;
            $sheet->setCellValue('A' . $row, 'Type')
                ->setCellValue('B' . $row, 'Details')
                ->setCellValue('C' . $row, 'Amount (ZMW)')
                ->setCellValue('D' . $row, 'Expiry Date');
            $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray($headerStyle);

            $row++;
            foreach ($equipment->equipmentInsurances as $insurance) {
                $sheet->setCellValue('A' . $row, 'Insurance')
                    ->setCellValue('B' . $row, $insurance->insurance_company)
                    ->setCellValue('C' . $row, ($insurance->premium > 0) ? number_format($insurance->premium, 2) : '-')
                    ->setCellValue('D' . $row, $insurance->expiry_date->format('Y/m/d'));
                $row++;
            }

            foreach ($equipment->equipmentTaxes as $tax) {
                $sheet->setCellValue('A' . $row, 'Tax')
                    ->setCellValue('B' . $row, $tax->name)
                    ->setCellValue('C' . $row, ($tax->cost > 0) ? number_format($tax->cost, 2) : '-')
                    ->setCellValue('D' . $row, $tax->expiry_date->format('Y/m/d'));
                $row++;
            }

            foreach ($equipment->spares as $spare) {
                $sheet->setCellValue('A' . $row, 'Spares')
                    ->setCellValue('B' . $row, $spare->name)
                    ->setCellValue('C' . $row, ($spare->price > 0) ? number_format($spare->price, 2) : '-')
                    ->setCellValue('D' . $row, '-');
                $row++;
            }

            // Add summary section
            $row++;
            $sheet->setCellValue('A' . $row, 'Summary');
            $sheet->mergeCells('A' . $row . ':' . ($dataType === 'machinery' ? 'K' : 'V') . $row);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            $totalInsuranceCost = $equipment->equipmentInsurances()->sum('premium');
            $totalTaxCost = $equipment->equipmentTaxes()->sum('cost');
            $totalSpareCost = $equipment->spares()->sum('price');

            $summaryRow = $row + 1;
            $sheet->setCellValue('A' . $summaryRow, 'Summary');
            if ($dataType === 'machinery') {
                $sheet->setCellValue('F' . $summaryRow, 'Total Hours Worked:')
                    ->setCellValue('G' . $summaryRow, ($totalDistanceOrHours > 0) ? number_format($totalDistanceOrHours, 2) . ' Hours' : '- Hours')
                    ->setCellValue('F' . ($summaryRow + 1), 'Total Fuel Used:')
                    ->setCellValue('G' . ($summaryRow + 1), number_format($totalFuelUsed, 2) . ' Litres')
                    ->setCellValue('F' . ($summaryRow + 2), 'Average Fuel Per Hour:')
                    ->setCellValue('G' . ($summaryRow + 2), ($totalDistanceOrHours > 0 && $totalFuelUsed > 0) ? number_format($totalFuelUsed / $totalDistanceOrHours, 2) . ' Litres/Hour' : '- Litres/Hour')
                    ->setCellValue('F' . ($summaryRow + 3), 'Total Fuel Cost:')
                    ->setCellValue('G' . ($summaryRow + 3), $totalFuelCost > 0 ? number_format($totalFuelCost, 2) . ' ZMW' : '-')
                    ->setCellValue('F' . ($summaryRow + 4), 'Average Fuel Cost Per Hour:')
                    ->setCellValue('G' . ($summaryRow + 4), ($totalDistanceOrHours > 0 && $totalFuelCost > 0) ? number_format($totalFuelCost / $totalDistanceOrHours, 2) . ' ZMW/Hour' : '-')
                    ->setCellValue('F' . ($summaryRow + 5), 'Total Insurance Cost:')
                    ->setCellValue('G' . ($summaryRow + 5), ($totalInsuranceCost > 0) ? number_format($totalInsuranceCost, 2) . ' ZMW' : '-')
                    ->setCellValue('F' . ($summaryRow + 6), 'Total Tax Cost:')
                    ->setCellValue('G' . ($summaryRow + 6), ($totalTaxCost > 0) ? number_format($totalTaxCost, 2) . ' ZMW' : '-')
                    ->setCellValue('F' . ($summaryRow + 7), 'Total Spare Cost:')
                    ->setCellValue('G' . ($summaryRow + 7), ($totalSpareCost > 0) ? number_format($totalSpareCost, 2) . ' ZMW' : '-');

                $summaryStyleRange = 'A' . $summaryRow . ':G' . ($summaryRow + 7);
            } else {
                $sheet->setCellValue('F' . $summaryRow, 'Total Distance Travelled:')
                    ->setCellValue('G' . $summaryRow, ($totalDistanceOrHours > 0) ? number_format($totalDistanceOrHours, 2) . ' Km' : '- Km')
                    ->setCellValue('F' . ($summaryRow + 1), 'Total Fuel Used:')
                    ->setCellValue('G' . ($summaryRow + 1), number_format($totalFuelUsed, 2) . ' Litres')
                    ->setCellValue('F' . ($summaryRow + 2), 'Average Fuel Per Kilometer:')
                    ->setCellValue('G' . ($summaryRow + 2), ($totalDistanceOrHours > 0 && $totalFuelUsed > 0) ? number_format($totalFuelUsed / $totalDistanceOrHours, 2) . ' Litres/Km' : '- Litres/Km')
                    ->setCellValue('F' . ($summaryRow + 3), 'Total Fuel Cost:')
                    ->setCellValue('G' . ($summaryRow + 3), $totalFuelCost > 0 ? number_format($totalFuelCost, 2) . ' ZMW' : '-')
                    ->setCellValue('F' . ($summaryRow + 4), 'Average Fuel Cost Per Kilometer:')
                    ->setCellValue('G' . ($summaryRow + 4), ($totalDistanceOrHours > 0 && $totalFuelCost > 0) ? number_format($totalFuelCost / $totalDistanceOrHours, 2) . ' ZMW/Km' : '-')
                    ->setCellValue('F' . ($summaryRow + 5), 'Total Net Weight (Material Delivered):')
                    ->setCellValue('G' . ($summaryRow + 5), number_format($data->sum('net_weight'), 2) . ' Tonnes')
                    ->setCellValue('F' . ($summaryRow + 6), 'Total Loading Cost:')
                    ->setCellValue('G' . ($summaryRow + 6), number_format($totalLoadingCost, 2) . ' ZMW')
                    ->setCellValue('F' . ($summaryRow + 7), 'Total Council Fee:')
                    ->setCellValue('G' . ($summaryRow + 7), number_format($totalCouncilFee, 2) . ' ZMW')
                    ->setCellValue('F' . ($summaryRow + 8), 'Total Weighbridge Fee:')
                    ->setCellValue('G' . ($summaryRow + 8), number_format($totalWeighbridgeFee, 2) . ' ZMW')
                    ->setCellValue('F' . ($summaryRow + 9), 'Total Toll Gate Fee:')
                    ->setCellValue('G' . ($summaryRow + 9), number_format($totalTollGateFee, 2) . ' ZMW')
                    ->setCellValue('F' . ($summaryRow + 10), 'Total Other Expenses:')
                    ->setCellValue('G' . ($summaryRow + 10), number_format($totalOtherExpenses, 2) . ' ZMW')
                    ->setCellValue('F' . ($summaryRow + 11), 'Total Insurance Cost:')
                    ->setCellValue('G' . ($summaryRow + 11), ($totalInsuranceCost > 0) ? number_format($totalInsuranceCost, 2) . ' ZMW' : '-')
                    ->setCellValue('F' . ($summaryRow + 12), 'Total Tax Cost:')
                    ->setCellValue('G' . ($summaryRow + 12), ($totalTaxCost > 0) ? number_format($totalTaxCost, 2) . ' ZMW' : '-')
                    ->setCellValue('F' . ($summaryRow + 13), 'Total Spare Cost:')
                    ->setCellValue('G' . ($summaryRow + 13), ($totalSpareCost > 0) ? number_format($totalSpareCost, 2) . ' ZMW' : '-');

                $summaryStyleRange = 'A' . $summaryRow . ':G' . ($summaryRow + 13);
            }

            $summaryStyle = [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ];
            $sheet->getStyle($summaryStyleRange)->applyFromArray($summaryStyle);

            // Auto-size columns
            foreach (range('A', $dataType === 'machinery' ? 'K' : 'V') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Save the file
            $filename = "equipment_report_{$equipment->registration_number}_{$equipment->name}_{$startDate}_to_{$endDate}.xlsx";
            $filePath = storage_path("app/public/{$filename}");
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            return response()->download($filePath, $filename)->deleteFileAfterSend();
        } catch (\Exception $e) {
            \Log::error('Excel Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate Excel file: ' . $e->getMessage())->withInput();
        }
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
