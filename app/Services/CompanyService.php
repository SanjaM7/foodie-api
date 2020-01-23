<?php

namespace App\Services;

use App\Criteria\ProducerCompaniesCriteria;
use App\Http\Requests\CompanyStoreRequest;
use App\Repositories\CompanyRepository;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CompanyService
 * @package App\Services
 */
class CompanyService
{
    /**
     * @var CompanyRepository
     */
    protected $repository;

    /**
     * UserService constructor.
     *
     * @param CompanyRepository $repository
     */
    public function __construct(CompanyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $limit
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function getPaginated($limit = null)
    {
        $this->repository->pushCriteria(new ProducerCompaniesCriteria());
        return $this->repository->paginate($limit);
    }

    /**
     * @param CompanyStoreRequest $request
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function store(CompanyStoreRequest $request)
    {
        return $this->repository->create($request->all());
    }

    /**
     * @param $request
     * @param $companyId
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function update($request, $companyId)
    {
        return $this->repository->update(
            $request->all(), $companyId);
    }

    /**
     * @param $companyId
     *
     * @return int
     */
    public function softDelete($companyId)
    {
        return $this->repository->delete($companyId);
    }
}
