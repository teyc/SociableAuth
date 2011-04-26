<h1>User Management</h1>

<form action="<?php echo site_url('/websecurity/search_user'); ?>">
    <p>Search user <input type="text" name="user" value="<?= isset($search)? $search : ''; ?>" /></p>              
</form>

<table>
    <tr>
        <th>UserId</th>
        <th>UserName</th>
        <th>Action</th>
        <th>Administrator</th>
    </tr>

<?php foreach ($users as $user): ?>
    <tr>
        <td>
        <?= $user->UserId ?>
        </td>
        <td>
        <?= $user->UserName ?>
        </td>        
        <td>
            <input type="submit" value="Delete User" />            
        </td>
        <td align="center">
            <input type="checkbox" />
        </td>
    </tr>
<?php endforeach; ?>
</table> 
