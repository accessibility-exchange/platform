<?php

namespace App\Notifications;

use App\Models\Individual;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class AccountUnsuspended extends PlatformNotification
{
    public mixed $account;

    public function __construct(mixed $account)
    {
        $this->account = $account;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Account suspension lifted'))
            ->line(__('Your account is no longer suspended.').' '.$this->getCapabilities($this->account).' '.__('Please contact us if you need further assistance.'))
            ->action(__('Contact support'), localized_route('dashboard').'#contact');
    }

    public function toVonage(): VonageMessage
    {
        return (new VonageMessage)
            ->content(
                __('Your account on the Accessibility Exchange is no longer suspended.').' '.$this->getCapabilities($this->account).' '.__('Please contact us at :email or :phone if you need further assistance.',
                    [
                        'email' => settings('email'),
                        'phone' => phone(settings('phone'), 'CA')->formatForCountry('CA'),
                    ]
                )
            )
            ->unicode();
    }

    public function toArray(): array
    {
        return [
            'title' => __('Your account suspension has been lifted'),
            'body' => __('Your account is no longer suspended.').' '.$this->getCapabilities($this->account).' '.__('Please contact us if you need further assistance.'),
        ];
    }

    public function getCapabilities($notifiable): string
    {
        if ($notifiable instanceof Individual && ! $notifiable->isConnector() && ! $notifiable->isConsultant()) {
            return __('You will be able to edit your information and browse projects and people on this site again.');
        }

        return __('You will be able to edit your information and browse projects and people on this site again. Your page will no longer be hidden to other users of the website.');
    }
}
