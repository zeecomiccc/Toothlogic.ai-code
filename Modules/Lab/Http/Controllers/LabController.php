<?php

namespace Modules\Lab\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Lab\Models\Lab;
use App\Models\User;
use Modules\Lab\Http\Requests\LabRequest;
use Yajra\DataTables\DataTables;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LabExport;

class LabController extends Controller
{
    protected string $exportClass = '\App\Exports\LabExport';

    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        // Page Title
        $this->module_title = 'messages.lab';
        // module name
        $this->module_name = 'lab';

        // module icon
        $this->module_icon = 'fa-solid fa-clipboard-list';

        view()->share([
            'module_title' => $this->module_title,
            'module_icon' => $this->module_icon,
            'module_name' => $this->module_name,
        ]);
    }

    public function index(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];
        $module_name = 'lab';
        // $auth_user = authSession();
        $pageTitle = trans('messages.lab');
        $assets = ['datatable'];
        $doctors = User::where('user_type', 'doctor')->get();
        $patients = User::where('user_type', 'user')->get();

        // Export functionality
        $export_import = true;
        $export_columns = [
            ['value' => 'ID', 'text' => __('lab.report_id')],
            ['value' => 'Doctor', 'text' => __('lab.doctor')],
            ['value' => 'Patient', 'text' => __('lab.patient')],
            ['value' => 'Case Type', 'text' => __('lab.case_type')],
            ['value' => 'Case Status', 'text' => __('lab.case_status')],
            ['value' => 'Delivery Date Estimate', 'text' => __('lab.delivery_date_estimate')],
            ['value' => 'Treatment Plan Link', 'text' => __('lab.treatment_plan_link')],
            ['value' => 'Notes', 'text' => __('lab.notes')],
            ['value' => 'Clear Aligner Impression Type', 'text' => __('lab.clear_aligner_impression_type')],
            ['value' => 'Prosthodontic Impression Type', 'text' => __('lab.prosthodontic_impression_type')],
            ['value' => 'Margin Location', 'text' => __('lab.margin_location')],
            ['value' => 'Contact Tightness', 'text' => __('lab.contact_tightness')],
            ['value' => 'Occlusal Scheme', 'text' => __('lab.occlusal_scheme')],
            ['value' => 'Temporary Placed', 'text' => __('lab.temporary_placed')],
            ['value' => 'Teeth Treatment Type', 'text' => __('lab.select_teeth_for_treatment')],
            ['value' => 'Shade Selection', 'text' => __('lab.shade_selection')],
            ['value' => 'Created At', 'text' => __('lab.date')],
        ];
        $export_url = route('backend.lab.export');
        $export_doctor_id = auth()->user()->user_type;

        return view('lab::backend.lab.index', compact('pageTitle', 'module_name', 'assets', 'filter', 'doctors', 'patients', 'export_import', 'export_columns', 'export_url', 'export_doctor_id'));
    }


    public function index_data(DataTables $datatable, Request $request)
    {
        $query = Lab::with(['doctor', 'patient'])->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->has('filter')) {
            $filter = $request->filter;

            if (!empty($filter['doctor_filter'])) {
                $query->where('doctor_id', $filter['doctor_filter']);
            }

            if (!empty($filter['patient_filter'])) {
                $query->where('patient_id', $filter['patient_filter']);
            }

            if (!empty($filter['case_type_filter'])) {
                $query->where('case_type', $filter['case_type_filter']);
            }

            if (!empty($filter['case_status_filter'])) {
                $query->where('case_status', $filter['case_status_filter']);
            }
        }

        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row" id="datatable-row-' . $row->id . '" name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->addColumn('action', function ($data) {
                return view('lab::backend.lab.action_column', compact('data'))->render();
            })
            ->editColumn('doctor', function ($data) {
                return $data->doctor ? $data->doctor->full_name : '';
            })
            ->filterColumn('doctor', function ($query, $keyword) {
                $query->whereHas('doctor', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$keyword%"]);
                });
            })
            ->orderColumn('doctor', function ($query, $order) {
                $query->select('labs.*')
                    ->join('users as doctors', 'doctors.id', '=', 'labs.doctor_id')
                    ->orderByRaw("CONCAT(doctors.first_name, ' ', doctors.last_name) $order");
            })
            ->editColumn('patient', function ($data) {
                return $data->patient ? $data->patient->full_name : '';
            })
            ->filterColumn('patient', function ($query, $keyword) {
                $query->whereHas('patient', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$keyword%"]);
                });
            })
            ->orderColumn('patient', function ($query, $order) {
                $query->select('labs.*')
                    ->join('users as patients', 'patients.id', '=', 'labs.patient_id')
                    ->orderByRaw("CONCAT(patients.first_name, ' ', patients.last_name) $order");
            })
            ->editColumn('case_type', function ($data) {
                return ucfirst(str_replace('_', ' ', $data->case_type));
            })
            ->editColumn('case_status', function ($data) {
                return ucfirst(str_replace('_', ' ', $data->case_status));
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at ? $data->created_at->format('Y-m-d H:i') : '';
            })
            ->rawColumns(['check', 'action'])
            ->toJson();
    }
    
    // Store new lab
    public function store(Request $request)
    {
        // Check if we have the quadrant structure and use it instead of the flattened array
        $teethTreatmentStructure = $request->input('teeth_treatment_structure');
        if ($teethTreatmentStructure && is_string($teethTreatmentStructure)) {
            $decodedStructure = json_decode($teethTreatmentStructure, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedStructure)) {
                // Store the complete quadrant structure
                $request->merge(['teeth_treatment_type' => $decodedStructure]);
            } else {
                $request->merge(['teeth_treatment_type' => []]);
            }
        } else {
            // No structure provided, check if we have regular teeth_treatment_type array
            $teethTreatmentType = $request->input('teeth_treatment_type');
            if (!is_array($teethTreatmentType) || count($teethTreatmentType) === 0) {
                $request->merge(['teeth_treatment_type' => []]);
            }
        }

        $data = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'case_type' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'case_status' => 'required|string',
            'delivery_date_estimate' => 'nullable|date',
            'treatment_plan_link' => 'nullable|string|max:255',
            'clear_aligner_impression_type' => 'nullable|string',
            'prosthodontic_impression_type' => 'nullable|string',
            'margin_location' => 'nullable|string|max:255',
            'contact_tightness' => 'nullable|string|max:255',
            'occlusal_scheme' => 'nullable|string|max:255',
            'temporary_placed' => 'nullable|boolean',
            'teeth_treatment_type' => 'nullable|array',
            'shade_selection' => 'nullable|string',
        ]);

        $lab = Lab::create($data);

        // Handle media uploads - using the exact same pattern as EncounterMedicalReport
        if ($request->hasFile('file_url')) {
            $files = $request->file('file_url');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files); // Uses default 'file_url' collection
        }

        if ($request->hasFile('clear_aligner_intraoral')) {
            $files = $request->file('clear_aligner_intraoral');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'clear_aligner_intraoral');
        }

        if ($request->hasFile('clear_aligner_pics')) {
            $files = $request->file('clear_aligner_pics');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'clear_aligner_pics');
        }

        if ($request->hasFile('clear_aligner_others')) {
            $files = $request->file('clear_aligner_others');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'clear_aligner_others');
        }

        if ($request->hasFile('prostho_prep_scans')) {
            $files = $request->file('prostho_prep_scans');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'prostho_prep_scans');
        }

        if ($request->hasFile('prostho_bite_scans')) {
            $files = $request->file('prostho_bite_scans');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'prostho_bite_scans');
        }

        if ($request->hasFile('prostho_preop_pictures')) {
            $files = $request->file('prostho_preop_pictures');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'prostho_preop_pictures');
        }

        if ($request->hasFile('prostho_others')) {
            $files = $request->file('prostho_others');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'prostho_others');
        }

        if ($request->hasFile('rx_prep_scan')) {
            $files = $request->file('rx_prep_scan');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'rx_prep_scan');
        }

        if ($request->hasFile('rx_bite_scan')) {
            $files = $request->file('rx_bite_scan');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'rx_bite_scan');
        }

        if ($request->hasFile('rx_preop_images')) {
            $files = $request->file('rx_preop_images');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'rx_preop_images');
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Lab created successfully!',
                'data' => $lab
            ]);
        }

        return redirect()->route('backend.lab.index')->with('success', 'Lab created successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $lab = Lab::with(['doctor', 'patient'])->findOrFail($id);

        // If it's an AJAX request, return JSON
        if (request()->ajax()) {
            // Prepare media data using the new methods
            $media = [
                'clear_aligner_intraoral' => $lab->getAllClearAlignerIntraoral(),
                'clear_aligner_pics' => $lab->getAllClearAlignerPics(),
                'clear_aligner_others' => $lab->getAllClearAlignerOthers(),
                'prostho_prep_scans' => $lab->getAllProsthoPrepScans(),
                'prostho_bite_scans' => $lab->getAllProsthoBiteScans(),
                'prostho_preop_pictures' => $lab->getAllProsthoPreopPictures(),
                'prostho_others' => $lab->getAllProsthoOthers(),
                'rx_prep_scan' => $lab->getAllRxPrepScan(),
                'rx_bite_scan' => $lab->getAllRxBiteScan(),
                'rx_preop_images' => $lab->getAllRxPreopImages()
            ];



            return response()->json([
                'status' => true,
                'data' => array_merge($lab->toArray(), ['media' => $media])
            ]);
        }

        $doctors = User::where('user_type', 'doctor')->get();
        $patients = User::where('user_type', 'user')->get();
        return view('lab::backend.lab.lab_form', compact('lab', 'doctors', 'patients'));
    }

    // Update lab
    public function update(Request $request, $id)
    {
        $lab = Lab::findOrFail($id);
        
        // Check if we have the quadrant structure and use it instead of the flattened array
        $teethTreatmentStructure = $request->input('teeth_treatment_structure');
        if ($teethTreatmentStructure && is_string($teethTreatmentStructure)) {
            $decodedStructure = json_decode($teethTreatmentStructure, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedStructure)) {
                // Store the complete quadrant structure
                $request->merge(['teeth_treatment_type' => $decodedStructure]);
            } else {
                $request->merge(['teeth_treatment_type' => []]);
            }
        } else {
            // No structure provided, check if we have regular teeth_treatment_type array
            $teethTreatmentType = $request->input('teeth_treatment_type');
            if (!is_array($teethTreatmentType) || count($teethTreatmentType) === 0) {
                $request->merge(['teeth_treatment_type' => []]);
            }
        }
        
        $data = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'case_type' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'case_status' => 'required|string',
            'delivery_date_estimate' => 'nullable|date',
            'treatment_plan_link' => 'nullable|string|max:255',
            'clear_aligner_impression_type' => 'nullable|string',
            'prosthodontic_impression_type' => 'nullable|string',
            'margin_location' => 'nullable|string|max:255',
            'contact_tightness' => 'nullable|string|max:255',
            'occlusal_scheme' => 'nullable|string|max:255',
            'temporary_placed' => 'nullable|boolean',
            'teeth_treatment_type' => 'nullable|array',
            'shade_selection' => 'nullable|string',
        ]);
        $lab->update($data);

        // Handle media uploads (append new files) - using the exact same pattern as EncounterMedicalReport
        if ($request->hasFile('file_url')) {
            $files = $request->file('file_url');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files); // Uses default 'file_url' collection
        }

        if ($request->hasFile('clear_aligner_intraoral')) {
            $files = $request->file('clear_aligner_intraoral');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'clear_aligner_intraoral');
        }

        if ($request->hasFile('clear_aligner_pics')) {
            $files = $request->file('clear_aligner_pics');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'clear_aligner_pics');
        }

        if ($request->hasFile('clear_aligner_others')) {
            $files = $request->file('clear_aligner_others');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'clear_aligner_others');
        }

        if ($request->hasFile('prostho_prep_scans')) {
            $files = $request->file('prostho_prep_scans');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'prostho_prep_scans');
        }

        if ($request->hasFile('prostho_bite_scans')) {
            $files = $request->file('prostho_bite_scans');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'prostho_bite_scans');
        }

        if ($request->hasFile('prostho_preop_pictures')) {
            $files = $request->file('prostho_preop_pictures');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'prostho_preop_pictures');
        }

        if ($request->hasFile('prostho_others')) {
            $files = $request->file('prostho_others');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'prostho_others');
        }

        if ($request->hasFile('rx_prep_scan')) {
            $files = $request->file('rx_prep_scan');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'rx_prep_scan');
        }

        if ($request->hasFile('rx_bite_scan')) {
            $files = $request->file('rx_bite_scan');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'rx_bite_scan');
        }

        if ($request->hasFile('rx_preop_images')) {
            $files = $request->file('rx_preop_images');
            if (!is_array($files)) {
                $files = [$files];
            }
            storeMediaFile($lab, $files, 'rx_preop_images');
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Lab updated successfully!',
                'data' => $lab
            ]);
        }

        return redirect()->route('backend.lab.index')->with('success', 'Lab updated successfully!');
    }

    // Delete lab
    public function destroy($id)
    {
        $lab = Lab::findOrFail($id);
        $lab->delete();

        if (request()->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Lab deleted successfully!'
            ]);
        }

        return redirect()->route('backend.lab.index')->with('success', 'Lab deleted successfully!');
    }

    // Show a single lab (optional)
    public function show($id)
    {
        $lab = Lab::with(['doctor', 'patient'])->findOrFail($id);

        if (request()->ajax()) {
            // Get patient statistics
            $patientStats = [];
            if ($lab->patient) {
                // Count appointments for this patient
                $appointmentCount = \Modules\Appointment\Models\Appointment::where('user_id', $lab->patient_id)
                    ->count();
                // Count lab reports for this patient
                $labCount = Lab::where('patient_id', $lab->patient_id)->count();
                $patientStats = [
                    'total_appointments' => $appointmentCount,
                    'total_labs' => $labCount
                ];
            }
            $media = [
                'clear_aligner_intraoral' => $lab->getAllClearAlignerIntraoral(),
                'clear_aligner_pics' => $lab->getAllClearAlignerPics(),
                'clear_aligner_others' => $lab->getAllClearAlignerOthers(),
                'prostho_prep_scans' => $lab->getAllProsthoPrepScans(),
                'prostho_bite_scans' => $lab->getAllProsthoBiteScans(),
                'prostho_preop_pictures' => $lab->getAllProsthoPreopPictures(),
                'prostho_others' => $lab->getAllProsthoOthers(),
                'rx_prep_scan' => $lab->getAllRxPrepScan(),
                'rx_bite_scan' => $lab->getAllRxBiteScan(),
                'rx_preop_images' => $lab->getAllRxPreopImages(),
                'file_url' => $lab->getAllFiles() // Add default file collection as fallback
            ];
            
            return response()->json([
                'status' => true,
                'data' => array_merge($lab->toArray(), [
                    'media' => $media,
                    'patient_stats' => $patientStats
                ])
            ]);
        }

        $doctors = User::where('user_type', 'doctor')->get();
        $patients = User::where('user_type', 'user')->get();
        return view('lab::backend.lab.show', compact('lab', 'doctors', 'patients'));
    }


    public function removeMedia($id, $media_id)
    {
        $lab = Lab::findOrFail($id);

        // Find the actual media item and delete it
        $mediaItem = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($media_id);

        if ($mediaItem) {
            $mediaItem->delete();

            if (request()->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Media removed successfully!'
                ]);
            }

            return redirect()->back()->withSuccess(trans('messages.media_removed'));
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => false,
                'message' => 'Media not found!'
            ], 404);
        }

        return redirect()->back()->withErrors(trans('messages.error_removing_media'));
    }


    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $message = __('messages.bulk_update');

        switch ($actionType) {
            case 'change-status':
                Lab::whereIn('id', $ids)->update(['case_status' => $request->status]);
                $message = __('messages.bulk_service_update');
                break;

            case 'delete':
                if (env('IS_DEMO')) {
                    return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
                }
                Lab::whereIn('id', $ids)->delete();
                $message = __('messages.bulk_service_delete');
                break;

            default:
                return response()->json(['status' => false, 'message' => __('service_providers.invalid_action')]);
        }

        return response()->json(['status' => true, 'message' => $message]);
    }

    /**
     * Show a print-friendly view of the lab
     */
    public function printLab($id)
    {
        $lab = Lab::with(['doctor', 'patient'])->findOrFail($id);

        // Get all media files
        $media = [
            'clear_aligner_intraoral' => $lab->getAllClearAlignerIntraoral(),
            'clear_aligner_pics' => $lab->getAllClearAlignerPics(),
            'clear_aligner_others' => $lab->getAllClearAlignerOthers(),
            'prostho_prep_scans' => $lab->getAllProsthoPrepScans(),
            'prostho_bite_scans' => $lab->getAllProsthoBiteScans(),
            'prostho_preop_pictures' => $lab->getAllProsthoPreopPictures(),
            'prostho_others' => $lab->getAllProsthoOthers(),
            'rx_prep_scan' => $lab->getAllRxPrepScan(),
            'rx_bite_scan' => $lab->getAllRxBiteScan(),
            'rx_preop_images' => $lab->getAllRxPreopImages()
        ];

        return view('lab::backend.lab.print', compact('lab', 'media'));
    }

    /**
     * Download the lab as a PDF
     */
    public function downloadLabPDF($id)
    {
        $lab = Lab::with(['doctor', 'patient'])->findOrFail($id);

        // Get all media files
        $media = [
            'clear_aligner_intraoral' => $lab->getAllClearAlignerIntraoral(),
            'clear_aligner_pics' => $lab->getAllClearAlignerPics(),
            'clear_aligner_others' => $lab->getAllClearAlignerOthers(),
            'prostho_prep_scans' => $lab->getAllProsthoPrepScans(),
            'prostho_bite_scans' => $lab->getAllProsthoBiteScans(),
            'prostho_preop_pictures' => $lab->getAllProsthoPreopPictures(),
            'prostho_others' => $lab->getAllProsthoOthers(),
            'rx_prep_scan' => $lab->getAllRxPrepScan(),
            'rx_bite_scan' => $lab->getAllRxBiteScan(),
            'rx_preop_images' => $lab->getAllRxPreopImages()
        ];

        $pdf = PDF::loadView('lab::backend.lab.print', compact('lab', 'media'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true, // useful if you have images
        ]);

        return $pdf->download('lab_report_' . $id . '.pdf');
        // return $pdf->stream('lab_report_' . $id . '.pdf');

    }

    /**
     * Export Lab data in various formats
     */
    public function export(Request $request)
    {
        $columns = $request->input('columns', []);
        if (is_string($columns)) {
            $columns = array_map('trim', explode(',', $columns));
        }
        $dateRange = $request->input('date_range', []);
        if (is_string($dateRange)) {
            $dateRange = array_map('trim', explode(' to ', $dateRange));
        }
        $exportDoctorId = $request->input('exportDoctorId', $request->input('export_doctor_id', ''));
        $fileType = $request->input('file_type', 'xlsx');
        $fileName = now()->format('Y-m-d') . '-lab.' . $fileType;

        switch (strtolower($fileType)) {
            case 'csv':
                return Excel::download(new LabExport($columns, $dateRange, $exportDoctorId), $fileName, \Maatwebsite\Excel\Excel::CSV);
            case 'ods':
                return Excel::download(new LabExport($columns, $dateRange, $exportDoctorId), $fileName, \Maatwebsite\Excel\Excel::ODS);
            case 'html':
                return Excel::download(new LabExport($columns, $dateRange, $exportDoctorId), $fileName, \Maatwebsite\Excel\Excel::HTML);
            case 'pdf':
                $data = (new LabExport($columns, $dateRange, $exportDoctorId))->collection();
                $headings = (new LabExport($columns, $dateRange, $exportDoctorId))->headings();
                $pdf = \PDF::loadView('lab::backend.lab.export_pdf', compact('data', 'headings'));
                return $pdf->download($fileName);
            case 'xlsx':
            default:
                return Excel::download(new LabExport($columns, $dateRange, $exportDoctorId), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }
    }

    /**
     * Download a specific file from the lab with proper MIME type handling
     */
    public function downloadFile($id, $mediaId)
    {
        $lab = Lab::findOrFail($id);
        
        // Search for the media in all collections
        $media = null;
        $collections = [
            'clear_aligner_intraoral',
            'clear_aligner_pics', 
            'clear_aligner_others',
            'prostho_prep_scans',
            'prostho_bite_scans',
            'prostho_preop_pictures',
            'prostho_others',
            'rx_prep_scan',
            'rx_bite_scan',
            'rx_preop_images'
        ];
        
        foreach ($collections as $collection) {
            $media = $lab->getMedia($collection)->where('id', $mediaId)->first();
            if ($media) {
                break;
            }
        }
        
        if (!$media) {
            abort(404, 'File not found');
        }
        
        // Get file path and check if it exists
        $filePath = $media->getPath();
        if (!file_exists($filePath)) {
            abort(404, 'File not found on disk');
        }
        
        // Set proper headers for download
        $headers = [
            'Content-Type' => $media->mime_type,
            'Content-Disposition' => 'attachment; filename="' . $media->file_name . '"',
            'Content-Length' => $media->size,
        ];
        
        // For STL files, ensure proper MIME type
        if (strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION)) === 'stl') {
            $headers['Content-Type'] = 'application/octet-stream';
            $headers['Content-Disposition'] = 'attachment; filename="' . $media->file_name . '"; filename*=UTF-8\'\'' . urlencode($media->file_name);
        }
        
        return response()->file($filePath, $headers);
    }

}
