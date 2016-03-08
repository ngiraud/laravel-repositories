<?php
namespace NGiraud\Repositories\Interfaces;

interface RepositoryInterface {

	public function query($limit=false, $order=false, $attr=false);

	public function get($limit=false, $order=false, $attr=false);

	public function count($attr=false);

	public function find($id);

	public function findOrNew($id);

	public function findByAttr($attr);

	public function paginate($nb);

	public function create(array $attr);

	public function update($id, $data);

	public function delete($id);
}