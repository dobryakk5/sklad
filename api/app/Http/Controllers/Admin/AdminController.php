<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Operator;
use Illuminate\Http\Request;

abstract class AdminController extends ApiController
{
    protected function operator(Request $request): Operator
    {
        /** @var Operator $operator */
        $operator = $request->attributes->get('_operator');

        return $operator;
    }
}
