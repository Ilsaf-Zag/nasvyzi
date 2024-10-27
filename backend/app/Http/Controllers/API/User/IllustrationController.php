<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\IllustrationResource;
use App\Models\user;

class IllustrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        $users = user::all();

        return IllustrationResource::collection($users)->resolve();
    }
}
