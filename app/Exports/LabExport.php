<?php

namespace App\Exports;

use Modules\Lab\Models\Lab;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class LabExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell
{
    public array $columns;
    public array $dateRange;
    public string $exportDoctorId;

    public function __construct($columns, $dateRange, $exportDoctorId = '')
    {
        $this->columns = $columns;
        $this->dateRange = $dateRange;
        $this->exportDoctorId = $exportDoctorId;
    }

    public function headings(): array
    {
        $modifiedHeadings = [];

        foreach ($this->columns as $column) {
            // Capitalize each word and replace underscores with spaces
            $modifiedHeadings[] = ucwords(str_replace('_', ' ', $column));
        }

        return $modifiedHeadings;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Lab::with(['doctor', 'patient']);


        // Apply date range filter
        if (!empty($this->dateRange) && count($this->dateRange) >= 2) {
            $query->whereDate('created_at', '>=', $this->dateRange[0]);
            $query->whereDate('created_at', '<=', $this->dateRange[1]);
        }

        // Apply doctor filter if provided
        if (!empty($this->exportDoctorId) && $this->exportDoctorId !== 'admin') {
            $query->where('doctor_id', $this->exportDoctorId);
        }

        // dd($query->get());


        $query = $query->orderBy('created_at', 'desc');
        $query = $query->get();

        // dd($query);

        $newQuery = $query->map(function ($row) {
            $selectedData = [];

            foreach ($this->columns as $column) {
                switch ($column) {
                    case 'ID':
                        $selectedData[$column] = $row->id;
                        break;

                    case 'Doctor':
                        $selectedData[$column] = $row->doctor ? $row->doctor->full_name : 'N/A';
                        break;

                    case 'Patient':
                        $selectedData[$column] = $row->patient ? $row->patient->full_name : 'N/A';
                        break;

                    case 'Case Type':
                        $selectedData[$column] = ucwords(str_replace('_', ' ', $row->case_type ?? ''));
                        break;

                    case 'Case Status':
                        $selectedData[$column] = ucwords(str_replace('_', ' ', $row->case_status ?? ''));
                        break;

                    case 'Delivery Date Estimate':
                        $selectedData[$column] = $row->delivery_date_estimate ? $row->delivery_date_estimate->format('Y-m-d') : 'N/A';
                        break;

                    case 'Treatment Plan Link':
                        $selectedData[$column] = $row->treatment_plan_link ?? 'N/A';
                        break;


                    case 'Notes':
                        $selectedData[$column] = $row->notes ?? 'N/A';
                        break;

                    case 'Clear Aligner Impression Type':
                        $selectedData[$column] = ucfirst($row->clear_aligner_impression_type ?? '');
                        break;

                    case 'Prosthodontic Impression Type':
                        $selectedData[$column] = ucfirst($row->prosthodontic_impression_type ?? '');
                        break;

                    case 'Margin Location':
                        $selectedData[$column] = $row->margin_location ?? 'N/A';
                        break;

                    case 'Contact Tightness':
                        $selectedData[$column] = $row->contact_tightness ?? 'N/A';
                        break;

                    case 'Occlusal Scheme':
                        $selectedData[$column] = $row->occlusal_scheme ?? 'N/A';
                        break;

                    case 'Temporary Placed':
                        $selectedData[$column] = $row->temporary_placed ? 'Yes' : 'No';
                        break;

                    case 'Teeth Treatment Type':
                        if ($row->teeth_treatment_type && is_array($row->teeth_treatment_type)) {
                            $treatmentTypes = array_map(function($type) {
                                return ucwords(str_replace('_', ' ', $type));
                            }, $row->teeth_treatment_type);
                            $selectedData[$column] = implode(', ', $treatmentTypes);
                        } else {
                            $selectedData[$column] = 'N/A';
                        }
                        break;

                    case 'Shade Selection':
                        $selectedData[$column] = $row->shade_selection ?: 'N/A';
                        break;

                    case 'Created At':
                        $selectedData[$column] = $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : 'N/A';
                        break;


                    default:
                        $selectedData[$column] = $row->{$column} ?? 'N/A';
                        break;
                }
            }

            return $selectedData;
        });

        return $newQuery;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:Z1')->getFont()->setBold(true);
            },
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }
}