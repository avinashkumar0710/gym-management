<?php
require_once '../config/database.php';
require_once '../config/functions.php';

if (isset($_POST['add_equipment'])) {
    $name = sanitize($_POST['name']);
    $quantity = (int)$_POST['quantity'];
    $status = sanitize($_POST['status']);
    $purchase_date = sanitize($_POST['purchase_date']);
    $maintenance_date = sanitize($_POST['maintenance_date']);
    
    $sql = "INSERT INTO equipment (name, quantity, status, purchase_date, maintenance_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisss", $name, $quantity, $status, $purchase_date, $maintenance_date);
    $stmt->execute();
    redirect('equipment.php');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM equipment WHERE id = $id");
    redirect('equipment.php');
}

if (isset($_POST['update_equipment'])) {
    $id = (int)$_POST['equipment_id'];
    $quantity = (int)$_POST['quantity'];
    $status = sanitize($_POST['status']);
    $conn->query("UPDATE equipment SET quantity = $quantity, status = '$status' WHERE id = $id");
    redirect('equipment.php');
}

$equipment = $conn->query("SELECT * FROM equipment ORDER BY created_at DESC");
$page_title = "Equipment Management";
?>
<?php require_once 'header.php'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2>Equipment Management</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Add Equipment
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Purchase Date</th>
                    <th>Last Maintenance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $equipment->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><span class="badge badge-<?php echo getStatusClass($row['status']); ?>"><?php echo ucfirst($row['status']); ?></span></td>
                    <td><?php echo formatDate($row['purchase_date']); ?></td>
                    <td><?php echo formatDate($row['maintenance_date']); ?></td>
                    <td>
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal<?php echo $row['id']; ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this equipment?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>

<div class="modal fade" id="editModal<?php echo $row['id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Equipment</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="equipment_id" value="<?php echo $row['id']; ?>">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="<?php echo $row['quantity']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="available" <?php echo $row['status']=='available'?'selected':''; ?>>Available</option>
                            <option value="maintenance" <?php echo $row['status']=='maintenance'?'selected':''; ?>>Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update_equipment" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Equipment</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Equipment Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="available">Available</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Last Maintenance Date</label>
                        <input type="date" name="maintenance_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_equipment" class="btn btn-primary">Add Equipment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
