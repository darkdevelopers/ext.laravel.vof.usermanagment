<?php
/**
 * @author     Marco Schauer <marco.schauer@darkdevelopers.de.de>
 * @copyright  2019 Marco Schauer
 */

namespace Vof\Usermanagment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Vof\Admin\Models\Admin;

/**
 * Class UsermanagmentController
 * @package Vof\Usermanagment\Http\Controllers
 */
class UsermanagmentController extends Controller
{
    /** @var int USERMANGAMENT_PAGINATION_DEFAULT */
    const USERMANGAMENT_PAGINATION_DEFAULT = 15;

    /**
     * UsermanagmentController constructor.
     */
    public function __construct()
    {
        $this->middleware(['web', 'auth:admin']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('vof.admin.usermanagment::index', [
            'admins' => Admin::paginate(self::USERMANGAMENT_PAGINATION_DEFAULT)
        ]);
    }

    public function create()
    {

    }

    public function show($id)
    {
        var_dump("Hello");
        var_dump($id);
        exit();
    }

    public function destroy(int $id)
    {
        var_dump("Destory");
        var_dump($id);
        exit();
    }
}
