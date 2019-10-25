<?php
/**
 * @author     Marco Schauer <marco.schauer@darkdevelopers.de.de>
 * @copyright  2019 Marco Schauer
 */

namespace Vof\Usermanagment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class UsermanagmentController
 * @package Vof\Usermanagment\Http\Controllers
 */
class UsermanagmentController extends Controller
{
    /**
     * UsermanagmentController constructor.
     */
    public function __construct()
    {
        $this->middleware(['web', 'auth:admin']);
    }

    /**
     * @param Request $request
     * @return |null
     */
    public function index(Request $request)
    {
        echo "Usermanagment";
        exit();
        return;
        //return view('admin::dashboard');
    }
}
