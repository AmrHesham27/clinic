<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Procedure;
use App\Models\services_procedures;
use App\Models\Visit;
use Exception;

class billsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_bills= Bill::paginate(8);
        return view('Bills.index', ['all_bills' => $all_bills]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $visitId = $request->id;
        try{
            $visit = Visit::findOrFail($visitId);
        }
        catch(Exception){
            $this->message(false, '', 'This visit does not exist');
            return redirect(url('/Visits/'));
        }

        // does this visit has a bill ?
        $bill = Bill::where('visit_id', $visitId)->get();
        if( $bill->count() != 0 ){
            $this->message(false, '', "This visit already has a bill with id : {$bill[0]['id']}");
            return redirect()->back();
        }
        else {
            $visitType = $visit['visitType'];
            $service = services_procedures::where('serviceName', $visitType)->get();
            $fees = $service[0]['price'];

            $op = Bill::create( ['visit_id' => $visit['id'], 'total' => $fees] );
            $this->message( $op, 'New bill was created successfully', 'Error happened, try again');

            if(!$op) return redirect()->back();
            return redirect(url("Bills/{$visitId}"));
        }
    }

    public function printFirstReceipt (Request $request){
        $billId = $request->id;
        $bill = Bill::findOrFail($billId);
        if( !$bill ) return redirect()->back();

        $visit = Visit::findOrFail($bill['visit_id']);
        $visitType = $visit['visitType'];
        $service = services_procedures::where('serviceName', $visitType)->get();
        $fees = $service[0]['price'];

        return view('printFirstReceipt', ['date' => $visit['date'], 'time' => $visit['startTime'], 'fees' => $fees]);
    }

    public function printSecondReceipt (Request $request){
        $billId = $request->id;
        $bill = Bill::findOrFail($billId);
        if( !$bill ) return redirect()->back();

        $visit = Visit::findOrFail($bill['visit_id']);
        if( !$visit ) return redirect()->back();

        $procedures = Procedure::where('bill_id', $billId)->get();
        if($procedures->count() == 0){
            $this->message(false, '', "There no second receipt");
            return redirect()->back();
        };
        $fees = 0;
        foreach($procedures as $procedure){
            $procedureRow = services_procedures::findOrFail( $procedure->procedureId );
            $procedure['name'] = $procedureRow['serviceName'];
            $fees += $procedure['price'];
        };
        return view('printSecondReceipt',
            [
                'date' => $visit['date'],
                'time' => $visit['startTime'],
                'procedures' => $procedures,
                'fees'=>$fees
            ]
        );
    }

    /** Add procedure */
    public function addProcedureView(Request $request){
        $billId = $request->id;
        $procedures = services_procedures::whereNotIn('serviceName', ['examination', 'consultation'])->get();
        return view('Bills.addProcedure', ['id' => $billId, 'procedures' => $procedures]);
    }

    public function addProcedure(Request $request){
        $data = $this->validate($request,[
            "procedureId" => "required|numeric",
            "bill_id" => "required|numeric"
        ]);

        try {
            $procedure = services_procedures::findOrFail( $data['procedureId'] );
            $bill = Bill::findOrFail($data['bill_id']);
        }
        catch (Exception){
            $this->message(false, '', 'Error, check procedure name and bill id.');
            return redirect(url('/'));
        }

        $procedurePrice = $procedure['price'];
        $data['price'] = $procedurePrice;

        $op = Procedure::create($data);
        $this->message($op, 'New procedure was added successfully', 'Error happened, please try again.');
        if( !$op )
            return redirect()->back();

        $billTotal = $bill['total'];
        $newTotal = $billTotal + $procedurePrice;
        Bill::where('id', $data['bill_id'])->first()->update(['total' => $newTotal]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // get BY visit id
    public function show($id)
    {
        $bill = Bill::where('visit_id', $id)->firstOrFail();
        if( !$bill ) return redirect(url('/Bills/'));

        return view('Bills.show', ['bill' => $bill]);
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
