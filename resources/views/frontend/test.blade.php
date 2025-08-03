<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 { color: #4CAF50; text-align: center; margin-bottom: 20px; }
        form { background-color: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 400px; max-width: 100%; }
        label { font-size: 16px; font-weight: 600; margin-bottom: 5px; display: block; }
        input, textarea, select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; }
        button { background-color: #4CAF50; color: white; border: none; border-radius: 4px; padding: 12px; font-size: 16px; cursor: pointer; width: 100%; transition: background-color 0.3s ease; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <div>
        <div>
            @if (session('success'))
                <p style="color: green;">{{ session('success') }}</p>
            @endif

            @if (session('error'))
                <p style="color: red;">{{ session('error') }}</p>
            @endif
        </div>

        <h1>Send an Otp</h1>

        <?php

function sendOtpSMS($mobile, $otp)
{
    $authKey = "446194AThF7RkYkZ687de9a2P1"; // ✅ MSG91 Auth Key
    $senderId = "URSDID"; // ✅ Sender ID
    $templateId = "67f78c8ad6fc05608c30d888"; // ✅ DLT Template ID

    $postData = array(
        "authkey"     => $authKey,
        "mobile"      => $mobile, // ✅ Corrected key here (not 'mobiles')
        "otp"         => $otp,
        "sender"      => $senderId,
        "template_id" => $templateId
    );

    $url = "https://control.msg91.com/api/v5/otp";

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($postData),
        CURLOPT_HTTPHEADER     => array(
            "authkey: $authKey",
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo "Curl Error: " . $err;
    } else {
        echo "OTP Sent to $mobile<br>";
        echo "API Response: " . $response;
    }
}

// ✅ Test
$mobile = "918433128326";
$otp = rand(100000, 999999); // generate 6-digit OTP

sendOtpSMS($mobile, $otp);

?>

        
    </div>
</body>
</html>
