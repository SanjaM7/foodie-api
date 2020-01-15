<?php

namespace App\Policies;

use App\Permissions\AdminPermission;
use App\Permissions\CompanyAdminsPermission;
use App\Permissions\PermissionFactory;
use App\Permissions\UsersPermission;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
     * UserPolicy constructor.
     *
     * @param PermissionFactory $permissionFactory
     */
    public function __construct(PermissionFactory $permissionFactory)
    {
        $this->permission = $permissionFactory->getPermission();
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
        return $this->permission->canUpdate($user, request()->input());
    }
}
