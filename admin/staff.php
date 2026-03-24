<?php
require_once '../config/database.php';
require_once '../config/functions.php';

if (isset($_POST['add_staff'])) {
    $name = sanitize($_POST['name']);
    $role = sanitize($_POST['role']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $salary = (float)$_POST['salary'];
    $join_date = sanitize($_POST['join_date']);
    
    $sql = "INSERT INTO staff (name, role, email, phone, salary, join_date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssds", $name, $role, $email, $phone, $salary, $join_date);
    $stmt->execute();
    redirect('staff.php');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM staff WHERE id = $id");
    redirect('staff.php');
}

$staff = $conn->query("SELECT * FROM staff ORDER BY created_at DESC");
$page_title = "Staff Management";
?>
<?php require_once 'header.php'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2>Staff Management</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Add Staff
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Salary</th>
                    <th>Join Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $staff->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><span class="badge badge-info"><?php echo $row['role']; ?></span></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td>$<?php echo number_format($row['salary'], 2); ?></td>
                    <td><?php echo formatDate($row['join_date']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff?')">
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
                <h5 class="modal-title">Add New Staff</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="Trainer">Trainer</option>
                            <option value="Receptionist">Receptionist</option>
                            <option value="Manager">Manager</option>
                            <option value="Cleaner">Cleaner</option>
                            <option value="Security">Security</option>
                        </select>
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
                        <label>Salary ($)</label>
                        <input type="number" step="0.01" name="salary" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Join Date</label>
                        <input type="date" name="join_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_staff" class="btn btn-primary">Add Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
