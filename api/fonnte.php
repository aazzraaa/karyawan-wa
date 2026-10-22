<?php

/*
|--------------------------------------------------------------------------
| KONFIGURASI FONNTE
|--------------------------------------------------------------------------
| FONNTE_TOKEN = token API dari Fonnte
| FONNTE_ADMIN = nomor WhatsApp admin
|--------------------------------------------------------------------------
*/

const FONNTE_TOKEN = '6JVEShvnZTYx8p1xhyoj';
const FONNTE_ADMIN = '085726575709';


/*
|--------------------------------------------------------------------------
| KIRIM WHATSAPP
|--------------------------------------------------------------------------
*/

function sendWhatsApp($target, $message)
{
    if (empty($target)) {
        return [
            'success' => false,
            'message' => 'Nomor WhatsApp tujuan kosong.'
        ];
    }

    if (empty(FONNTE_TOKEN)) {
        return [
            'success' => false,
            'message' => 'Token Fonnte kosong.'
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | BERSIHKAN NOMOR
    |--------------------------------------------------------------------------
    */

    $target = preg_replace('/[^0-9]/', '', $target);


    /*
    |--------------------------------------------------------------------------
    | UBAH 08xxxx MENJADI 628xxxx
    |--------------------------------------------------------------------------
    */

    if (substr($target, 0, 1) === '0') {
        $target = '62' . substr($target, 1);
    }


    /*
    |--------------------------------------------------------------------------
    | CURL
    |--------------------------------------------------------------------------
    */

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.fonnte.com/send',

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_POST => true,

        CURLOPT_POSTFIELDS => [
            'target' => $target,
            'message' => $message
        ],

        CURLOPT_HTTPHEADER => [
            'Authorization: ' . FONNTE_TOKEN
        ],

        CURLOPT_TIMEOUT => 20
    ]);


    $response = curl_exec($curl);

    $error = curl_error($curl);

    $httpCode = curl_getinfo(
        $curl,
        CURLINFO_HTTP_CODE
    );

    curl_close($curl);


    /*
    |--------------------------------------------------------------------------
    | ERROR CURL
    |--------------------------------------------------------------------------
    */

    if ($error) {
        return [
            'success' => false,
            'message' => 'cURL Error: ' . $error
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSE FONNTE
    |--------------------------------------------------------------------------
    */

    $data = json_decode($response, true);


    /*
    |--------------------------------------------------------------------------
    | JIKA RESPONSE BISA DIBACA SEBAGAI JSON
    |--------------------------------------------------------------------------
    */

    if (is_array($data)) {

        $status = $data['status'] ?? false;

        if (
            $status === true ||
            $status === 'true' ||
            $status === 1
        ) {
            return [
                'success' => true,
                'message' => 'WhatsApp berhasil dikirim.',
                'response' => $data
            ];
        }

        $reason = $data['reason'] ?? null;

        if (is_array($reason)) {
            $reason = json_encode(
                $reason,
                JSON_UNESCAPED_UNICODE
            );
        }

        return [
            'success' => false,
            'message' => $reason ?: 'Fonnte menolak pengiriman WhatsApp.',
            'response' => $data
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSE BUKAN JSON
    |--------------------------------------------------------------------------
    */

    return [
        'success' => $httpCode >= 200 && $httpCode < 300,
        'message' => $response ?: 'Fonnte tidak memberikan response.',
        'http_code' => $httpCode
    ];
}


/*
|--------------------------------------------------------------------------
| KIRIM KE KARYAWAN DAN ADMIN
|--------------------------------------------------------------------------
*/

function sendToEmployeeAndAdmin(
    $employeePhone,
    $employeeMessage,
    $adminMessage = null
) {

    /*
    |--------------------------------------------------------------------------
    | KIRIM KE KARYAWAN
    |--------------------------------------------------------------------------
    */

    $employeeResult = sendWhatsApp(
        $employeePhone,
        $employeeMessage
    );


    /*
    |--------------------------------------------------------------------------
    | KIRIM KE ADMIN
    |--------------------------------------------------------------------------
    */

    $adminResult = null;

    if (!empty(FONNTE_ADMIN)) {

        $adminResult = sendWhatsApp(
            FONNTE_ADMIN,
            $adminMessage ?: $employeeMessage
        );
    }


    return [
        'employee' => $employeeResult,
        'admin' => $adminResult
    ];
}