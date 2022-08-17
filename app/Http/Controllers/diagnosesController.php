<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Diagnose;
use App\Models\Patient;

class diagnosesController extends Controller
{
    public function show_patient_diagnoses($id) {

        $patient_diagnoses = Patient::findOrFail($id)->visits()
            ->select('created_at', 'updated_at', 'date')
            ->addSelect(['diagnose' => Diagnose::select('diagnosis')
                ->whereColumn('visit_id', 'visits.id')
                ->limit(1)
            ])
        ->get();
        return response()->json([
            "status" => true,
            "data" => $patient_diagnoses,
            "message" => "Patient diagnose was fetched successfully"
        ], 200);
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
            "visit_id"  => "required|numeric",
            "diagnosis" => "required|string|max:250"
        ]);
        try{
            Visit::findOrFail($data['visit_id']);
            Diagnose::create($data);
            return response()->json([
                "status" => true,
                "message" => 'Diagnose was added successfully!'
            ], 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "status" => false,
                "message" => 'visit was not found'
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
                "data" => $visit,
                "message" => "Visit was fetched successfully!",
            ], 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "status" => 500,
                "message" => 'Visit was not found'
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
        $data = $this->validate($request, [
            "visit_id"  => "required|numeric",
            "diagnosis" => "required|string|max:250"
        ]);
        try {
            Diagnose::where('id', $id)->update([
                "visit_id" => $data['visit_id'],
                "diagnosis" => $data['diagnosis'],
            ]);
            return response()->json([
                "status" => true,
                "message" => 'Diagnose was edited successfully'
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Diagnose::where('id', $id)->delete();
            return response()->json([
                "status" => true,
                "message" => 'Diagnose was deleted successfully'
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
