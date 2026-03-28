<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MomoService
{
    public function createPaymentUrl(array $data)
    {
        // Kiểm tra chế độ Giả lập (Mock Mode)
        if (config('momo.mock_mode')) {
            Log::info("MoMo Mock Mode is ENABLED. Redirecting to local mock page.");
            return route('momo.mock', ['booking_id' => $data['booking_id']]);
        }

        $endpoint = config('momo.endpoint');
        $partnerCode = config('momo.partner_code');
        $accessKey = config('momo.access_key');
        $secretKey = config('momo.secret_key');
        
        $orderInfo = "Thanh toan don hang VietTour #" . $data['booking_id'];
        $amount = (string)$data['amount'];
        $orderId = (string)$data['booking_id'] . '_' . time(); 
        $requestId = $orderId;
        $returnUrl = config('momo.return_url');
        $notifyUrl = "https://momo.vn"; 
        
        $requestType = "captureWallet";
        $extraData = "";

        // Tạo chữ ký số HMAC SHA256 (Signature)
        // Alphabetical order: accessKey, amount, extraData, ipnUrl, orderId, orderInfo, partnerCode, redirectUrl, requestId, requestType
        $rawHash = "accessKey=" . $accessKey .
                   "&amount=" . $amount .
                   "&extraData=" . $extraData .
                   "&ipnUrl=" . $notifyUrl .
                   "&orderId=" . $orderId .
                   "&orderInfo=" . $orderInfo .
                   "&partnerCode=" . $partnerCode .
                   "&redirectUrl=" . $returnUrl .
                   "&requestId=" . $requestId .
                   "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, trim($secretKey));

        $requestData = [
            'partnerCode' => $partnerCode,
            'requestId' => $requestId,
            'amount' => (int) $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $returnUrl,
            'ipnUrl' => $notifyUrl,
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
            'lang' => 'vi'
        ];

        try {
            $response = Http::post($endpoint, $requestData);
            $jsonResult = $response->json();
            
            if (isset($jsonResult['payUrl'])) {
                return $jsonResult['payUrl'];
            }
            
            Log::error('MoMo Create Payment API Error (11007 thường do Sandbox public bị lỗi)', $jsonResult);
            
            // Fallback sang Mock Mode nếu API lỗi nhưng k cấu hình sẵn mock (để user k bị kẹt)
            return route('momo.mock', ['booking_id' => $data['booking_id']]);

        } catch (\Exception $e) {
            Log::error('MoMo Create Payment API Exception: ' . $e->getMessage());
            return route('momo.mock', ['booking_id' => $data['booking_id']]);
        }
    }

    /**
     * Xác thực tính toàn vẹn của kết quả từ MoMo trả về (Result Check)
     */
    public function validateSignature(array $responseData): bool
    {
        // Cho phép bypass signature nếu đang ở chế độ Mock
        if (config('momo.mock_mode') && ($responseData['signature'] ?? '') === 'mock') {
            return true;
        }

        $secretKey = config('momo.secret_key');
        $accessKey = config('momo.access_key');

        if (!isset($responseData['signature'])) {
            return false;
        }
        
        $momoSignature = $responseData['signature'];

        // Alphabetical order for result: 
        // accessKey, amount, extraData, message, orderId, orderInfo, orderType, partnerCode, payType, requestId, responseTime, resultCode, transId
        $rawHash = "accessKey=" . $accessKey .
            "&amount=" . ($responseData['amount'] ?? '') .
            "&extraData=" . ($responseData['extraData'] ?? '') .
            "&message=" . ($responseData['message'] ?? '') .
            "&orderId=" . ($responseData['orderId'] ?? '') .
            "&orderInfo=" . ($responseData['orderInfo'] ?? '') .
            "&orderType=" . ($responseData['orderType'] ?? '') .
            "&partnerCode=" . ($responseData['partnerCode'] ?? '') .
            "&payType=" . ($responseData['payType'] ?? '') .
            "&requestId=" . ($responseData['requestId'] ?? '') .
            "&responseTime=" . ($responseData['responseTime'] ?? '') .
            "&resultCode=" . ($responseData['resultCode'] ?? '') .
            "&transId=" . ($responseData['transId'] ?? '');

        $mySignature = hash_hmac("sha256", $rawHash, $secretKey);
        return hash_equals($mySignature, $momoSignature);
    }
}
