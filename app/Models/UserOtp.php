<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use App\Services\Fast2SMSService;

class UserOtp extends Model
{
    use HasFactory;

    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = ['user_id', 'otp', 'expire_at'];

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function sendSMS($receiverNumber)
    {
        $message = "Your OTP is $this->otp. Do not share it with anyone.";

        try {
            (new Fast2SMSService())->sendSMS($receiverNumber, $message);
            $smsService->sendSMS($receiverNumber, $message);
            info('SMS Sent Successfully.');
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
        }
    }
}
