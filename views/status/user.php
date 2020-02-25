<?php $this->setLayoutVar('title', $user['user_name']) ?>

<h2><?php echo $this->escape($user['user_name']); ?></h2>

<div>
    <?php foreach ($statuses as $status): ?>
    <?php echo $this->render('status/status', array('status' => $status)); ?>
    <?php endforeach; ?>
</div>