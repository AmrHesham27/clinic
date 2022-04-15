<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Visit;
use App\Models\Bill;
use App\Models\services_procedures;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function message($var, $successMssg, $dangerMssg){
        if($var){
            if($successMssg != ''){
                session()->flash('mssg', $successMssg);
                session()->flash('alert', 'alert-success');
            }
            return;
        }

        if($dangerMssg != ''){
            session()->flash('mssg', $dangerMssg);
            session()->flash('alert', 'alert-danger');
        }
    }

    public function getProcedure($procedure_id){
        $procedures = services_procedures::where('id', $procedure_id)->get();
        if( $procedures->count() == 0 ){
            $this->message( false , '', 'Error, could not find this procedure' );
            return;
        }
        return $procedures[0];
    }

    protected function getVisit($visit_id){
        $visit = Visit::where('id', $visit_id)->get();
        if( $visit->count() == 0 ){
            $this->message( false , '', 'Error, could not find this visit' );
            return;
        }
        return $visit[0];
    }

    protected function getBill($bill_id){
        $bill = Bill::where('id', $bill_id)->get();
        if( $bill->count() == 0 ){
            $this->message( false , '', 'Error, could not find this bill' );
            return;
        }
        return $bill[0];
    }

    protected function getBill_byVisitId($visit_id){
        $visit = $this->getVisit($visit_id);
        if( !$visit ) return;

        $bill = Bill::where('visit_id', $visit['id'])->get();
        if( $bill->count() == 0 ){
            $this->message( false , '', 'Error, this visit has no bill yet' );
            return;
        }
        return $bill[0];
    }
}
