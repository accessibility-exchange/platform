<?php

namespace App\Http\Livewire;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Livewire\Component;

class DatePicker extends Component
{
    public string $name;

    public string $label;

    public string $hint;

    public bool $required = false;

    public bool $disabled = false;

    public string|Carbon|null $value;

    public int|null $year = null;

    public int|null $month = null;

    public int|null $day = null;

    public array $months;

    public int $minimumYear = 2022;

    public function mount(): void
    {
        if ($this->value && ! $this->value instanceof CarbonInterface) {
            $this->value = Carbon::parse($this->value);
        } else {
            $this->value = null;
        }

        $this->year = $this->value?->year;
        $this->month = $this->value?->month;
        $this->day = $this->value?->day;
    }

    public function render()
    {
        $this->months = [
            [
                'value' => '',
                'label' => __('Choose a month…'),
            ],
            [
                'value' => '1',
                'label' => __('forms.month_january'),
            ],
            [
                'value' => '2',
                'label' => __('forms.month_february'),
            ],
            [
                'value' => '3',
                'label' => __('forms.month_march'),
            ],
            [
                'value' => '4',
                'label' => __('forms.month_april'),
            ],
            [
                'value' => '5',
                'label' => __('forms.month_may'),
            ],
            [
                'value' => '6',
                'label' => __('forms.month_june'),
            ],
            [
                'value' => '7',
                'label' => __('forms.month_july'),
            ],
            [
                'value' => '8',
                'label' => __('forms.month_august'),
            ],
            [
                'value' => '9',
                'label' => __('forms.month_september'),
            ],
            [
                'value' => '10',
                'label' => __('forms.month_october'),
            ],
            [
                'value' => '11',
                'label' => __('forms.month_november'),
            ],
            [
                'value' => '12',
                'label' => __('forms.month_december'),
            ],
        ];

        return view('livewire.date-picker');
    }
}
