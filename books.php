<?php
include 'db.php';

// Fetch all books
$result = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Books - Public View</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .public-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .public-header {
            background: white;
            border-radius: 15px;
            padding: 30px 40px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .public-header h1 {
            margin: 0;
            color: #333;
            font-size: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .public-header h1:before {
            content: "📚";
            font-size: 42px;
        }

        .public-header p {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .admin-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #667eea;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .admin-link:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .search-filter-container {
            background: white;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .search-section {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .search-input {
            flex: 1;
            padding: 14px 20px;
            border: 2px solid #e1e5ee;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-button {
            background: #667eea;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-button:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .filter-section {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }

        .filter-label {
            color: #333;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-select {
            padding: 12px 20px;
            border: 2px solid #e1e5ee;
            border-radius: 10px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 150px;
        }

        .filter-select:focus {
            outline: none;
            border-color: #667eea;
        }

        .stats-section {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            flex: 1;
            min-width: 200px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .count {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-card .label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .books-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .books-header {
            padding: 25px 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .books-header h2 {
            margin: 0;
            color: #333;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .books-count {
            background: #667eea;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 20px 15px;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
            transition: background 0.3s ease;
        }

        tr:hover td {
            background: #f8f9fa;
        }

        .book-title {
            color: #333;
            font-weight: 600;
            font-size: 16px;
        }

        .book-author {
            color: #666;
            font-size: 14px;
        }

        .genre-tag {
            background: #e9ecef;
            color: #495057;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state h3 {
            margin-bottom: 15px;
            color: #495057;
            font-size: 20px;
        }

        .empty-state p {
            font-size: 16px;
            max-width: 500px;
            margin: 0 auto 20px;
            line-height: 1.6;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 25px 30px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .page-btn {
            padding: 10px 18px;
            border: 1px solid #dee2e6;
            background: white;
            color: #495057;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .page-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .page-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .page-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .public-footer {
            text-align: center;
            padding: 30px;
            color: white;
            font-size: 14px;
            margin-top: 40px;
            opacity: 0.9;
        }

        .book-card-view {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 30px;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .view-btn {
            background: white;
            border: 2px solid #e1e5ee;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .view-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .view-btn:hover {
            border-color: #667eea;
        }

        .book-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .book-card-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .book-card-body {
            padding: 25px;
        }

        .book-card-title {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 18px;
            font-weight: 600;
            line-height: 1.4;
        }

        .book-card-author {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .book-card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .public-container {
                padding: 15px;
            }

            .public-header {
                padding: 20px;
            }

            .public-header h1 {
                font-size: 28px;
                flex-direction: column;
                gap: 10px;
            }

            .search-section {
                flex-direction: column;
            }

            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-select {
                width: 100%;
            }

            .books-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            .book-card-view {
                grid-template-columns: 1fr;
                padding: 20px;
            }

            .stat-card {
                min-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .public-header h1 {
                font-size: 24px;
            }

            .public-header p {
                font-size: 14px;
            }

            .admin-link {
                width: 100%;
                justify-content: center;
            }
        }

        /* Animation for new books */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        tr, .book-card {
            animation: fadeIn 0.5s ease-out;
        }

        tr:nth-child(even), .book-card:nth-child(even) {
            animation-delay: 0.1s;
        }
    </style>
</head>
<body>
    <div class="public-container">
        <div class="public-header">
            <h1>📚 Public Library Collection</h1>
            <p>Browse our extensive collection of books. Filter by genre or status to find your next read.</p>
            <a href="index.php" class="admin-link">🔐 Admin Login</a>
        </div>

        <?php
        // Get statistics for public view
        $totalBooks = $conn->query("SELECT COUNT(*) as total FROM books")->fetch_assoc()['total'];
        $availableBooks = $conn->query("SELECT COUNT(*) as available FROM books WHERE status = 'available'")->fetch_assoc()['available'];
        $fictionBooks = $conn->query("SELECT COUNT(*) as fiction FROM books WHERE genre LIKE '%Fiction%' OR genre LIKE '%fiction%'")->fetch_assoc()['fiction'];
        ?>
        
        <div class="stats-section">
            <div class="stat-card">
                <div class="count"><?php echo $totalBooks; ?></div>
                <div class="label">Total Books</div>
            </div>
            <div class="stat-card">
                <div class="count"><?php echo $availableBooks; ?></div>
                <div class="label">Available Now</div>
            </div>
            <div class="stat-card">
                <div class="count"><?php echo $fictionBooks; ?></div>
                <div class="label">Fiction Books</div>
            </div>
            <div class="stat-card">
                <div class="count"><?php echo date('Y'); ?></div>
                <div class="label">Current Year</div>
            </div>
        </div>

        <div class="search-filter-container">
            <div class="search-section">
                <input type="text" id="searchInput" class="search-input" placeholder="Search books by title, author, or genre...">
                <button onclick="searchBooks()" class="search-button">
                    🔍 Search
                </button>
            </div>
            <div class="filter-section">
                <span class="filter-label">Filter by:</span>
                <select id="genreFilter" class="filter-select" onchange="filterBooks()">
                    <option value="">All Genres</option>
                    <option value="Fiction">Fiction</option>
                    <option value="Science">Science</option>
                    <option value="History">History</option>
                    <option value="Biography">Biography</option>
                    <option value="Technology">Technology</option>
                </select>
                <select id="statusFilter" class="filter-select" onchange="filterBooks()">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="borrowed">Borrowed</option>
                    <option value="reserved">Reserved</option>
                </select>
                <select id="yearFilter" class="filter-select" onchange="filterBooks()">
                    <option value="">All Years</option>
                    <option value="2020">2020 & Above</option>
                    <option value="2010">2010-2019</option>
                    <option value="2000">2000-2009</option>
                    <option value="1990">Before 2000</option>
                </select>
            </div>
        </div>

        <div class="books-container">
            <div class="books-header">
                <h2>📖 Available Books <span class="books-count"><?php echo $totalBooks; ?></span></h2>
                <div class="view-toggle">
                    <button class="view-btn active" onclick="setView('table')" title="Table View">📋</button>
                    <button class="view-btn" onclick="setView('card')" title="Card View">🃏</button>
                </div>
            </div>

            <?php if($result->num_rows > 0): ?>
                <!-- Table View -->
                <div id="tableView">
                    <table id="booksTable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Year</th>
                                <th>Status</th>
                                <th>Due Back</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                          <tr>
                                <td>
                                    <div class="book-title"><?php echo htmlspecialchars($row['title']); ?></div>
                                </td>
                                <td>
                                    <div class="book-author"><?php echo htmlspecialchars($row['author']); ?></div>
                                </td>
                                <td>
                                    <span class="genre-tag"><?php echo htmlspecialchars($row['genre']); ?></span>
                                </td>
                                <td><?php echo $row['year']; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($row['status'] === 'borrowed' && !empty($row['due_date'])): ?>
                                        <?php echo date('M j, Y', strtotime($row['due_date'])); ?>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>  

                <!-- Card View -->
                <div id="cardView" style="display: none;">
                    <div class="book-card-view" id="booksCardView">
                        <?php 
                        // Reset result pointer
                        $result->data_seek(0);
                        while($row = $result->fetch_assoc()): 
                        ?>
                        <div class="book-card">
                            <div class="book-card-header">
                                <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </div>
                            <div class="book-card-body">
                                <h3 class="book-card-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                                <div class="book-card-author">
                                    <span>✍️</span>
                                    <?php echo htmlspecialchars($row['author']); ?>
                                </div>
                               <p><strong>Genre:</strong> <span class="genre-tag"><?php echo htmlspecialchars($row['genre']); ?></span></p>
                                <p><strong>Published:</strong> <?php echo $row['year']; ?></p>
                                <?php if($row['status'] === 'borrowed' && !empty($row['due_date'])): ?>
                                <p><strong>Due Back:</strong> <?php echo date('M j, Y', strtotime($row['due_date'])); ?></p>
                                <?php endif; ?>
                                <div class="book-card-meta">
                                    <span>📅 Year: <?php echo $row['year']; ?></span>
                                    <span>📚 ID: #<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="pagination">
                    <button class="page-btn disabled">← Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">Next →</button>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <h3>📭 No Books Available</h3>
                    <p>Our library is currently empty. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="public-footer">
            © <?php echo date('Y'); ?> Public Library • All books are available for public viewing
        </div>
    </div>

    <script>
        let currentView = 'table';

        function setView(view) {
            currentView = view;
            document.getElementById('tableView').style.display = view === 'table' ? 'block' : 'none';
            document.getElementById('cardView').style.display = view === 'card' ? 'block' : 'none';
            
            // Update active button
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.view-btn[onclick="setView('${view}')"]`).classList.add('active');
        }

        function searchBooks() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#booksTable tbody tr');
            const cards = document.querySelectorAll('#booksCardView .book-card');
            let visibleCount = 0;

            // Search in table view
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Search in card view
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            updateBooksCount(visibleCount);
        }

        function filterBooks() {
            const genre = document.getElementById('genreFilter').value;
            const status = document.getElementById('statusFilter').value;
            const year = document.getElementById('yearFilter').value;
            const rows = document.querySelectorAll('#booksTable tbody tr');
            const cards = document.querySelectorAll('#booksCardView .book-card');
            let visibleCount = 0;

            rows.forEach((row, index) => {
                const genreText = row.cells[2].textContent;
                const statusText = row.cells[4].textContent.toLowerCase();
                const yearText = parseInt(row.cells[3].textContent);
                let showRow = true;

                if (genre && !genreText.includes(genre)) showRow = false;
                if (status && !statusText.includes(status)) showRow = false;
                if (year) {
                    const yearNum = parseInt(year);
                    if (year === '2020' && yearText < 2020) showRow = false;
                    if (year === '2010' && (yearText < 2010 || yearText >= 2020)) showRow = false;
                    if (year === '2000' && (yearText < 2000 || yearText >= 2010)) showRow = false;
                    if (year === '1990' && yearText >= 2000) showRow = false;
                }

                row.style.display = showRow ? '' : 'none';
                cards[index].style.display = showRow ? 'block' : 'none';
                if (showRow) visibleCount++;
            });

            updateBooksCount(visibleCount);
        }

        function updateBooksCount(count) {
            const countElement = document.querySelector('.books-count');
            countElement.textContent = count;
            
            // Show empty state if no results
            const emptyState = document.querySelector('.empty-state');
            const tableView = document.getElementById('tableView');
            const cardView = document.getElementById('cardView');
            
            if (count === 0 && !emptyState) {
                const noResults = document.createElement('div');
                noResults.className = 'empty-state';
                noResults.innerHTML = `
                    <h3>🔍 No Books Found</h3>
                    <p>Try adjusting your search or filters to find what you're looking for.</p>
                `;
                if (currentView === 'table') {
                    tableView.parentNode.insertBefore(noResults, tableView);
                } else {
                    cardView.parentNode.insertBefore(noResults, cardView);
                }
            } else if (emptyState && count > 0) {
                emptyState.remove();
            }
        }

        // Initialize with table view
        document.addEventListener('DOMContentLoaded', function() {
            setView('table');
            document.getElementById('searchInput').focus();
            
            // Add enter key support for search
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchBooks();
                }
            });
        });
    </script>
</body>
</html>