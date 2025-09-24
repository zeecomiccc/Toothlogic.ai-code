<?php
namespace Modules\Clinic\Trait;
use Modules\Clinic\Models\Clinics;
use Modules\Tax\Models\Tax;

trait ClinicTrait
{


    public function getClinicSession($data){
    
        $clinicData = [];
    
        foreach($data as $session) {
            $breaks = is_array($session->breaks) ? $session->breaks : 
                 (is_string($session->breaks) ? json_decode($session->breaks, true) : []);

            $clinicData[] = [
                'day' => ucfirst($session->day),
                'status' => $session->is_holiday ? 'closed' : 'open',
                'start_time' => $session->start_time,
                'end_time' => $session->end_time,
                'breaks' => $breaks
            ];
        }
    
        $openDays = [];
        $holidays = [];
    
        foreach ($clinicData as $key => $day) {
            if ($day['status'] === 'closed') {
                $holidays[] = $day['day'];
            } else {
                if (!empty($openDays) && $day['start_time'] === $clinicData[$key - 1]['start_time'] && $day['end_time'] === $clinicData[$key - 1]['end_time']) {
                    $openDays[count($openDays) - 1]['day'] .= ', ' . $day['day'];
                } else {
                    $openDays[] = [
                        'day' => $day['day'],
                        'start_time' => $day['start_time'],
                        'end_time' => $day['end_time'],
                        'breaks' => $day['breaks'] 
                    ];
                }
            }
        }
    
        return [
            'open_days' => $openDays,
            'close_days' => $holidays
        ];
    }
    

    public function getNearestclinic($latitude, $longitude)
    {
        $nearestEntities = Clinics::selectRaw("*, latitude, longitude,
        ( 6371 * acos( cos( radians($latitude) ) *
        cos( radians( latitude ) ) *
        cos( radians( longitude ) - radians($longitude) ) +
        sin( radians($latitude) ) *
        sin( radians( latitude ) ) )
        ) AS distance")
            ->where('latitude', '!=', null)
            ->where('longitude', '!=', null)
            ->having("distance", "<=", 16) // Adjust distance as needed
            ->orderBy("distance", 'asc')
            ->get();
      
      return $nearestEntities;
    }

    function calculateDistance($latitude1, $longitude1, $latitude2, $longitude2)
    {
       
        $latFrom = deg2rad($latitude1);
        $lonFrom = deg2rad($longitude1);
        $latTo = deg2rad($latitude2);
        $lonTo = deg2rad($longitude2);
    
        $latDiff = $latTo - $latFrom;
        $lonDiff = $lonTo - $lonFrom;
    
        $earthRadius = 6371; 
        $distance = 2 * $earthRadius * asin(sqrt(
            pow(sin($latDiff / 2), 2) +
            cos($latFrom) * cos($latTo) *
            pow(sin($lonDiff / 2), 2)
        ));
    
        return $distance; 
    }

    // public function inclusiveTaxPrice($request_data)
    // {
    //      $request_data['inclusive_tax'] = null;
    //      $request_data['inclusive_tax_price'] = 0;
    //      $request_data['service_inclusive_tax_price'] = 0;
         
    //     if (isset($request_data['is_inclusive_tax']) && $request_data['is_inclusive_tax'] == 1) {
    //         $taxes = Tax::where('module_type', 'services')->where('tax_type', 'inclusive')->where('status', 1)->get();

    //         $total_tax = $taxes->sum(function ($tax) use ($request_data) {
    //             $taxvalue = (float) $tax->value;
    //             return $tax->type === 'percent'
    //                 ? round($request_data['service_discount_price'] * $taxvalue / 100, 2)
    //                 : $taxvalue;
    //         });
            
    //         $request_data['inclusive_tax'] = $taxes;
    //         $request_data['inclusive_tax_price'] = round($total_tax, 2);
    //         $request_data['service_inclusive_tax_price'] = round($request_data['service_discount_price'] + $total_tax, 2);
    //     }

    //     return $request_data;
    // }
 

    // function TaxCalculation($amount){

    //     $taxes=Tax::active()->whereNull('module_type')->orWhere('module_type', 'services')->where('status',1)->get();


    //     $totalTaxAmount=0;

    //     foreach ($taxes as $tax) {
    //         if ($tax->type === 'percent') {

    //             $percentageAmount = ($tax->value / 100) * $amount;
    //             $totalTaxAmount += $percentageAmount;

    //         } elseif ($tax->type === 'fixed') {

    //             $totalTaxAmount += $tax->value;
    //         }
    //     }

    //     return $totalTaxAmount;

    // }



}
