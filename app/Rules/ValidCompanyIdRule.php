<?php

namespace App\Rules;

use App\Company;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class ValidCompanyId
 * @package App\Rules
 */
class ValidCompanyIdRule implements Rule, ImplicitRule
{
    /**
     * @var
     */
    protected $input;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * ValidCompanyIdRule constructor.
     *
     * @param UserRepository    $userRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(UserRepository $userRepository, CompanyRepository $companyRepository)
    {
        $this->userRepository    = $userRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param $input
     *
     * @return ValidCompanyIdRule
     */
    public function setRequest($input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (request()->user()->role == User::ROLE_ADMIN) {

            switch ($this->input['role']) {
                case User::ROLE_ADMIN:
                case User::ROLE_USER:
                    return !($value != null);
                case User::ROLE_PRODUCER_USER:
                case User::ROLE_CUSTOMER_USER:
                    return $this->validCompanyIdForCompanyUsers();
                case User::ROLE_PRODUCER_ADMIN:
                case User::ROLE_CUSTOMER_ADMIN:
                    return $this->validCompanyIdForCompanyAdmins($this->input['role']);
                default:
                    return true;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    private function validCompanyIdForCompanyUsers()
    {
        $company = $this->findCompany();

        return !($company->type != $this->companyType($this->input['role']));
    }

    /**
     * @param $role
     *
     * @return bool
     */
    private function validCompanyIdForCompanyAdmins($role)
    {
        $company      = $this->findCompany();
        $companyAdmin = $this->userRepository->findWhere([
            'company_id' => $this->input['company_id'],
            'role'       => $role,
            ['id', '!=', $this->input['id']]
        ]);

        return !($company->type != $this->companyType($role) || count($companyAdmin));
    }

    /**
     * @return mixed
     */
    private function findCompany()
    {
        if ($this->input['company_id'] != null) {
            return $this->companyRepository->find($this->input['company_id']);
        }
    }

    /**
     * @param $role
     *
     * @return string
     */
    private function companyType($role)
    {
        if ($role == User::ROLE_PRODUCER_ADMIN || $role == User::ROLE_PRODUCER_USER) {
            return Company::TYPE_PRODUCER;
        }
        if ($role == User::ROLE_CUSTOMER_ADMIN || $role == User::ROLE_CUSTOMER_USER) {
            return Company::TYPE_CUSTOMER;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid company id.';
    }
}
