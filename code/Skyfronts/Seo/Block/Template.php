<?php

namespace Skyfronts\Seo\Block {

    class Template extends Abstractt
    {

      public function getGoogleConfig($code)
      {
          return $this->helperData->getGoogleConfig($code);
      }
    }
}
