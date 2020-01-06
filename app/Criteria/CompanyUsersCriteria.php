<?php

namespace App\Criteria;

use App\Company;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CompanyUsersCriteria.
 *
 * @package namespace App\Criteria;
 */
class CompanyUsersCriteria implements CriteriaInterface
{
    /**
     * @var Company
     */
    protected $company;

    /**
     * CompanyUsersCriteria constructor
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('company_id', '=', $this->company->id);
    }
}
