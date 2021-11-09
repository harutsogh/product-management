<?php


namespace App\Http\Controllers;


use App\Services\ProductService;

/**
 * Class ProductController
 * @package App\Http\Controllers\Admin
 */
class ProductController extends BaseController
{
    /**
     * ProductController constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->baseService = $productService;
    }

    /**
     * @return array
     */
    public function totalCount() : array
    {
        $productsCount = $this->baseService->getTotalsCount();
        return $this->makeResponse($productsCount);
    }

    /**
     * @return mixed
     */
    public function categoryTree()
    {
        return $this->makeResponse($this->baseService->categoryTree());
    }

}
