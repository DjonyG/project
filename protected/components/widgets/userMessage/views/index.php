<?php if(UserMessage::has()): ?>
    <div class="notification">
        <?php foreach(UserMessage::getAll() as $msg): ?>
            <div class="alert alert-<?php echo $msg->getClass(); ?>" id="msg_<?php echo $msg->id; ?>">
                <?php if($msg->is_closable): ?>
                    <a href="javascript:void(0)" onclick="closeNotification(<?php echo $msg->id; ?>)" class="close">
                        <?php echo Html::image('images/site/close-small.png'); ?>
                    </a>
                <?php endif; ?>
                <?php echo $msg->message; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
