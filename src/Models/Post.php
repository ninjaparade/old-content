<?php namespace Ninjaparade\Content\Models;

use Platform\Attributes\Models\Entity;

class Post extends Entity {

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'posts';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
		'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
		'values.attribute',
	];

	protected $touches = ['post', 'tags', 'author'];

	/**
	 * {@inheritDoc}
	 */
	protected $eavNamespace = 'ninjaparade/content.post';

    public function getGroupsAttribute($groups)
    {
        if ( ! $groups)
        {
            return [];
        }

        if (is_array($groups))
        {
            return $groups;
        }

        if ( ! $_groups = json_decode($groups, true))
        {
            throw new InvalidArgumentException("Cannot JSON decode groups [{$groups}].");
        }

        return $_groups;
    }

    public function setGroupsAttribute($groups)
    {
        // If we get a string, let's just ensure it's a proper JSON string
        if ( ! is_array($groups))
        {
            $groups = $this->getGroupsAttribute($groups);
        }

        if ( ! empty($groups))
        {
            $groups = array_values(array_map('intval', $groups));
            $this->attributes['groups'] = json_encode($groups);
        }
        else
        {
            $this->attributes['groups'] = '';
        }
    }

    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = rtrim($value, ',');
    }

	public function author()
    {
        return $this->belongsTo('Ninjaparade\Content\Models\Author');
    }


    public function post()
    {
        return $this->belongsTo('Ninjaparade\Content\Models\Post');
    }


    public function tags()
    {
      return $this->belongsToMany('Ninjaparade\Content\Models\Tag');
    }


    public function categories()
    {
      return $this->belongsToMany('Ninjaparade\Content\Models\Category');
    }

    public function image()
    {
        return $this->belongsTo('Platform\Media\Models\Media', 'image', 'id');
    }

    public function cover_image()
    {
        return $this->belongsTo('Platform\Media\Models\Media', 'cover_image', 'id');
    }

}
