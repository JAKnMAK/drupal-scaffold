<?php

namespace JAKnMAK;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class DrupalScaffoldPlugin implements PluginInterface {
    private const PACKAGE_NAME = 'jaknmak/drupal-scaffold';

    public function activate(Composer $composer, IOInterface $io) {
        $package = $composer->getPackage();

        $extra = $package->getExtra();

        if (empty($extra['drupal-scaffold']['allowed-packages'])) {
            $extra['drupal-scaffold']['allowed-packages'] = [];
        }

        if (!in_array(self::PACKAGE_NAME, $extra['drupal-scaffold']['allowed-packages'], true)) {
            $extra['drupal-scaffold']['allowed-packages'][] = self::PACKAGE_NAME;
        }

        $package->setExtra($extra);

        $configSource = $composer->getConfig()->getConfigSource();
        $configSource->addProperty('extra.drupal-scaffold.allowed-packages', $extra['drupal-scaffold']['allowed-packages']);
    }

    public function deactivate(Composer $composer, IOInterface $io) {
        $package = $composer->getPackage();
        
        $extra = $package->getExtra();
        
        if (!empty($extra['drupal-scaffold']['allowed-packages'])) {
            $key = array_search(self::PACKAGE_NAME, $extra['drupal-scaffold']['allowed-packages'], true);
        
            if ($key !== false) {
                unset($extra['drupal-scaffold']['allowed-packages'][$key]);

                $package->setExtra($extra);
                
                $configSource = $composer->getConfig()->getConfigSource();
                $configSource->addProperty('extra.drupal-scaffold.allowed-packages', $extra['drupal-scaffold']['allowed-packages']);
            }
        }
    }

    public function uninstall(Composer $composer, IOInterface $io) {}
}
