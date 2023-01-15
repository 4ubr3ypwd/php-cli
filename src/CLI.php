<?php

namespace aubreypwd\PHP_CLI;

abstract class CLI extends \splitbrain\phpcli\CLI {

	abstract protected function setup( \splitbrain\phpcli\Options $options );
	abstract protected function main( \splitbrain\phpcli\Options $options );

	protected function has_command( string $command ) : bool {

		$command = exec( "command -v {$command}" );

		return empty( $command ) ? false : true;
	}

	protected function get_php_version() : string {

		$command = exec( "php -r 'echo phpversion() . \"\n\";' | sed 's/ *$//g'" );

		return is_string( $command ) ? trim( $command ) : '';
	}

	protected function get_working_dirname() : string {

		$command = exec( 'echo "${PWD##*/}"' );

		return is_string( $command ) ? trim( $command ) : '';
	}
}
