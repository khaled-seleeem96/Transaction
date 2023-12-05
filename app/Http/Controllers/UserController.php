<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function listUsers(Request $request)
    {
        $statusCode = $request->input('statusCode');
        $currency = $request->input('currency');
        $amountRange = $request->input('amountRange');
        $dateRange = $request->input('dateRange');
        $users = $this->userService->getUsersWithFilters($statusCode, $currency, $amountRange, $dateRange);

        return response()->json($users);
    }
}
