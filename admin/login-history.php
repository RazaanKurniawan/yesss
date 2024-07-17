<?php
include('includes/header.php');
global $conn;

// Sanitizing the GET parameters to prevent SQL injection
$filterLoginTime = isset($_GET['login_time']) ? mysqli_real_escape_string($conn, $_GET['login_time']) : '';
$filterLogoutTime = isset($_GET['logout_time']) ? mysqli_real_escape_string($conn, $_GET['logout_time']) : '';

// Creating conditional filter clauses
$filterClause = '';
if (!empty($filterLoginTime)) {
    $filterClause .= " AND login_history.login_time >= '$filterLoginTime'";
}
if (!empty($filterLogoutTime)) {
    $filterClause .= " AND login_history.logout_time <= '$filterLogoutTime'";
}

$sql = "
    SELECT 
        login_history.admin_id, 
        admin.nama AS admin_nama,
        admin.level AS admin_level, 
        login_history.login_time, 
        login_history.logout_time 
    FROM 
        login_history 
    JOIN 
        admin 
    ON 
        login_history.admin_id = admin.id
    WHERE 1 $filterClause
    ORDER BY 
        login_history.login_time DESC
";
$result = $conn->query($sql);
?>

<div class="container-fluid px-4">
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="card mt-4">
                    <div class="page-wrapper">
                        <div class="page-body">
                            <div class="card-header">
                                <h1>Login History</h1>
                                <form method="GET" class="form-inline mb-2">
                                    <label for="login_time" class="mr-2">Waktu Aktif:</label>
                                    <input type="datetime-local" id="login_time" name="login_time" value="<?php echo htmlspecialchars($filterLoginTime); ?>" class="form-control mr-2">

                                    <label for="logout_time" class="mr-2">Waktu Keluar:</label>
                                    <input type="datetime-local" id="logout_time" name="logout_time" value="<?php echo htmlspecialchars($filterLogoutTime); ?>" class="form-control mr-2">

                                    <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                    <a href="login-history.php" class="btn btn-danger">Reset</a>
                                </form>
                            </div>
                            <?php if ($result->num_rows > 0): ?>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered mt-2 mb-2" id="myTable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Akun</th>
                                                    <th>Bagian</th>
                                                    <th>Waktu Aktif</th>
                                                    <th>Waktu Keluar</th>
                                                    <th>Durasi Login</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $i = 1;
                                                while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php echo htmlspecialchars($row["admin_nama"]); ?></td>
                                                        <td><?php echo htmlspecialchars($row["admin_level"]); ?></td>
                                                        <td><?php echo htmlspecialchars($row["login_time"]); ?></td>
                                                        <td>
                                                            <?php
                                                            if ($row["logout_time"] === NULL) {
                                                                echo "Sedang Aktif";
                                                            } else {
                                                                echo htmlspecialchars($row["logout_time"]);
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($row["logout_time"] === NULL) {
                                                                echo "Sedang Aktif";
                                                            } else {
                                                                $login_time = new DateTime($row["login_time"]);
                                                                $logout_time = new DateTime($row["logout_time"]);
                                                                $interval = $login_time->diff($logout_time);
                                                                echo $interval->format('%h jam %i menit %s detik');
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="card-body">
                                    <p class="text-center">0 results</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
