<?php
/**
 * @author     Marco Schauer <marco.schauer@darkdevelopers.de.de>
 * @copyright  2019 Marco Schauer
 */

namespace Vof\Usermanagment;

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;

/**
 * Class UsermanagmentServiceProvider
 * @package Vof\Usermanagment
 */
class UsermanagmentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'vof.admin.usermanagment');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'vof.admin.usermanagment');
    }

    public function register()
    {

    }
}
