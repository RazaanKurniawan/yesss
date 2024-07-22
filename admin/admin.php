<?php include('includes/header.php'); 
if ($_SESSION['loggedInUser']['level'] != 'Admin' && $_SESSION['loggedInUser']['level'] != 'Manajer') {
    echo '<script>window.location.href = "index.php";</script>';
    exit(); // Tambahkan exit untuk memastikan skrip berhenti setelah redirect
}
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header text-center">
            <h4 class="mb-0">
                <?php if ($_SESSION['loggedInUser']['level'] == 'Admin') : ?>
                    Administrator/Pekerja
                    <a href="admin-create.php" class="btn btn-success float-end"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Admin</a>
                <?php elseif ($_SESSION['loggedInUser']['level'] == 'Manajer') : ?>
                    Pekerja
                    <a href="admin-create.php" class="btn btn-success float-end"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Staff</a>
                <?php endif; ?>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            
            <?php
            $admin = getAll('admin');
            if (!$admin) {
                echo '<h4>Ada sesuatu yang salah!</h4>';
                return false;
            }

            if (mysqli_num_rows($admin) > 0) {
                $loggedInUserLevel = $_SESSION['loggedInUser']['level'];
                $adminUsers = [];
                $managerUsers = [];
                $staffUsers = [];

                // Pisahkan akun berdasarkan level
                foreach ($admin as $adminItem) {
                    if ($adminItem['level'] == 'Admin') {
                        $adminUsers[] = $adminItem;
                    } elseif ($adminItem['level'] == 'Manajer') {
                        $managerUsers[] = $adminItem;
                    } elseif ($adminItem['level'] == 'Staff') {
                        $staffUsers[] = $adminItem;
                    }
                }
                ?>
                <div class="table-responsive">
                    <?php if ($_SESSION['loggedInUser']['level'] == 'Admin') : ?>
                        <h5>Admin</h5>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">No.</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Bagian</th>
                                    <th>Status</th>
                                    <th style="width: 145px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                foreach ($adminUsers as $adminItem): 
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?= $adminItem['nama']; ?> <?php if ($adminItem['id'] == $_SESSION['loggedInUser']['user_id']) echo "<span class='badge bg-primary'>Sedang Login</span>"; ?></td>
                                        <td><?= $adminItem['email']; ?></td>
                                        <td><?= $adminItem['level']; ?></td>
                                        <td>
                                            <?php if ($adminItem['is_ban'] == 1): ?>
                                                <span class="badge bg-danger">Banned</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($adminItem['id'] == $_SESSION['loggedInUser']['user_id']): ?>
                                                <a href="admin-edit.php?id=<?= $adminItem['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <?php elseif ($_SESSION['loggedInUser']['level'] == 'Admin'): ?>
                                                <a href="admin-edit.php?id=<?= $adminItem['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <h5>Manajer</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px;">No.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Bagian</th>
                                <th>Status</th>
                                <th style="width: 145px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $j = 1;
                            foreach ($managerUsers as $managerItem): 
                                ?>
                                <tr>
                                    <td><?php echo $j++; ?></td>
                                    <td><?= $managerItem['nama']; ?> <?php if ($managerItem['id'] == $_SESSION['loggedInUser']['user_id']) echo "<span class='badge bg-primary'>Sedang Login</span>"; ?></td>
                                    <td><?= $managerItem['email']; ?></td>
                                    <td><?= $managerItem['level']; ?></td>
                                    <td>
                                        <?php if ($managerItem['is_ban'] == 1): ?>
                                            <span class="badge bg-danger">Banned</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($managerItem['id'] == $_SESSION['loggedInUser']['user_id']): ?>
                                            <a href="admin-edit.php?id=<?= $managerItem['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <?php elseif ($_SESSION['loggedInUser']['level'] == 'Admin'): ?>
                                            <a href="admin-edit.php?id=<?= $managerItem['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a href="admin-delete.php?id=<?= $managerItem['id']; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <h5>Staff</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px;">No.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Bagian</th>
                                <th>Status</th>
                                <th style="width: 145px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $k = 1;
                            foreach ($staffUsers as $staffItem): 
                                ?>
                                <tr>
                                    <td><?php echo $k++; ?></td>
                                    <td><?= $staffItem['nama']; ?></td>
                                    <td><?= $staffItem['email']; ?></td>
                                    <td><?= $staffItem['level']; ?></td>
                                    <td>
                                        <?php if ($staffItem['is_ban'] == 1): ?>
                                            <span class="badge bg-danger">Banned</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="admin-edit.php?id=<?= $staffItem['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <a href="admin-delete.php?id=<?= $staffItem['id']; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <h4 class="mb-0">Data tidak ditemukan!</h4>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
