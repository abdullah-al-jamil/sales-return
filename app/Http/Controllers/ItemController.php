<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Item::select(['id', 'name', 'sku', 'stock', 'price'])->orderBy('id', 'desc');

            $recordsTotal = $query->count();

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $query->skip($start)->take($length);

            if ($request->has('order') && count($request->order) > 0) {
                $columnIndex = $request->order[0]['column'];
                $columnDir = $request->order[0]['dir'];
                $columns = ['id', 'name', 'sku', 'stock', 'price'];
                if (isset($columns[$columnIndex])) {
                    $query->orderBy($columns[$columnIndex], $columnDir);
                }
            }

            $items = $query->get();

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'sku' => $item->sku,
                        'stock' => $item->stock,
                        'price' => number_format($item->price, 2),
                    ];
                })
            ]);
        }

        return view('items.index');
    }
}
