# aubreypwd/php-cli

This is a CLI-tool I use to write command line tools using PHP. It's a wrapper for [splitbrain/php-cli](https://github.com/splitbrain/php-cli) that adds some tools and improvements I enjoy having, like:

1. HTML-like tags for bullets, paragraphs, break-returns, and colorization
2. A `log()` method that's more powerful than the built in info, error, etc methods from `splitbrain/php-cli`
3. Tools like `has_command()`, `get_php_version()`, `rid()` (run in directory), etc
4. Easier ways to run shell commands and extrapolate results including status, output (`array|string`), etc, with or without `stderr` (see `aubreypwd\PHP_CLI\Command::exec()`)

...and more.

The best way to familiarize yourself with what you can do see:

- [src/Command.php](src/Command.php)
- [aubreypwd/php-cli-dev](https://github.com/aubreypwd/php-cli-dev)

The both should document and examplify use-cases until I build more tools with it.

## Tips & Tricks

### Avoid un-explained options from being thrown as Exceptions

To avoid un-explained options from being thrown as `\Exception`'s run `::run()` with a `try/catch` like so:

```php

namespace aubreypwd\My_CLI;

final class Command extends \aubreypwd\PHP_CLI\Command {
	...
}

$cli = new \aubreypwd\My_CLI\Command();

try {

	$cli->run();

} catch ( \Exception $e ) {

	$cli->log( 'error', "{$e->getMessage()}\n" );
}
```

`splitbrain/php-cli` shows an odd message when you try to use an option like `--option|-o` that has not been explained.