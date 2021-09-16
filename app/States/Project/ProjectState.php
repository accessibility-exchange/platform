<?php

namespace App\States\Project;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ProjectState extends State
{
    abstract public function slug(): string;

    abstract public function label(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition(Preparing::class, ConfirmingConsultants::class)
            ->allowTransition(ConfirmingConsultants::class, NegotiatingConsultations::class)
            ->allowTransition(NegotiatingConsultations::class, HoldingConsultations::class)
            ->allowTransition(HoldingConsultations::class, WritingReport::class)
            ->allowTransition(WritingReport::class, Completed::class);
    }
}
