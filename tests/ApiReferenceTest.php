<?php

use PHPHtmlParser\Dom;

class ApiReferenceTest extends \PHPUnit\Framework\TestCase
{
    const MAPPING = [
        '/v1/order/123456?translit=true' => '/v1/order/{$orderId}'
    ];

    /**
     * Check for new api methods
     */
    public function testActualRealization()
    {
        $dom = new Dom();
        $dom->loadFromUrl('https://cb-api.ozonru.me/apiref/en/');
        $tabs = $dom->find('.highlight.http.tab-http');
        self::assertNotEmpty($tabs->count());

        $theirMethods = [];
        /** @var Dom\HtmlNode $tab */
        foreach ($tabs as $tab) {
            $path = $tab->find('.nn')[0]->text;
            if ('/' === $path) {
                continue;
            }

            $path = self::MAPPING[$path] ?? $path;
            if (in_array($path, $theirMethods)) {
                continue;
            }
            $theirMethods[] = $path;
        }
        asort($theirMethods);
        $theirMethods = array_values($theirMethods);
        self::assertNotEmpty($theirMethods);

        $ourMethods = $this->collectRealizedMethods();
        self::assertNotEmpty($ourMethods);

        self::assertJsonStringEqualsJsonString(json_encode($theirMethods), json_encode($ourMethods));
    }

    private function collectRealizedMethods(): array
    {
        $finder = new \Symfony\Component\Finder\Finder();

        $dir = realpath(__DIR__ . '/../src/Service');
        self::assertDirectoryExists($dir);
        $finder->files()->in($dir)->name('*.php');

        $result = [];
        foreach ($finder as $file) {
            $matches = [];
            preg_match_all('/[\'|"](\/v1.*?)[\'|"]/', $file->getContents(), $matches);
            $result = array_merge($result, $matches[1]);
        }

        asort($result);
        return array_values(array_unique($result));
    }
}