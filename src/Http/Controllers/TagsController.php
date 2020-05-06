<?php

namespace Umomega\Tags\Http\Controllers;

use Umomega\Foundation\Http\Controllers\Controller;
use Umomega\Tags\Http\Requests\StoreTag;
use Umomega\Tags\Http\Requests\UpdateTag;
use Umomega\Tags\Http\Requests\TranslateTag;
use Umomega\Tags\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
	
	/**
	 * Returns a list of tags
	 *
	 * @param Request $request
	 * @return json
	 */
	public function index(Request $request)
	{
		return Tag::orderBy($request->get('s', 'name') . '->' . app()->getLocale(), $request->get('d', 'asc'))->paginate(30);
	}

	/**
	 * Returns a list of tags filtered by search
	 *
	 * @param Request $request
	 * @return json
	 */
	public function search(Request $request)
	{
		return ['data' => Tag::containing($request->get('q'))->get()];
	}

	/**
	 * Stores the new tag
	 *
	 * @param StoreTag $request
	 * @return json
	 */
	public function store(StoreTag $request)
	{
		$tag = Tag::findOrCreate($request->get('name'), $request->get('type'));

		activity()->on($tag)->log('TagStored');

		return [
			'message' => __('tags::tags.created'),
			'payload' => $tag
		];
	}

	/**
	 * Retrieves the tag information
	 *
	 * @param Tag $tag
	 * @return json
	 */
	public function show(Tag $tag)
	{
		return $tag;
	}

	/**
	 * Updates the tag
	 *
	 * @param UpdateTag $request
	 * @param Tag $tag
	 * @return json
	 */
	public function update(UpdateTag $request, Tag $tag)
	{
		$tag->update($request->validated());

		activity()->on($tag)->log('TagUpdated');

		return [
			'message' => __('tags::tags.edited'),
			'payload' => $tag
		];
	}

	/**
	 * Translates the tag
	 *
	 * @param TranslateTag $request
	 * @param Tag $tag
	 * @return json
	 */
	public function translate(TranslateTag $request, Tag $tag)
	{
		$tag->setTranslation('name', $request->get('locale'), $request->get('name_translation'));
		$tag->save();

		activity()->on($tag)->log('TagTranslated');

		return [
			'message' => __('tags::tags.translated'),
			'payload' => $tag,
			'action' => ['locale', $request->get('locale')] 
		];
	}

	/**
	 * Deletes a tag translation
	 *
	 * @param Tag $tag
	 * @param string $locale
	 * @return json
	 */
	public function destroyTranslation(Tag $tag, $locale)
	{
		$name = $tag->getTranslation('name', $locale);
		$tag->forgetTranslation('name', $locale);
		$tag->forgetTranslation('slug', $locale);
		$tag->save();

		activity()->withProperties(compact('name'))->log('TagTranslationDestroyed');

		return [
			'message' => __('foundation::general.deleted_translation'),
			'action' => 'fallback'
		];
	}

	/**
	 * Bulk deletes tags
	 *
	 * @param Request $request
	 * @return json
	 */
	public function destroyBulk(Request $request)
	{
		$items = $this->validate($request, ['items' => 'required|array'])['items'];
		
		$names = Tag::whereIn('id', $items)->pluck('name')->toArray();
		
		Tag::whereIn('id', $items)->delete();

		activity()->withProperties(compact('names'))->log('TagsDestroyedBulk');

		return ['message' => __('tags::tags.deleted_multiple')];
	}

	/**
	 * Deletes a tags
	 *
	 * @param Tag $tag
	 * @return json
	 */
	public function destroy(Tag $tag)
	{
		$name = $tag->name;

		$tag->delete();

		activity()->withProperties(compact('name'))->log('TagDestroyed');

		return [
			'message' => __('tags::tags.deleted'),
			'action' => 'index'
		];
	}

}