<?php

namespace TorqIT\WikiBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class WikiBundle extends AbstractPimcoreBundle implements PimcoreBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;

    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
