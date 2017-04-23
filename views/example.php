<?php
/**
 * Every Dudley module that meets its data requirements exposes the `$module` variable when it renders. By adding the
 * class name at the top of the view file, your IDE will be able to recognize which public methods are available for
 * rendering that data.
 *
 * @var $module \Dudley\Patterns\Pattern\Example\Example
 */
?>

<div class="dudley-example">
	<h1 class="dudley-example__hd"><?php $module->heading(); ?></h1>
</div>
