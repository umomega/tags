<?php

namespace Umomega\Tags;

use Spatie\Tags\Tag as BaseTag;
use Illuminate\Database\Eloquent\Model;

class Tag extends BaseTag {

    /**
     * Override from trait
     */
    public static function bootHasSlug()
    {
        static::saving(function (Model $model) {
            collect($model->getTranslatedLocales('name'))
                ->each(function (string $locale) use ($model) {
                    if(empty($model->getTranslation('slug', $locale, false))) {
                        $model->setTranslation('slug', $locale, $model->generateSlug($locale));
                    } 
                });
        });
    }

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