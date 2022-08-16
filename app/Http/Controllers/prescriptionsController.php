<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Prescription;
use stdClass;

class prescriptionsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = new stdClass();
        $data = $this->validate($request,[
            "visit_id"  => "required|numeric",
            "name" => "required|string|max:100"
        ]);
        try{
            Visit::findOrFail($data['visit_id']);
            Prescription::create($data);
            $response->message = 'New Prescriptiont was added successfully';
            return response()->json($response, 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response->message = 'Visit was not found';
            return response()->json($response, 500);
        }
        catch (\Exception $e) {
            $response->message = $e->getMessage();
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
        try {
            $Prescription = Prescription::findOrFail($id);
            return response()->json($Prescription, 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json('could not find this Prescription', 500);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
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
        $response = new stdClass;
        $data = $this->validate($request,[
            "visit_id"  => "required|numeric",
            "name" => "required|string|max:100"
        ]);
        try {
            Prescription::where('id', $id)->update([
                "visit_id" => $data['visit_id'],
                "name" => $data['name'],
            ]);
        }
        catch (\Exception $e) {
            $response->message = $e->getMessage();
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
        try {
            Prescription::where('id', $id)->delete();
            return response()->json('Prescription was deleted successfully', 200);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
