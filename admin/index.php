<?php
require_once '../config/database.php';
require_once '../config/functions.php';

$total_members = $conn->query("SELECT COUNT(*) FROM members")->fetch_row()[0];
$active_members = $conn->query("SELECT COUNT(*) FROM members WHERE status = 'active'")->fetch_row()[0];
$total_staff = $conn->query("SELECT COUNT(*) FROM staff")->fetch_row()[0];
$total_equipment = $conn->query("SELECT COUNT(*) FROM equipment")->fetch_row()[0];
$total_revenue = $conn->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'paid'")->fetch_row()[0];

$recent_members = $conn->query("SELECT * FROM members ORDER BY created_at DESC LIMIT 5");
$recent_payments = $conn->query("SELECT p.*, m.name as member_name FROM payments p LEFT JOIN members m ON p.member_id = m.id ORDER BY p.created_at DESC LIMIT 5");
?>
<?php require_once 'header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5><i class="fas fa-users"></i> Total Members</h5>
                <h2><?php echo $total_members; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5><i class="fas fa-user-check"></i> Active Members</h5>
                <h2><?php echo $active_members; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5><i class="fas fa-user-tie"></i> Staff</h5>
                <h2><?php echo $total_staff; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5><i class="fas fa-dollar-sign"></i> Revenue</h5>
                <h2>$<?php echo number_format($total_revenue, 2); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Members</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $recent_members->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><span class="badge badge-<?php echo getStatusClass($row['status']); ?>"><?php echo $row['status']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Payments</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $recent_payments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['member_name'] ?? 'N/A'; ?></td>
                            <td>$<?php echo number_format($row['amount'], 2); ?></td>
                            <td><span class="badge badge-<?php echo getStatusClass($row['status']); ?>"><?php echo $row['status']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
