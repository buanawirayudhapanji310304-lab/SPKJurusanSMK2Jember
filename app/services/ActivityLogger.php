<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ActivityLogger
{
    public static function log(string $aksi, ?int $userId = null): void
    {
        $userId ??= Auth::id();

        if (!$userId) {
            return;
        }

        try {
            ActivityLog::create([
                'user_id' => $userId,
                'aksi' => Str::limit($aksi, 60, ''),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Activity log gagal dibuat', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
