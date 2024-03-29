<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'qty'         => 'required|integer|min:0',
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

    public function show($inventoryId, $itemId) {
        $item = Item::find($itemId);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        if ($item->inventory->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($item);
    }

    public function update(Request $request, $inventoryId, $itemId) {

        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:items,name',
            'description' => 'required',
            'file'        => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qty'         => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $item = Item::find($itemId);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->name        = $request->name;
        $item->description = $request->description;

        if ($request->hasFile('file')) {
            $image     = $request->file('file');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $item->image = $imageName;
        }

        $item->quantity = $request->qty;
        $item->save();

        return response()->json($item, 200);
    }

    public function destroy($inventory_id, $itemId) {
        $item = Item::find($itemId);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        Storage::delete('public/images/' . $item->image_name);
        $item->delete();
        return response()->json(['message' => 'Item deleted']);
    }

}
