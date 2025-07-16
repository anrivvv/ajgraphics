<?php
session_start();
if ($_SESSION['username'] !== 'admin') {
    header('Location: home.php');
    exit;
}

include_once "get_db.inc.php";

// Get all users
$query = "SELECT * FROM users";
$stmt = $pdo->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - AJ Graphics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #32CD32;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-bg: #f8f9fc;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #4a4a4a;
            display: flex;
            min-height: 100vh;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            transition: all 0.3s;
        }
        
        .user-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .page-title {
            font-weight: 600;
            color: #2e3a4d;
            margin-bottom: 0;
        }
        
        .table-responsive {
            border-radius: 0.35rem;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--light-bg);
            color: #4a4a4a;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom-width: 1px;
            padding: 1rem 1.25rem;
        }
        
        .table tbody td {
            padding: 1.25rem;
            vertical-align: middle;
            border-color: #e3e6f0;
        }
        
        .table tbody tr:hover {
            background-color: rgba(50, 205, 50, 0.05);
        }
        
        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
            margin-right: 0.5rem;
        }
        
        .alert {
            border-radius: 0.35rem;
            padding: 1rem 1.5rem;
        }
        
        .user-role {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .role-admin {
            background-color: rgba(50, 205, 50, 0.1);
            color: var(--primary-color);
        }
        
        .role-user {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success-color);
        }
        
        .no-users {
            text-align: center;
            padding: 3rem;
            color: #6e707e;
        }
        
        .no-users i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d3e2;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .user-container {
                padding: 1.5rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .table thead {
                display: none;
            }
            
            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }
            
            .table tr {
                margin-bottom: 1.5rem;
                border: 1px solid #e3e6f0;
                border-radius: 0.35rem;
                overflow: hidden;
            }
            
            .table td {
                padding: 0.75rem;
                text-align: right;
                position: relative;
                padding-left: 50%;
            }
            
            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                width: calc(50% - 1rem);
                padding-right: 1rem;
                font-weight: 600;
                text-align: left;
                color: #4a4a4a;
            }
            
            .action-buttons {
                display: flex;
                justify-content: flex-end;
            }
        }
        
        /* Modal styles */
        .modal-content {
            border: none;
            border-radius: 0.5rem;
        }
        
        .modal-header {
            border-bottom: 1px solid #e3e6f0;
            padding: 1.25rem 1.5rem;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .form-label {
            font-weight: 500;
            color: #4a4a4a;
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6e707e;
        }
        
        .password-input-container {
            position: relative;
        }
    </style>
</head>
<body>
    <?php include_once "sidebar.php"; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <?php include_once "navbar.php"?>

        <div class="user-container">
            <div class="page-header">
                <h1 class="page-title"><i class="bi bi-people-fill me-2"></i>Manage Users</h1>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6">
                                    <div class="no-users">
                                        <i class="bi bi-person-x"></i>
                                        <h4>No Users Found</h4>
                                        <p>There are currently no users registered.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td data-label="ID"><?= htmlspecialchars($user['id']) ?></td>
                                    <td data-label="Username">
                                        <strong><?= htmlspecialchars($user['username']) ?></strong>
                                    </td>
                                    <td data-label="Email">
                                        <a href="mailto:<?= htmlspecialchars($user['email']) ?>">
                                            <?= htmlspecialchars($user['email']) ?>
                                        </a>
                                    </td>
                                    <td data-label="Phone"><?= htmlspecialchars($user['phone_number']) ?></td>
                                    <td data-label="Role">
                                        <span class="user-role <?= $user['username'] === 'admin' ? 'role-admin' : 'role-user' ?>">
                                            <?= $user['username'] === 'admin' ? 'Admin' : 'User' ?>
                                        </span>
                                    </td>
                                    <td data-label="Actions" class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary btn-action" 
                                                onclick="editUser(<?= htmlspecialchars(json_encode($user)) ?>)">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </button>
                                        <?php if ($user['username'] !== 'admin'): ?>
                                            <button class="btn btn-sm btn-outline-danger btn-action" 
                                                    onclick="deleteUser(<?= $user['id'] ?>)">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-gear me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" action="update_user.php" method="POST">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="edit_username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="edit_email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" id="edit_phone_number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" name="role" id="edit_role">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 password-input-container">
                            <label class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" name="password" id="edit_password">
                            <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('edit_password')"></i>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editUser(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_phone_number').value = user.phone_number;
            document.getElementById('edit_role').value = user.username === 'admin' ? 'admin' : 'user';
            
            const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
            modal.show();
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                window.location.href = `delete_user.php?id=${userId}`;
            }
        }
        
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.querySelector(`#${inputId} + .password-toggle`);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }
    </script>
</body>
</html>