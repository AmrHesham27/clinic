<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Test;
use stdClass;

class testsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = new stdClass;
        $data = $this->validate($request,[
            "visit_id"  => "required|numeric",
            "testName" => "required|string|max:80"
        ]);
        try{
            Visit::findOrFail($data['visit_id']);
            Test::create($data);
            $response->message = 'New Test was added successfully';
            return response()->json($response, 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response->message = 'Patient was not found';
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
            $test = Test::findOrFail($id);
            return response()->json($test, 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json('could not find this test', 500);
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
            "id" => "required|numeric",
            "visit_id"  => "required|numeric",
            "testName" => "required|string|max:80"
        ]);
        try {
            Test::where('id', $id)->update([
                "visit_id" => $data['visit_id'],
                "testName" => $data['testName'],
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
            Test::where('id', $id)->delete();
            return response()->json('test was deleted successfully', 200);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
