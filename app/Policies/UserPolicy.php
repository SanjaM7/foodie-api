<?php

namespace App\Policies;

use App\Permissions\AdminPermission;
use App\Permissions\CompanyAdminsPermission;
use App\Permissions\PermissionFactory;
use App\Permissions\UsersPermission;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

/**
 * Class UserPolicy
 * @package App\Policies
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @var AdminPermission|CompanyAdminsPermission|UsersPermission|bool
     */
    private $permission;
    /**
     * @var Request
     */
    private $request;

    /**
     * UserPolicy constructor.
     *
     * @param PermissionFactory $permissionFactory
     * @param Request           $request
     */
    public function __construct(PermissionFactory $permissionFactory, Request $request)
    {
        $this->permission = $permissionFactory->getPermission($request->user('api'));
        $this->request    = $request;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $authUser
     * @param User $user
     *
     * @return mixed
     */
    public function view(User $authUser, User $user)
    {
        return $this->permission->canView($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $authUser
     * @param User $user
     *
     * @return mixed
     */
    public function update(User $authUser, User $user)
    {
        $input = (object)$this->request->input();

//        $input->role       = $this->request->role ?: $user->role;
//        $input->company_id = $this->request->company_id ?: null;

        return $this->permission->canUpdate($user, $input);
    }
}
