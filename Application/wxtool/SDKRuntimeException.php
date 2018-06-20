<?php
namespace wxtool;

class  SDKRuntimeException extends \Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}
}
?>