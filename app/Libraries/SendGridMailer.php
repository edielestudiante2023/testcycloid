<?php

namespace App\Libraries;

/**
 * Envia correo via SendGrid API (HTTP directo) con click tracking
 * desactivado para que los enlaces no se reescriban a URLs de tracking.
 */
class SendGridMailer
{
    /**
     * @param string[] $toEmails
     * @return array{ok: bool, status: int, body: string}
     */
    public function send(array $toEmails, string $subject, string $html): array
    {
        $secretsFile = APPPATH . 'Config/Mail.' . ENVIRONMENT . '.php';
        if (!is_file($secretsFile)) {
            return ['ok' => false, 'status' => 0, 'body' => "Falta {$secretsFile}"];
        }
        $cfg = require $secretsFile;

        $toEmails = array_values(array_unique(array_filter($toEmails, static fn ($e) => $e !== '')));
        if (empty($toEmails)) {
            return ['ok' => false, 'status' => 0, 'body' => 'Sin destinatarios'];
        }

        $payload = [
            'personalizations' => [[
                'to' => array_map(static fn ($e) => ['email' => $e], $toEmails),
            ]],
            'from'    => ['email' => $cfg['from_email'], 'name' => $cfg['from_name']],
            'subject' => $subject,
            'content' => [['type' => 'text/html', 'value' => $html]],
            'tracking_settings' => [
                'click_tracking' => [
                    'enable'      => false,
                    'enable_text' => false,
                ],
            ],
        ];

        $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $cfg['api_key'],
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT    => 15,
        ]);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            return ['ok' => false, 'status' => 0, 'body' => "cURL error: {$error}"];
        }

        return ['ok' => $status >= 200 && $status < 300, 'status' => $status, 'body' => (string) $body];
    }
}
