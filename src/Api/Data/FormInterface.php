<?php
/**
 * FormInterface.php
 *
 * PHP Version 5
 *
 * @category addictedtomagento_dynamic-forms
 * @package  addictedtomagento_dynamic-forms
 * @author   David Verholen <david@verholen.com>
 * @license  http://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link     http://github.com/davidverholen
 */

namespace AddictedToMagento\DynamicForms\Api\Data;

/**
 * Interface FormInterface
 *
 * @category addictedtomagento_dynamic-forms
 * @package  AddictedToMagento\DynamicForms\Api\Data
 * @author   David Verholen <david@verholen.com>
 * @license  http://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link     http://github.com/davidverholen
 */
interface FormInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const FORM_ID = 'form_id';
    const IDENTIFIER = 'identifier';
    const TITLE = 'title';
    const CSS_CLASSES = 'css_classes';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME = 'update_time';
    const IS_ACTIVE = 'is_active';

    /**
     * getId
     *
     * @return int|null
     */
    public function getId();

    /**
     * getIdentifier
     *
     * @return string|null
     */
    public function getIdentifier();

    /**
     * getTitle
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * getCssClasses
     *
     * @return string[]|null
     */
    public function getCssClasses();

    /**
     * getCreationTime
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * getUpdateTime
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * getIsActive
     *
     * @return bool|null
     */
    public function getIsActive();

    /**
     * setId
     *
     * @param int $id
     *
     * @return FormInterface
     */
    public function setId($id);

    /**
     * setIdentifier
     *
     * @param string $identifier
     *
     * @return FormInterface
     */
    public function setIdentifier($identifier);

    /**
     * setTitle
     *
     * @param string $title
     *
     * @return FormInterface
     */
    public function setTitle($title);

    /**
     * setCssClasses
     *
     * @param array $cssClasses
     *
     * @return FormInterface
     */
    public function setCssClasses(array $cssClasses);

    /**
     * setCreationTime
     *
     * @param string $creationTime
     *
     * @return FormInterface
     */
    public function setCreationTime($creationTime);

    /**
     * setUpdateTime
     *
     * @param string $updateTime
     *
     * @return FormInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * setIsActive
     *
     * @param bool $isActive
     *
     * @return FormInterface
     */
    public function setIsActive($isActive);
}
