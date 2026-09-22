<?php
session_start();
include 'db.php';
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
if(isset($_POST['update'])){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $year = $_POST['year'];
    $status = $_POST['status'];
    $borrowed_by = $_POST['borrowed_by'];
    $due_date = $_POST['due_date'];

    if($due_date === ''){
        $due_date = null;
    }

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, genre=?, year=?, status=?, borrowed_by=?, due_date=? WHERE id=?");
    $stmt->bind_param("sssisssi", $title, $author, $genre, $year, $status, $borrowed_by, $due_date, $id);
    if($stmt->execute()){
   header("Location: dashboard.php?msg=updated");
        exit();
    } else {
        $error = $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book - Library Tracker</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            background: white;
            padding: 25px 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .header-left h1 {
            margin: 0;
            color: #333;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-left h1:before {
            content: "📚";
            font-size: 36px;
        }

        .breadcrumb {
            color: #666;
            font-size: 14px;
            margin-top: 8px;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .back-button {
            background: #6c757d;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-button:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 40px;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f2f5;
            position: relative;
        }

        .form-header h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-header h2:after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: linear-gradient(to right, #667eea, #764ba2);
            border-radius: 2px;
        }

        .book-id {
            background: #f0f2f5;
            color: #666;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            margin-left: 15px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-required:after {
            content: " *";
            color: #ff4757;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e1e5ee;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            box-sizing: border-box;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control:hover {
            border-color: #a5b1fc;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23667eea' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
            padding-right: 45px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #f0f2f5;
        }

        .btn {
            padding: 14px 30px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-update {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            min-width: 150px;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-update:active {
            transform: translateY(0);
        }

        .btn-cancel {
            background: #f8f9fa;
            color: #495057;
            border: 2px solid #e1e5ee;
        }

        .btn-cancel:hover {
            background: #e9ecef;
            border-color: #ced4da;
            transform: translateY(-2px);
        }

        .error-message {
            background: #ffeaea;
            color: #ff4757;
            padding: 15px 20px;
            border-radius: 10px;
            margin-top: 30px;
            border-left: 4px solid #ff4757;
            animation: shake 0.3s ease-in-out;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message:before {
            content: "⚠️";
            font-size: 18px;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .preview-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
            border-left: 4px solid #667eea;
        }

        .preview-card h3 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .preview-card h3:before {
            content: "👁️";
        }

        .preview-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            font-size: 14px;
        }

        .preview-item {
            background: white;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .preview-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .preview-value {
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-available {
            background: #d4edda;
            color: #155724;
        }

        .status-borrowed {
            background: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
                padding: 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .edit-form-container {
                padding: 25px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        /* Loading state for form */
        .form-loading {
            position: relative;
            opacity: 0.7;
        }

        .form-loading:after {
            content: "Updating...";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #667eea;
        }
    </style>
    <script>
        function showLoading() {
            document.querySelector('form').classList.add('form-loading');
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <h1>Edit Book <span class="book-id">ID: #<?php echo str_pad($id, 4, '0', STR_PAD_LEFT); ?></span></h1>
                <div class="breadcrumb">
                    <a href="dashboard.php">Dashboard</a> &raquo; <a href="#">Edit Book</a>
                </div>
            </div>
            <a href="dashboard.php" class="back-button">← Back to Dashboard</a>
        </div>

        <div class="edit-form-container">
            <div class="form-header">
                <h2>✏️ Book Information</h2>
            </div>

            <form method="POST" onsubmit="showLoading()">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-required">Book Title</label>
                        <input type="text" name="title" class="form-control" 
                               value="<?php echo htmlspecialchars($book['title']); ?>" required
                               placeholder="Enter book title">
                    </div>

                    <div class="form-group">
                        <label class="form-required">Author</label>
                        <input type="text" name="author" class="form-control" 
                               value="<?php echo htmlspecialchars($book['author']); ?>" required
                               placeholder="Enter author name">
                    </div>

                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" name="genre" class="form-control" 
                               value="<?php echo htmlspecialchars($book['genre']); ?>"
                               placeholder="e.g., Fiction, Science, History">
                    </div>

                    <div class="form-group">
                        <label>Publication Year</label>
                        <input type="number" name="year" class="form-control" 
                               value="<?php echo htmlspecialchars($book['year']); ?>"
                               min="1000" max="<?php echo date('Y'); ?>"
                               placeholder="YYYY">
                    </div>

                 <div class="form-group">
                        <label class="form-required">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="available" <?php echo $book['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                            <option value="borrowed" <?php echo $book['status'] == 'borrowed' ? 'selected' : ''; ?>>Borrowed</option>
                            <option value="reserved" <?php echo $book['status'] == 'reserved' ? 'selected' : ''; ?>>Reserved</option>
                            <option value="maintenance" <?php echo $book['status'] == 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Borrowed By</label>
                        <input type="text" name="borrowed_by" class="form-control" 
                               value="<?php echo htmlspecialchars($book['borrowed_by'] ?? ''); ?>"
                               placeholder="Name of the borrower">
                    </div>

                    <div class="form-group">
                        <label>Due Date</label>
                        <input type="date" name="due_date" class="form-control" 
                               value="<?php echo htmlspecialchars($book['due_date'] ?? ''); ?>">
                    </div>
                </div>

                <div class="preview-card">
                    <h3>Live Preview</h3>
                    <div class="preview-content">
                        <div class="preview-item">
                            <div class="preview-label">Title</div>
                            <div class="preview-value" id="preview-title"><?php echo htmlspecialchars($book['title']); ?></div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">Author</div>
                            <div class="preview-value" id="preview-author"><?php echo htmlspecialchars($book['author']); ?></div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">Status</div>
                            <div class="preview-value">
                                <span class="status-badge status-<?php echo $book['status']; ?>" id="preview-status">
                                    <?php echo ucfirst($book['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="dashboard.php" class="btn btn-cancel">Cancel</a>
                    <button type="submit" name="update" class="btn btn-update">
                        💾 Update Book
                    </button>
                </div>
            </form>

            <?php if(isset($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Live preview functionality
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.querySelector('input[name="title"]');
            const authorInput = document.querySelector('input[name="author"]');
            const statusSelect = document.querySelector('select[name="status"]');
            
            const previewTitle = document.getElementById('preview-title');
            const previewAuthor = document.getElementById('preview-author');
            const previewStatus = document.getElementById('preview-status');

            function updatePreview() {
                previewTitle.textContent = titleInput.value || '<?php echo htmlspecialchars($book['title']); ?>';
                previewAuthor.textContent = authorInput.value || '<?php echo htmlspecialchars($book['author']); ?>';
                
                const status = statusSelect.value || '<?php echo $book['status']; ?>';
                previewStatus.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                previewStatus.className = 'status-badge status-' + status;
            }

            titleInput.addEventListener('input', updatePreview);
            authorInput.addEventListener('input', updatePreview);
            statusSelect.addEventListener('change', updatePreview);

            // Initialize preview
            updatePreview();
        });
    </script>
</body>
</html>