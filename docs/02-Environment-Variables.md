# Environment Variables



## The issue with configuration files

Pretty much all applications need configuration. There are different approaches to that, one of the most used is to have configuration files in some data-exchange format (XML, JSON, Yaml, Neon, Toml...).

PHP application might use PHP files.

WordPress uses a single PHP file, `wp-config.php` that is used to declare PHP constants with configuration values.

This approach has surely some advantages (speed above all), but also issues. The main problems are two:

- `wp-config.php` will very likely contain "secrets" that should **not** be kept under version control. But being the file required by WordPress it is hard to conciliate the two things.
- `wp-config.php` does not support multiple environments. Meaning that if the same code will be deployed to, for example, a "staging" and a "production" server it will be necessary to have two versions of the file. This is possible using separate VCS "branches" (if the VCS of choice support them), but then we fall in the previous issue being forced to keep secrets versioned.

This issue is surely not limited to WordPress.

A modern approach to this issue (see [The Twelve-Factor App](https://12factor.net/)) is to use **environment variables** to save configuration values.



## What are environment variables?

Environment variables (or simply "env vars") are key-value pairs that can be set in the environment, i.e. in the server that runs the application.

For example, those could be set into the webserver ([Apache](https://httpd.apache.org/docs/2.4/env.html), [nginx](http://nginx.org/en/docs/ngx_core_module.html#env)) making the configuration very specific to the server that runs the application.

As additional advantage, env vars can be set "on the fly" acting on the system, e.g. a continuous integration service can set them without touching the code.

Finally, not being code they don't need to be kept under version control, avoiding the issue to keep "secrets" under version control.

It is undeniable that to set the values on the "bare environment" could be quite cumbersome. This is why many applications and tools support "env files".

In the rest of the documentation we will refer to "actual environment" to mean variables set on the server itself, to distinguish from variables set by parsing env files.



## Introducing env files

An env file is nothing else than a shell script file that does not contain any command, but only variables.

```shell
HELLO="Hello"
GREETING="${HELLO} World!"
```

Tools that support such files read them and set values on the environment "on the fly".

By convention those file are often named `.env`.



## PHP and env vars

In PHP there are two functions: [`getevn`](http://php.net/manual/en/function.getenv.php) and [`putenv`](http://php.net/manual/en/function.getenv.php) that allow to, respectively, read and write env vars on the server in a OS-agnostic way.

There's nothing in PHP core that parse env files, but is no surprise that there are different libraries to do that.

WP Starter uses one of this libraries: the **[Symfony Dotenv Component](https://symfony.com/doc/3.4/components/dotenv.html).**



## WP Starter and env vars

WP Starter uses Symfony Dotenv Component to load an `.env` file found in the root folder of the project (the folder and the file name can be configured, if necessary).

The env vars loaded from the file will never overwrite variables that are set in the actual environment.

Moreover, if the actual environment contains all the variables WP Starter and WordPress need, there's actually no need to load and parse env files, and **this is actually the suggested way to go in production**, to maximize speed.

To tell WP Starter to don't load any env file and just assume all the variables are set in the actual environment it is necessary to set a **`WPSTARTER_ENV_LOADED`** env variable to a non-null value: when WP Starter recognizes that var is set before any env file is loaded, it does not proceed any further loading env file.



## Environment variables and WordPress

Even if WP Starter loads env vars (no matter if from file or from actual environment) to work properly WordPress still needs PHP constants to be set with configuration.

WP Starter generates a  `wp-config.php` file that reads env variables and declare PHP constants "on the fly" when an env var matching a WP configuration constant is found.

For example, by having an env file like the following:

```shell
DB_NAME=mydb
DB_USER=mydb_user
DB_PASSWORD=mysecret!
```

WP starter will load it, will set related environment variables and will also **define `DB_NAME`, `DB_USER`, and `DB_PASSWORD` PHP constants** so that WordPress can work properly.

If the same env vars would be set in the actual environment instead of in env file, nothing would change.

Note that **only variables matching WordPress core constants names will be defined as constants**.

If there's a plugin that supports a constant like `"AWESOME_PLUGIN_CONFIG"`, by setting the related env var WP Starter will *not* declare the constant automatically.

So you might need to write a code like:

```php
$config = getenv('AWESOME_PLUGIN_CONFIG');
if ($config) {
    define('AWESOME_PLUGIN_CONFIG', $config);
}
```

there are many places in which such code can be placed, for example a MU plugin.



## WP Starter specific env vars

As described above, all WordPress configuration constants are natively supported by WP Starter.

Moreover there are a few env variables that have a special meaning for WP Starter. Those are used in the `wp-config.php` that WP Starter generates and are documented in the *"WordPress Integration"* documentation chapter.