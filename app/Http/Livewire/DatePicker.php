<?php

namespace App\Http\Livewire;

use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Carbon;
use Livewire\Component;

class DatePicker extends Component
{
    public string $name;

    public string $label;

    public string $hint;

    public bool $required = false;

    public bool $disabled = false;

    public string|null $value = null;

    public string|null $year = null;

    public string|null $month = null;

    public string|null $day = null;

    public array $months;

    public int|null $minimumYear = null;

    public int|null $maximumYear = null;

    public function mount(): void
    {
        if ($this->value) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $this->value);
                $this->year = strval($date->year);
                $this->month = str_pad(strval($date->month), 2, '0', STR_PAD_LEFT);
                $this->day = strval($date->day);
            } catch (InvalidFormatException $exception) {
                [$y, $m, $d] = explode('-', $this->value);
                $this->year = $y;
                $this->month = $m;
                $this->day = $d;
            }
        }
    }

    public function getDateProperty(): string
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d', implode('-', [$this->year, $this->month, $this->day]));

            return $date->format('Y-m-d');
        } catch (InvalidFormatException $exception) {
            return implode('-', [$this->year, $this->month, $this->day]);
        }
    }

    public function render()
    {
        $this->months = [
            [
                'value' => '',
                'label' => __('Choose a month…'),
            ],
            [
                'value' => '01',
                'label' => __('forms.months.1'),
            ],
            [
                'value' => '02',
                'label' => __('forms.months.2'),
            ],
            [
                'value' => '03',
                'label' => __('forms.months.3'),
            ],
            [
                'value' => '04',
                'label' => __('forms.months.4'),
            ],
            [
                'value' => '05',
                'label' => __('forms.months.5'),
            ],
            [
                'value' => '06',
                'label' => __('forms.months.6'),
            ],
            [
                'value' => '07',
                'label' => __('forms.months.7'),
            ],
            [
                'value' => '08',
                'label' => __('forms.months.8'),
            ],
            [
                'value' => '09',
                'label' => __('forms.months.9'),
            ],
            [
                'value' => '10',
                'label' => __('forms.months.10'),
            ],
            [
                'value' => '11',
                'label' => __('forms.months.11'),
            ],
            [
                'value' => '12',
                'label' => __('forms.months.12'),
            ],
        ];

        return view('livewire.date-picker');
    }
}
