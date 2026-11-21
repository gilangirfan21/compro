<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('kategori')->toString() ?: null;

        return view('portfolios.index', [
            'portfolios' => Portfolio::query()
                ->active()
                ->when($category, fn ($query) => $query->where('category', $category))
                ->latest('date')
                ->latest('id')
                ->paginate(9)
                ->withQueryString(),
            'categories' => Portfolio::query()
                ->active()
                ->whereNotNull('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
            'activeCategory' => $category,
        ]);
    }

    public function show(Portfolio $portfolio): View
    {
        abort_unless($portfolio->is_active, 404);

        return view('portfolios.show', [
            'portfolio' => $portfolio,
            'related' => Portfolio::query()
                ->active()
                ->whereKeyNot($portfolio->getKey())
                ->when($portfolio->category, fn ($query) => $query->where('category', $portfolio->category))
                ->latest('date')
                ->limit(3)
                ->get(),
        ]);
    }
}
