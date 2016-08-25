<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Skyfronts\Seo\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetId = $eavSetup->getAttributeSetId($entityTypeId, 'Default');

        // Custom Category H1 Attribute

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'seo_heading', array(
            'group'         => 'General Information',
            'input'         => 'text',
            'type'          => 'varchar',
            'label'         => 'Category Heading',
            'visible'       => true,
            'required'      => false,
            'user_defined' => true,
            'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE
        ));

        // Custom Category Meta Robots

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'seo_metarobots', array(
            'group'         => 'General Information',
            'input'         => 'select',
            'type'          => 'varchar',
            'label'         => 'Meta Robots',
            'source'        => 'Skyfronts\Seo\Model\Source\Robots',
            'visible'       => true,
            'required'      => false,
            'user_defined' => true,
            'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE
        ));

        // Disabled Product Redirect Options

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'seo_discontinued',
            [
                'type' => 'int',
                'backend' => '',
                'label' => 'Discontinued',
                'input' => 'select',
                'class' => 'seo_discontinued',
                'source' => 'Skyfronts\Seo\Model\Source\Discontinued',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => true,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'unique' => false,
                'used_in_product_listing' => false
            ]
        );

        $attribute = $eavSetup->getAttribute($entityTypeId, 'seo_discontinued');

        $eavSetup->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            'Search Engine Optimization',
            $attribute['attribute_id'],
            110
        );

        // If disabled product is redirected to product, this is the SKU

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'seo_discontinued_product',
            [
                'type' => 'varchar',
                'backend' => '',
                'label' => 'Redirect to Product SKU',
                'input' => 'text',
                'class' => 'seo_discontinued',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'unique' => false,
                'used_in_product_listing' => false
            ]
        );

        $attribute = $eavSetup->getAttribute($entityTypeId, 'seo_discontinued_product');

        $eavSetup->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            'Search Engine Optimization',
            $attribute['attribute_id'],
            120
        );

        // Custom Product Meta Robots

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'seo_metarobots',
            [
                'type' => 'varchar',
                'backend' => '',
                'label' => 'Meta Robots',
                'input' => 'select',
                'class' => 'seo_metarobots',
                'source' => 'Skyfronts\Seo\Model\Source\Robots',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'unique' => false,
                'used_in_product_listing' => false
            ]
        );

        $attribute = $eavSetup->getAttribute($entityTypeId, 'seo_metarobots');

        $eavSetup->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            'Search Engine Optimization',
            $attribute['attribute_id'],
            130
        );
    }

}
