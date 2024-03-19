<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class Info extends BaseWidget
{

    protected static bool $isLazy = false;
      
    /**
     * @var view-string
     */
    protected static string $view = 'filament-panels::widgets.account-widget';

}
