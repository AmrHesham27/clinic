<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Patient;
use App\Models\WorkingDays;
use Carbon\Carbon;
use Exception;
use stdClass;
use Illuminate\Support\Facades\DB;

class visitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_visits = Visit::orderBy('created_at', 'desc')->paginate(8);
        return response()->json($all_visits, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = new stdClass();
        try{
            $data = $this->validate($request,[
                "patientId"  => "required|numeric",
                "date" => "required|date|after:yesterday",
                "startTime" => "required|date_format:H:i",
                "endTime" => "required|date_format:H:i|after:startTime",
                "visitType" => "required|in:examination,consultation",
            ]);

            // check date is working day
            $day_name = Carbon::parse($data['date'])->format('l');
            $workingDays = DB::table('WorkingDays')->select('day')
                ->groupBy('day')
                ->get();
            $workingDays_array = $workingDays->pluck('day')->toArray();
            if( !in_array($day_name, $workingDays_array) ) {
                $response->message = 'This is not working day';
                return response()->json($response ,500);
            }

            // check if there are visits in the same day and time
            $same_time_visits = Visit::whereDate('date', '=', $data['date'])
                ->where( function($query) use($data) {
                    $query
                        ->whereTime('startTime', '<=', $data['startTime'])
                        ->whereTime('endTime', '>=', $data['endTime']);
                })
                ->orWhere( function($query) use($data) {
                    $query
                        ->whereBetween('startTime', [$data['startTime'], $data['endTime']])
                        ->orWhereBetween('endTime', [$data['startTime'], $data['endTime']]);
                })
                ->get();

                if (!count($same_time_visits)) {
                    Visit::create($data);
                    $response->message = 'New visit was added';
                    return response()->json($response, 200);
                }
                else {
                    $response->message = 'There is a visit in this time';
                    return response()->json($response, 200);
                }
        }
        catch(\Exception $e) {
            $response->message = $e->getMessage();
            $response->class = get_class($e); // get the class of exception to catch it
            return response()->json($response, 500);
        }
    }

    public function checkDate(Request $request){
        $response = new stdClass();
        try{
            $data = $this->validate($request,[
                "date" => "required|date|after:yesterday",
            ]);
            $day_name = Carbon::parse($data['date'])->format('l');
            $is_working_day = WorkingDays::where('workingdays.day', $day_name)
                ->get()[0]->working;

            $same_day_visits = Visit::whereDate('date', '=', $data['date'])
            ->addSelect(['patientName' => Patient::select('patientName')
            ->whereColumn('patients.id', 'visits.patientId')])
            ->get();

            $response->data = $same_day_visits;
            $response->is_working_day = $is_working_day;
            return response()->json($response, 200);
        }
        catch (\Exception $e) {
            $response->message = $e->getMessage();
            return response()->json($response, 500);
        }
    }

    public function checkWorkingDays() {
        $response = new stdClass();
        try{
            $workingDays = DB::table('WorkingDays')->select('day')
                ->groupBy('day')
                ->get();
            $response->data = $workingDays;
            return response()->json($response, 200);
        }
        catch (Exception) {
            $response->message = 'Error';
            return response()->json($response, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = new stdClass();
        try{
            $visit = Visit::findOrFail($id);
            $response->data = $visit;
            return response()->json($response, 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response->message = 'Patient was not found';
            return response()->json($response, 500);
        }
        catch(\Exception $e){
            $response->message = $e->getMessage();
            return response()->json($response, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = new stdClass();
        try{
            $data = $this->validate($request,[
                "patientId" => "required|numeric",
                "date" => "required|date|after:yesterday",
                "startTime" => "required|date_format:H:i",
                "endTime" => "required|date_format:H:i|after:startTime",
                "visitType" => "required|in:examination,consultation",
            ]);

            // check date is working day
            $day_name = Carbon::parse($data['date'])->format('l');
            $workingDays = DB::table('WorkingDays')->select('day')
                ->groupBy('day')
                ->get();
            $workingDays_array = $workingDays->pluck('day')->toArray();
            if( !in_array($day_name, $workingDays_array) ) {
                $response->message = 'This is not working day';
                return response()->json($response ,500);
            }

            // check if there are visits in the same day and time
            $same_time_visits = Visit::whereDate('date', '=', $data['date'])
                ->where( function($query) use($data) {
                    $query
                        ->whereTime('startTime', '<=', $data['startTime'])
                        ->whereTime('endTime', '>=', $data['endTime']);
                })
                ->orWhere( function($query) use($data) {
                    $query
                        ->whereBetween('startTime', [$data['startTime'], $data['endTime']])
                        ->orWhereBetween('endTime', [$data['startTime'], $data['endTime']]);
                })
                ->get();

                if (!count($same_time_visits)) {
                    Visit::where('id', $id)->update(
                        [
                            'patientId' => $data['patientId'],
                            'date' => $data['date'],
                            'startTime' => $data['startTime'],
                            'endTime' => $data['endTime'],
                            'visitType' => $data['visitType'],
                        ]
                    );
                    $response->message = 'visit was edited';
                    return response()->json($response, 200);
                }
                else {
                    Visit::where('id', $id)->update(
                        [
                            'patientId' => $data['patientId'],
                            'visitType' => $data['visitType'],
                        ]
                    );
                    $response->message = 'There is a visit in this time';
                    return response()->json($response, 200);
                }
        }
        catch(\Exception $e) {
            $response->message = $e->getMessage();
            $response->class = get_class($e); // get the class of exception to catch it
            return response()->json($response, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = new stdClass;
        try {
            Visit::where('id', $id)->delete();
            $response->message = 'Visit was deleted succesfully';
            return response()->json($response, 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response->message = 'Visit was not found';
            return response()->json($response, 500);
        }
        catch (\Exception $e) {
            $response->message = $e->getMessage();
            return response()->json($response, 500);
        };
    }

    public function search (Request $request){
        $response = new stdClass;
        try {
            $this->validate($request, [
                'userEntry' => 'numeric|nullable'
            ]);
            $userEntry = $request->userEntry;
            $date = $request->date;
            $visits = [];

            if( $userEntry && !$date ) {
                $visits = Visit::where('patientId', $userEntry)->paginate(8);
                $response->data = $visits;
                return response()->json($response, 200);
            }
            elseif ( !$userEntry && $date ) {
                $visits = Visit::where('date', $date)->paginate(8);
                $response->data = $visits;
                return response()->json($response, 200);
            }
            elseif ( $userEntry && $date ) {
                $visits = Visit::where('date', $date)
                    ->where('patientId', $userEntry)->paginate(8);
                $response->data = $visits;
                return response()->json($response, 200);
            }
            $response->message = 'Please enter valid patient id or visits date.';
            return response()->json($response, 200);
        }
        catch (Exception $e){
            $response->message = $e->getMessage();
            return response()->json($response, 500);
        }
    }
}
