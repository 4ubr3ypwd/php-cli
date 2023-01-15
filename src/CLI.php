<?php

namespace aubreypwd\PHP_CLI;

abstract class CLI extends \splitbrain\phpcli\CLI {

	abstract protected function setup( \splitbrain\phpcli\Options $options );
	abstract protected function main( \splitbrain\phpcli\Options $options );

	protected function has_command( string $command ) : bool {

		$command = exec( "command -v {$command}", $result );

		return count( $result ) > 0;
	}
}
