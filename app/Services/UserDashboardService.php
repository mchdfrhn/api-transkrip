<?php

namespace App\Services;

use App\Models\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardService
{
    public function getDashboardStats()
    {
        $userId = Auth::id();
        
        $totalRequests = Request::where('user_id', $userId)->count();
        
        // Get requests with responses
        $requestsWithResponses = Request::with('response')
            ->where('user_id', $userId)
            ->get();
        
        // Calculate statistics
        $accepted = 0;
        $processing = 0;
        $rejected = 0;
        
        foreach ($requestsWithResponses as $request) {
            if (!$request->response) {
                $processing++;
                continue;
            }
            
            $status = $request->response->status ?? 'processing';
            
            switch ($status) {
                case 'accepted':
                    $accepted++;
                    break;
                case 'rejected':
                    $rejected++;
                    break;
                default:
                    $processing++;
                    break;
            }
        }
        
        return [
            'total_requests' => $totalRequests,
            'requests_accepted' => $accepted,
            'requests_processing' => $processing,
            'requests_rejected' => $rejected
        ];
    }
}