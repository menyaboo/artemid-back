<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppealController extends Controller
{
    public function index()
    {
       $appeals = Appeal::with(['status', 'type.category', 'user'])->get();
       return $appeals->map(function ($item) {
            return [
                ...$item->makeHidden(['status_id', 'type_id', 'user_id'])->toArray(),
                'status' => $item->status,
                'type' => [
                    ...$item->type->makeHidden(['category_id'])->toArray(),
                    'category' => $item->type->category,
                ],
                'user' => $item->user,
            ];
        });
    }

    public function personal() {
        $appeals = Appeal::with(['status', 'type.category', 'user'])
            ->where('user_id', auth()->id())
            ->get();

        return $appeals->map(function ($item) {
            return [
                ...$item->makeHidden(['status_id', 'type_id', 'user_id'])->toArray(),
                'status' => $item->status,
                'type' => [
                    ...$item->type->makeHidden(['category_id'])->toArray(),
                    'category' => $item->type->category,
                ],
                'user' => $item->user,
            ];
        });
    }

    public function store(Request $request, NotificationService $notifier)
    {
        $data = $request->validate([
            'message' => ['required'],
            'type_id' => ['required'],
        ]);

        // Создаём обращение
        $appeal = Appeal::create([...$data, 'user_id' => Auth::user()->id]);

        // Уведомляем админов о новом обращении
        $notifier->notifyNewAppeal($appeal->id);

        return $appeal;
    }

    public function show(Appeal $appeal)
    {
        $appeal->load(['status', 'type.category', 'user']);

        return [
            ...$appeal->makeHidden(['status_id', 'type_id', 'user_id'])->toArray(),
            'status' => $appeal->status,
            'type' => [
                ...$appeal->type->makeHidden(['category_id'])->toArray(),
                'category' => $appeal->type->category,
            ],
            'user' => $appeal->user,
        ];
    }

    public function update(Request $request, Appeal $appeal, NotificationService $notifier)
    {
        // Валидация данных
        $data = $request->validate([
            'message' => ['exclude'],
            'type_id' => ['exclude'],
            'user_id' => ['exclude'],
            'status_id' => ['required', 'exists:status_services,id'],
        ]);

        // Сохраняем старый статус для сравнения (если нужно)
        $oldStatusId = $appeal->status_id;

        // Обновляем обращение
        $appeal->update($request->all());

        // Если статус изменился
        if ($oldStatusId !== $appeal->status_id) {
            // Получаем имя нового статуса
            $newStatusName = $appeal->status->name; // Предполагается, что связь `status` загружена или доступна

            // Отправляем уведомление пользователю
            $notifier->notifyStatusChanged(
                $appeal->user_id,
                $appeal->id,
                $newStatusName
            );
        }

        return $appeal;
    }

    public function destroy(Appeal $appeal)
    {
        $appeal->delete();

        return response()->json();
    }

    public function search(Request $request)
    {
        $appeals = Appeal::where('id', "LIKE","%{$request->input("search")}%")
            ->orWhere('message', "LIKE","%{$request->input("search")}%")
            ->orWhereHas('type', function(Builder $query) use($request) {
                $query->where('name', "LIKE", "%{$request->input("search")}%");
            } )
            ->orWhereHas('category', function (Builder $query) use ($request) {
                $query->where('category_services.name', 'LIKE', "%{$request->input("search")}%");
            });

        if ($request->input('type') === 'all') {
            if (Auth::user()->role->code === 'client') {
                return response()->json('forbidden', 403);
            }
            return $appeals->get()->map(function ($item) {
                $item->makeHidden(["status_id", "type_id", "user_id"]);
                return [...$item->toArray(),
                    "status" => $item->status,
                    "type" => $item->type,
                    "user" => $item->user,
                ];
            });
        }
        return $appeals->where('user_id', Auth::id())->get()->map(function ($item) {
            $item->makeHidden(["status_id", "type_id", "user_id"]);
            return [...$item->toArray(),
                "status" => $item->status,
                "type" => $item->type,
                "user" => $item->user,
            ];
        });
    }
}
