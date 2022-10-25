<?php

namespace App\Notifications;

use App\Models\Individual;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class AccountSuspended extends PlatformNotification
{
    public mixed $account;

    public function __construct(mixed $account)
    {
        $this->account = $account;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Account suspended'))
            ->line(__('Your account has been suspended.').' '.$this->getCapabilities($this->account).' '.__('Please contact us if you need further assistance.'))
            ->action(__('Contact support'), localized_route('dashboard').'#contact');
    }

    public function toVonage(): VonageMessage
    {
        return (new VonageMessage)
            ->content(
                __('Your account on the Accessibility Exchange has been suspended.').' '.$this->getCapabilities($this->account).' '.__('Please contact us at :email or :phone if you need further assistance.',
                    [
                        'email' => settings()->get('email', 'support@accessibilityexchange.ca'),
                        'phone' => phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA'),
                    ]
                )
            )
            ->unicode();
    }

    public function toArray(): array
    {
        return [
            'title' => __('Your account has been suspended'),
            'body' => __('Your account has been suspended.').' '.$this->getCapabilities($this->account).' '.__('Please contact us if you need further assistance.'),
        ];
    }

    public function getCapabilities($notifiable): string
    {
        if ($notifiable instanceof Individual && ! $notifiable->isConnector() && ! $notifiable->isConsultant()) {
            return __('You will not be able to edit any information in your account.');
        }

        return __('You will not be able to edit any information in your account. If you published your page already, it has been unpublished.');
    }
}
