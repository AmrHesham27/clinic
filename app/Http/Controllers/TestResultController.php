<?php

namespace App\Http\Controllers;

use App\Models\testResult;
use Illuminate\Http\Request;

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
        try {
            $data = $this->validate($request, [
                "test_id" => "required|numeric",
                "result" => "required|string|max:100"
            ]);
            testResult::create($data);
            return response()->json([
                "status" => true,
                "message" => 'New test result was added successfully'
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
     * @param  \App\Models\testResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $testResult = testResult::findOrFail($id);
            return response()->json([
                "status" => true,
                "message" => "Test result was fetched successfully",
                "data" => $testResult
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
            return response()->json([
                "status" => true,
                "message" => "Result was updated successfully!"
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
     * @param  \App\Models\testResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            testResult::where('id', $id)->delete();
            return response([
                "status" => true,
                "message" => "Test Result was deleted successfully"
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
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

            return response()->json([
                "status" => true,
                "message" => "Image was saved successfully"
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
