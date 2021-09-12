<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            "users" => User::getUsers()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $users = User::getUsers();
        $user = $users[$id - 1];
        return response()->json($user);
    }

    /**
     * Search user by email
     *
     * @param string $email
     * @return JsonResponse
     * @throws \Exception
     */
    public function search($email): JsonResponse
    {
        $users = User::getUsers();
        $userId = array_search($email, array_column($users, "email"));
        $users = [$users[$userId]];

        if (empty($users)) {
            throw new \Exception("No users with such email", 404);
        }

        return response()->json($users);
    }

    /**
     * @param string $name
     * @return JsonResponse
     * @throws \Exception
     */
    public function contains($name): JsonResponse
    {
        $users = User::getUsers();
        $users = array_filter($users, function ($user) use ($name) {
            return str_contains($user["name"], $name);
        });

        if (empty($users)) {
            throw new \Exception("Such users not found", 404);
        }

        return response()->json($users);
    }
}
