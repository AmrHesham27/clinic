<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Exception;
use Illuminate\Http\Request;
use stdClass;

class patientsContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_patients = Patient::orderBy('created_at', 'desc')->paginate(8);
        return response()->json($all_patients, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        try{
            $data = $this->validate($request,[
                "patientName"  => "required|string|min:4|max:60",
                "age" => "required|numeric|gte:3|lte:120",
                "address" => "required|string|max:100",
                "phoneNumber" => "required|regex:/(01)[0,1,2,5][0-9]{8}/", // after developement add |unique:patients|
            ]);
            $id = Patient::create($data)->id;
            $response = new stdClass;
            $response->data = $id;
            $response->message = 'New patient was added successfully';
            return response()->json($response ,200);
        }
        catch (Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function search (Request $request){
        try {
            $userEntry = $request->entry;
            $patients = [];
            if ( preg_match('/^[a-zA-Z\s]*$/', $userEntry) ){
                $patients = Patient::where('patientName', 'LIKE', "%{$userEntry}%")->paginate(8);
            }
            elseif ( preg_match('/(01)[0,1,2,5][0-9]{8}/', $userEntry) ){
                $patients = Patient::where('phoneNumber', $userEntry)->get()
                    ->paginate(8);
            }
            elseif ( preg_match('/^[0-9]+$/', $userEntry) ){
                $patients = Patient::where('id', $userEntry)->paginate(8);
            }
            $response = new stdClass;
            $response->data = $patients;
            return response()->json($response, 200);
        }
        catch (Exception $e){
            return response()->json($e->getMessage(), 500);
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
        try {
            $patient = Patient::findOrFail($id);
            $response = new stdClass;
            $response->data = $patient;
            $response->message = 'user was found successfully';
            return response()->json($response, 200);
        }
        catch (Exception) {
            $response = new stdClass;
            $response->message = 'user was not found';
            return response()->json($response, 500);
        }
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
