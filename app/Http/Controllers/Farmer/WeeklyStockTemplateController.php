<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\WeeklyStockTemplate;
use App\Models\Product;
use Illuminate\Http\Request;

class WeeklyStockTemplateController extends Controller
{
    public function index()
    {
        $farmer = auth()->user()->farmerProfile;

        $templates = WeeklyStockTemplate::with('product')
            ->where('farmer_profile_id', $farmer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Products this farmer owns, to allow adding new template rows
        $products = Product::where('farmer_profile_id', $farmer->id)
            ->orderBy('name')
            ->get();

        return view('farmer.weekly-stock-templates.index', compact('templates', 'products'));
    }

    public function store(Request $request)
    {
        $farmer = auth()->user()->farmerProfile;

        $validated = $request->validate([
            'product_id'      => 'required|exists:products,id',
            'weekly_quantity' => 'required|integer|min:0',
            'notes'           => 'nullable|string|max:1000',
        ]);

        // Ensure the product belongs to this farmer
        $productBelongsToFarmer = Product::where('id', $validated['product_id'])
            ->where('farmer_profile_id', $farmer->id)
            ->exists();

        abort_unless($productBelongsToFarmer, 403);

        WeeklyStockTemplate::updateOrCreate(
            [
                'farmer_profile_id' => $farmer->id,
                'product_id'        => $validated['product_id'],
            ],
            [
                'weekly_quantity' => $validated['weekly_quantity'],
                'notes'           => $validated['notes'] ?? null,
            ]
        );

        return back()->with('success', 'Weekly stock template saved.');
    }

    public function destroy(WeeklyStockTemplate $weeklyStockTemplate)
    {
        $farmer = auth()->user()->farmerProfile;

        abort_unless($weeklyStockTemplate->farmer_profile_id === $farmer->id, 403);

        $weeklyStockTemplate->delete();

        return back()->with('success', 'Template entry deleted.');
    }
}