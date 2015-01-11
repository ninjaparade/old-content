<?php namespace Ninjaparade\Content\Validator;

interface PostValidatorInterface {

	/**
	 * Updating a {{lower_model}} scenario.
	 *
	 * @return void
	 */
	public function onUpdate();

}
