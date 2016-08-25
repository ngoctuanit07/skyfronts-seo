<?php

namespace Skyfronts\Seo\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Skyfronts\Seo\Helper\Data as HelperData;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Checkout\Model\Session;
use Magento\Theme\Block\Html\Header\Logo;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Review\Model\ResourceModel\Review\Collection as ReviewCollection;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory;
use Magento\Review\Model\ReviewFactory;
class Abstractt extends Template
{
    protected $storeManager;
    protected $objectManager;
    protected $helperData;
    protected $objectFactory;
    protected $checkoutSession;
    protected $logo;
    protected $registry;
    protected $urlManager;
    protected $reviewCollection;
    protected $reviewCollectionFactory;
    protected $reviewFactory;
    protected $reviewRederer;
    public function __construct(
        Context $context,
        HelperData $helperData,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        Session $session,
        Registry $registry,
        Logo $logo,
        UrlInterface $urlManager,
        CollectionFactory $reviewCollectionFactory,
        ReviewFactory $reviewFactory,
        array $data = []
    )
    {
        $this->helperData = $helperData;
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        $this->checkoutSession = $session;
        $this->registry = $registry;
        $this->logo = $logo;
        $this->urlManager = $urlManager;
        $this->reviewCollectionFactory = $reviewCollectionFactory;
        $this->reviewFactory=$reviewFactory;
        parent::__construct($context, $data);
    }

    public function getHelper()
    {
        return $this->helperData;
    }

    public function getBusinessName()
    {
        return $this->helperData->getConfigValue('general/store_information/name');
    }

    public function getBusinessPhone()
    {
        return $this->helperData->getConfigValue('general/store_information/phone');
    }

    public function getBaseUrl()
    {
//        return $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
//            ->getStore()
//            ->getBaseUrl();
        return $this->urlManager->getBaseUrl();
    }

    public function getTwitterAccount()
    {
        $prefix = '@';
        $account = $this->helperData->getGeneralConfig('twitter_account');
        return $prefix . $account;
    }

    public function getLangCode()
    {
        /** @var \Magento\Framework\ObjectManagerInterface $om */
        $om = $this->objectManager;
        /** @var \Magento\Framework\Locale\Resolver $resolver */
        $resolver = $om->get('Magento\Framework\Locale\Resolver');
        $resolver = strtolower($resolver);
        return $resolver;
    }

    public function getCoreObject($helper)
    {
        return $this->objectManager->create($helper);
    }
}