<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}

// Fetch books
$result = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f7fa;
            color: #333;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header-left h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .header-left p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            text-align: right;
        }

        .user-info .welcome {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .user-info .role {
            font-size: 12px;
            opacity: 0.8;
            background: rgba(255, 255, 255, 0.2);
            padding: 3px 10px;
            border-radius: 12px;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
        }

        .nav-buttons a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
            white-space: nowrap;
        }

        .nav-buttons .add-book {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-buttons .logout {
            background: #ff4757;
        }

        .nav-buttons .view-user {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-buttons a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .dashboard-container {
            padding: 30px 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }

        .stat-card .label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            padding: 25px 30px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h2 {
            margin: 0;
            color: #333;
            font-size: 22px;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-box input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            width: 250px;
        }

        .search-box button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .search-box button:hover {
            background: #5a6fd8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 18px 15px;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-available {
            background: #d4edda;
            color: #155724;
        }

        .status-borrowed {
            background: #f8d7da;
            color: #721c24;
        }

        .status-reserved {
            background: #fff3cd;
            color: #856404;
        }

        .status-maintenance {
            background: #e2e3e5;
            color: #383d41;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-edit, .btn-delete {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-edit {
            background: #17a2b8;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-edit:hover {
            background: #138496;
            transform: translateY(-2px);
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: #495057;
        }

        .empty-state p {
            margin-bottom: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .footer {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
            margin-top: 40px;
            background: white;
            border-radius: 10px;
        }

        /* Genre tag styling */
        .genre-tag {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
            display: inline-block;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
                padding: 20px;
            }

            .header-right {
                flex-direction: column;
                width: 100%;
                align-items: center;
            }

            .user-info {
                text-align: center;
            }

            .nav-buttons {
                flex-direction: column;
                width: 100%;
            }

            .nav-buttons a {
                text-align: center;
                width: 100%;
            }

            .table-header {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
                text-align: center;
            }

            .search-box {
                width: 100%;
            }

            .search-box input {
                width: 100%;
            }

            .dashboard-container {
                padding: 20px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .nav-buttons a {
                padding: 12px 15px;
                font-size: 14px;
            }
        }

        /* Print styles */
        @media print {
            .dashboard-header, 
            .stats-cards, 
            .search-box, 
            .action-buttons,
            .footer {
                display: none;
            }
            
            .table-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }

        /* Animation for status badges */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .status-badge {
            animation: pulse 2s infinite;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php if(isset($_GET['msg'])): 
    $messages = [
        'added' => 'Book added successfully!',
        'updated' => 'Book updated successfully!',
        'deleted' => 'Book deleted successfully!'
    ];
    $toastMsg = $messages[$_GET['msg']] ?? '';
?>
<div id="toast" style="position: fixed; top: 20px; right: 20px; background: #4caf50; color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 9999; font-weight: 600;">
    ✅ <?php echo htmlspecialchars($toastMsg); ?>
</div>
<script>
    setTimeout(() => {
        const toast = document.getElementById('toast');
        if(toast) toast.style.display = 'none';
    }, 3000);
</script>
<?php endif; ?>
    <div class="dashboard-header">
        <div class="header-left">
            <h1>📚 Library Tracker</h1>
            <p>Manage your library collection efficiently</p>
        </div>
        <div class="header-right">
            <div class="user-info">
                <div class="welcome">Welcome, <strong><?php echo $_SESSION['admin']; ?></strong></div>
                <div class="role">Administrator</div>
            </div>
            <div class="nav-buttons">
                <a href="add_book.php" class="btn-add">
                    <span>+</span> Add New Book
                </a>
                <a href="export_books.php" class="view-user">📥 Export CSV</a>
                <a href="books.php" target="_blank" class="view-user">👀 View as User</a>
                <a href="logout.php" class="logout">🚪 Logout</a>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="stats-cards">
            <?php
            // Get statistics
            $totalBooks = $conn->query("SELECT COUNT(*) as total FROM books")->fetch_assoc()['total'];
            $availableBooks = $conn->query("SELECT COUNT(*) as available FROM books WHERE status = 'available'")->fetch_assoc()['available'];
            $borrowedBooks = $conn->query("SELECT COUNT(*) as borrowed FROM books WHERE status = 'borrowed'")->fetch_assoc()['borrowed'];
            $reservedBooks = $conn->query("SELECT COUNT(*) as reserved FROM books WHERE status = 'reserved'")->fetch_assoc()['reserved'];
            $maintenanceBooks = $conn->query("SELECT COUNT(*) as maintenance FROM books WHERE status = 'maintenance'")->fetch_assoc()['maintenance'];
            $overdueBooks = $conn->query("SELECT COUNT(*) as overdue FROM books WHERE status = 'borrowed' AND due_date < CURDATE()")->fetch_assoc()['overdue'];
            ?>
            <div class="stat-card">
                <div class="number"><?php echo $totalBooks; ?></div>
                <div class="label">Total Books</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $availableBooks; ?></div>
                <div class="label">Available</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $borrowedBooks; ?></div>
                <div class="label">Borrowed</div>
            </div>
         <div class="stat-card">
                <div class="number"><?php echo $reservedBooks + $maintenanceBooks; ?></div>
                <div class="label">Other Status</div>
            </div>
            <div class="stat-card" style="background: <?php echo $overdueBooks > 0 ? '#fff5f5' : ''; ?>;">
                <div class="number" style="color: <?php echo $overdueBooks > 0 ? '#d32f2f' : ''; ?>;"><?php echo $overdueBooks; ?></div>
                <div class="label">Overdue</div>
            </div>
        </div>

        </div>

        <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; max-width: 400px;">
            <h3 style="margin-top: 0;">Books by Status</h3>
            <canvas id="statusChart"></canvas>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2>📖 Book Collection</h2>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search books by title, author, or genre...">
                    <button onclick="searchBooks()">🔍 Search</button>
                </div>
            </div>

            <?php if($result->num_rows > 0): ?>
                <table id="booksTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Year</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><span class="genre-tag"><?php echo htmlspecialchars($row['genre']); ?></span></td>
                          <td><?php echo $row['year']; ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if($row['status'] === 'borrowed' && !empty($row['due_date'])): ?>
                                    <?php echo date('M j, Y', strtotime($row['due_date'])); ?>
                                    <?php if(strtotime($row['due_date']) < strtotime('today')): ?>
                                        <br><small style="color:#d32f2f;">Overdue</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="btn-edit">✏️ Edit</a>
                                    <a href="delete_book.php?id=<?php echo $row['id']; ?>" 
                                       class="btn-delete" 
                                       onclick="return confirmDelete(<?php echo $row['id']; ?>, '<?php echo addslashes($row['title']); ?>')">🗑️ Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <h3>📭 No books found</h3>
                    <p>Start building your library collection by adding your first book.</p>
                    <a href="add_book.php" class="add-book" style="display: inline-block; margin-top: 10px; padding: 12px 24px;">➕ Add Your First Book</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            Library Tracker System • Total Books: <?php echo $totalBooks; ?> • Last updated: <?php echo date('F j, Y, g:i a'); ?>
        </div>
    </div>

    <script>
        function searchBooks() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#booksTable tbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(input)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show message if no results
            const noResultsMsg = document.querySelector('.no-results');
            if (visibleCount === 0 && rows.length > 0) {
                if (!noResultsMsg) {
                    const msg = document.createElement('div');
                    msg.className = 'empty-state';
                    msg.innerHTML = `
                        <h3>🔍 No matching books found</h3>
                        <p>Try searching with different keywords.</p>
                    `;
                    document.querySelector('.table-container').appendChild(msg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchBooks();
            }
        });

        // Clear search when input is cleared
        document.getElementById('searchInput').addEventListener('input', function(e) {
            if (this.value === '') {
                searchBooks();
            }
        });

        function confirmDelete(id, title) {
            return confirm(`Are you sure you want to delete the book "${title}"? This action cannot be undone.`);
        }

        // Auto-refresh every 5 minutes
        setTimeout(() => {
            if (confirm('Do you want to refresh the page to see the latest updates?')) {
                location.reload();
            }
        }, 300000); // 5 minutes

    
const ctx = document.getElementById('statusChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Available', 'Borrowed', 'Other'],
        datasets: [{
            data: [<?php echo $availableBooks; ?>, <?php echo $borrowedBooks; ?>, <?php echo $reservedBooks + $maintenanceBooks; ?>],
            backgroundColor: ['#4caf50', '#e57373', '#ffb74d']
        }]
    },
    options: {
        responsive: true
    }
});

    </script>
</body>
</html>