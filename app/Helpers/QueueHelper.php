<?php

namespace App\Helpers;

use App\Models\Request;
use Carbon\Carbon;

class QueueHelper
{
    public static function generateQueueNumber(string $type): string
    {
        // Get first 3 letters of type and convert to uppercase
        $prefix = strtoupper(substr($type, 0, 3));
        $today = Carbon::now();
        $dateCode = $today->format('ymd');
        
        // Get the last queue number for today
        $lastQueue = Request::where('queue', 'like', $prefix . $dateCode . '-%')
            ->orderBy('queue', 'desc')
            ->first();

        $sequence = '001';
        if ($lastQueue) {
            // Extract the sequence number and increment it
            $lastSequence = intval(substr($lastQueue->queue, -3));
            $sequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $dateCode . '-' . $sequence;
    }
}