<?php

namespace App\Http\Controllers;

use App\Services\BaseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as CoreController;

/**
 * Class BaseController
 * @package App\Http\Controllers
 */
class BaseController extends CoreController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var BaseService
     */
    protected BaseService $baseService;

    /**
     * @param Request $request
     * @return array
     */
    public function paginated(Request $request) : array
    {
        return $this->makeResponse($this->baseService->paginated($request->all()));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        return $this->makeResponse($this->baseService->create($request->all()));
    }

    /**
     * @param $id
     * @param Request $request
     * @return array
     */
    public function update($id, Request $request)
    {
        return $this->makeResponse($this->baseService->update($id, $request->all()));
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        return $this->makeResponse($this->baseService->delete($id));
    }

    /**
     * @param $id
     * @return array
     */
    public function getSingle($id)
    {
        return $this->makeResponse($this->baseService->getSingle($id));
    }

    /**
     * @param $data
     * @param string $message
     * @return array
     */
    public function makeResponse($data, $message = ''): array
    {
        return [
            'success' => $data['success'] ?? (bool)$data,
            'data' => $data['data'] ?? $data ,
            'error' => $data['error'] ?? $message
        ];
    }
}
