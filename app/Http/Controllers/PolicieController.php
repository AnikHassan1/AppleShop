<?php

namespace App\Http\Controllers;

use App\Models\policie;
use Illuminate\Http\Request;

class PolicieController extends Controller
{
    function PolicyByType(Request $request){
        return policie::where('type','=',$request->type)->first();
      }
}
