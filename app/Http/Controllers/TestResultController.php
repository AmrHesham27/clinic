<?php

namespace App\Http\Controllers;

use App\Models\testResult;
use Illuminate\Http\Request;
use stdClass;

class TestResultController extends Controller
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
        try {
            $data = $this->validate($request, [
                "test_id" => "required|numeric",
                "result" => "required|string|max:100"
            ]);
            testResult::create($data);
            $response->message = 'New test result was added successfully';
            return response()->json($response, 200);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\testResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $testResult = testResult::findOrFail($id);
            return response()->json($testResult, 200);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\testResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->validate($request, [
            "test_id" => "required|numeric",
            "date" => "required|date",
            "result" => "required|string|lte:100"
        ]);
        try {
            testResult::where('id', $id)->update([
                "test_id" => $data['test_id'],
                "date" => $data['date'],
                "result" => $data['result'],
            ]);
            return response()->json('test result was edited successfully', 200);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\testResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            testResult::where('id', $id)->delete();
            return response('test result was deleted successfully', 200);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function saveImage(Request $request)
    {
        try {
            $this->validate($request, [
                "testResult_id" => "required|numeric",
                "image" => "required",
                "image.*" => "image",
            ]);
            $visit_id = testResult::where('id', $request->testResult_id)->first()
                ->test->visit->id;

            foreach($request->file('image') as $image) {
                $fileName = random_int(10000, 99999) . time() . '.' . $image->extension();
                $image->move(
                    public_path( 'uploads/' . $visit_id . '/' . $request->testResult_id),
                    $fileName
                );
            }

            return response()->json('New Images were added successfully', 200);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
