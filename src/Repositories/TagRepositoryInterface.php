<?php namespace Ninjaparade\Content\Repositories;

interface TagRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Ninjaparade\Content\Models\Tag
	 */
	public function grid();

	/**
	 * Returns all the content entries.
	 *
	 * @return \Ninjaparade\Content\Models\Tag
	 */
	public function findAll();

	/**
	 * Returns a content entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Ninjaparade\Content\Models\Tag
	 */
	public function find($id);

	/**
	 * Determines if the given content is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given content is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates a content entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Ninjaparade\Content\Models\Tag
	 */
	public function create(array $data);

	/**
	 * Updates the content entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Ninjaparade\Content\Models\Tag
	 */
	public function update($id, array $data);

	/**
	 * Deletes the content entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
