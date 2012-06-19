<?php

include('inc.php');

//
// After building, follow these steps:
//
// 1. Set DEBUG to 0 here.
define('DEBUG', 1);

// 2. Search for where DEBUG is used and follow the instructions there.
//


class jCore extends HtmlDoc {
	public function js() {
		?>
		<script src="<?php echo $this->root; ?>js/libs/json3.min.js"></script>
		<script src="<?php echo $this->root; ?>js/libs/jshash-2.2/sha256-min.js"></script>
		<script src="<?php echo $this->root; ?>js/libs/knockout-2.1.0.js"></script>
		<script src="<?php echo $this->root; ?>js/libs/knockout.mapping-2.1.2.js"></script>
		<script src="<?php echo $this->root; ?>js/libs/jcore-client-0.0.1.js"></script>
		<?php

		if (!DEBUG) {
			// Replace XXXX below with the name of your built js file.
			?>
				<script src="<?php echo $this->root; ?>js/XXXX.js"></script>
			<?php
		}
		else {
			?>
				<script src="<?php echo $this->root; ?>js/plugins.js"></script>
				<script src="<?php echo $this->root; ?>js/script.js"></script>
			<?php
		}
	}
	public function css() {
		if (!DEBUG) {
			?>
				<link rel="stylesheet" href="<?php echo $this->root; ?>css/XXXX.css">
			<?php
		}
		else {
			?>
				<link rel="stylesheet" href="<?php echo $this->root; ?>css/style.css">
			<?php
		}
	}
	public function title() {
		echo 'jCore v0.0.2';
	}
	public function desc() {
		$this->title();
	}
	public function author() {
		echo 'Ryan Barry';
	}
	public function main() {
		/*
			Server time: <span data-bind='text: datetime.month'>Syncing...</span> <span data-bind='text: datetime.mday'></span> <span data-bind='text: datetime.hours'>0</span>:<span data-bind='text: datetime.minutes'>00</span>:<span data-bind='text: datetime.seconds'>00</span>.
<code data-bind="text: test.foo"></code>
		 */
		?>
			<div class="box">
				<code data-bind="text: resource.text"></code><br/>
				
			</div>
			<code class="debug" data-bind="text: ko.toJSON(viewModel)"></code>
		<?php
	}
}

$doc = new jCore();

$doc->render();


