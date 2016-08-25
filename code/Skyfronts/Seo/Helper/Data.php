<?php
namespace Skyfronts\Seo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\UrlInterface;

class Data extends AbstractHelper
{
    const XML_PATH_GENERAL = 'seo/settings/';
    const XML_PATH_Store = 'general/store_information/';
    const XML_PATH_META = 'seo/metadata/';
    const XML_PATH_GTM = 'seo/google_tag_manager/';
    const XML_PATH_RICHSNIPPETS = 'seo/richsnippets/';
    const XML_PATH_HTACCESS = 'seo/htaccess/';
    const XML_PATH_ROBOTS = 'seo/robots/';
    const XML_PATH_HTML_SITEMAP = 'seo/htmlsitemap/';
    protected $storeManager;
    protected $objectManager;
    protected $urlManager;

    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        UrlInterface $urlManager
    )
    {
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        $this->urlManager = $urlManager;
        parent::__construct($context);
    }

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GENERAL . $code, $storeId);
    }

    public function getGoogleConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GTM . $code, $storeId);
    }

    public function getStoreName($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GENERAL . $code, $storeId);
    }
  //  public function getStoreName()

    public function getMetaConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_META . $code, $storeId);
    }

    public function getRichsnippetsConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_RICHSNIPPETS . $code, $storeId);
    }

    public function getHtaccessConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_HTACCESS . $code, $storeId);
    }

    public function getRobotsConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_ROBOTS . $code, $storeId);
    }

    public function getHtmlsitemapConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_HTML_SITEMAP . $code, $storeId);
    }

    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Sam
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])
            ) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    public function getHtmlSitemapUrl()
    {
        $this->_getUrl('Skyfronts_seo/sitemap');
    }
}
