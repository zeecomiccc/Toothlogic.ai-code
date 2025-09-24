<?php

namespace Modules\Appointment\Http\Controllers\Backend\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Appointment\Models\Appointment;
use Modules\Appointment\Transformers\AppointmentResource;
use Laravel\Socialite\Facades\Socialite;
use Modules\Appointment\Transformers\AppointmentDetailResource;
use Auth;
use Carbon\Carbon;
use Modules\Appointment\Trait\AppointmentTrait;
use Modules\Clinic\Models\Receptionist;
use Notification;
use Modules\Commission\Models\CommissionEarning;
class AppointmentsController extends Controller
{
    use AppointmentTrait;

    public function appointmentList(Request $request){
        $perPage = $request->input('per_page', 10); 

        $user_id = $request->has('user_id') ? $request->user_id : auth()->id();
  
        $appointment = Appointment::CheckMultivendor()->with('appointmenttransaction','cliniccenter','clinicservice','user','doctor','otherPatient');


        if(auth()->user() !== null ){

            if(auth()->user()->hasRole('vendor')){

                $vendor_id=$request->has('vendor_id') ? $request->vendor_id : Auth::id();

               $appointment->whereHas('cliniccenter', function ($query) use ($vendor_id) {
                    $query->where('vendor_id', $vendor_id);
                });

            }elseif(auth()->user()->hasRole('doctor')){

                $doctor_id=$request->has('doctor_id') ? $request->doctor_id : Auth::id();

                $appointment->where('doctor_id',$doctor_id);
         

            }elseif(auth()->user()->hasRole('user')){

                $user_id=$request->has('user_id') ? $request->user_id : Auth::id();

                $appointment->where('user_id',$user_id);

            }elseif(auth()->user()->hasRole('receptionist')){

                $user_id = Auth::id();

                $receptionist=Receptionist::where('receptionist_id', $user_id)->first();

                $clinic_id = $receptionist->clinic_id;
                if($request->has('user_id')){
                    $appointment->where('clinic_id',$clinic_id)->where('user_id',$request->user_id);
                }else{
                    $appointment->where('clinic_id',$clinic_id);
                }
                

            }
            
          }

         if($request->has('vendor_id') && ($request->vendor_id !='')){

            $vendor_id=$request->vendor_id ;

            $appointment->whereHas('cliniccenter', function ($query) use ($vendor_id) {
                 $query->where('vendor_id', $vendor_id);
             });

          }

          if($request->has('doctor_id') && ($request->doctor_id !='')){
          
              $appointment->where('doctor_id',$request->doctor_id);
          }

          if($request->has('clinic_id') && ($request->clinic_id !='')){
          
            $appointment->where('clinic_id',$request->clinic_id);
          }

          if($request->has('service_id') && ($request->service_id !='')){
          
            $appointment->where('service_id',$request->service_id);
          }

        if($request->has('user_id') && ($request->user_id !='')){
          
            $appointment->where('user_id',$request->user_id);
         }


        if($request->has('upcoming_appointment')) {
            $appointment->whereIn('status', ['pending', 'confirmed'])->where('start_date_time', '>', now());
          }

         if($request->has('status') && $request->status !=null) {
            $appointment->where('status',$request->status);
         }
         if ($request->has('search')) {
            $searchTerm = trim($request->input('search'));
            $appointment->where(function ($query) use ($searchTerm) {
                $query->whereHas('cliniccenter', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%");
                      })
                      ->orWhereHas('doctor', function ($q) use ($searchTerm) {
                          $q->where('first_name', 'like', "%{$searchTerm}%")
                          ->orWhere('last_name', 'like', "%{$searchTerm}%")
                          ->orWhere('email', 'like', "%{$searchTerm}%");
                      })
                      ->orWhereHas('user', function ($q) use ($searchTerm) {
                          $q->where('first_name', 'like', "%{$searchTerm}%")
                          ->orWhere('last_name', 'like', "%{$searchTerm}%")
                          ->orWhere('email', 'like', "%{$searchTerm}%")
                          ->orWhere('profile_image', 'like', "%{$searchTerm}%");
                      })
                      ->orWhereHas('clinicservice', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%");
                      });
            });
        }
  
        $appointments = $appointment->orderBy('updated_at', 'desc')->paginate($perPage);
        $appointmentsCollection = AppointmentResource::collection($appointments);

        return response()->json([
            'status' => true,
            'data' => $appointmentsCollection,
            'message' => __('appointment.lbl_appointment_list'),
        ], 200);
    }

    

    public function appointmentDetails(Request $request){

        $appointmentId = $request->appointment_id;  
        $appointment = Appointment::with('appointmenttransaction', 'clinicservice', 'serviceRating', 'patientEncounter' ,'cliniccenter','bodyChart')->where('id',$appointmentId)->first();

        $appointmentDetail=null;

        if($appointment){

            $appointmentDetail =new AppointmentDetailResource($appointment);

        }
        if($request->has('notification_id')){
            $notification = \App\Models\Notification::where('id', $request->notification_id)->first();
            $notification->read_at = Carbon::now();
            $notification->save();
        }
        return response()->json([
            'status' => true,
            'data' => $appointmentDetail,
            'message' => __('appointment.lbl_appointment_detail'),
        ], 200);
    }

    public function rescheduleBooking(Request $request){
        
        $appointment_id = $request->appointment_id;  
        $appointment = Appointment::with('user','doctor','clinicservice')->findOrFail($appointment_id);

        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_time = $request->appointment_time;
        $appointment->start_date_time = Carbon::createFromFormat('Y-m-d H:i', $request->appointment_date . ' ' . $request->appointment_time, setting('default_time_zone'))->setTimezone('UTC');
        $appointment->save();   
        
        $notification_data = [
            'id' => $appointment->id,
            'description' => $appointment->description,
            'appointment_duration' => $appointment->duration,
            'user_id' => $appointment->user_id,
            'user_name' => optional($appointment->user)->first_name ?? default_user_name(),
            'doctor_id' => $appointment->doctor_id,
            'doctor_name' => optional($appointment->doctor)->first_name,
            'appointment_date' => $appointment->start_date_time->format('d/m/Y'),
            'appointment_time' => $appointment->start_date_time->format('h:i A'),
            'appointment_services_names' => optional($appointment->clinicservice)->name ?? '--',
            'appointment_services_image' => optional($appointment->clinicservice)->file_url,
            'appointment_date_and_time' => $appointment->start_date_time->format('Y-m-d H:i'),
            'latitude' =>  null,
            'longitude' => null,
        ];
        $this->sendNotificationOnBookingUpdate('reschedule_appointment', $notification_data);
      
        return response()->json([
            'status' => true,
            'message' => __('appointment.update_appointment'),
        ], 200);
    }

    public function getRevenuechartData(Request $request)
    {
 
        $user = auth()->user();
        $userid = $user->id;

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthlyTotals = Appointment::CheckMultivendor()->selectRaw('YEAR(start_date_time) as year')
                ->selectRaw('MONTH(start_date_time) as month')
                ->selectRaw('SUM(total_amount) as total_amount')
                ->where('status', 'checkout')
                ->groupByRaw('YEAR(start_date_time), MONTH(start_date_time)')
                ->orderByRaw('YEAR(start_date_time), MONTH(start_date_time)')
                ->get();

        if (auth()->user()->hasRole('vendor')) {
            $monthlyTotals = CommissionEarning::where('employee_id', $userid)->where('commission_status', '!=', 'pending')
                            ->join('appointments', 'commission_earnings.commissionable_id', '=', 'appointments.id')
                            ->selectRaw('YEAR(appointments.start_date_time) as year, MONTH(appointments.start_date_time) as month, SUM(commission_earnings.commission_amount) as total_amount')
                            ->groupByRaw('YEAR(appointments.start_date_time), MONTH(appointments.start_date_time)')
                            ->orderByRaw('YEAR(appointments.start_date_time), MONTH(appointments.start_date_time)')
                            ->get();
        }
        $yearChartData = [];

        for ($month = 1; $month <= 12; $month++) {
            $found = false;
            foreach ($monthlyTotals as $total) {
                if ((int)$total->month === $month) {
                    $yearChartData[] = (float)$total->total_amount;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $yearChartData[] = 0;
            }
        };

        $monthNames = [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct",
            "Nov", "Dec"
        ];

        
        $firstWeek = Carbon::now()->startOfMonth()->week;

        $monthlyWeekTotals = Appointment::CheckMultivendor()->selectRaw('YEAR(start_date_time) as year, MONTH(start_date_time) as month, WEEK(start_date_time) as week, COALESCE(SUM(total_amount), 0) as total_amount')
                ->where('status', 'checkout')
                ->whereYear('start_date_time', $currentYear)
                ->whereMonth('start_date_time', $currentMonth)
                ->groupBy('year', 'month', 'week')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('week')
                ->get();

        if (auth()->user()->hasRole('vendor')) {
            $monthlyWeekTotals = CommissionEarning::where('employee_id', $userid)
                    ->where('commission_status', '!=', 'pending')
                    ->join('appointments', 'commission_earnings.commissionable_id', '=', 'appointments.id') // assuming commissionable_id relates to appointments
                    ->selectRaw('YEAR(appointments.start_date_time) as year, MONTH(appointments.start_date_time) as month, WEEK(appointments.start_date_time) as week, COALESCE(SUM(commission_earnings.commission_amount), 0) as total_amount')
                    ->whereYear('appointments.start_date_time', $currentYear)
                    ->whereMonth('appointments.start_date_time', $currentMonth)
                    ->groupBy('year', 'month', 'week')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->orderBy('week')
                    ->get();
        }

        $monthlyChartData = [];


        for ($i = $firstWeek; $i <= $firstWeek + 4; $i++) {
            $found = false;

            foreach ($monthlyWeekTotals as $total) {

                if ((int)$total->month === $currentMonth && (int)$total->week === $i) {
                    $monthlyChartData[] = (float)$total->total_amount;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $monthlyChartData[] = 0;
            }
        }

        $weekNames = ["Week 1", "Week 2", "Week 3", "Week 4", 'Week 5'];


        $currentWeekStartDate = Carbon::now()->startOfWeek();
        $lastDayOfWeek = Carbon::now()->endOfWeek();

        $weeklyDayTotals = Appointment::CheckMultivendor()->selectRaw('DAY(start_date_time) as day, COALESCE(SUM(total_amount), 0) as total_amount')
                ->where('status', 'checkout')
                ->whereYear('start_date_time', $currentYear)
                ->whereMonth('start_date_time', $currentMonth)
                ->whereBetween('start_date_time', [$currentWeekStartDate, $currentWeekStartDate->copy()->addDays(6)])
                ->groupBy('day')
                ->orderBy('day')
                ->get();

        if (auth()->user()->hasRole('vendor')) {
            $weeklyDayTotals = CommissionEarning::where('employee_id', $userid)
                    ->where('commission_status', '!=', 'pending')
                    ->join('appointments', 'commission_earnings.commissionable_id', '=', 'appointments.id')
                    ->selectRaw('DAY(appointments.start_date_time) as day, COALESCE(SUM(commission_earnings.commission_amount), 0) as total_amount')
                    ->where('appointments.status', 'checkout')
                    ->whereYear('appointments.start_date_time', $currentYear)
                    ->whereMonth('appointments.start_date_time', $currentMonth)
                    ->whereBetween('appointments.start_date_time', [$currentWeekStartDate, $currentWeekStartDate->copy()->addDays(6)])
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();
        }

        $weeklyChartData = [];

        for ($day =  $currentWeekStartDate; $day <= $lastDayOfWeek; $day->addDay()) {
            $found = false;

            foreach ($weeklyDayTotals as $total) {
                if ((int)$total->day === $day->day) {
                    $weeklyChartData[] = (float)$total->total_amount;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $weeklyChartData[] = 0;
            }
        };

        $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];


        $data = [
            'year_chart_data' => $yearChartData,
            'month_names' => $monthNames,
            'month_chart_data' => $monthlyChartData,
            'weekNames' => $weekNames,
            'week_chart_data' => $weeklyChartData,
            'dayNames' => $dayNames,
        ];

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => __('appointment.revenue_chart_data'),
        ], 200);
    }

}