<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Exception;
use Illuminate\Http\Request;

class patientsContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $all_patients = Patient::orderBy('created_at', 'desc')->paginate(8);
            return response()->json([
                "status" => true,
                "message" => "Patients were fetched successfully!",
                "data" => $all_patients
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }

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
            Patient::create($data)->id;
            return response()->json([
                "status" => true,
                "message" => 'New patient was added successfully'
            ] ,200);
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
            return response()->json([
                "status" => true,
                "message" => "Search result was fetched successfully!",
                "data" => $patients
            ], 200);
        }
        catch (Exception $e){
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
        try {
            $patient = Patient::findOrFail($id);
            return response()->json([
                "status" => true,
                "data" => $patient,
                "message" => 'patient was found successfully'
            ], 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "status" => false,
                "message" => 'patient was not found',
                "userWasNotFound" => true
            ], 500);
        }
        catch (\Exception $e) {
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
                "patientName"  => "required|string|min:4|max:60",
                "age" => "required|numeric|gte:3|lte:120",
                "address" => "required|string|max:100",
                "phoneNumber" => "required|regex:/(01)[0,1,2,5][0-9]{8}/", // after developement add |unique:patients|
            ]);
            Patient::where('id', $id)->update([
                "patientName"  => $data['patientName'],
                "age" => $data['age'],
                "address" => $data['address'],
                "phoneNumber" => $data['phoneNumber']
            ]);
            return response()->json([
                "status" => true,
                "message" => "Patient was edited successfully"
            ] ,200);
        }
        catch (Exception $e){
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
            $patient = Patient::findOrFail($id);
            $patient->delete();
            return response()->json([
                "status" => true,
                "message" => 'Patient was deleted successfully'
            ], 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "status" => false,
                "message" => 'Patient was not found'
            ], 500);
        }
        catch (\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
