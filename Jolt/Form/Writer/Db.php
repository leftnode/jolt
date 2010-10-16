<?php

declare(encoding='UTF-8');
namespace Jolt\Form\Writer;
use \Jolt\Form\Writer;

class Db extends Writer {

	private $pdo = NULL;
	private $table = 'form';

	public function attachPdo(\PDO $pdo) {
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$this->pdo = $pdo;

		return $this;
	}

	public function write() {
		$pdo = $this->getPdo();
		if ( is_null($pdo) ) {
			return false;
		}

		$data = $this->getData();
		if ( empty($data) || 0 === count($data) ) {
			return false;
		}

		$id = $this->getId();
		if ( empty($id) ) {
			return false;
		}

		$created = date('Y-m-d H:i:s', time());
		$dataKey = $this->getDataKey();
		$name = $this->getName();
		$table = $this->getTable();

		$dataJson = json_encode($data);

		$sql = "INSERT INTO {$table} (created, id, name, datakey, data, status) VALUES(:created, :id, :name, :datakey, :data, :status)";

		$pdo->beginTransaction();
			$stmt = $pdo->prepare($sql);

			if ( !$stmt ) {
				$pdo->rollback();
				return false;
			}

			$parameters = array(
				'created' => $created,
				'id' => $id,
				'name' => $name,
				'datakey' => $dataKey,
				'data' => $dataJson,
				'status' => 1
			);

			$executed = $stmt->execute($parameters);
		$pdo->commit();

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