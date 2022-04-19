<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Patient;
use App\Models\workinghours;
use Carbon\Carbon;
use Exception;

class visitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_visits = Visit::paginate(8);
        return view('Visits.index', ['all_visits' => $all_visits]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->id;
        return view('Visits.create', ['id' => $id]);
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
            "visitType" => "required|in:examination,consultation",
        ]);
        $day = Carbon::createFromFormat('Y-m-d', $request->date)->format('l');

        // does this working hour exist?
        $workingHour = workinghours::where('day', $day)->where('startTime', $request->startTime)->get();
        if ( $workingHour->count() == 0){
            $this->message(false, '', 'please choose valid time.');
            return redirect()->back();
        };

        // is there a visit in this time ?
        $visitTime = Visit::where('date', $request->date)->where('startTime', $request->startTime)->get();
        if ( $visitTime->count() != 0){
            $this->message(false, '', 'This time is reserved already.');
            return redirect()->back();
        };

        $endTime = workinghours::where('day', $day)->where('startTime', $request->startTime)->get();
        $data['endTime']= $endTime[0]['endTime'];

        $id = Visit::create($data)->id;
        $this->message($id, 'new appointment was reserved successfully', 'Error try again');
        if( !$id ) return redirect()->back();
        return redirect(url("/Visits/{$data['patientId']}"));
    }

    public function checkDateView(){
        return view('checkDate', ['date' => 0]);
    }

    public function checkDate(Request $request){
        $this->validate($request,[
            "date" => "required|date|after:yesterday",
        ]);
        $day = Carbon::createFromFormat('Y-m-d', $request->date)->format('l');

        $workingHoursArray = [];
        $workingHours = workinghours::where('day', $day)->get();
        foreach($workingHours as $hour){
            array_push($workingHoursArray, $hour->startTime);
        }

        $startTimes = [];
        $dayVisits = Visit::where('date', $request->date)->get();
        foreach($dayVisits as $visit){
            array_push($startTimes, $visit->startTime);
        }

        return view('checkDate',
            [
                'workingHours' => $workingHoursArray,
                'startTimes' => $startTimes,
                'date' => $request->date
            ]);
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
