<?php

namespace App\Http\Controllers;

use App\Models\brand;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Symfony\Component\HttpFoundation\JsonResponse;

class brandController extends Controller
{
    public function BrandList():JsonResponse
    {
        $data= brand::all();
        return ResponseHelper::Out('success',$data,200);
    }
}
