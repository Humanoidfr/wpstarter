<?php declare(strict_types=1);
/*
 * This file is part of the WP Starter package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WeCodeMore\WpStarter\Tests\Unit\Util;

use WeCodeMore\WpStarter\Config\Config;
use WeCodeMore\WpStarter\Tests\TestCase;
use WeCodeMore\WpStarter\Util\FileBuilder;

class FileBuilderTest extends TestCase
{
    public function testBuild()
    {
        $templates = $this->packagePath() . '/templates';
        $config = new Config([Config::TEMPLATES_DIR => $templates], $this->makeValidator());
        $paths = $this->makePaths();
        $paths->initTemplates($config);

        $builder = new FileBuilder();

        $actual = $builder->build(
            $paths,
            'index.php',
            ['BOOTSTRAP_PATH' => '/foo/bar/baz.php']
        );

        $expected = "<?php\nrequire '/foo/bar/baz.php';";

        static::assertSame(trim($expected), trim($actual));
    }
}