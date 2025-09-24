<?php

namespace Modules\Product\Http\Controllers\Backend\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Models\ProductCategory;
use Modules\Product\Transformers\ProductCategoryResource;

class CategoryController extends Controller
{
    public function categoryList(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Get the number of items per page from the request (default: 10)
        $serviceProviderId = $request->input('service_provider_id');
        $category = ProductCategory::with('media')
            ->where('status', 1);
        // ->whereHas('service_providers', function ($query) use ($serviceProviderId) {
            //     $query->where('service_provider_id', $serviceProviderId);
        // });

        if ($request->has('category_id') && $request->category_id != '') {
            $category = $category->where('parent_id', $request->category_id);
        } else {
            $category = $category->whereNull('parent_id');
        }

        $category = $category->paginate($perPage);
        // $category = $category->paginate($perPage)->appends('service_provider_id', $serviceProviderId);
        $categoryCollection = ProductCategoryResource::collection($category);

        return response()->json([
            'status' => true,
            'data' => $categoryCollection,
            'message' => __('category.category_list'),
        ], 200);
    }
}
