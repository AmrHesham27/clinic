<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Diagnose;
use App\Models\Prescription;
use App\Models\Test;
use App\Models\workinghours;
use Carbon\Carbon;


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

    public function checkDateView(){
        return view('checkDate', ['date' => 0]);
    }



    /** Add diagnoses, tests and prescriptions */
    public function addDiagnoseView(Request $request){
        $id = $request->id;
        return view('Visits.addTest', ['id' => $id]);
    }

    public function addDiagnose(Request $request){
        $data = $this->validate($request,[
            "visit_id"  => "required|numeric",
            "diagnosis" => "required|string|max:250"
        ]);
        $visit = $this->getVisit($data['visit_id']);
        if(!$visit){
            $this->message(false, '', 'This visit does not exist');
            return redirect()->back();
        };
        $op = Diagnose::create($data);
        $this->message($op, 'Diagnose was created', 'Error happened');
        return redirect()->back();
    }

    public function addPrescriptionView(Request $request){
        $id = $request->id;
        return view('Visits.addTest', ['id' => $id]);
    }

    public function addPrescription(Request $request){
        $data = $this->validate($request,[
            "visit_id"  => "required|numeric",
            "name" => "required|string|max:100"
        ]);
        $visit = $this->getVisit($data['visit_id']);
        if(!$visit){
            $this->message(false, '', 'This visit does not exist');
            return redirect()->back();
        };
        $op = Prescription::create($data);
        $this->message($op, 'Prescription was created', 'Error happened');
        return redirect()->back();
    }

    public function addTestView(Request $request){
        $id = $request->id;
        return view('Visits.addTest', ['id' => $id]);
    }

    public function addTest(Request $request){
        $data = $this->validate($request,[
            "visit_id"  => "required|numeric",
            "testName" => "required|string|max:80"
        ]);
        $visit = $this->getVisit($data['visit_id']);
        if(!$visit){
            $this->message(false, '', 'This visit does not exist');
            return redirect()->back();
        };
        $op = Test::create($data);
        $this->message($op, 'Test was created', 'Error happened');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patientVisits = Visit::where('patientId', $id)->orderBy('created_at', 'desc')->get();
        if($patientVisits->count() == 0){
            $this->message(false, '', 'This patient has no visits yet');
            return redirect()->back();
        };
        $lastVisit = $patientVisits[0];
        return view('Visits.show', ['lastVisit' => $lastVisit]);
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
