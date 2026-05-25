<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class MailSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'mail_mailer', 'value' => 'smtp', 'type' => 'select'],
            ['key' => 'mail_host', 'value' => '', 'type' => 'text'],
            ['key' => 'mail_port', 'value' => '587', 'type' => 'text'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'text'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'password'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'type' => 'select'],
            ['key' => 'mail_from_address', 'value' => 'no-reply@tapanmemorialclub.com', 'type' => 'email'],
            ['key' => 'mail_from_name', 'value' => 'Tapan Memorial Club', 'type' => 'text'],
        ];

        foreach ($defaults as $row) {
            Setting::query()->updateOrCreate(
                ['key' => $row['key']],
                [
                    'group' => 'mail',
                    'value' => $row['value'],
                    'type' => $row['type'],
                    'is_public' => false,
                ]
            );
        }
    }
}
