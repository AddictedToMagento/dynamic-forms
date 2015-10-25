<?php
/**
 * Form.php
 *
 * PHP Version 5
 *
 * @category addictedtomagento_dynamic-forms
 * @package  addictedtomagento_dynamic-forms
 * @author   David Verholen <david@verholen.com>
 * @license  http://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link     http://github.com/davidverholen
 */

namespace AddictedToMagento\DynamicForms\Model\ResourceModel;

use AddictedToMagento\DynamicForms\Api\Data\FormInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Form
 *
 * @category addictedtomagento_dynamic-forms
 * @package  AddictedToMagento\DynamicForms\Model\Resource
 * @author   David Verholen <david@verholen.com>
 * @license  http://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link     http://github.com/davidverholen
 */
class Form extends AbstractDb
{
    const MAIN_TABLE_NAME = 'atm_dynamic_form';
    const STORE_TABLE_NAME = 'atm_dynamic_form_store';

    const ID_FIELD_NAME = 'form_id';

    /**
     * Store model
     *
     * @var null|\Magento\Store\Model\Store
     */
    protected $_store = null;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_date = $date;
        $this->_storeManager = $storeManager;
        $this->dateTime = $dateTime;
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE_NAME, self::ID_FIELD_NAME);
    }

    /**
     * Process page data before deleting
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['form_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($this->getTable(self::STORE_TABLE_NAME), $condition);

        return parent::_beforeDelete($object);
    }

    /**
     * Process form data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        /** @var \AddictedToMagento\DynamicForms\Model\Form $object */
        if (!$this->isValidFormIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The Form Identifier contains capital letters or disallowed symbols.')
            );
        }

        if ($object->isObjectNew() && !$object->hasData(FormInterface::CREATION_TIME)) {
            $object->setCreationTime($this->_date->gmtDate());
        }

        $object->setUpdateTime($this->_date->gmtDate());

        return parent::_beforeSave($object);
    }

    /**
     * Assign page to store views
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        /** @var \AddictedToMagento\DynamicForms\Model\Form $object */
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table = $this->getTable(self::STORE_TABLE_NAME);
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = ['form_id = ?' => (int)$object->getId(), 'store_id IN (?)' => $delete];
            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = ['form_id' => (int)$object->getId(), 'store_id' => (int)$storeId];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Load an object using 'identifier' field if there's no field specified and value is not numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'identifier';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Check if page identifier exist for specific store
     * return page id if page exists
     *
     * @param string $identifier
     * @param int $storeId
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        $stores = [\Magento\Store\Model\Store::DEFAULT_STORE_ID, $storeId];
        $select = $this->_getLoadByIdentifierSelect($identifier, $stores, 1);
        $select->reset(\Magento\Framework\DB\Select::COLUMNS)
            ->columns('df.form_id')
            ->order('dfs DESC')
            ->limit(1);

        return $this->getConnection()->fetchOne($select);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \AddictedToMagento\DynamicForms\Model\Form $object
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = [\Magento\Store\Model\Store::DEFAULT_STORE_ID, (int)$object->getStoreId()];
            $select->join(
                ['dfs' => $this->getTable(self::STORE_TABLE_NAME)],
                $this->getMainTable() . '.form_id = dfs.form_id',
                []
            )->where(
                'is_active = ?',
                1
            )->where(
                'dfs.store_id IN (?)',
                $storeIds
            )->order(
               'dfs.store_id DESC'
            )->limit(
                1
            );
        }

        return $select;
    }

    /**
     * Retrieve load select with filter by identifier, store and activity
     *
     * @param string $identifier
     * @param int|array $store
     * @param int $isActive
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadByIdentifierSelect($identifier, $store, $isActive = null)
    {
        $select = $this->getConnection()->select()->from(
            ['df' => $this->getMainTable()]
        )->join(
            ['dfs' => $this->getTable(self::STORE_TABLE_NAME)],
            'df.form_id = dfs.form_id',
            []
        )->where(
            'df.identifier = ?',
            $identifier
        )->where(
            'dfs.store_id IN (?)',
            $store
        );

        if (!is_null($isActive)) {
            $select->where('df.is_active = ?', $isActive);
        }

        return $select;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $formId
     * @return array
     */
    public function lookupStoreIds($formId)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable(self::STORE_TABLE_NAME),
            'store_id'
        )->where(
            'form_id = ?',
            (int)$formId
        );

        return $connection->fetchCol($select);
    }

    /**
     *  Check whether form identifier is valid
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isValidFormIdentifier(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }

    /**
     * getStoreTable
     *
     * @return string
     */
    protected function getStoreTable()
    {
        return $this->getTable(self::STORE_TABLE_NAME);
    }
}
