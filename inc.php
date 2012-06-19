<?php


/*!
 * Convenience function for var_dump. 
 *
 * Simply calls {@link http://www.php.net/manual/en/function.var-dump.php var_dump} on the argument
 * passed in. Less typing == better.
 *
 * @author Ryan Barry <ryanc.barry@yahoo.com>
 * @version 0.0.1
 * @package jCore
 * 
 */
/*!
 * @function _d
 *
 * Convenience function for var_dump. 
 *  
 * Simply calls {@link http://www.php.net/manual/en/function.var-dump.php var_dump} on the argument
 * passed in. Less typing == better.
 * 
 * @param  misc $arg The PHP variable to dump.
*/
function _d($arg) {
	var_dump($arg);
}

/*!
     @class FunctionChain
     @abstract Simple function chain class.
     @discussion An IOCommandGate instance is an extremely light weight mechanism that
         executes an action on the driver's work-loop...
     @throws foo_exception
     @throws bar_exception
     @namespace I/O Kit (this is just a string)
     @updated 2012-06-17
 */
class FunctionChain {

	private $_ptr = 0;

	/**
	 * A series of anonymous functions.
	 * 
	 * @param array A series of anonymous functions.
	 */
	public $chain = array();

	/**
	 * Creates a function chain.
	 *
	 * @method int __construct() __construct(array $c) Creates a function chain.
	 * @throws  If $c is not an array.
	 * @param  $c A series (array) of anonymous functions (closures) that form the chain.
	 */
	public function __construct($c) {
		if (is_array($c)) 
			$this->chain = $c;
		else
            throw new Exception("Function chain must be initialized to an array of anonymous functions.");
	}

	/**
	 *
	 * Calls the next function in the chain.
	 * 
	 * @method next() Calls the next function in the chain.
	 * 
	 */
	public function next() {
		$this->_ptr++;
		$c = $this;

		if ($this->_ptr < count($this->chain)) {
			if (array_key_exists($this->_ptr, $this->chain)) {
				$this->chain[$this->_ptr]($c);
			}
		}
	}

	/**
	 *
	 * Runs the chain, starting from the first function.
	 * 
	 * @method run() Runs the chain, starting from the first function.
	 * 
	 */
	public function run() {
		$this->_ptr = 0;
		$chain = $this;
		$this->chain[$this->_ptr]($chain);
	}

	public function reset() {
		$this->_ptr = 0;
	}

	public function removeLink($linkIndex) {
		array_splice($this->chain, $linkIndex, 1);
	}
}



/**
 * Inline text element.
 *
 * @since 0.0.1
 * @package jCore
 */
class InlineText extends FunctionChain {
	public $className, $text;

	/**
	 *
	 * Creates a function chain that renders an inline text element.
	 * 
	 * @method __construct() Creates a function chain that renders an inline text element.
	 * 
	 */
	public function __construct() {
		parent::__construct(array(
			// Element.
			function($chain) {
				?><span class="<?php $chain->next(); ?>"><?php $chain->next(); ?></span><?php 
			}
			// CSS class.
			,function($chain) {
				echo 'class-name';
			}
			// Text content.
			,function($chain) {
				echo '[text]';
			}
		));

		$this->className = &$this->chain[1];
		$this->text = &$this->chain[2];
	}
}

/**
 * HTML 5 document template.
 */
class HtmlDoc {
	public $title, $desc, $author, $header, $main, $footer, $js;
	public $root;

	public function __construct($root = '') {
		$this->root = $root;
	}

	public function render() {
			?><!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php $this->title(); ?></title>
	<meta name="description" content="<?php $this->desc(); ?>">
	<meta name="author" content="<?php $this->author(); ?>">

	<meta name="viewport" content="width=device-width">

	<?php $this->css(); ?>

	<script src="<?php echo $this->root; ?>js/libs/modernizr-2.5.3.min.js"></script>
</head>
<body>
	<header><?php $this->header(); ?></header>
	<div role="main"><?php $this->main(); ?></div>
	<footer><?php $this->footer(); ?></footer>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

	<!-- scripts concatenated and minified via ant build script-->
	<?php $this->js(); ?>
	<!-- end scripts-->

	<script>
		var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>

</body>
</html><?php
	}

	public function title() {
		echo 'Untitled';
	}

	public function desc() {}
	public function author() {}
	public function css() {
		?>
			<link rel="stylesheet" href="<?php echo $this->root; ?>css/style.css">
		<?php
	}

	public function header() {}
	public function main() {}
	public function footer() {}
	public function js() {
		?>
			<script src="<?php echo $this->root; ?>js/plugins.js"></script>
			<script src="<?php echo $this->root; ?>js/script.js"></script>
		<?php
	}
}





