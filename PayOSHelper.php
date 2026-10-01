<?php
class PayOSHelper {
    private $clientId;
    private $apiKey;
    private $checksumKey;

    public function __construct(
        $clientId = "885eb0f0-6711-4172-b076-a5e3a6fcc2da",
        $apiKey = "e4f78678-f575-4f80-a388-dd537401331f",
        $checksumKey = "db1ebc90cd79c569ba6eeaa49f1452ac849d5c500690b616cb0abd50f777c23a"
    ) {
        $this->clientId = $clientId;
        $this->apiKey = $apiKey;
        $this->checksumKey = $checksumKey;
    }

    // Tạo chữ ký dữ liệu (signature) theo đúng chuẩn PayOS
    private function createSignature($data) {
        // Sắp xếp các key theo thứ tự a-z
        ksort($data);
        $signArr = [];
        foreach ($data as $key => $value) {
            if ($value === null || $value === "") continue;
            // Bỏ qua signature nếu có sẵn
            if ($key == "signature") continue;
            $signArr[] = $key . "=" . $value;
        }
        $dataStr = implode("&", $signArr);
        return hash_hmac("sha256", $dataStr, $this->checksumKey);
    }

    // Gọi API PayOS để tạo link thanh toán (có hỗ trợ danh sách items kèm size)
    public function createPaymentLink($orderCode, $amount, $description, $returnUrl, $cancelUrl, $items = []) {
        // 1. Dữ liệu bắt buộc dùng để tạo chữ ký HMAC SHA256
        $signData = [
            "amount" => $amount,
            "cancelUrl" => $cancelUrl,
            "description" => $description,
            "orderCode" => $orderCode,
            "returnUrl" => $returnUrl
        ];

        $signature = $this->createSignature($signData);

        // 2. Ghép dữ liệu gửi sang API PayOS
        $body = array_merge($signData, [
            "signature" => $signature
        ]);

        // Nếu có truyền danh sách sản phẩm thì thêm vào body
        if (!empty($items)) {
            $body["items"] = $items;
        }

        // 3. Gửi request qua cURL
        $ch = curl_init("https://api-merchant.payos.vn/v2/payment-requests");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "x-client-id: " . $this->clientId,
            "x-api-key: " . $this->apiKey,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new Exception("cURL Error: " . $err);
        }

        $resData = json_decode($response, true);
        if (isset($resData['code']) && $resData['code'] == "00") {
            return $resData['data']['checkoutUrl'];
        } else {
            $msg = isset($resData['desc']) ? $resData['desc'] : 'Lỗi không xác định';
            throw new Exception("PayOS Error: " . $msg);
        }
    }
}
?>