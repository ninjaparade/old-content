<?php namespace Ninjaparade\Content\Validator;

interface PosttypeValidatorInterface {

	/**
	 * Updating a {{lower_model}} scenario.
	 *
	 * @return void
	 */
	public function onUpdate();

}
