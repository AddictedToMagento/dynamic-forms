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

namespace AddictedToMagento\DynamicForms\Model;

use AddictedToMagento\DynamicForms\Api\Data\FormInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Form
 *
 * @category addictedtomagento_dynamic-forms
 * @package  AddictedToMagento\DynamicForms\Model
 * @author   David Verholen <david@verholen.com>
 * @license  http://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link     http://github.com/davidverholen
 *
 * @method \AddictedToMagento\DynamicForms\Model\ResourceModel\Form _getResource()
 * @method \AddictedToMagento\DynamicForms\Model\ResourceModel\Form getResource()
 *
 * @method int getStoreId()
 */
class Form extends AbstractModel implements FormInterface, IdentityInterface
{
    const CACHE_TAG = 'dynamic_form';

    /**#@+
     * Form's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dynamic_form';

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\AddictedToMagento\DynamicForms\Model\ResourceModel\Form::class);
    }

    /**
     * Prepare form's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_ENABLED  => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled')
        ];
    }

    /**
     * Receive form store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores')
            ? $this->getData('stores')
            : $this->getData('store_id');
    }

    /**
     * Check if page identifier exist for specific store
     * return page id if page exists
     *
     * @param string $identifier
     * @param int    $storeId
     *
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * getIdentifier
     *
     * @return string|null
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * getTitle
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * getCssClasses
     *
     * @return string[]|null
     */
    public function getCssClasses()
    {
        return $this->getData(self::CSS_CLASSES);
    }

    /**
     * getCreationTime
     *
     * @return string|null
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * getUpdateTime
     *
     * @return string|null
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * getIsActive
     *
     * @return bool|null
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * setIdentifier
     *
     * @param string $identifier
     *
     * @return FormInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * setTitle
     *
     * @param string $title
     *
     * @return FormInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * setCssClasses
     *
     * @param array $cssClasses
     *
     * @return FormInterface
     */
    public function setCssClasses(array $cssClasses)
    {
        return $this->setData(self::CSS_CLASSES, $cssClasses);
    }

    /**
     * setCreationTime
     *
     * @param string $creationTime
     *
     * @return FormInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * setUpdateTime
     *
     * @param string $updateTime
     *
     * @return FormInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * setIsActive
     *
     * @param bool $isActive
     *
     * @return FormInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }
}
