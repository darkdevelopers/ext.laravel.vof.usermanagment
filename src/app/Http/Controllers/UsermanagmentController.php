<?php
/**
 * @author     Marco Schauer <marco.schauer@darkdevelopers.de.de>
 * @copyright  2019 Marco Schauer
 */

namespace Vof\Usermanagment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Vof\Admin\Models\Admin;
use Validator;

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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('vof.admin.usermanagment::create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        return view('vof.admin.usermanagment::edit');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(int $id)
    {
        Admin::where('id', $id)->delete();

        return redirect(route('usermanagement'))->with('success', trans('vof.admin.usermanagment::usermanagment.partials.table.user-delete-success'));
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            $failedRules = $validator->failed();
            if (isset($failedRules['name']['Required'])) {
                return redirect(route('usermanagement.create'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.username-required'))->withInput()->withErrors($validator, 'error');
            }
            if (isset($failedRules['email']['Required'])) {
                return redirect(route('usermanagement.create'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.email-required'))->withInput()->withErrors($validator, 'error');
            }
        }

        $isUnique = Admin::where('email', $request->get('email'))->count();
        if ($isUnique) {
            return redirect(route('usermanagement.create'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.email-isexists'))->withInput()->withErrors('email is exists', 'error');
        }

        /** @var Admin $admin */
        $admin = Admin::create($request->all());
        if(!$admin->exists()){
            return redirect(route('usermanagement.create'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.admin-cant-created'))->withInput();
        }

        return redirect(route('usermanagement'))->with('success', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.admin-created-success'));
    }
}
