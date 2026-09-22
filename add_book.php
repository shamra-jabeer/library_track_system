<?php
session_start();
include 'db.php';
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}

if(isset($_POST['add'])){
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $year = trim($_POST['year']);
    $status = $_POST['status'];

    $errors = [];

    if(empty($title)){
        $errors[] = "Book title is required.";
    }
    if(empty($author)){
        $errors[] = "Author name is required.";
    }
    if(empty($genre)){
        $errors[] = "Genre is required.";
    }
    if(empty($year) || !is_numeric($year)){
        $errors[] = "A valid publication year is required.";
    } elseif($year < 1000 || $year > (date('Y') + 1)){
        $errors[] = "Publication year must be between 1000 and " . (date('Y') + 1) . ".";
    }

    if(empty($errors)){

 $stmt = $conn->prepare("INSERT INTO books (title, author, genre, year, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $title, $author, $genre, $year, $status);

        if($stmt->execute()){
            header("Location: dashboard.php?msg=added");
            exit();
        } else {
            $errors[] = $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Book - Library Tracker</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 900px;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            color: #333;
            font-size: 36px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .header h1:before {
            content: "📚";
            font-size: 42px;
        }

        .header p {
            color: #666;
            font-size: 16px;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .add-book-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
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

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
        }

        .card-header h2 {
            margin: 0;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header h2:before {
            content: "➕";
            font-size: 28px;
        }

        .form-container {
            padding: 40px;
        }

        .form-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f2f5;
            padding-bottom: 10px;
        }

        .tab {
            padding: 12px 24px;
            background: #f8f9fa;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            color: #666;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .tab.active {
            background: white;
            color: #667eea;
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
        }

        .tab:hover:not(.active) {
            background: #e9ecef;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .required:after {
            content: " *";
            color: #ff4757;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e1e5ee;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            box-sizing: border-box;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-control:hover {
            border-color: #a5b1fc;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23667eea' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 20px center;
            background-size: 18px;
            padding-right: 50px;
        }

        .status-options {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .status-option {
            flex: 1;
            min-width: 120px;
        }

        .status-option input[type="radio"] {
            display: none;
        }

        .status-option label {
            display: block;
            padding: 15px;
            background: #f8f9fa;
            border: 2px solid #e1e5ee;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #666;
        }

        .status-option input[type="radio"]:checked + label {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .status-option label:hover {
            border-color: #667eea;
            background: #eef2ff;
        }

        .status-available label:before {
            content: "✅ ";
        }

        .status-borrowed label:before {
            content: "📖 ";
        }

        .status-reserved label:before {
            content: "⏳ ";
        }

        .status-maintenance label:before {
            content: "🔧 ";
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #f0f2f5;
        }

        .btn {
            padding: 16px 35px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-submit {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            min-width: 180px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:active {
            transform: translateY(-1px);
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
            border-radius: 12px;
            margin-top: 30px;
            border-left: 4px solid #ff4757;
            animation: shake 0.3s ease-in-out;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .error-message:before {
            content: "⚠️";
            font-size: 20px;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .quick-tips {
            background: #eef2ff;
            border-radius: 12px;
            padding: 25px;
            margin-top: 40px;
            border-left: 4px solid #667eea;
        }

        .quick-tips h3 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quick-tips h3:before {
            content: "💡";
            font-size: 20px;
        }

        .quick-tips ul {
            margin: 0;
            padding-left: 20px;
            color: #555;
            line-height: 1.6;
        }

        .quick-tips li {
            margin-bottom: 8px;
        }

        .form-loading {
            position: relative;
            opacity: 0.7;
        }

        .form-loading:after {
            content: "Adding Book...";
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
            font-size: 18px;
            border-radius: 20px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 20px 25px;
            border-radius: 12px;
            border-left: 4px solid #28a745;
            margin-top: 30px;
            display: none;
        }

        .success-message.show {
            display: block;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .card-header, .form-container {
                padding: 25px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-actions {
                flex-direction: column;
                gap: 15px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .header h1 {
                font-size: 28px;
            }

            .status-options {
                flex-direction: column;
            }

            .status-option {
                min-width: 100%;
            }
        }

        /* Autocomplete styling */
        .autocomplete-list {
            position: absolute;
            background: white;
            border: 2px solid #667eea;
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .autocomplete-item {
            padding: 12px 15px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .autocomplete-item:hover {
            background: #f0f2f5;
        }
    </style>
    <script>
        function showLoading() {
            document.querySelector('form').classList.add('form-loading');
        }

        function switchTab(tabName) {
            // Update tab styles
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
                if(tab.textContent.toLowerCase().includes(tabName)) {
                    tab.classList.add('active');
                }
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Add New Book</h1>
            <p>Expand your library collection by adding new books. Fill in the details below to catalog a new book.</p>
        </div>

        <div class="add-book-card">
            <div class="card-header">
                <h2>New Book Information</h2>
            </div>

            <div class="form-container">
                <div class="form-tabs">
                    <div class="tab active" onclick="switchTab('basic')">📋 Basic Info</div>
                    <div class="tab" onclick="switchTab('advanced')">⚙️ Advanced</div>
                </div>
             <?php if(!empty($errors)): ?>
             <div style="background: #ffebee; color: #c62828; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #c62828;">
                  <strong>Please fix the following:</strong>
                  <ul style="margin: 8px 0 0 20px;">
                    <?php foreach($errors as $err): ?>
                      <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                   </ul>
            </div>
            <?php endif; ?>
                <form method="POST" onsubmit="showLoading()">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="required">
                                <span>📖 Book Title</span>
                            </label>
                            <input type="text" name="title" class="form-control" required
                                   placeholder="Enter the book title"
                                   autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label class="required">
                                <span>✍️ Author</span>
                            </label>
                            <input type="text" name="author" class="form-control" required
                                   placeholder="Enter author's name"
                                   autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>
                                <span>🏷️ Genre</span>
                            </label>
                            <input type="text" name="genre" class="form-control"
                                   placeholder="e.g., Fiction, Science, Biography,Fantasy"
                                   list="genre-suggestions">
                            <datalist id="genre-suggestions">
                                <option value="Fiction">
                                <option value="Non-Fiction">
                                <option value="Science Fiction">
                                <option value="Fantasy">
                                <option value="Mystery">
                                <option value="Biography">
                                <option value="History">
                                <option value="Science">
                                <option value="Technology">
                                <option value="Romance">
                                <option value="Horror">
                                <option value="Poetry">
                                <option value="Drama">
                            </datalist>
                        </div>

                        <div class="form-group">
                            <label>
                                <span>📅 Publication Year</span>
                            </label>
                            <input type="number" name="year" class="form-control"
                                   min="1000" max="<?php echo date('Y'); ?>"
                                   placeholder="<?php echo date('Y'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required">
                            <span>📊 Status</span>
                        </label>
                        <div class="status-options">
                            <div class="status-option status-available">
                                <input type="radio" id="available" name="status" value="available" checked>
                                <label for="available">Available</label>
                            </div>
                            <div class="status-option status-borrowed">
                                <input type="radio" id="borrowed" name="status" value="borrowed">
                                <label for="borrowed">Borrowed</label>
                            </div>
                            <div class="status-option status-reserved">
                                <input type="radio" id="reserved" name="status" value="reserved">
                                <label for="reserved">Reserved</label>
                            </div>
                            <div class="status-option status-maintenance">
                                <input type="radio" id="maintenance" name="status" value="maintenance">
                                <label for="maintenance">Maintenance</label>
                            </div>
                        </div>
                    </div>

                    <div class="quick-tips">
                        <h3>Quick Tips</h3>
                        <ul>
                            <li>Ensure the title and author fields are accurate for better search results</li>
                            <li>Use specific genres to help users find books more easily</li>
                            <li>Mark books as "Available" if they're ready for borrowing</li>
                            <li>Books under maintenance will not appear in available listings</li>
                        </ul>
                    </div>

                    <div class="form-actions">
                        <a href="dashboard.php" class="btn btn-cancel">← Cancel</a>
                        <button type="submit" name="add" class="btn btn-submit">
                            ➕ Add Book to Library
                        </button>
                    </div>
                </form>

                <?php if(isset($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="success-message" id="successMessage">
                    ✅ Book added successfully! Redirecting to dashboard...
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const title = document.querySelector('input[name="title"]').value.trim();
            const author = document.querySelector('input[name="author"]').value.trim();
            
            if(!title || !author) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }

            // Show success message if form is valid
            const successMessage = document.getElementById('successMessage');
            successMessage.classList.add('show');
        });

        // Auto-focus first input
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="title"]').focus();
        });

        // Genre suggestions
        const genreInput = document.querySelector('input[name="genre"]');
        genreInput.addEventListener('focus', function() {
            if(!this.value) {
                this.value = 'Fiction';
            }
        });

        // Year auto-fill
        const yearInput = document.querySelector('input[name="year"]');
        yearInput.addEventListener('focus', function() {
            if(!this.value) {
                this.value = new Date().getFullYear();
            }
        });
    </script>
</body>
</html>