<?php
/**
 * @author     Marco Schauer <marco.schauer@darkdevelopers.de.de>
 * @copyright  2019 Marco Schauer
 */

Auth::routes();

Route::prefix('admin')->group(function () {
    Route::resource('/usermanagement', '\Vof\Usermanagment\Http\Controllers\UsermanagmentController', [
        'names' => [
            'index' => 'usermanagement',
            'destroy' => 'usermanagement.destroy',
        ],
    ]);
});
