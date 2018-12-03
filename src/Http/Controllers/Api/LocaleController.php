<?php

namespace Chang\Erp\Http\Controllers\Api;

use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;

class LocaleController extends Controller
{

    public function set($locale = 'en-US')
    {
        app()->setLocale($locale);
        return $this->getTranslations();
    }
    /**
     * Get the translation keys from file.
     *
     * @return array
     */
    private static function getTranslations()
    {
        $translationFile = resource_path('lang/vendor/nova/'.app()->getLocale().'.json');

        if (! is_readable($translationFile)) {
            return [];
        }

        return json_decode(file_get_contents($translationFile), true);
    }
}
