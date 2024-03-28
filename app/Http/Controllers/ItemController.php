<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller {
    public function index($inventoryId) {
        $items = Item::where('inventory_id', $inventoryId)->get();
        return response()->json($items);
    }

    public function store(Request $request, $inventoryId) {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:items,name',
            'description' => 'required',
            'file'        => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qty'    => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $item               = new Item();
        $item->inventory_id = $inventoryId;
        $item->name         = $request->name;
        $item->description  = $request->description;

        if ($request->hasFile('file')) {
            $image     = $request->file('file');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $item->image = $imageName;
        }

        $item->quantity = $request->qty;
        $item->save();

        return response()->json($item, 201);
    }

    public function update(Request $request, $inventoryId, $itemId) {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:items,name',
            'description' => 'required',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity'    => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = Item::find($itemId);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->name        = $request->name;
        $item->description = $request->description;

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $item->image = $imageName;
        }

        $item->quantity = $request->quantity;
        $item->save();

        return response()->json($item, 200);
    }

    public function destroy($itemId) {
        $item = Item::find($itemId);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->delete();
        return response()->json(['message' => 'Item deleted']);
    }

}
