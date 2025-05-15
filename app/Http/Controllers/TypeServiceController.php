<?php

namespace App\Http\Controllers;

use App\Models\TypeService;
use Illuminate\Http\Request;
use function Psy\debug;

class TypeServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = TypeService::query();

        if ($request->has('id')) {
            $categoryId = $request->input('id');
            $query->where('category_id', $categoryId);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'category_id' => ['required'],
        ]);

        return TypeService::create($data);
    }

    public function show(TypeService $typeService)
    {
        return $typeService;
    }

    public function update(Request $request, TypeService $typeService)
    {
        $data = $request->validate([
            'name' => ['required'],
            'category_id' => ['required'],
        ]);

        $typeService->update($data);

        return $typeService;
    }

    public function destroy(TypeService $typeService)
    {
        $typeService->delete();

        return response()->json();
    }
}
