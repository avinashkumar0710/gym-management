<?php
require_once '../config/database.php';
require_once '../config/functions.php';

if (isset($_POST['mark_attendance'])) {
    $member_id = (int)$_POST['member_id'];
    $date = sanitize($_POST['date']);
    $status = sanitize($_POST['status']);
    
    $check = $conn->query("SELECT id FROM attendance WHERE member_id = $member_id AND date = '$date'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE attendance SET status = '$status' WHERE member_id = $member_id AND date = '$date'");
    } else {
        $conn->query("INSERT INTO attendance (member_id, date, status) VALUES ($member_id, '$date', '$status')");
    }
    redirect('attendance.php');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM attendance WHERE id = $id");
    redirect('attendance.php');
}

$today = date('Y-m-d');
$attendance = $conn->query("SELECT a.*, m.name as member_name FROM attendance a LEFT JOIN members m ON a.member_id = m.id WHERE a.date = '$today' ORDER BY a.id DESC");
$members = $conn->query("SELECT * FROM members WHERE status = 'active'");
$page_title = "Attendance Management";
?>
<?php require_once 'header.php'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2>Attendance Management - <?php echo formatDate($today); ?></h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Mark Attendance
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Member Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($attendance->num_rows > 0): ?>
                <?php while($row = $attendance->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['member_name']; ?></td>
                    <td><?php echo formatDate($row['date']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $row['status']=='present'?'success':'danger'; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No attendance records for today</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Attendance</h5>
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
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="<?php echo $today; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="mark_attendance" class="btn btn-primary">Mark Attendance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
