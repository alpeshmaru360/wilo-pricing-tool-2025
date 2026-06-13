<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Models\Auth\User;
use App\Http\Controllers\Controller;
use App\Events\Backend\Auth\User\UserDeleted;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\UserRepository;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Http\Requests\Backend\Auth\User\StoreUserRequest;
use App\Http\Requests\Backend\Auth\User\ManageUserRequest;
use App\Http\Requests\Backend\Auth\User\UpdateUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $r, ManageUserRequest $request)
    {
        $user_types = DB::table('wilo_user_type')->get();
        $user_status = DB::table('users')->select('active')->get();
        // dd($user_status);
        $status_users = array();
        foreach ($user_status as $status) {
            if (!in_array($status->active, $status_users)) {
                $status_users[] = $status->active;
            }
        }
        // $user_list_url = url('admin/auth/user');
        if ($_GET) {
            return view('backend.auth.user.index')
                ->withUsers($this->userRepository->getActivePaginatedfilter(10, 'id', 'asc'))
                ->with('user_types', $user_types)
                ->with('user_status', $status_users);
            // ->with('user_list_url', $user_list_url);
        }

        return view('backend.auth.user.index')
            ->withUsers($this->userRepository->getActivePaginated(10, 'id', 'asc'))
            ->with('user_types', $user_types)
            ->with('user_status', $status_users);
    }

    public function search_result(Request $request)
    {
        // dd($request->type);
        return view('backend.auth.user.search_result')
            ->withUsers($this->userRepository->getActivePaginated(10, 'id', 'asc', $request->status, $request->type));

        // return view('backend.auth.user.search_result');
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $usertype = DB::table('wilo_user_type')->select('id', 'user_type_name')->get();
        // $continent = DB::table('continent')->select('id', 'name')->get();
        $country   = DB::table('country')->select('id', 'name')->get();
        // return view('backend.auth.user.create')->with('continent', $continent)->with('usertype', $usertype)
        return view('backend.auth.user.create')->with('country', $country)->with('usertype', $usertype)
            ->withRoles($roleRepository->with('permissions')->get(['id', 'name']))
            ->withPermissions($permissionRepository->get(['id', 'name']));
    }

    /**
     * @param StoreUserRequest $request
     *
     * @throws \Throwable
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        // dd($request);

        $this->userRepository->create($request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'active',
            'confirmed',
            'confirmation_email',
            'designation',
            'usertype',
            'country',
            // 'roles',
            'permissions'
        ));
        if ($request->usertype == 1) {
            // die("here");
            $admin_user =  User::where('email', $request->email)->first();
            //    dd($admin_user);
            //    $admin_user->role_id = 1;
            //    $admin_user->save();
            DB::table('model_has_roles')->insert(
                ['role_id' => 1, 'model_type' => "App\Models\Auth\User", 'model_id' => $admin_user->id]
            );
        }
        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.created'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @return mixed
     */
    public function show(ManageUserRequest $request, User $user)
    {
        $country   = DB::table('wilo_user_to_country')->where('user_id', $user->id)->get();
        $country_name = DB::table('country')->where('id', $country[0]->country_id)->get();
        // $continentid = DB::table('country')->where('id', $country[0]->country_id)->get();
        // $continentname = DB::table('continent')->where('id', $continentid[0]->continent_id)->get();
        return view('backend.auth.user.show')
            ->with('country_name', $country_name[0]->name)
            // ->with('continentid', $continentid)
            // ->with('continentname', $continentname)
            // ->withUserRoles($user->roles->pluck('name')->all())
            ->withUser($user);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param User                 $user
     *
     * @return mixed
     */
    public function edit(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository, User $user)
    {
        //dd($user->id);
        $usertype = DB::table('wilo_user_type')->select('id', 'user_type_name')->get();
        // $continent = DB::table('continent')->select('id', 'name')->get();
        $country   = DB::table('wilo_user_to_country')->where('user_id', $user->id)->get();
        $country_name = DB::table('country')->where('id', $country[0]->country_id)->get();
        $countries = DB::table('country')->select('id', 'name')->get();
        // dd($country);
        //  dd($country[0]->country_id);
        //Not required continent name
        // $continentname = DB::table('country')->where('id', $country[0]->country_id)->get();
        // dd($continentname);
        // dd($country_name[0]->name);
        return view('backend.auth.user.edit')
            ->with('country', $country_name)
            ->with('countries', $countries)
            //Not required continent name
            // ->with('continent', $continent)
            ->with('usertype', $usertype)
            //Not required continent name
            // ->with('continentname', $continentname)
            ->withUser($user)
            ->withRoles($roleRepository->get())
            ->withUserRoles($user->roles->pluck('name')->all())
            ->withPermissions($permissionRepository->get(['id', 'name']))
            ->withUserPermissions($user->permissions->pluck('name')->all());
    }

    /**
     * @param UpdateUserRequest $request
     * @param User              $user
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     * @return mixed
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // dd($request->roles);
        $this->userRepository->update($user, $request->only(
            'first_name',
            'last_name',
            'email',
            'designation',
            'country',
            'usertype',
            // 'roles',
            'permissions'
        ));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.updated'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @throws \Exception
     * @return mixed
     */
    public function destroy(ManageUserRequest $request, User $user)
    {
        $this->userRepository->deleteById($user->id);

        event(new UserDeleted($user));

        return redirect()->route('admin.auth.user.deleted')->withFlashSuccess(__('alerts.backend.users.deleted'));
    }
}
