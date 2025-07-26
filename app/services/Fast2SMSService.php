<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class Fast2SMSService
{
    public function sendSMS($phone, $message)
    {
        $apiKey = env('FAST2SMS_API_KEY');

        $response = Http::withHeaders([
            'authorization' => $apiKey,
            'cache-control' => 'no-cache',
        ])->post('https://www.fast2sms.com/dev/bulkV2', [
            'sender_id' => 'FSTSMS', // or your DLT sender ID
            'message' => $message,
            'language' => 'english',
            'route' => 'q', // 'q' is for transactional/OTP
            'numbers' => $phone,
        ]);

        return $response->json();
    }
}
