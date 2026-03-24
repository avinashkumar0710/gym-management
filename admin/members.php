<?php
require_once '../config/database.php';
require_once '../config/functions.php';

if (isset($_POST['add_member'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $plan_id = (int)$_POST['plan_id'];
    $join_date = sanitize($_POST['join_date']);
    
    $sql = "INSERT INTO members (name, email, phone, address, plan_id, join_date, status) VALUES (?, ?, ?, ?, ?, ?, 'active')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiss", $name, $email, $phone, $address, $plan_id, $join_date);
    $stmt->execute();
    redirect('members.php');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM members WHERE id = $id");
    redirect('members.php');
}

if (isset($_POST['update_status'])) {
    $id = (int)$_POST['member_id'];
    $status = sanitize($_POST['status']);
    $conn->query("UPDATE members SET status = '$status' WHERE id = $id");
    redirect('members.php');
}

$members = $conn->query("SELECT m.*, p.name as plan_name FROM members m LEFT JOIN plans p ON m.plan_id = p.id ORDER BY m.created_at DESC");
$plans = $conn->query("SELECT * FROM plans");
$page_title = "Members Management";
?>
<?php require_once 'header.php'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2>Members Management</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Add Member
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Plan</th>
                    <th>Join Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $members->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['plan_name']; ?></td>
                    <td><?php echo formatDate($row['join_date']); ?></td>
                    <td>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="member_id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="active" <?php echo $row['status']=='active'?'selected':''; ?>>Active</option>
                                <option value="expired" <?php echo $row['status']=='expired'?'selected':''; ?>>Expired</option>
                                <option value="pending" <?php echo $row['status']=='pending'?'selected':''; ?>>Pending</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this member?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Member</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Plan</label>
                        <select name="plan_id" class="form-control" required>
                            <option value="">Select Plan</option>
                            <?php while($plan = $plans->fetch_assoc()): ?>
                            <option value="<?php echo $plan['id']; ?>"><?php echo $plan['name']; ?> - $<?php echo $plan['price']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Join Date</label>
                        <input type="date" name="join_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_member" class="btn btn-primary">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
