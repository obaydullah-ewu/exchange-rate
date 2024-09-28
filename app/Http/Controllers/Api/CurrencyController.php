<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CurrencyHistory;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class CurrencyController extends Controller
{
    use ApiStatusTrait;
    public function index(): JsonResponse
    {
        $response['currencies'] = Currency::paginate(10);
        return $this->successApiResponse($response);
    }

    public function show($id): JsonResponse
    {
        $response['currency'] = Currency::findOrFail($id);
        return $this->successApiResponse($response);
    }

    public function currencyHistories(Request $request): JsonResponse
    {
        $response['currency_histories'] = CurrencyHistory::paginate(10);
        return $this->successApiResponse($response);
    }
}
