<?php

namespace aubreypwd\PHP_CLI;

use \splitbrain\phpcli\Options;
use \splitbrain\phpcli\Colors;

/**
 * CLI
 *
 * @TODO Methods to do colors easier
 *     - ->log( )
 *
 * @since 1.0.0
 */
abstract class CLI extends \splitbrain\phpcli\CLI {

	abstract protected function setup( Options $options );
	abstract protected function main( Options $options );

	/**
	 * Colors
	 *
	 * @since 1.0.0
	 *
	 * @var \splitbrain\phpcli\Colors
	 */
	public $colors;

	/**
	 * Valid options.
	 *
	 * This is where we will store valid options so
	 * invalidate_unexplained_options() can only flag options
	 * not registered or explained.
	 *
	 * @see \aubreypwd\PHP_CLI\CLI::invalidate_unexplained_options() For more.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $valid_options = [];

	/**
	 * Construct
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {

		$this->colors = new Colors();

		parent::__construct();
	}

	/**
	 * Does the system have the command?
	 *
	 * @since  1.0.0
	 *
	 * @param  string   $command The command, e.g. `ls`.
	 * @return bool
	 */
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

	/**
	 * Get the running PHP version.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function get_php_version() : string {
		return $this->get_last_line( $this->safe_exec( "php -r 'echo phpversion() . \"\n\";' | sed 's/ *$//g'" ) );
	}

	/**
	 * Get the working directory path.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function get_working_dir() : string {
		return $this->get_last_line( $this->safe_exec( 'pwd' ) );
	}

	/**
	 * Get the working directory basename.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function get_working_dirname() : string {
		return $this->get_last_line( $this->safe_exec( 'echo "${PWD##*/}"' ) );
	}

	/**
	 * Execute a system command (only if the command exists).
	 *
	 * @since  1.0.0
	 *
	 * @param  string   $command The command.
	 * @return array             Data about the execution.
	 */
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

	/**
	 * Get the output of safe_exec().
	 *
	 * @since  1.0.0
	 *
	 * @param  array    $result The result from safe_exec().
	 * @param  string   $as     Set to `string` to get back a string with \n
	 *                          line breaks. Defaults to `array` with an array
	 *                          of lines of the output.
	 * @return string|array     See $as.
	 */
	private function get_output( array $result, string $as = 'array' ) : string|array {

		if ( 'array' !== $string && 'string' !== $as ) {
			throw new \InvalidArgumentException( '$as must be set to array|string.' );
		}

		if ( ! isset( $array['output'] ) || ! is_array( $array['output'] ) ) {
			throw new \InvalidArgumentException( 'We can only work with an array from exec().' );
		}

		return 'array' === $as ? $array['output'] : implode( "\n", $output );
	}

	/**
	 * Get the last line of safe_exec().
	 *
	 * @since  1.0.0
	 *
	 * @param  array    $exec Result of safe_exec().
	 * @return string
	 */
	private function get_last_line( array $exec ) : string {
		return isset( $exec['last_line'] ) ? trim( $exec['last_line'] ) : '';
	}

	/**
	 * Get argument of position.
	 *
	 * @since  1.0.0
	 *
	 * @param  Options  $options  Options.
	 * @param  int      $position Position.
	 * @return string             The value of the argument in that position.
	 */
	protected function get_arg( Options $options, int $position ) : string {

		$args = $options->getArgs();

		return isset( $args[ $position ] )
			? $args[ $position ]
			: '';
	}

	/**
	 * Is an argument present (in any position)?
	 *
	 * @since  1.0.0
	 *
	 * @param  Options  $options Options.
	 * @param  string   $arg     Argument name.
	 * @return bool
	 */
	protected function arg_present( Options $options, string $arg ) : bool {
		return in_array( $arg, $this->get_args( $options ), true );
	}

	/**
	 * Get all the arguments.
	 *
	 * @since  1.0.0
	 *
	 * @param  Options  $options Options.
	 * @return array
	 */
	protected function get_args( Options $options ) : array {
		return $options->getArgs();
	}

	/**
	 * Explain/Register an argument.
	 *
	 * @since  1.0.0
	 *
	 * @param  Options  $options  Options.
	 * @param  string   $arg      The argument name.
	 * @param  string   $help     The explanation of the argument.
	 * @param  bool     $required Is the argument required?
	 * @param  string   $command  Command
	 * @return void
	 */
	protected function explain_argument( Options $options, string $arg, string $help, bool $required = true, string $command = '' ) : void {
		$options->registerArgument( $arg, $help, $required, $command );
	}

	/**
	 * Set the description of the command.
	 *
	 * @since 1.0.0
	 *
	 * @param Options  $options Options.
	 * @param string   $help    Description string.
	 */
	protected function set_desc( Options $options, string $help ) : void {
		$options->setHelp( $help );
	}

	/**
	 * Explain/Register an option.
	 *
	 * @since  1.0.0
	 *
	 * @param  Options    $options  Options.
	 * @param  string     $long     Long version.
	 * @param  string     $help     Help contents.
	 * @param  mixed|null $short    Short version (optional).
	 * @param  bool       $needsarg Does it require an argument.
	 * @param  string     $command  Command.
	 * @return void
	 */
	protected function explain_option( Options $options, string $long, string $help, mixed $short = null, bool $needsarg = false, string $command = '' ) : void {

		$options->registerOption( $long, $help, $short, $needsarg, $command );

		$this->valid_options[ $long ] = $short;
	}

	/**
	 * Get the value of an option of if an option is set.
	 *
	 * @since  1.0.0
	 *
	 * @param  Options  $options Options.
	 * @param  string   $option  Option.
	 * @return bool|string True or false if it is or is not set.
	 *                     String if it's set to a value, e.g. --flag=.
	 */
	protected function get_opt( Options $options, $option ) : bool|string {
		return $options->getOpt( $option );
	}

	/**
	 * Show the help.
	 *
	 * @since  1.0.0
	 *
	 * @param  Options  $options Options.
	 * @return void
	 */
	protected function show_help( Options $options ) : void {
		echo $options->help();
	}

	/**
	 * Log something out to console.
	 *
	 * This method is a bit of a hack to do things like:
	 *
	 *     $this->log( "A generic message to the console." );
	 *
	 * That way you can express something out without having to format
	 * it using debug, info, warning, etc.
	 *
	 * But, if you want to use those designations (see parent::$logLevel),
	 * you can by setting $level normally to a log level and using
	 * $message instead.
	 *
	 * @since  1.0.0
	 *
	 * @param  string   $level   If you set this to anything in
	 *                           parent::$logLevel we'll use parent::logMessage()
	 *                           and forward your $message to the console.
	 *                           But if it is not, we will treat $level like the
	 *                           message (ignoring $message) to ouput that to
	 *                           the console.
	 * @param  string   $message If you set $level to anything in
	 *                           parent::$logLevel then we will treat $message as
	 *                           the message and forward it along to
	 *                           parent::logMessage().
	 * @param  array    $context Conect (see parent::logMessage()).
	 * @return void
	 */
	public function log( $level = '', $message = '', array $context = array() ) {

		if ( in_array( $level, array_keys( $this->loglevel ), true ) ) {

			// $level is set to something specific, use that.
			$this->logMessage($level, $message, $context);

			return;
		}

		// Just log out some text.
		echo $this->clog( "{$level}\n" );
	}

	/**
	 * Colorize a message.
	 *
	 * @since  [-NEXT-]
	 *
	 * @param  string   $message The message with colors in HTML tags, e.g.
	 *                           "This is <red>bad</red>".
	 * @return string            Colorized message,
	 */
	public function clog( string $message ) : string {

		foreach ( [
			'reset',
			'black',
			'darkgray',
			'blue',
			'lightblue',
			'green',
			'lightgreen',
			'cyan',
			'lightcyan',
			'red',
			'lightred',
			'purple',
			'lightpurple',
			'brown',
			'yellow',
			'lightgray',
			'white',
		] as $color ) {

			$message = str_replace(
				array(
					"<$color>",
					"</$color>",
				),
				array(
					$this->colors->getColorCode( $color ),
					$this->colors->getColorCode( 'reset' )
				),
				$message
			);

		}


		return $message;
	}

	/**
	 * Log a line break.
	 *
	 * Just a quicker way to ouput a blank line.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	protected function lb( $return = false ) {
		$this->log( '' );
	}
}
