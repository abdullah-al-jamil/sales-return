<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Stock::with('item')->select('stocks.*')->orderBy('stocks.id', 'desc');

            if ($request->has('start_date') && !empty($request->start_date)) {
                $query->whereDate('stocks.updated_at', '>=', $request->start_date);
            }

            if ($request->has('end_date') && !empty($request->end_date)) {
                $query->whereDate('stocks.updated_at', '<=', $request->end_date);
            }

            if ($request->has('item') && !empty($request->item)) {
                $query->whereHas('item', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->item . '%');
                });
            }

            $recordsTotal = $query->count();

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->whereHas('item', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $query->skip($start)->take($length);

            if ($request->has('order') && count($request->order) > 0) {
                $columnIndex = $request->order[0]['column'];
                $columnDir = $request->order[0]['dir'];
                $columns = ['id', 'item', 'opening_stock', 'stock_in', 'stock_out', 'available_stock'];
                if (isset($columns[$columnIndex])) {
                    $query->orderBy('stocks.' . $columns[$columnIndex], $columnDir);
                }
            }

            $stocks = $query->get();

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $stocks->map(function ($stock) {
                    return [
                        'id' => $stock->id,
                        'item' => $stock->item->name,
                        'opening_stock' => $stock->opening_stock,
                        'stock_in' => $stock->stock_in,
                        'stock_out' => $stock->stock_out,
                        'available_stock' => $stock->available_stock,
                    ];
                })
            ]);
        }

        return view('stocks.index');
    }
}
