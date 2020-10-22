<h2>商品を出品する</h2>
<?= $this->Form->create($biditem, [
	'type' => 'file'
]) ?>
<fieldset>
	<legend>※商品名と終了日時を入力：</legend>
	<?php
	echo $this->Form->hidden('user_id', ['value' => $authuser['id']]);
	echo '<p><strong>USER: ' . $authuser['username'] . '</strong></p>';
	echo $this->Form->control('name');
	echo $this->Form->hidden('finished', ['value' => 0]);
	echo $this->Form->control('endtime');
	echo $this->Form->input('detail', [
		'type' => 'textarea',
		'before' => 'detail',
		'maxLength' => 1000
	]);
	echo $this->Form->error('detail');
	echo $this->Form->input('picture_name', [
		'type' => 'file'
	]);
	if ($biditem['save_error'] === 1) {
		echo 'もう一度選択してください。<br>';
	}
	if ($biditem['picture_name_error'] === 1) {
		echo '拡張子が必ず.jpg, .jpeg, .png, .gifのいずれかのファイルを選択してください。';
	}
	?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
