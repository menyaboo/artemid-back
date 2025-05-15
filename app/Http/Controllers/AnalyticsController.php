<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function personal()
    {
        $userId = Auth::id();

        $totalAppeals = DB::table('appeals')
            ->where('user_id', $userId)
            ->count();

        // Количество выполненных обращений
        $completedCount = DB::table('appeals')
            ->where('user_id', $userId)
            ->where('status_id', 3)
            ->count();

        // Процент выполненных обращений
        $completionRate = $totalAppeals > 0
            ? round(($completedCount / $totalAppeals) * 100, 2)
            : 0;

        // Количество обращений, которые ожидают завершения (например, статус не "завершено" и не "отменено")
        $pendingCount = DB::table('appeals')
            ->where('user_id', $userId)
            ->whereNotIn('status_id', [3, 4])
            ->count();

        return response()->json([
            'total_appeals' => $totalAppeals,
            'completion_rate' => $completionRate . '%',
            'pending_appeals' => $pendingCount,
        ]);
    }
    public function overall()
    {
        // Общее количество обращений
        $totalAppeals = DB::table('appeals')->count();

        // Топ N категорий
        $topCategories = DB::table('appeals')
            ->join('type_services', 'appeals.type_id', '=', 'type_services.id')
            ->join('category_services', 'type_services.category_id', '=', 'category_services.id')
            ->select('category_services.id', 'category_services.name', DB::raw('COUNT(appeals.id) as count'))
            ->groupBy('category_services.id', 'category_services.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'count' => (int)$item->count
                ];
            });

        // Топ N типов обращений
        $topTypes = DB::table('appeals')
            ->join('type_services', 'appeals.type_id', '=', 'type_services.id')
            ->select('type_services.id', 'type_services.name', DB::raw('COUNT(appeals.id) as count'))
            ->groupBy('type_services.id', 'type_services.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'count' => (int)$item->count
                ];
            });

        // Распределение по статусам
        $statusDistribution = DB::table('appeals')
            ->join('status_services', 'appeals.status_id', '=', 'status_services.id')
            ->select('status_services.id', 'status_services.name', DB::raw('COUNT(appeals.id) as count'))
            ->groupBy('status_services.id', 'status_services.name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'count' => (int)$item->count
                ];
            });

        return response()->json([
            'total_appeals' => $totalAppeals,
            'top_categories' => $topCategories,
            'top_types' => $topTypes,
            'status_distribution' => $statusDistribution,
        ]);
    }
}
