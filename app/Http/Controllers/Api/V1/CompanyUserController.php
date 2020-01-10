<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Exceptions\AlreadyExistsException;
use App\Exceptions\InvalidUserRoleException;
use App\Http\Requests\CompanyUserStoreRequest;
use App\Services\CompanyService;
use App\Services\CompanyUserService;
use App\Transformers\UserIndexTransformer;
use App\Transformers\UserTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Http\Request;

/**
 * Class CompanyUserController
 * @package App\Http\Controllers\Api\V1
 */
class CompanyUserController extends Controller
{
    /**
     * @var CompanyUserService
     */
    private $companyUserService;

    /**
     * CompanyUserController constructor.
     *
     * @param CompanyUserService $companyUserService
     */
    public function __construct(CompanyUserService $companyUserService)
    {
        $this->companyUserService = $companyUserService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Company $company
     *
     * @return array
     * @throws RepositoryException
     */
    public function index(Request $request, Company $company)
    {
        $companyUsers           = $this->companyUserService->getPaginated($request->limit, $company);
        $companyUsersCollection = $companyUsers->getCollection();

        return fractal()
            ->collection($companyUsersCollection)
            ->transformWith(new UserIndexTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($companyUsers))
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Company                 $company
     *
     * @param CompanyUserStoreRequest $request
     *
     * @param CompanyService          $companyService
     *
     * @return mixed
     * @throws ValidatorException
     * @throws AlreadyExistsException
     * @throws InvalidUserRoleException
     */
    public function store(Company $company, CompanyUserStoreRequest $request, CompanyService $companyService)
    {
        $company = $companyService->get($company->id);
        $user    = $this->companyUserService->store($request, $company);

        return fractal()
            ->item($user)
            ->parseIncludes('company')
            ->transformWith(new UserTransformer())
            ->toArray();
    }
}
