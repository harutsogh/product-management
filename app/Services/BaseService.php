<?php

namespace App\Services;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\ValidationException;

/**
 * Class BaseService
 * @package App\Services
 */
abstract class BaseService
{
    /**
     * @var
     */
    protected $validator;

    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var Builder
     */
    protected Builder $query;

    /**
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     * @return string
     */
    abstract protected function getValidatorClass(): string;

    /**
     * BaseService constructor.
     */
    public function __construct()
    {
        $this->makeModel();
        $this->makeValidator();
    }

    /**
     * Make model instance
     */
    protected function makeModel()
    {
        $class = $this->getModelClass();
        $this->model = new $class();
    }

    /**
     * Make Validator instance
     */
    protected function makeValidator()
    {
        $class = $this->getValidatorClass();
        $this->validator = new $class();
    }

    /**
     * @param array $params
     * @return Object
     */
    public function paginated(array $params): Object
    {
        $query = $this->model;
        $functions = ['with', 'order', 'where'];
        foreach ($params as $function => $param) {
            if (!in_array($function, $functions)) continue;
            if ($function == 'order') {
                $query = $query->orderBy($param['field'], $param['type']);
            } else {
                $query = $query->$function($param);
            }

        }

        return $query->paginate();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * @return mixed
     */
    public function getTotalsCount()
    {
        return $this->model->count();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $error = false;
        $success = true;
        $response = [];
        try {
            $this->validator
                ->setData($data)
                ->validate('create');
            $response = $this->model->create($data);
        } catch (ValidationException $e) {
            $error = $e->errors();
            $success = false;
        }

        return ['error' => $error, 'data' => $response, 'success' => $success];
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $error = false;
        $success = true;
        $response = [];
        try {
            $this->validator
                ->setData($data)
                ->validate('update');

            $model = $this->model->find($id);
            if (!$model) {
                return ['error' => 'No Item found', 'data' => [], 'success' => false];
            }
            $response = $model->update($data);
        } catch (\ValidationException $e) {
            $error = $e->errors();
            $success = false;
        }

        return ['error' => $error, 'data' => $response, 'success' => $success];
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $error = false;
        $success = true;
        $response = [];
        try {
            $item = $this->model->find($id);

            if (!$item) {
                return ['error' => 'No Item found', 'data' => [], 'success' => false];
            }
            $response = $item->delete();
        } catch (\ValidationException $e) {
            $error = $e->errors();
            $success = false;
        }

        return ['error' => $error, 'data' => $response, 'success' => $success];

    }

    /**
     * @param $id
     * @param null $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getSingle($id, $relations = null): object
    {
        $model = $this->model;
        return ($relations) ? $model->with($relations)->where('id', $id)->get() : $model->find($id);
    }

}
