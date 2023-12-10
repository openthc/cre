<?php
/**
 *
 */

?>

<div class="container">
<table class="table">
	<tr>
		<td>ID</td><td><?= __h($data['object']['id']) ?></td>
	</tr>
	<tr>
		<td>License</td>
		<td><?= __h($data['object']['license_id']) ?></td>
	</tr>
	<?php
	if ( ! empty($data['License'])) {
	?>
		<tr>
			<td></td>
			<td><?= __h($data['License']['name']) ?></td>
		</tr>
	<?php
	}
	?>

	<tr>
		<td>Section</td><td><?= __h($data['object']['section_id']) ?></td>
	</tr>
	<?php
	if ( ! empty($data['Section'])) {
	?>
		<tr>
			<td></td>
			<td><?= __h($data['Section']['name']) ?></td>
		</tr>
	<?php
	}
	?>

	<tr>
		<td>Variety</td><td><?= __h($data['object']['variety_id']) ?></td>
	</tr>
	<?php
	if ( ! empty($data['Variety'])) {
	?>
		<tr>
			<td></td>
			<td><?= __h($data['Variety']['name']) ?></td>
		</tr>
	<?php
	}
	?>

	<?php
	if ( ! empty($data['object']['product_id'])) {
	?>
		<tr>
			<td>Product</td><td><?= __h($data['object']['product_id']) ?></td>
		</tr>
		<?php
		if ( ! empty($data['Product'])) {
		?>
			<tr>
				<td></td>
				<td><?= __h($data['Product']['name']) ?></td>
			</tr>
		<?php
		}
	}
	?>



	<?php
	if (isset($data['object']['qty'])) {
	?>
		<tr>
			<td>Quantity</td><td><?= __h($data['object']['qty']) ?></td>
		</tr>
	<?php
	}
	?>
	<tr>
		<td>Status</td><td><?= __h($data['object']['stat']) ?></td>
		<td>
			<form method="post">
				<div class="btn-group btn-group-sm">
					<button class="btn" name="a" value="object-hold">Hold <i class="fa-solid fa-lock"></i></button>
				</div>
			</form>
	</tr>
	<tr>
		<td>Created</td><td><?= __h($data['object']['created_at']) ?></td>
	</tr>
	<tr>
		<td>Updated</td><td><?= __h($data['object']['updated_at']) ?></td>
	</tr>
	<tr>
		<td>Deleted</td><td><?= __h($data['object']['deleted_at']) ?></td>
	</tr>
</table>
</div>



<?php

echo '<pre>';
echo __h(json_encode($data['object'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
echo '</pre>';
