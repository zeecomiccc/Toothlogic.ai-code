<?php

namespace Modules\Product\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'product_image' => $this->media->pluck('original_url')->first(),
            'category' => ProductCategoryResource::collection($this->categories),
            'brand_id' => $this->brand_id,
            'brand_name' => optional($this->brand)->name,
            'unit_id' => $this->unit_id,
            'unit_name' => optional($this->unit)->name,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'discount_value' => $this->discount_value,
            'discount_type' => $this->discount_type,
            'min_discounted_product_amount' => getDiscountedProductPrice($this->min_price, $this->id),
            'max_discounted_product_amount' => getDiscountedProductPrice($this->max_price, $this->id),
            'discount_start_date' => $this->discount_start_date ? Carbon::createFromTimestamp($this->discount_start_date)->format('Y-m-d') : null,
            'discount_end_date' => $this->discount_end_date ? Carbon::createFromTimestamp($this->discount_end_date)->format('Y-m-d') : null,
            'sell_target' => $this->sell_target,
            'stock_qty' => $this->stock_qty,
            'status' => $this->status,
            'min_purchase_qty' => $this->min_purchase_qty,
            'max_purchase_qty' => $this->max_purchase_qty,
            'has_variation' => $this->has_variation,
            'rating' => count($this->product_review) > 0 ? $this->product_review->sum('rating') / count($this->product_review) : 0,
            'variation_data' => ProductVariationResource::collection($this->product_variations),
            'in_wishlist' => $request->has('user_id') ? checkInWishList($this->id, $request->input('user_id')) : 0,
            'has_warranty' => $this->has_warranty,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
