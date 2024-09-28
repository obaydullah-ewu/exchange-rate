<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CurrencyHistory;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    use ApiStatusTrait;
    protected $paginate = 5;
    public function index(): JsonResponse
    {
        $response['user'] = Auth::guard('sanctum')->user();
        $response['currencies'] = Currency::paginate($this->paginate);
        return $this->successApiResponse($response);
    }

    public function show($id): JsonResponse
    {
        $response['currency'] = Currency::findOrFail($id);
        return $this->successApiResponse($response);
    }

    public function currencyHistories(): JsonResponse
    {
        $response['currency_histories'] = CurrencyHistory::paginate($this->paginate);
        return $this->successApiResponse($response);
    }
}
