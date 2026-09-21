<?php

namespace App\Support;

class MailDelivery
{
    /**
     * Check for a sending transport, not whether the provider will accept a message.
     * Logging fallbacks also count as non-delivery: they can silently swallow failures.
     */
    public static function usesSendingTransport(?string $mailer = null, array $visited = []): bool
    {
        $mailer ??= config('mail.default');
        if (! $mailer || in_array($mailer, $visited, true)) {
            return false;
        }

        $settings = config("mail.mailers.{$mailer}", []);
        $transport = $settings['transport'] ?? null;
        if (! $transport || in_array($transport, ['log', 'array'], true)) {
            return false;
        }

        if (in_array($transport, ['failover', 'roundrobin'], true)) {
            $children = $settings['mailers'] ?? [];
            if ($children === []) {
                return false;
            }

            foreach ($children as $child) {
                if (! self::usesSendingTransport($child, [...$visited, $mailer])) {
                    return false;
                }
            }
        }

        return true;
    }
}
