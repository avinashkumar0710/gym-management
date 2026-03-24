<?php
require_once '../config/database.php';
require_once '../config/functions.php';

if (isset($_POST['add_plan'])) {
    $name = sanitize($_POST['name']);
    $duration = (int)$_POST['duration'];
    $price = (float)$_POST['price'];
    $description = sanitize($_POST['description']);
    
    $sql = "INSERT INTO plans (name, duration, price, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sids", $name, $duration, $price, $description);
    $stmt->execute();
    redirect('plans.php');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM plans WHERE id = $id");
    redirect('plans.php');
}

$plans = $conn->query("SELECT * FROM plans ORDER BY price ASC");
$page_title = "Membership Plans";
?>
<?php require_once 'header.php'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2>Membership Plans</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Add Plan
    </button>
</div>

<div class="row">
    <?php while($row = $plans->fetch_assoc()): ?>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4><?php echo $row['name']; ?></h4>
            </div>
            <div class="card-body text-center">
                <h2 class="text-primary">$<?php echo number_format($row['price'], 2); ?></h2>
                <p class="text-muted"><?php echo $row['duration']; ?> days</p>
                <p><?php echo $row['description']; ?></p>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this plan?')">
                    <i class="fas fa-trash"></i> Delete
                </a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Plan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Plan Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Duration (days)</label>
                        <input type="number" name="duration" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Price ($)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_plan" class="btn btn-primary">Add Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
