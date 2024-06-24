<?php

namespace App\Service\Vue;

use App\Service\Explorer;

class VueFormatter
{
    public static function createDatatableComponent(
        string $name,
        string $component,
        array $settings,
        array $items
    )
    {
       assert(self::isVueComponent($component), 'Vue component ' . $component . ' not found');

       return [
           'name' => $name,
           'component' => $component,
           'data' => self::createDatatable($settings, $items)
       ];
    }

    private static function isVueComponent(string $path): bool
    {
        return in_array($path, Explorer::findFileNames('../assets/vue', 'vue'));
    }

    public static function createDatatable(
        array $settings,
        array $items
    ): array
    {
        return [
            'settings' => self::constructSettings($settings),
            'items' => $items
        ];
    }

    private static function constructSettings(array $settings): array
    {
        foreach ($settings as $setting) {
            assert($setting instanceof VueDatatableSetting);
        }

        $formattedSettings = [];
        foreach ($settings as $setting) {
            assert($setting instanceof VueDatatableSetting);
            $formattedSettings[$setting->getSetting('codeName')] = $setting->getSetting();
        }
        return $formattedSettings;
    }
}