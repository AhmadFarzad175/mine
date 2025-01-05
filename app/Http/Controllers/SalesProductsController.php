<?php

namespace App\Http\Controllers;

use App\Models\SaleProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\saleProductsRequest;
use App\Http\Resources\saleProductResource;
use Illuminate\Auth\AuthenticationException;

class SalesProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewSaleProduct')->only(['index', 'show']);
        $this->middleware('can:createSaleProduct')->only(['store']);
        $this->middleware('can:editSaleProduct')->only(['update']);
        $this->middleware('can:deleteSaleProduct')->only('destroy');
    }
    private function authorizeUser(saleProducts $saleProducts)
    {
        if ($saleProducts->user_id !== auth()->id()) {
            throw new AuthenticationException('you must be logged in');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage');
        $search = $request->input('search');
        // Eager load relationships and apply search
        $saleProducts = SaleProducts::query()->search($search);
        $saleProducts = $perPage  ? $saleProducts->latest()->paginate($perPage) : $saleProducts->latest()->get();
        return SaleProductResource::collection($saleProducts);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleProductsRequest $request)
    {
        // dd(request()->all());
        $validated = $request->validated();
        $validated['created_by'] = Auth::id() ?? 1;
        $salesProduct = SaleProducts::create($validated);

        return SaleProductResource::make($salesProduct);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $saleProduct = saleProducts::find($id);
        return saleProductResource::make($saleProduct);


        //
    }
    // public function searchProduct(Request $request)
    // {
    //     $name=$request->query('name');
    //     $saleproduct = SaleProducts::where('product_name', 'LIKE', '%' .$name . '%')->get();
    //     return SaleProductResource::collection($saleproduct);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaleProductsRequest $request,$id)
    {
        $saleProducts = SaleProducts::find($id);
        $validated = $request->validated();
        $validated['created_by'] = Auth::id() ?? 1;
        $saleProducts-> update($validated);
        return SaleProductResource::make($saleProducts);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $saleProduct = SaleProducts::find($id);

        $saleProduct->delete();
        return response()->json(['message' => 'saleproduct deleted succesfully'], '200');
    }


}
