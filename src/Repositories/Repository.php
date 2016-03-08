<?php
namespace NGiraud\Repositories\Repositories;

use Doctrine\DBAL\Exception\ReadOnlyException;
use NGiraud\Repositories\RepositoryInterface;
use NGiraud\Repositories\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

abstract class Repository implements RepositoryInterface {

	/**
	 * @var App
	 */
	private $app;

	/**
	 * @var
	 */
	protected $model;

	/**
	 * Repository constructor.
	 *
	 * @param App $app
	 * @throws \NGiraud\Repositories\RepositoryException;
	 */
	public function __construct(App $app) {
		$this->app	= $app;
		$this->makeModel();
	}

	/**
	 * Specify Model class name
	 * @return mixed
	 */
	abstract function model();

	public function query($limit=false, $order=false, $attr=false) {
		$query = $this->model->query();

		if($limit !== false && is_int($limit))
			$query->take($limit);

		if($order !== false && is_array($order)) {
			foreach ( $order as $k => $item ) {
				$query->orderBy($k, $item);
			}
		}

		if($attr !== false && is_array($attr)) {
			foreach ( $attr as $k => $item ) {
				$query->where($k, $item);
			}
		}

		return $query;
	}

	public function get($limit=false, $order=false, $attr=false) {
		return self::query($limit, $order, $attr, $type)->get();
	}

	public function count($attr=false, $type=self::ALL) {
		return self::query(false, false, $attr, $type)->count();
	}

	public function find($id) {
		return $this->model->findOrFail($id);
	}

	public function findOrNew($id) {
		if(!is_numeric($id) || $id <= 0)
			return new News();

		return $this->model->findOrNew($id);
	}

	public function findByAttr($attr) {
		if(!is_array($attr))
			throw new RepositoryException("Invalid attributes for function findOneByAttr in class {$this->model()}");

		$query = self::query(false, false, $attr);
		return $query->firstOrFail();
	}

	public function paginate($nb) {
		return self::query()->simplePaginate($nb);
		//return self::query()->paginate($nb);
	}

	public function create(array $attr) {
		return $this->model->create($attr);
	}

	public function update(array $data, $id, $attribute="id") {
		return $this->model->where($attribute, '=', $id)->update($data);
	}

	public function delete($id) {
		return $this->model->destroy($id);
	}

	/**
	 * @return Model
	 * @throws RepositoryException
	 */
	public function makeModel() {
		$model	= $this->app->make($this->model());

		if(!$model instanceof Model)
			throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

		return $this->model = $model;
	}
}