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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request,[
            "patientId"  => "required|numeric",
            "date" => "required|date|after:yesterday",
            "startTime" => "required|date_format:H:i",
            "endTime" => "required|date_format:H:i",
            "visitType" => "required|in:examination,consultation",
        ]);
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
        }
        return response()->json(count($same_time_visits), 200);
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
        try{
            $visit = Visit::findOrFail($id);
        }
        catch(Exception){
            $this->message(false, '', 'This visit does not exist');
            return redirect(url('/Visits/'));
        }
        return view('Visits.show', ['visit' => $visit]);
    }

    public function showLastVisit($patientId){
        try {
            Patient::findOrFail($patientId);
        }
        catch (Exception){
            $this->message(false, '', 'This patient does not exist');
            return redirect(url('/'));
        }
        $visit = Visit::where('patientId', $patientId)->orderBy('created_at', 'desc')->firstOrFail();
        $visit_id = $visit['id'];
        return redirect(url("Visits/$visit_id"));
    }

    public function showPatientVisits($patientId){
        try {
            $patient = Patient::findOrFail($patientId);
        }
        catch (Exception){
            $this->message(false, '', 'This patient does not exist');
            return redirect(url('/Visits/'));
        }

        $visits = $patient->visits()->paginate(8);
        return view('Visits.showVisits', ['visits' => $visits]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
