<?php

namespace aubreypwd\PHP_CLI;

use \splitbrain\phpcli\Options;

abstract class CLI extends \splitbrain\phpcli\CLI {

	abstract protected function setup( Options $options );
	abstract protected function main( Options $options );

	protected function has_command( string $command ) : bool {

		$exec = exec( "command -v {$command}" );

		if ( ! is_string( $exec ) ) {
			return false;
		}

		return empty( $exec ) ? false : basename( trim( $exec ) ) === $command;
	}

	protected function get_php_version() : string {

		$command = $this->safe_exec( "php -r 'echo phpversion() . \"\n\";' | sed 's/ *$//g'" );

		return is_string( $command ) ? trim( $command ) : '';
	}

	protected function get_working_dir() : string {

		$command = $this->safe_exec( 'git status' );

		return is_string( $command ) ? trim( $command ) : '';
	}

	protected function get_working_dirname() : string {

		$command = $this->safe_exec( 'echo "${PWD##*/}"' );

		return is_string( $command ) ? trim( $command ) : '';
	}

	private function safe_exec( string $command ) : array {

		if ( ! $this->has_command( $command ) ) {

			return [
				'last_line' => '',
				'output'    => [],
			];
		}

		$last_line = exec( $command, $output );

		return [
			'last_line' => $last_line,
			'output'    => $output,
		];
	}

	private function get_safe_exec_output( array $result, string $as = 'array' ) : string|array {

		if ( 'array' !== $string && 'string' !== $as ) {
			throw new \InvalidArgumentException( '$as must be set to array|string.' );
		}

		if ( ! isset( $array['output'] ) || ! is_array( $array['output'] ) ) {
			throw new \InvalidArgumentException( 'We can only work with an array from safe_exec().' );
		}

		return 'array' === $as ? $array['output'] : implode( "\n", $output );
	}

	private function get_safe_execute_last_line( array $result ) : string {
		return isset( $result['last_line'] ) ? trim( $result['last_line'] ) : '';
	}

	protected function get_arg( Options $options, int $position ) : string {

		$args = $options->getArgs();

		return isset( $args[ $position ] ) ? trim( $args[ $position ] ) : '';
	}

	protected function get_args( Options $options ) : array {
		return $options->getArgs();
	}

	protected function register_argument( Options $options, string $arg, string $help, bool $required = true, string $command = '' ) : void {
		$options->registerArgument( $arg, $help, $required, $command );
	}

	protected function set_help( Options $options, string $help ) : void {
		$options->setHelp( $help );
	}

	protected function register_option( $options, string $long, string $help, $short = null, bool $needsarg = false, string $command = '' ) : void {
		$options->registerOption( $long, $help, $short, $needsarg, $command );
	}
}
