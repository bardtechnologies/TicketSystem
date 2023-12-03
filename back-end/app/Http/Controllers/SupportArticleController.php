<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportArticleRequest;
use App\Http\Requests\UpdateSupportArticleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SupportArticle;

class SupportArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supportArticles = SupportArticle::all();
        return response()->json($supportArticles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupportArticleRequest $request)
    { 
        $data = $request->validated();

        $supportArticle = SupportArticle::create($data);
        return response()->json($supportArticle, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $supportArticle = SupportArticle::findOrFail($id);
        return response()->json($supportArticle, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupportArticleRequest $request, SupportArticle $supportArticle)
    {
        $data = $request->validated();

        $supportArticle->update($data);

        return response()->json($supportArticle, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupportArticle $supportArticle)
    {
        $supportArticle->delete();
        return response()->json(null, 204);
    }

    public function articleData(Request $request) 
    {
        $rowsPerPage = $request->pagination['rowsPerPage'];
        $page = $request->pagination['page'];

        $filter = $request->filter;
        $selectedFilter = $request->selectedfield;

        $idFilter = '';
        $nameFilter = '';
        $productFilter = '';

        switch ($selectedFilter) {
            case 'ID':
                $idFilter = $filter;
                break;
            case 'Name':
                $nameFilter = $filter;
                break;
            case 'Product':
                $productFilter = $filter;
                break;
        }

        $orderBy = $request->pagination['sortBy'];
        $sortState = $request->pagination['descending'];
        $sortDirection = 'desc';

        if($sortState)
            $sortDirection = 'asc';

        if(!$orderBy)
            $orderBy = 'id';

        $tickets = DB::table('support_articles')
        ->leftJoin('products_support_articles','products_support_articles.support_article_id','=','support_articles.id')
        ->leftJoin('products', 'products.id', '=', 'products_support_articles.product_id')
        ->select('support_articles.id as ID', 'support_articles.name as Name', 'products.name as Product')
        ->when($idFilter != null,function($query) use ($idFilter) {
            $query->where('support_articles.id', 'like', '%'.$idFilter.'%' );
        })
        ->when($nameFilter != null,function($query) use ($nameFilter) {
            $query->where('support_articles.name', 'like', '%'.$nameFilter.'%' );
        })
        ->when($productFilter != null,function($query) use ($productFilter) {
            $query->where('products.name', 'like', '%'.$productFilter.'%' );
        })
        ->orderBy($orderBy, $sortDirection)
        ->offset(($page * $rowsPerPage) - ($rowsPerPage))
        ->paginate($rowsPerPage);


        return response()->json($tickets, 200);
    }
}
