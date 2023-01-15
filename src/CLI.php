<?php

namespace aubreypwd\PHP_CLI;

use \splitbrain\phpcli\Options;
use \splitbrain\phpcli\Colors;

abstract class CLI extends \splitbrain\phpcli\CLI {

	public $colors;

	public $_options = [];

	public function __construct() {

		$this->colors = new Colors();

		parent::__construct();
	}

	abstract protected function setup( Options $options );
	abstract protected function main( Options $options );

	protected function has_command( string $command ) : bool {

		if ( empty( $command ) ) {
			return false;
		}

		$exec = exec( "command -v {$command}" );

		if ( ! is_string( $exec ) ) {
			return false;
		}

		if ( empty( $exec ) ) {
			return false;
		}

		return basename( trim( $exec ) ) === $command;
	}

	protected function get_php_version() : string {
		return $this->get_last_line( $this->safe_exec( "php -r 'echo phpversion() . \"\n\";' | sed 's/ *$//g'" ) );
	}

	protected function get_working_dir() : string {
		return $this->get_last_line( $this->safe_exec( 'pwd' ) );
	}

	protected function get_working_dirname() : string {
		return $this->get_last_line( $this->safe_exec( 'echo "${PWD##*/}"' ) );
	}

	private function safe_exec( string $command ) : array {

		if ( ! $this->has_command( strtok( $command, ' ' ) ) ) {

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

	private function get_output( array $result, string $as = 'array' ) : string|array {

		if ( 'array' !== $string && 'string' !== $as ) {
			throw new \InvalidArgumentException( '$as must be set to array|string.' );
		}

		if ( ! isset( $array['output'] ) || ! is_array( $array['output'] ) ) {
			throw new \InvalidArgumentException( 'We can only work with an array from exec().' );
		}

		return 'array' === $as ? $array['output'] : implode( "\n", $output );
	}

	private function get_last_line( array $exec ) : string {
		return isset( $exec['last_line'] ) ? trim( $exec['last_line'] ) : '';
	}

	protected function get_arg( Options $options, int $position ) : string {

		$args = $options->getArgs();

		return isset( $args[ $position ] )
			? $args[ $position ]
			: '';
	}

	protected function get_args( Options $options ) : array {
		return $options->getArgs();
	}

	protected function explain_argument( Options $options, string $arg, string $help, bool $required = true, string $command = '' ) : void {
		$options->registerArgument( $arg, $help, $required, $command );
	}

	protected function set_help( Options $options, string $help ) : void {
		$options->setHelp( $help );
	}

	protected function explain_option( Options $options, string $long, string $help, mixed $short = null, bool $needsarg = false, string $command = '' ) : void {
		$options->registerOption( $long, $help, $short, $needsarg, $command );

		$this->_options[ $long ] = $short;
	}

	protected function get_opt( Options $options, $option ) : bool|string {
		return $options->getOpt( $option );
	}

	protected function show_help( Options $options ) {
		echo $options->help();
	}

	protected function invalidate_unexplained_options( Options $options ) {

		foreach ( $_SERVER['argv'] as $position => $arg ) {

			if ( 0 === intval( $position ) ) {
				continue;
			}

			if ( '-' !== substr( $arg, 0, 1 ) ) {
				continue;
			}

			if ( ! strstr( $arg, '-' ) ) {
				continue;
			}

			$base = str_replace( array( '--', '-' ), '', $arg );

			if ( in_array( $base, array_values( $this->_options ), true ) ) {
				continue;
			}

			if ( in_array( $base, array_keys( $this->_options ), true ) ) {
				continue;
			}

			$this->explain_option(
				$options,
				$base,
				$this->colors->wrap( 'Invalid option.', $this->colors::C_RED ),
				strstr( $arg, '--' )
					? ''
					: substr( $base, 0, 1 )
			);
		}
	}
}
