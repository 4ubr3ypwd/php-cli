# PHP_CLI

This is a CLI-tool I use to write command line tools using PHP. It's a wrapper for [splitbrain/php-cli](https://github.com/splitbrain/php-cli) that adds some tools and improvements I enjoy having, like:

1. HTML-like tags for bullets, paragraphs, break-returns, and colorization
2. A `log()` method that's more powerful than the built in info, error, etc methods from `splitbrain/php-cli`
3. Tools like `has_command()`, `get_php_version()`, `rid()` (run in directory), etc
4. Easier ways to run shell commands and extrapolate results including status, output (`array|string`), etc, with or without `stderr` (see `aubreypwd\PHP_CLI\Command::exec()`)

...and more.

The best way to familiarize yourself with what you can do see:

- [src/CLI.php](src/CLI.php)
- [aubreypwd/php-cli-dev](https://github.com/aubreypwd/php-cli-dev)

The both should document and examplify use-cases until I build more tools with it.