<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<div class="container">
    <h1>Benutzer und Gruppen</h1>

    <table id="usersTable" class="display">
        <thead>
        <tr>
            <th>ID</th>
            <th>Benutzername</th>
            <th>E-Mail</th>
            <th>Gruppe</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($this->users as $user) { ?>
            <tr>
                <td><?php echo $user->user_id; ?></td>
                <td><?php echo $user->user_name; ?></td>
                <td><?php echo $user->user_email; ?></td>
                <td><?php echo $user->group_name; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#usersTable').DataTable();
    });
</script>