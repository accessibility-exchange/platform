<?php

namespace App\Notifications;

use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class AccountApproved extends PlatformNotification
{
    public mixed $account;

    public function __construct(mixed $account)
    {
        $this->account = $account;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Account approved'))
            ->line(__('Your account has been approved.').' '.$this->getCapabilities($this->account))
            ->action(__('Access your dashboard'), localized_route('dashboard'));
    }

    public function toVonage(): VonageMessage
    {
        return (new VonageMessage)
            ->content(
                __('Your account on the Accessibility Exchange has been approved.').' '.$this->getCapabilities($this->account).' '.__('Access your dashboard at :url.', ['url' => localized_route('dashboard')])
            )
            ->unicode();
    }

    public function toArray(): array
    {
        return [
            'title' => __('Your account has been approved'),
            'body' => __('Your account has been approved.').' '.$this->getCapabilities($this->account),
        ];
    }

    public function getCapabilities($notifiable): string
    {
        if ($notifiable instanceof Individual) {
            if ($notifiable->isConnector() || $notifiable->isConsultant()) {
                if ($notifiable->isParticipant()) {
                    return __('You are now able to publish your page and sign up for projects.');
                }
            } elseif ($notifiable->isParticipant()) {
                return __('You are now able to sign up for projects.');
            }
        }
        if ($notifiable instanceof Organization && $notifiable->isParticipant()) {
            return __('You are now able to publish your page and take part in consultations.');
        } elseif ($notifiable instanceof RegulatedOrganization) {
            return __('You are now able to publish your page and create projects and engagements.');
        }

        return __('You are now able to publish your page.');
    }
}
