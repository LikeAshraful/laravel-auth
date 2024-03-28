<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller {
    public function index() {
        $inventories = Inventory::all();
        return response()->json($inventories);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:inventories',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $inventory              = new Inventory();
        $inventory->user_id     = auth()->id();
        $inventory->name        = $request->name;
        $inventory->description = $request->description;
        $inventory->save();

        return response()->json($inventory, 201);
    }

    public function show($id) {
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], 404);
        }

        if ($inventory->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($inventory);
    }

    public function update(Request $request, $id) {
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], 404);
        }

        if ($inventory->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $inventory->name        = $request->name;
        $inventory->description = $request->description;
        $inventory->save();

        return response()->json($inventory);
    }

    public function destroy($id) {
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], 404);
        }

        if ($inventory->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $inventory->delete();

        return response()->json(['message' => 'Inventory deleted']);
    }

}
