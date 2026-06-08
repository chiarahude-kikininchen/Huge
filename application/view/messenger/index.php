<div class="container">
    <h1>Messenger</h1>

    <div class="messenger-layout">

        <aside class="user-sidebar">
            <h3>Users</h3>

            <?php foreach ($this->users as $user) : ?>
                <?php if ($user->user_id != Session::get('user_id')) : ?>
                    <a class="user-item <?= ($this->toUserId == $user->user_id) ? 'selected-user' : ''; ?>"
                       href="<?= Config::get('URL'); ?>messenger/index/<?= htmlentities($user->user_id); ?>">
                        <div class="avatar">
                            <?= strtoupper(substr($user->user_name, 0, 1)); ?>
                        </div>
                        <div>
                            <strong><?= htmlentities($user->user_name); ?></strong>
                            <small>
                                <?= ($this->toUserId == $user->user_id) ? 'Currently chatting' : 'Click to chat'; ?>
                            </small>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </aside>

        <main class="chat-area">
            <?php foreach ($this->messages as $message) : ?>
                <?php
                $bubbleClass = ($message->from_user_id == Session::get('user_id'))
                        ? 'sender'
                        : 'recipient';
                ?>

                <div class="bubble <?= $bubbleClass ?>">
                    <?= htmlentities($message->content_text); ?>
                </div>
            <?php endforeach; ?>

            <?php if (!empty($this->toUserId)) : ?>
                <form class="message-form" method="post" action="<?= Config::get('URL'); ?>messenger/sendMessage">
                    <input type="hidden" name="chat_id" value="<?= htmlentities($this->chatId); ?>">
                    <input type="hidden" name="to_user_id" value="<?= htmlentities($this->toUserId); ?>">

                    <input type="text" name="content_text" placeholder="Write a message ..." required>
                    <button type="submit">Send</button>
                </form>
            <?php else : ?>
                <p class="choose-user">Choose a user to start chatting.</p>
            <?php endif; ?>
        </main>

    </div>
</div>

<style>
    .messenger-layout {
        display: flex;
        max-width: 900px;
        margin: 20px auto;
        border-top: 1px solid #ddd;
    }

    .user-sidebar {
        width: 260px;
        border-right: 1px solid #ddd;
        padding: 15px;
    }

    .user-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        text-decoration: none;
        color: #333;
        border-bottom: 1px solid #eee;
        border-radius: 12px;
    }

    .user-item:hover {
        background: #f2f2f2;
    }

    .user-item.selected-user {
        background: #e8f0ff;
    }

    .user-item.selected-user .avatar {
        background: #2f63d8;
    }

    .user-item.selected-user strong {
        color: #2f63d8;
    }

    .avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: cornflowerblue;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .user-item small {
        display: block;
        color: #888;
        margin-top: 3px;
    }

    .chat-area {
        flex: 1;
        padding: 25px;
        display: flex;
        flex-direction: column;
    }

    .bubble {
        border-radius: 14px;
        padding: 8px 12px;
        margin: 6px 0;
        max-width: 55%;
    }

    .bubble.sender {
        align-self: flex-end;
        background: cornflowerblue;
        color: white;
    }

    .bubble.recipient {
        align-self: flex-start;
        background: #efefef;
    }

    .message-form {
        display: flex;
        gap: 8px;
        margin-top: 20px;
    }

    .message-form input {
        flex: 1;
        padding: 8px;
    }

    .message-form button {
        padding: 8px 14px;
    }

    .choose-user {
        color: #888;
    }
</style>