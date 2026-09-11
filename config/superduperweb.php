<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Email Notifikasi Admin
    |--------------------------------------------------------------------------
    |
    | Alamat tujuan pemberitahuan brief baru dan bukti pembayaran baru.
    | Kalau dikosongkan, sistem memakai MAIL_FROM_ADDRESS.
    |
    */

    'admin_email' => env('ADMIN_NOTIFICATION_EMAIL') ?: env('MAIL_FROM_ADDRESS'),

    /*
    |--------------------------------------------------------------------------
    | Batas Unggahan Bukti Pembayaran
    |--------------------------------------------------------------------------
    */

    'proof_max_kb' => 2048,

    'proof_mimes' => ['jpg', 'jpeg', 'png', 'pdf'],

];
