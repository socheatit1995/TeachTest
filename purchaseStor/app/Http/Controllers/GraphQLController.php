<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GraphQLController extends Controller
{
    public function query(Request $request, GraphQLContext $context)
    {
        $result = $context->executeQuery($request->input('query'));
        return response()->json($result);
    }
}