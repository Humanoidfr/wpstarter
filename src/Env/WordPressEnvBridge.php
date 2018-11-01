<?php declare(strict_types=1);
/*
 * This file is part of the WP Starter package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WeCodeMore\WpStarter\Env;

use Symfony\Component\Dotenv\Dotenv;

/**
 * Handle WordPress related environment variables using Symfony Env component.
 */
final class WordPressEnvBridge implements \ArrayAccess
{
    const WP_CONSTANTS = [
        'ALLOW_UNFILTERED_UPLOADS' => Filters::FILTER_BOOL,
        'ALTERNATE_WP_CRON' => Filters::FILTER_BOOL,
        'AUTOMATIC_UPDATER_DISABLED' => Filters::FILTER_BOOL,
        'ALLOW_SUBDIRECTORY_INSTALL' => Filters::FILTER_BOOL,
        'COMPRESS_CSS' => Filters::FILTER_BOOL,
        'COMPRESS_SCRIPTS' => Filters::FILTER_BOOL,
        'CONCATENATE_SCRIPTS' => Filters::FILTER_BOOL,
        'CORE_UPGRADE_SKIP_NEW_BUNDLED' => Filters::FILTER_BOOL,
        'DIEONDBERROR' => Filters::FILTER_BOOL,
        'DISABLE_WP_CRON' => Filters::FILTER_BOOL,
        'DISALLOW_FILE_EDIT' => Filters::FILTER_BOOL,
        'DISALLOW_FILE_MODS' => Filters::FILTER_BOOL,
        'DISALLOW_UNFILTERED_HTML' => Filters::FILTER_BOOL,
        'DO_NOT_UPGRADE_GLOBAL_TABLES' => Filters::FILTER_BOOL,
        'ENFORCE_GZIP' => Filters::FILTER_BOOL,
        'IMAGE_EDIT_OVERWRITE' => Filters::FILTER_BOOL,
        'MEDIA_TRASH' => Filters::FILTER_BOOL,
        'MULTISITE' => Filters::FILTER_BOOL,
        'FORCE_SSL_LOGIN' => Filters::FILTER_BOOL,
        'FORCE_SSL_ADMIN' => Filters::FILTER_BOOL,
        'FTP_SSH' => Filters::FILTER_BOOL,
        'FTP_SSL' => Filters::FILTER_BOOL,
        'SAVEQUERIES' => Filters::FILTER_BOOL,
        'SCRIPT_DEBUG' => Filters::FILTER_BOOL,
        'SUBDOMAIN_INSTALL' => Filters::FILTER_BOOL,
        'WP_ALLOW_MULTISITE' => Filters::FILTER_BOOL,
        'WP_ALLOW_REPAIR' => Filters::FILTER_BOOL,
        'WP_AUTO_UPDATE_CORE' => Filters::FILTER_BOOL,
        'WP_HTTP_BLOCK_EXTERNAL' => Filters::FILTER_BOOL,
        'WP_CACHE' => Filters::FILTER_BOOL,
        'WP_DEBUG' => Filters::FILTER_BOOL,
        'WP_DEBUG_DISPLAY' => Filters::FILTER_BOOL,
        'WP_DEBUG_LOG' => Filters::FILTER_BOOL,
        'WPMU_ACCEL_REDIRECT' => Filters::FILTER_BOOL,
        'WPMU_SENDFILE' => Filters::FILTER_BOOL,
        'AUTOSAVE_INTERVAL' => Filters::FILTER_INT,
        'EMPTY_TRASH_DAYS' => Filters::FILTER_INT,
        'FS_TIMEOUT' => Filters::FILTER_INT,
        'FS_CONNECT_TIMEOUT' => Filters::FILTER_INT,
        'WP_CRON_LOCK_TIMEOUT' => Filters::FILTER_INT,
        'WP_MAIL_INTERVAL' => Filters::FILTER_INT,
        'SITE_ID_CURRENT_SITE' => Filters::FILTER_INT,
        'BLOG_ID_CURRENT_SITE' => Filters::FILTER_INT,
        'WP_PROXY_PORT' => Filters::FILTER_INT,
        'ABSPATH' => Filters::FILTER_STRING,
        'ADMIN_COOKIE_PATH' => Filters::FILTER_STRING,
        'AUTH_COOKIE' => Filters::FILTER_STRING,
        'BLOGUPLOADDIR' => Filters::FILTER_STRING,
        'COOKIEHASH' => Filters::FILTER_STRING,
        'COOKIEPATH' => Filters::FILTER_STRING,
        'COOKIE_DOMAIN' => Filters::FILTER_STRING,
        'CUSTOM_USER_META_TABLE' => Filters::FILTER_STRING,
        'CUSTOM_USER_TABLE' => Filters::FILTER_STRING,
        'DB_CHARSET' => Filters::FILTER_STRING,
        'DB_COLLATE' => Filters::FILTER_STRING,
        'DB_HOST' => Filters::FILTER_STRING,
        'DB_NAME' => Filters::FILTER_STRING,
        'DB_PASSWORD' => Filters::FILTER_STRING,
        'DB_TABLE_PREFIX' => Filters::FILTER_STRING,
        'DB_USER' => Filters::FILTER_STRING,
        'DOMAIN_CURRENT_SITE' => Filters::FILTER_STRING,
        'ERRORLOGFILE' => Filters::FILTER_STRING,
        'FS_METHOD' => Filters::FILTER_STRING,
        'FTP_BASE' => Filters::FILTER_STRING,
        'FTP_CONTENT_DIR' => Filters::FILTER_STRING,
        'FTP_HOST' => Filters::FILTER_STRING,
        'FTP_PASS' => Filters::FILTER_STRING,
        'FTP_PLUGIN_DIR' => Filters::FILTER_STRING,
        'FTP_PRIKEY' => Filters::FILTER_STRING,
        'FTP_PUBKEY' => Filters::FILTER_STRING,
        'FTP_USER' => Filters::FILTER_STRING,
        'LOGGED_IN_COOKIE' => Filters::FILTER_STRING,
        'MU_BASE' => Filters::FILTER_STRING,
        'NOBLOGREDIRECT' => Filters::FILTER_STRING,
        'PASS_COOKIE' => Filters::FILTER_STRING,
        'PATH_CURRENT_SITE' => Filters::FILTER_STRING,
        'PLUGINS_COOKIE_PATH' => Filters::FILTER_STRING,
        'SECURE_AUTH_COOKIE' => Filters::FILTER_STRING,
        'SITECOOKIEPATH' => Filters::FILTER_STRING,
        'TEST_COOKIE' => Filters::FILTER_STRING,
        'UPLOADBLOGSDIR' => Filters::FILTER_STRING,
        'UPLOADS' => Filters::FILTER_STRING,
        'USER_COOKIE' => Filters::FILTER_STRING,
        'WPLANG' => Filters::FILTER_STRING,
        'WPMU_PLUGIN_DIR' => Filters::FILTER_STRING,
        'WPMU_PLUGIN_URL' => Filters::FILTER_STRING,
        'WP_ACCESSIBLE_HOSTS' => Filters::FILTER_STRING,
        'WP_CONTENT_DIR' => Filters::FILTER_STRING,
        'WP_CONTENT_URL' => Filters::FILTER_STRING,
        'WP_DEFAULT_THEME' => Filters::FILTER_STRING,
        'WP_HOME' => Filters::FILTER_STRING,
        'WP_LANG_DIR' => Filters::FILTER_STRING,
        'WP_MAX_MEMORY_LIMIT' => Filters::FILTER_STRING,
        'WP_MEMORY_LIMIT' => Filters::FILTER_STRING,
        'WP_PLUGIN_DIR' => Filters::FILTER_STRING,
        'WP_PLUGIN_URL' => Filters::FILTER_STRING,
        'WP_PROXY_BYPASS_HOSTS' => Filters::FILTER_STRING,
        'WP_PROXY_HOST' => Filters::FILTER_STRING,
        'WP_PROXY_PASSWORD' => Filters::FILTER_STRING,
        'WP_PROXY_USERNAME' => Filters::FILTER_STRING,
        'WP_SITEURL' => Filters::FILTER_STRING,
        'WP_TEMP_DIR' => Filters::FILTER_STRING,
        'WP_POST_REVISIONS' => Filters::FILTER_INT_OR_BOOL,
        'FS_CHMOD_DIR' => Filters::FILTER_OCTAL_MOD,
        'FS_CHMOD_FILE' => Filters::FILTER_OCTAL_MOD,
    ];

    const WP_STARTER_VARS = [
        'WP_ENV' => Filters::FILTER_STRING,
        'WORDPRESS_ENV' => Filters::FILTER_STRING,
        'WP_ADMIN_COLOR' => Filters::FILTER_STRING,
        'WP_FORCE_SSL_FORWARDED_PROTO' => Filters::FILTER_BOOL,
        'WPDB_ENV_VALID' => Filters::FILTER_BOOL,
        'WPDB_EXISTS' => Filters::FILTER_BOOL,
        'WP_INSTALLED' => Filters::FILTER_BOOL,
    ];

    /**
     * @var Dotenv
     */
    private static $defaultDotEnv;

    /**
     * @var array
     */
    private static $loadedVars;

    /**
     * @var null|Dotenv
     */
    private $dotenv;

    /**
     * @var Filters
     */
    private $filters;

    /**
     * Symfony stores a variable with the keys of variables it loads.
     *
     * @return array
     */
    private static function loadedVars(): array
    {
        if (self::$loadedVars === null) {
            self::$loadedVars = array_flip(explode(',', (getenv('SYMFONY_DOTENV_VARS') ?: '')));
            unset(self::$loadedVars['']);
        }

        return self::$loadedVars;
    }

    /**
     * @param Dotenv|null $dotenv
     */
    public function __construct(Dotenv $dotenv = null)
    {
        $this->dotenv = $dotenv;
    }

    /**
     * @param string $file Environment file path relative to `$path`
     * @param string $path Environment file path
     * @return void
     */
    public function load(string $file = '.env', string $path = null)
    {
        $this->loadFile($this->fullpathFor($file, $path));
    }

    /**
     * @param string $path
     * @return void
     */
    public function loadFile(string $path)
    {
        $loaded = $_ENV['WPSTARTER_ENV_LOADED'] ?? $_SERVER['WPSTARTER_ENV_LOADED'] ?? null;
        if ($loaded !== null) {
            self::$loadedVars = [];

            return;
        }

        if (self::$loadedVars !== null) {
            return;
        }

        $path = $this->fullpathFor('', $path);
        if ($path) {
            $this->dotenv()->load($path);
            self::loadedVars();
        }
    }

    /**
     * @param string $file
     * @param string|null $path
     */
    public function loadAppended(string $file, string $path = null)
    {
        if (self::$loadedVars === null) {
            $this->load($file, $path);

            return;
        }

        $fullpath = $this->fullpathFor($file, $path);
        if (!$fullpath) {
            return;
        }

        $values = $this->dotenv()->parse(file_get_contents($fullpath), $fullpath);
        foreach ($values as $name => $value) {
            $this->isWritable($name) and $this->offsetSet($name, $value);
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isLoadedVar(string $name): bool
    {
        return array_key_exists($name, self::loadedVars());
    }

    /**
     * Return all environment variables that have been set
     *
     * @return void
     */
    public function setupWordPress()
    {
        $names = array_keys(self::WP_CONSTANTS);
        array_walk($names, [$this, 'defineWpConstant']);
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->offsetGet($offset) !== null;
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        // Unfortunately we can't type-declare `string` having to stick with ArrayAccess signature.
        $this->assertString($offset, __METHOD__);

        if (defined($offset)) {
            return constant($offset);
        }

        // We don't check $_SERVER for keys starting with 'HTTP_' because clients can write there.
        $serverSafe = strpos($offset, 'HTTP_') !== 0;

        // We consider anything not loaded by Symfony Dot Env as "actual" environment, and because
        // of thread safety issues, we don't use getenv() for those "actual" environment variables.
        $loadedVar = self::$loadedVars && $this->isLoadedVar($offset);

        $value = null;
        switch (true) {
            case ($loadedVar && $serverSafe):
                // Both $_SERVER and getenv() are ok.
                $value = $_ENV[$offset] ?? $_SERVER[$offset] ?? getenv($offset) ?: null;
                break;
            case ($loadedVar && !$serverSafe):
                // $_SERVER is not ok, getenv() is.
                $value = $_ENV[$offset] ?? getenv($offset) ?: null;
                break;
            case (!$loadedVar && $serverSafe):
                // $_SERVER is ok, getenv() is not.
                $value = $_ENV[$offset] ?? $_SERVER[$offset] ?? null;
                break;
            case (!$loadedVar && !$serverSafe):
                // Neither $_SERVER nor getenv() are ok.
                $value = $_ENV[$offset] ?? null;
                break;
        }

        if ($value === null) {
            return null;
        }

        /** @var string|null $filter */
        $filter = self::WP_CONSTANTS[$offset] ?? self::WP_STARTER_VARS[$offset] ?? null;
        if (!$filter) {
            return $value;
        }

        $this->filters or $this->filters = new Filters();

        return $this->filters->filter($filter, $value);
    }

    /**
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (!$this->isWritable($offset)) {
            throw new \BadMethodCallException("{$offset} is not a writable ENV var.");
        }

        putenv("{$offset}={$value}");
        $_ENV[$offset] = $value;
        (strpos($offset, 'HTTP_') !== 0) and $_SERVER[$offset] = $value;

        $loaded = self::loadedVars();
        $loaded[$offset] = true;
        self::$loadedVars = $loaded;
    }

    /**
     * Disabled. No unset is available.
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException(__CLASS__ . ' is read only.');
    }

    /**
     * Define WP constants from environment variables
     *
     * @param string $name
     */
    private function defineWpConstant(string $name)
    {
        if ($name === 'DB_TABLE_PREFIX') {
            $this->defineTablePrefix();

            return;
        }

        if (!defined($name)) {
            $value = $this->offsetGet($name);
            $value === null or define($name, $value);
        }
    }

    /**
     * DB table prefix is a global variable in WP, so it differs from other settings to be set
     * from environment variables because those are constants in WP.
     *
     * WP Starter makes up the `DB_TABLE_PREFIX` environment variable to set DB table prefix.
     */
    private function defineTablePrefix()
    {
        $value = $this->offsetGet('DB_TABLE_PREFIX') ?: '';

        if ($value || !isset($GLOBALS['table_prefix'])) {
            $value or $value = 'wp_';
            $GLOBALS['table_prefix'] = preg_replace('#[\W]#', '', (string)$value);
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    private function isWritable(string $name): bool
    {
        return !$this->offsetExists($name) || $this->isLoadedVar($name);
    }

    /**
     * @param string $filename
     * @param string|null $basePath
     * @return string
     */
    private function fullpathFor(string $filename, string $basePath = null): string
    {
        $basePath === null and $basePath = getcwd();

        $fullpath = realpath(rtrim(rtrim($basePath, '\\/') . "/{$filename}", '\\/'));
        if (!$fullpath || !is_file($fullpath) || !is_readable($fullpath)) {
            return '';
        }

        return $fullpath;
    }

    /**
     * @return Dotenv
     */
    private function dotEnv(): Dotenv
    {
        $dotEnv = $this->dotenv ?? self::$defaultDotEnv;
        if (!$dotEnv) {
            self::$defaultDotEnv or self::$defaultDotEnv = new Dotenv();
            $dotEnv = self::$defaultDotEnv;
        }

        return $dotEnv;
    }

    /**
     * @param $value
     * @param string $method
     *
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration
     */
    private function assertString($value, string $method)
    {
        // phpcs:enable

        if (!is_string($value)) {
            throw new \TypeError(
                sprintf(
                    'Argument 1 passed to %s() must be of the type string, %s given.',
                    $method,
                    gettype($value)
                )
            );
        }
    }
}