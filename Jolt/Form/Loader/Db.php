<?php

declare(encoding='UTF-8');
namespace Jolt\Form\Loader;
use \Jolt\Form\Loader;

class Db extends Loader {

	private $pdo = NULL;
	private $table = 'form';

	public function attachPdo(\PDO $pdo) {
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$this->pdo = $pdo;

		return $this;
	}

	public function load() {
		$pdo = $this->getPdo();
		if ( is_null($pdo) ) {
			return false;
		}

		$id = $this->getId();
		if ( empty($id) ) {
			return false;
		}

		$name = $this->getName();
		if ( empty($name) ) {
			return false;
		}

		$table = $this->getTable();

		$sql = "SELECT * FROM {$table} WHERE id = :id AND name = :name AND status = 1";
		$stmt = $pdo->prepare($sql);

		if ( !$stmt ) {
			return false;
		}

		$parameters = array(
			'id' => $id,
			'name' => $name
		);

		$executed = $stmt->execute($parameters);

		if ( $executed ) {
			$formData = $stmt->fetchObject();
			if ( !$formData ) {
				return false;
			}

			$formId = $formData->form_id;

			$sql = "UPDATE {$table} SET status = 0 WHERE form_id = :form_id";
			$stmt = $pdo->prepare($sql);

			if ( false !== $stmt ) {
				$executed = $stmt->execute(array('form_id' => $formId));

				$data = json_decode($formData->data, true);
				if ( is_null($data) ) {
					$data = $formData->data;
				}

				if ( !is_array($data) ) {
					$data = array($data);
				}

				$this->setDataKey($formData->datakey);
				$this->setData($data);
			}
		}

		return $executed;
	}

	public function setTable($table) {
		$this->table = trim($table);
		return $this;
	}

	public function getPdo() {
		return $this->pdo;
	}

	public function getTable() {
		return $this->table;
	}

}