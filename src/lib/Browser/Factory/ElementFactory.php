<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\Browser\Factory;

use EzSystems\Behat\Browser\Context\BrowserContext;
use EzSystems\Behat\Core\Environment\InstallType;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\PlatformElementFactory;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\EnterpriseElementFactory;
use Tests\App\Behat\PageElement\DemoEnterpriseElementFactory;

class ElementFactory
{
    private static $installType;

    private static $factory;

    /**
     * Creates a Element object based on given Element Name.
     *
     * @param UtilityContext $context
     * @param string $elementName Name of the Element to creator
     */
    public static function createElement(BrowserContext $context, string $elementName, ?string ...$parameters)
    {
        /* Note: no return type to enable type-hinting */

        if (self::$factory === null) {
            self::$factory = self::getFactory(self::$installType);
        }

        return self::$factory::createElement($context, $elementName, ...$parameters);
    }

    public static function setInstallType(int $installType)
    {
        self::$installType = $installType;
    }

    public static function getPreviewType(string $contentType)
    {
        /* Note: no return type to enable type-hinting */

        if (self::$factory === null) {
            self::$factory = self::getFactory(self::$installType);
        }

        return self::$factory::getPreviewType($contentType);
    }

    /**
     * @param int $installType
     *
     * @return EnterpriseElementFactory|PlatformElementFactory
     */
    private static function getFactory(int $installType): PlatformElementFactory
    {
        switch ($installType) {
            case InstallType::PLATFORM:
            case InstallType::PLATFORM_DEMO:
                return new PlatformElementFactory();
            case InstallType::ENTERPRISE:
                return new EnterpriseElementFactory();
            case InstallType::ENTERPRISE_DEMO:
                return new DemoEnterpriseElementFactory();
            default:
                throw new \Exception('Unrecognised install type');
        }
    }
}
