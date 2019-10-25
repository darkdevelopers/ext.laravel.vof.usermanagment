<?php
/**
 * @author     Marco Schauer <marco.schauer@darkdevelopers.de.de>
 * @copyright  2019 Marco Schauer
 */

Auth::routes();

Route::prefix('admin')->group(function () {
    Route::get('/usermanagment', '\Vof\Usermanagment\Http\Controllers\UsermanagmentController@index')->name('admin-usermanagment');
});
