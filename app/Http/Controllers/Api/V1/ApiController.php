<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponse;

    protected $policyClass;

    public function __construct()
    {
        Gate::guessPolicyNamesUsing(function () {
            return $this->policyClass;
        });
    }

    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }

//    public function isAble($ability, $targetModel)
//    {
//        $gate = Gate::policy($targetModel, $this->policyClass);
//        return $gate->authorize($ability, [$targetModel]);
//    }
}
