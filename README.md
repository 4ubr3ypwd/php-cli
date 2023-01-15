# How to use

Create a new class, e.g. per [php-cli/examples/complex.php](https://github.com/splitbrain/php-cli/blob/master/examples/complex.php):

```php
final class Command extends \aubreypwd\PHP_CLI\CLI {

	protected function setup( Options $options ) : void {

		$this->set_help( $options, 'My cool command.' );

		$this->explain_option( $options, 'version', 'Print version.', 'v' );

		// Stops exceptions when using unexplained options.
		$this->invalidate_unexplained_options( $options );
	}

	protected function main( Options $options ) : void {

		if ( in_array( true, [
			$this->version( $options ),
		], true ) ) {
			return;
		}

		$this->show_help( $options );
	}

	private function version( Options $options ) : bool {

		if ( ! $this->get_opt( $options, 'version' ) ) {
			return false;
		}

		$this->info( '1.0.0' );

		return true;
	}
}
```

------------

Once you do this the command has access to a suite of useful `protected` methods:

# Methods

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

## `get_working_dirname() : string`

This will give you back the directory (name) where the command was ran.

```php
$this->info( "The current dirname is: {$this->get_working_dirname()}" );
```

If you run this in `/my/great/directory` you will get back `directory`.