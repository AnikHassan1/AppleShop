<?php

namespace App\Http\Controllers;

use App\Models\categorie;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Symfony\Component\HttpFoundation\JsonResponse;

class categoryController extends Controller
{
    public function CategoryList():JsonResponse
    {
        $data= categorie::all();
        return  ResponseHelper::Out('success',$data,200);
    }
}
