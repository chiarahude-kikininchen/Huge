<div class="container">
    <h1>Admin/index</h1>

    <div class="box">

        <?php $this->renderFeedbackMessages(); ?>

        <h3>User administration</h3>

        <div>
            Change user groups here!
        </div>

        <div>
            <table class="overview-table">
                <thead>
                <tr>
                    <td>Id</td>
                    <td>Username</td>
                    <td>User's email</td>
                    <td>Group</td>
                    <td>Group Change</td>
                    <td>Submit</td>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->users as $user) { ?>
                    <tr>
                        <td><?= $user->user_id; ?></td>
                        <td><?= $user->user_name; ?></td>
                        <td><?= $user->user_email; ?></td>
                        <td><?= $user->group_name; ?></td>

                        <form action="<?= Config::get("URL"); ?>admin/actionChangeUserGroup" method="post">
                            <td>
                                <select name="user_account_type">
                                    <option value="1" <?= ($user->user_account_type == 1 ? 'selected' : ''); ?>>Gast</option>
                                    <option value="2" <?= ($user->user_account_type == 2 ? 'selected' : ''); ?>>Normaler User</option>
                                    <option value="3" <?= ($user->user_account_type == 3 ? 'selected' : ''); ?>>Gruppe 3</option>
                                    <option value="4" <?= ($user->user_account_type == 4 ? 'selected' : ''); ?>>Gruppe 4</option>
                                    <option value="5" <?= ($user->user_account_type == 5 ? 'selected' : ''); ?>>Gruppe 5</option>
                                    <option value="6" <?= ($user->user_account_type == 6 ? 'selected' : ''); ?>>Gruppe 6</option>
                                    <option value="7" <?= ($user->user_account_type == 7 ? 'selected' : ''); ?>>Admin</option>
                                </select>
                            </td>

                            <td>
                                <input type="hidden" name="user_id" value="<?= $user->user_id; ?>" />
                                <input type="submit" value="Senden" />
                            </td>
                        </form>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>