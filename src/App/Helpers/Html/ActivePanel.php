<?php
// To do or not to do - that's the question

namespace Revys\RevyAdmin\App\Helpers\Html;

class ActivePanel
{
	private $data;
	private $caption;
	private $buttons;

	public function __construct($preset = null, $data = null)
	{
		if ($data !== null)
			$this->data = $data;

		if ($preset !== null)
			$this->{$preset.'Preset'}($data);
	}

	/*
	 * Edit page preset
	 */
	public function editPreset($object)
	{
		$this->setCaption($object->title);
		$this->addButton('back', false);
	}

	public function data($key)
	{
		return $this->data[$key];
	}

	public function setCaption($text)
	{
		$this->caption = $text;
	}

	public function getCaption()
	{
		return $this->caption;
	}

	public function addButton($name, $action, $caption = null)
	{

	}
}
