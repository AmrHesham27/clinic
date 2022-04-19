<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Controller as BaseController;

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

    public function uploadImage(Request $request){
        if( $request->hasFile('image')){
            $extension = request()->file('image')->getClientOriginalExtension();
            $newName = rand(6).time().'.'.$extension;
            request()->file('image')->storeAs('/images', $newName );
            return $newName;
        };
    }
}
