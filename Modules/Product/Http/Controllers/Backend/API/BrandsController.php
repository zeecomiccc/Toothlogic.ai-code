<?php

namespace Modules\Product\Http\Controllers\Backend\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Brands;

class BrandsController extends Controller
{
    public function product_brand()
    {
        $brands = Brands::where('status', 1)->get(['id', 'name']);
        return response()->json(['status' => true, 'data' => $brands]);
    }
}
