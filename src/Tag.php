<?php

namespace Umomega\Tags;

use Spatie\Tags\Tag as BaseTag;

class Tag extends BaseTag {

	/**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['locales'];

    /**
     * Returns the translated locales
     *
     * @return array
     */
    public function getLocalesAttribute()
    {
    	return $this->getTranslatedLocales('name');
    }

}