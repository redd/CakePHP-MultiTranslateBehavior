<?php
App::uses('TranslateBehavior', 'Model/Behavior');

class MultiTranslateBehavior extends TranslateBehavior {

/**
 * afterFind Callback
 * Converts structure of translated content by TranslateBehavior
 * to help saving with Model::saveAssociated() and creating
 * multi language forms with Model.fieldName.locale
 *
 * @param Model $Model Model find was run on
 * @param array $results Array of model results.
 * @param boolean $primary Did the find originate on $model.
 * @return array Modified results
 * @link http://rafal-filipek.blogspot.com/2009/01/translatebehavior-i-formularze-w.html
 */
	public function afterFind(Model $Model, $results, $primary = false) {
		parent::afterFind($Model, &$results, $primary);
		if ($Model->Behaviors->loaded('Translate')) {
			foreach ($Model->Behaviors->Translate->settings[$Model->alias] as $value) {
				foreach ($results as $index => $row) {
					if (is_array($row) && array_key_exists($value, $row)) {
						foreach($row[$value] as $locale) {
							if (isset($results[$index][$Model->alias][$locale['field']])) {
								if (!is_array($results[$index][$Model->alias][$locale['field']])) {
									$results[$index][$Model->alias][$locale['field']] = array();
								}
								$results[$index][$Model->alias][$locale['field']][$locale['locale']] = $locale['content'];
							}
						}
					}
				}
			}
		}
		return $results;
	}
}
