<?php

namespace Skyfronts\Seo\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Skyfronts\Seo\Helper\Data as SeoHelper;
use Magento\Framework\Registry;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Context;
use Magento\Framework\App\Request\Http as Url;
use Magento\Store\Model\Group;

class GenerateBlocksAfterObserver implements ObserverInterface
{
    protected $helper;
    protected $registry;
    protected $objectManager;
    protected $urlManager;
    protected $context;
    protected $url;
    protected $storeGroup;
   protected $_config;
   protected $_title;
   protected $_storeManager;
    public function __construct(
        SeoHelper $helper,
        Registry $registry,
        ObjectManagerInterface $objectManager,
        UrlInterface $urlManager,
        Context $context,
        Url $url,
         \Magento\Framework\View\Page\Config $config,
          \Magento\Framework\View\Page\Title $title,
           \Magento\Store\Model\StoreManagerInterface $storeManager,

        Group $storeGroup
    )
    {
        $this->helper = $helper;
         $this->_config = $config;
          $this->_title = $title;
        $this->registry = $registry;

        $this->objectManager = $objectManager;
        $this->urlManager = $urlManager;
        $this->context = $context;
        $this->url = $url;
         $this->_storeManager = $storeManager;
        $this->storeGroup=$storeGroup;
    }

    public function execute(Observer $observer)
    {
        $this->basicSetup($observer);

        return $this;
    }

    public function getBaseUrl()
    {
        return $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
            ->getStore()
            ->getBaseUrl();
    }

    public function basicSetup($observer)
    {

        $layout = $observer->getEvent()->getLayout();
        $action = $observer->getEvent()->getFullActionName();
        //var_dump($action); die();
        /**
        *
        */
        if($action == 'contact_index_index'){
          //die('77');
          $title = 'WPP';
        //  var_dump($title); die();
            $this->_title->set($title);
        }
        /**
         * catalog_category_view
         */
        if ($action == 'catalog_category_view') {
            $category = $this->registry->registry('current_category');
            $pageTitle = $category->getName();
          //  var_dump($pageTitle); die();
            $pageDescription = $category->getDescription();
            $pageKeywords = $category->getMetaKeywords();
            if($this->getGeneralConfig('noindexparams')){

                $this->_config->setRobots('NOINDEX, FOLLOW');
              //  var_dump($this->_config->getMetaDescription()); die();
              $this->_config->setDescription(strip_tags($pageDescription));
            }

            if($this->getMetaConfig('category_title_enabled')){
              //  die('81');
              //$this->_title->set($pageTitle);
                //var_dump($this->getMetaConfig('category_title')); die();
              if(trim($this->getMetaConfig('category_title')) == '[name] - [store]'){
                //die('120');
                $title = $pageTitle.'-'.$this->getStoreName();
                //var_dump($this->getStoreName()); die();
              //  var_dump($this->getStoreName('name')); die();
                  $this->_title->set($title);
              }else{
                $this->_title->set($pageTitle);
              }


            }

          //  $pageRobots = $category->getMetaRobots();
            $url = $category->getUrl();

        }

        /**
        * search result page
        */

        if($action == 'catalogsearch_result_index'){
          if($this->getGeneralConfig('noindexparamssearch')){
              $this->_config->setRobots('NOINDEX, FOLLOW');
          }
        }

        /**
         * catalog_product_view
         */
        if ($action == 'catalog_product_view') {
            $product = $this->registry->registry('current_product');
            $pageTitle = $product->getName();
            if($this->getMetaConfig('product_title_enabled')){
              //var_dump($this->getMetaConfig('product_title')); die();
            //  [name]
              if(trim($this->getMetaConfig('product_title')) === '[name]-[store]'){
                //die('120');
                $title = $pageTitle.'-'.$this->getStoreName();
                //var_dump($this->getStoreName()); die();
              //  var_dump($this->getStoreName('name')); die();
                  $this->_title->set($title);
              }else{
                $this->_title->set($pageTitle);
              }

            }
            /**
             * Auto set page title, meta description
             */
            if (empty($product->getMetaDescription())) {

                $pageDescription = trim(strip_tags($product->getShortDescription()));
                  $this->_config->setDescription(strip_tags($pageDescription));
            } else {
              die('167');
                $pageDescription = trim(strip_tags($product->getMetaDescription()));
                  $this->_config->setDescription(strip_tags($pageDescription));
            }
            $pageKeywords = $product->getMetaKeywords();
            $this->_config->setRobots('NOINDEX, FOLLOW');
          //  $pageRobots = $product->getMetaRobots();
            $url = $product->getUrl();
        }
        if ($action == 'cms_index_index' OR $action == 'cms_page_view') {
            $page = $this->objectManager->get('Magento\Cms\Model\Page');
            $pageTitle = $page->getTitle();
            $pageDescription = $page->getMetaDescription();
            $pageKeywords = $page->getMetaKeywords();
            //$pageRobots = $page->getMetaRobots();
            $this->_config->setRobots('NOINDEX, FOLLOW');
            if ($action == 'cms_index_index') {
                $url = $this->urlManager->getBaseUrl();
            } else {
                $url = $this->urlManager->getUrl($page->getIdentifier());
            }
        }

    }

    public function getLangCode()
    {
        $code = $this->storeGroup->getDefaultStore()->getLocaleCode();
        $code = strtolower($code);

        return $code;
    }

    protected function getGeneralConfig($code)
    {
        return $this->helper->getGeneralConfig($code);
    }
    protected function getMetaConfig($code)
    {
        return $this->helper->getMetaConfig($code);

    }
    /**
     * Get Store name
     *
     * @return string
     */
    protected function getStoreName()
    {
        return $this->_storeManager->getStore()->getName();
    }

}
