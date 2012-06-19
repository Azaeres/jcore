<?php

require_once('../inc.php');
require_once('php-markdown/markdown.php');


class DocumentationViewer extends HtmlDoc {
	public function js() {}
	public function css() {
		?>
			<link rel="stylesheet" href="<?php echo $this->root; ?>css/style.css">
			<link rel="stylesheet" href="<?php echo $this->root; ?>css/docs.css">
		<?php
	}
	public function title() {
		echo 'Documentation';
	}
	public function desc() {
		$this->title();
	}
	public function author() {
		echo 'Ryan Barry';
	}
	public function main() {
		foreach (glob("*.md") as $filename) {
			$markdown = file_get_contents($filename);
			
			?>
			<div class="docblock">
				<?php echo Markdown($markdown); ?>
			</div>
			<?php
		}

	}
}

$doc = new DocumentationViewer('../');

$doc->render();
