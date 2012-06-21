<?php

include(JCORE_PROJECT.'libs/php/jcore-server.inc.php');

//
// After building, follow these steps:
//
// 1. Set DEBUG to 0 here.
define('DEBUG', 1);

// 2. Search for where DEBUG is used and follow the instructions there.
//


class jCore extends HtmlDoc {
	public function title() {
		echo 'jCore v0.0.2';
	}
	public function desc() {
		$this->title();
	}
	public function author() {
		echo 'Ryan Barry';
	}
	public function css() {
		if (!DEBUG) {
			// Replace XXXX below with the name of your built css file.
			?>
				<link rel="stylesheet" href="<?php echo $this->root; ?>app/css/XXXX.css">
			<?php
		}
		else {
			?>
				<link rel="stylesheet" href="<?php echo $this->root; ?>app/css/style.css">
			<?php
		}
		?>
			<link rel="stylesheet" href="<?php echo $this->root; ?>css/jcore.css">
		<?php
	}
	public function js() {
		?>
		<script src="<?php echo $this->root; ?>libs/js/json3.min.js"></script>
		<script src="<?php echo $this->root; ?>libs/js/jshash-2.2/sha256-min.js"></script>
		<script src="<?php echo $this->root; ?>libs/js/knockout-2.1.0.js"></script>
		<script src="<?php echo $this->root; ?>libs/js/knockout.mapping-2.1.2.js"></script>
		<script src="<?php echo $this->root; ?>libs/js/jcore-client.js"></script>
		<?php

		if (!DEBUG) {
			// Replace XXXX below with the name of your built js file.
			?>
				<script src="<?php echo $this->root; ?>app/js/XXXX.js"></script>
			<?php
		}
		else {
			?>
				<script src="<?php echo $this->root; ?>app/js/plugins.js"></script>
				<script src="<?php echo $this->root; ?>app/js/script.js"></script>
			<?php
		}
	}
	public function main() {
		/*
			Server time: <span data-bind='text: datetime.month'>Syncing...</span> <span data-bind='text: datetime.mday'></span> <span data-bind='text: datetime.hours'>0</span>:<span data-bind='text: datetime.minutes'>00</span>:<span data-bind='text: datetime.seconds'>00</span>.
		 */
		?>
			<div class="box">
				<code data-bind="text: resource.text"></code><br/>
				<code data-bind="text: test.foo"></code>
			</div>
			<code class="debug" data-bind="text: ko.toJSON(viewModel)"></code>
		<?php
	}
}

$doc = new jCore();

$doc->render();
