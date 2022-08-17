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
                return response()->json([
                    "status" => false,
                    "message" => "This is not working day"
                ] ,500);
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
                    return response()->json([
                        "status" => true,
                        "message" => "New visit was added"
                    ], 200);
                }
                else {
                    return response()->json([
                        "status" => false,
                        "message" => "There is a visit in this time"
                    ], 500);
                }
        }
        catch(\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function checkDate(Request $request){
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

            return response()->json([
                "status" => true,
                "data" => $same_day_visits,
                "is_working_day" => $is_working_day
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function checkWorkingDays() {
        try{
            $workingDays = DB::table('WorkingDays')->select('day')
                ->groupBy('day')
                ->get();
            return response()->json([
                "status" => true,
                "data" => $workingDays
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
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
        try{
            $visit = Visit::findOrFail($id);
            return response()->json([
                "status" => true,
                "data" => $visit
            ], 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "status" => false,
                "message" => "Patient was not found"
            ], 500);
        }
        catch(\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
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
                return response()->json([
                    "status" => false,
                    "message" => 'There is a visit in this time'
                ] ,500);
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
                    return response()->json([
                        "status" => true,
                        "message" => 'message was edited'
                    ], 200);
                }
                else {
                    Visit::where('id', $id)->update(
                        [
                            'patientId' => $data['patientId'],
                            'visitType' => $data['visitType'],
                        ]
                    );
                    return response()->json([
                        "status" => true,
                        "message" => 'There is a visit in this time'
                    ], 200);
                }
        }
        catch(\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
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
        try {
            Visit::where('id', $id)->delete();
            return response()->json([
                "status" => true,
                "message" => "Visit was deleted succesfully"
            ], 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "status" => false,
                "message" => "visit was not found"
            ], 500);
        }
        catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        };
    }

    public function search (Request $request){
        try {
            $this->validate($request, [
                'userEntry' => 'numeric|nullable'
            ]);
            $userEntry = $request->userEntry;
            $date = $request->date;
            $visits = [];

            if( $userEntry && !$date ) {
                $visits = Visit::where('patientId', $userEntry)->paginate(8);
                return response()->json([
                    "status" => true,
                    "data" => $visits,
                    "message" => "Search data were fetched successfully"
                ], 200);
            }
            elseif ( !$userEntry && $date ) {
                $visits = Visit::where('date', $date)->paginate(8);
                return response()->json([
                    "status" => true,
                    "data" => $visits,
                    "message" => "Search data were fetched successfully"
                ], 200);
            }
            elseif ( $userEntry && $date ) {
                $visits = Visit::where('date', $date)
                    ->where('patientId', $userEntry)->paginate(8);
                return response()->json([
                    "status" => true,
                    "data" => $visits,
                    "message" => "Search data were fetched successfully"
                ], 200);
            }
            return response()->json([
                "status" => false,
                "message" => 'Please enter valid patient id or visits date.'
            ], 200);
        }
        catch (Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
