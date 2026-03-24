<?php
require_once '../config/database.php';
require_once '../config/functions.php';

if (isset($_POST['add_payment'])) {
    $member_id = (int)$_POST['member_id'];
    $amount = (float)$_POST['amount'];
    $payment_date = sanitize($_POST['payment_date']);
    $payment_method = sanitize($_POST['payment_method']);
    
    $sql = "INSERT INTO payments (member_id, amount, payment_date, payment_method, status) VALUES (?, ?, ?, ?, 'paid')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idss", $member_id, $amount, $payment_date, $payment_method);
    $stmt->execute();
    redirect('payments.php');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM payments WHERE id = $id");
    redirect('payments.php');
}

if (isset($_GET['mark_paid'])) {
    $id = (int)$_GET['mark_paid'];
    $conn->query("UPDATE payments SET status = 'paid' WHERE id = $id");
    redirect('payments.php');
}

$payments = $conn->query("SELECT p.*, m.name as member_name FROM payments p LEFT JOIN members m ON p.member_id = m.id ORDER BY p.created_at DESC");
$members = $conn->query("SELECT * FROM members WHERE status = 'active'");
$total_revenue = $conn->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'paid'")->fetch_row()[0];
$page_title = "Payments Management";
?>
<?php require_once 'header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h3>Total Revenue: $<?php echo number_format($total_revenue, 2); ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between mb-3">
    <h2>Payments Management</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Record Payment
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Member</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $payments->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['member_name'] ?? 'N/A'; ?></td>
                    <td>$<?php echo number_format($row['amount'], 2); ?></td>
                    <td><?php echo formatDate($row['payment_date']); ?></td>
                    <td><?php echo $row['payment_method']; ?></td>
                    <td><span class="badge badge-<?php echo getStatusClass($row['status']); ?>"><?php echo ucfirst($row['status']); ?></span></td>
                    <td>
                        <?php if($row['status'] == 'pending'): ?>
                        <a href="?mark_paid=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i>
                        </a>
                        <?php endif; ?>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')">
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
                <h5 class="modal-title">Record Payment</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Member</label>
                        <select name="member_id" class="form-control" required>
                            <option value="">Select Member</option>
                            <?php while($member = $members->fetch_assoc()): ?>
                            <option value="<?php echo $member['id']; ?>"><?php echo $member['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Amount ($)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="Cash">Cash</option>
                            <option value="Card">Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_payment" class="btn btn-primary">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
