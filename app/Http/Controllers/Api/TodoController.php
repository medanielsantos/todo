<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\TodoStoreRequest;
use App\Http\Requests\Todo\TodoUpdateRequest;
use App\Http\Resources\Todo\TodoResource;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return TodoResource::collection(Todo::query()->paginate(10));
    }

    public function show(Todo $todo): TodoResource
    {
        return new TodoResource($todo);
    }

    public function store(TodoStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $todo = Todo::query()->create($data);

        return response()->json(new TodoResource($todo), 201);
    }

    public function update(TodoUpdateRequest $request, Todo $todo): Response
    {
        $todo->update($request->validated());

        return response()->noContent();
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json([], 204);
    }
}
