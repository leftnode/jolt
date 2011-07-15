<?php namespace jolt;
declare(encoding='UTF-8');

use \jolt\form_controller;

require_once('jolt/form_controller.php');
require_once('jolt/form/loader.php');
require_once('jolt/form/writer.php');

class form extends form_controller {

	private $exception = null;
	private $loader = null;
	private $validator = null;
	private $writer = null;

	public function __construct() {
		
	}

	public function __destruct() {
		$this->data = array();
	}

	public function attach_exception(\Exception $exception) {
		$this->exception = $exception;
		return $this;
	}

	public function attach_loader(\jolt\form\loader $loader) {
		$this->loader = $loader;
		return $this;
	}
	
	public function attach_writer(\jolt\form\writer $writer) {
		$this->writer = $writer;
		return $this;
	}

	public function attach_validator(\jolt\form\validator $validator) {
		$this->validator = $validator;
		return $this;
	}
	
	public function render_messages() {
		$message = $this->get_message();
		$message_html = null;
		if (!empty($message)) {
			$message_html_template = '<div class="messages"><ul class="error"><li>%s</li></ul></div>';
			$message_html = sprintf($message_html_template, $this->get_message());
		}
		
		return $message_html;
	}

	public function load() {
		if (is_null($this->loader)) {
			return false;
		}

		$this->loader->set_id($this->get_id())
			->set_name($this->get_name());

		$loaded = $this->loader->load();
		if (!$loaded) {
			return false;
		}

		$this->set_data($this->loader->get_data())
			->set_errors($this->loader->get_errors())
			->set_message($this->loader->get_message());

		return true;
	}

	public function write() {
		if (is_null($this->writer)) {
			return false;
		}

		$this->writer->set_id($this->get_id())
			->set_name($this->get_name())
			->set_data($this->get_data())
			->set_errors($this->get_errors())
			->set_message($this->get_message());

		$written = $this->writer->write();
		return $written;
	}

	public function validate() {
		if (is_null($this->validator)) {
			return false;
		}

		if ($this->validator->is_empty()) {
			return true;
		}

		$name = $this->get_name();
		$data = $this->get_data();

		$rule_set = $this->validator->get_rule_set();
		foreach ($rule_set as $field => $set) {
			$value = null;
			if (array_key_exists($field, $data)) {
				$value = $data[$field];
			}

			if (!$set->is_valid($value)) {
				$this->add_error($field, $set->get_error());
			}
		}

		if ($this->get_errors_count() > 0 ) {
			$error = $this->validator->get_error();
			if (empty($error)) {
				$error = "The form {$name} failed to validate.";
			}

			$exception = $this->exception;
			if (!is_null($exception)) {
				throw new $exception($error, $this);
			} else {
				throw new \jolt\exception($error);
			}
		}

		return true;
	}
	
	public function set_response($response) {
		if (is_object($response)) {
			if ($response->has_errors()) {
				$this->set_errors($response->errors);
			}
			$this->set_message($response->message)
				->set_data($response->content);
		}
		
		return $this;
	}

	public function get_exception() {
		return $this->exception;
	}

}