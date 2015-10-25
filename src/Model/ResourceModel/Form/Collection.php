<?php
/**
 * Collection.php
 *
 * PHP Version 5
 *
 * @category addictedtomagento_dynamic-forms
 * @package  addictedtomagento_dynamic-forms
 * @author   David Verholen <david@verholen.com>
 * @license  http://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link     http://github.com/davidverholen
 */

namespace AddictedToMagento\DynamicForms\Model\ResourceModel\Form;

use AddictedToMagento\DynamicForms\Model\Form;
use AddictedToMagento\DynamicForms\Model\ResourceModel\AbstractCollection;
use AddictedToMagento\DynamicForms\Model\ResourceModel\Form as FormResource;

/**
 * Class Collection
 *
 * @category addictedtomagento_dynamic-forms
 * @package  AddictedToMagento\DynamicForms\Model\ResourceModel\Form
 * @author   David Verholen <david@verholen.com>
 * @license  http://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link     http://github.com/davidverholen
 */
class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Form::class, FormResource::class);
        $this->_map['fields']['form_id'] = 'main_table.form_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->performAfterLoad('cms_page_store', 'page_id');
        $this->_previewFlag = false;

        return parent::_afterLoad();
    }
}
