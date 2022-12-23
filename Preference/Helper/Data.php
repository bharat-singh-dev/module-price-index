<?php

namespace SixtySeven\PriceIndex\Preference\Helper;


class Data extends \Zoho\Salesiq\Helper\Data
{
    const WIDGET_STAUS = 'widget_config/option_group/isallowed';
    const SCRIPT_CODE = 'widget_config/option_group/salesiqscript';


    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    
    protected $request;


    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Request\Http $request
    ) {
        parent::__construct($context);
         $this->request = $request;
    }


    public function isScriptEmbedEnabled()
    {

        if($this->request->getFullActionName()=='checkout_index_index'){
            return 0;
        }else{

        return $this->scopeConfig->getValue(
            self::WIDGET_STAUS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        }
       
    }

    public function getSalesiqScript()
    {
        return $this->scopeConfig->getValue(
            self::SCRIPT_CODE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
