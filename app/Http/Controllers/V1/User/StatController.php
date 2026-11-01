<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrafficLogResource;
use App\Models\StatUser;
use App\Services\StatisticalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{
    public function getTrafficLog(Request $request)
    {
        // Rolling ~30 calendar days (web「近月」). startOfMonth() returns only
        // today on the 1st, which is too short for the client and user center.
        $startDate = now()->subDays(30)->startOfDay()->timestamp;
        $records = StatUser::query()
            ->where('user_id', $request->user()->id)
            ->where('record_at', '>=', $startDate)
            ->orderBy('record_at', 'DESC')
            ->get();

        $data = TrafficLogResource::collection(collect($records));
        return $this->success($data);
    }
}
