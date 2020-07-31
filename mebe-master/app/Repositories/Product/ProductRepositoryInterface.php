<?php

namespace App\Repositories\Product;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface ProductRepositoryInterface extends RepositoryInterface {

    public function index(Request $request);

    public function getProductsByType($type);

    public function getProductByIdOrSku($id);

    public function getVariantProductsByMaster($id);

    public function productIsValid($id);

    public function filterProductByRangePrice($from, $to);

    public function getProductBySlug($slug, $type = null);

    public function getProductRelated($productId);

    public function changeQty($id, $qty,int $typeChange = 0);
}