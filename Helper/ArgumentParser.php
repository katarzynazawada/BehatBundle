<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\BehatBundle\Helper;

use Behat\Gherkin\Node\TableNode;
use EzSystems\BehatBundle\API\Facade\RoleFacade;

class ArgumentParser
{
    public function __construct(RoleFacade $roleFacade)
    {
        $this->roleFacade = $roleFacade;
    }

    public function parseUrl(string $url)
    {
        if ($url === 'root') {
            return '/';
        }

        $url = str_replace(' ', '-', $url);

        return strpos($url, '/') === 0 ? $url : sprintf('/%s', $url);
    }

    public function parseLimitations(TableNode $limitations)
    {
        $parsedLimitations = [];
        $limitationParsers = $this->roleFacade->getLimitationParsers();

        foreach ($limitations->getHash() as $rawLimitation) {
            foreach ($limitationParsers as $parser) {
                if ($parser->supports($rawLimitation['limitationType'])) {
                    $parsedLimitations[] = $parser->parse($rawLimitation['limitationValue']);
                    break;
                }
            }
        }

        return $parsedLimitations;
    }
}
