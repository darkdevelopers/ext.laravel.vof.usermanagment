<?php
/**
 * @author     Marco Schauer <marco.schauer@darkdevelopers.de.de>
 * @copyright  2019 Marco Schauer
 */

namespace Vof\Usermanagment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
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
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $admin = Admin::where('id', $id)->first();
        return view('vof.admin.usermanagment::edit', ['admin' => $admin]);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            $failedRules = $validator->failed();
            if (isset($failedRules['name']['Required'])) {
                return redirect(route('usermanagement.edit'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.username-required'))->withInput()->withErrors($validator, 'error');
            }
            if (isset($failedRules['email']['Required'])) {
                return redirect(route('usermanagement.edit'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.email-required'))->withInput()->withErrors($validator, 'error');
            }
        }

        Admin::where('id', $id)->update(['name' => $request->get('name'), 'email' => $request->get('email'), 'password' => Hash::make($request->get('password'))]);

        return redirect(route('usermanagement'))->with('success', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.admin-created-success'));
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
            if(isset($failedRules['email'])){
                return redirect(route('usermanagement.create'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.email-is-wrong'))->withInput()->withErrors($validator, 'error');
            }
            if(isset($failedRules['password']['Min'])){
                return redirect(route('usermanagement.create'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.password-too-short'))->withInput()->withErrors($validator, 'error');
            }
            if(isset($failedRules['password']['Required'])){
                return redirect(route('usermanagement.create'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.password-required'))->withInput()->withErrors($validator, 'error');
            }
        }

        $isUnique = Admin::where('email', $request->get('email'))->count();
        if ($isUnique) {
            return redirect(route('usermanagement.create'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.email-isexists'))->withInput()->withErrors('email is exists', 'error');
        }

        /** @var Admin $admin */
        $admin = Admin::create(['name' => $request->get('name'), 'email' => $request->get('email'), 'password' => Hash::make($request->get('password'))]);
        if (!$admin->exists()) {
            return redirect(route('usermanagement.create'))->with('error', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.admin-cant-created'))->withInput();
        }

        return redirect(route('usermanagement'))->with('success', trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.admin-created-success'));
    }
}
