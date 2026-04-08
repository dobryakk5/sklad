<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateOperatorRequest;
use App\Http\Requests\Admin\UpdateOperatorRequest;
use App\Models\Operator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class OperatorController extends AdminController
{
    public function index(): JsonResponse
    {
        $items = Operator::query()->orderBy('name')->orderBy('id')->get();

        return $this->collection(
            items: $items->map(fn (Operator $operator) => $this->format($operator))->all(),
            total: $items->count(),
        );
    }

    public function store(CreateOperatorRequest $request): JsonResponse
    {
        $operator = Operator::query()->create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => $request->string('password')->toString(),
            'role' => $request->string('role')->toString(),
        ]);

        return $this->item($this->format($operator), 201);
    }

    public function update(UpdateOperatorRequest $request, int $id): JsonResponse
    {
        $operator = Operator::query()->find($id);

        if (! $operator) {
            return $this->notFound("Пользователь #{$id} не найден");
        }

        $currentOperator = $this->operator($request);
        $nextRole = $request->string('role')->toString();

        if ($operator->id === $currentOperator->id && $operator->isAdmin() && $nextRole !== 'admin') {
            return response()->json(['error' => 'Нельзя понизить собственную роль'], 422);
        }

        if (
            $operator->isAdmin()
            && $nextRole !== 'admin'
            && Operator::query()->where('role', 'admin')->count() === 1
        ) {
            return response()->json(['error' => 'Нельзя убрать единственного администратора'], 422);
        }

        $data = [
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'role' => $nextRole,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->string('password')->toString();
        }

        $operator->update($data);

        return $this->item($this->format($operator));
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        $operator = Operator::query()->find($id);

        if (! $operator) {
            return $this->notFound("Пользователь #{$id} не найден");
        }

        $currentOperator = $this->operator($request);

        if ($operator->id === $currentOperator->id) {
            return response()->json(['error' => 'Нельзя удалить себя'], 422);
        }

        if ($operator->isAdmin() && Operator::query()->where('role', 'admin')->count() === 1) {
            return response()->json(['error' => 'Нельзя удалить единственного администратора'], 422);
        }

        $operator->delete();

        return response()->json(['message' => 'Deleted']);
    }

    private function format(Operator $operator): array
    {
        return [
            'id' => $operator->id,
            'name' => $operator->name,
            'email' => $operator->email,
            'role' => $operator->role,
            'created_at' => $operator->created_at?->toDateString(),
        ];
    }
}
