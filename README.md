# How to use

Create a new class, e.g. per [php-cli/examples/complex.php](https://github.com/splitbrain/php-cli/blob/master/examples/complex.php):

```php
final class Command extends \aubreypwd\PHP_CLI\CLI {

	protected function setup( \splitbrain\phpcli\Options $options ) {}
	protected function main( \splitbrain\phpcli\Options $options ) {}
}
```

# Methods

Once you do this the command has access to a suite of useful methods:

## `has_command( string $command ) : bool`

This will give you a `true|false` when testing if a command exists on the system.

### E.g.

```php
$brew = $this->has_command( 'brew' );
```

If the user has `brew` installed and accessable, you will get back `true`.

## `get_php_version() : string`

This will give you the current PHP version in use.

### E.g.

```php
$this->info( "The PHP version installed is: {$this->get_php_version()}" );
```

This will return something like `7.4.0`.