<?php

namespace aubreypwd\PHP_CLI;

abstract class CLI extends \splitbrain\phpcli\CLI {

	abstract protected function setup( \splitbrain\phpcli\Options $options );
	abstract protected function main( \splitbrain\phpcli\Options $options );

	protected function has_command( string $command ) : bool {

		$command = exec( "command -v {$command}", $result );

		if ( ! is_array( $result ) ) {
			return false;
		}

		return count( $result ) > 0;
	}

	protected function get_php_version() : string {

		$command = exec( "php -r 'echo phpversion() . \"\n\";' | sed 's/ *$//g'", $result );

		return is_string( $command ) ? $command : '';
	}
}
