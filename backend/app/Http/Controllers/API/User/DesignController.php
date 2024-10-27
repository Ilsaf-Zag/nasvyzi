<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        $designs = Design::ordered()->get();

        return DesignResource::collection($designs)->resolve();
    }
}
