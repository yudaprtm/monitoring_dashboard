<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="container">
        <h2 class="text-center"><i class="fas fa-tachometer-alt"></i> Dashboard Monitoring</h2>

        <!-- Dropdown dan Search Bar -->
        <div class="controls">
            <select id="tableSelector" class="form-select w-auto me-2">
                <option value="contents">Table List</option>
                <option value="heavyQuery">Top Heavy Queries</option>
            </select>
            <form method="GET" action="{{ route('search') }}" class="search-form d-flex" id="searchForm">
                <input type="text" id="searchBar" name="search" placeholder="Search by table name" value="{{ request()->get('search') }}" class="form-control me-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>

        <!-- Table List -->
        <div id="contentsTable">
            <h2><i class="fas fa-table"></i> Table List</h2>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="sortable" data-sort="table_name">Table Name<i class="fas fa-sort"></i></th>
                        <th class="sortable" data-sort="as_of_date">As of Date<i class="fas fa-sort"></i></th>
                        <th class="sortable" data-sort="row_insert">Row Insert<i class="fas fa-sort"></i></th>
                        <th class="sortable" data-sort="flag">Flag<i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <td>{{ $data->table_name }}</td>
                            <td>{{ $data->as_of_date }}</td>
                            <td>{{ $data->row_insert }}</td>
                            <td>{{ $data->flag }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Heavy Query Table -->
        <div id="heavyQueryTable" style="display: none;">
            <h2><i class="fas fa-database"></i> Top Heavy Queries</h2>
            <div id="impalaQueries" class="query-type">
                <h3>Impala Queries</h3>
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="sortable" data-sort="execution_time">Name<i class="fas fa-sort"></i></th>
                            <th class="sortable" data-sort="execution_time">Execution Time (ms)<i class="fas fa-sort"></i></th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($impala->where('type', 'impala') as $query)
                            <tr>
                                <td>{{ $query->name }}</td>
                                <td>{{ $query->exect_time }}</td>
                                <td>{{ $query->type }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="hiveQueries" class="query-type">
                <h3>Hive Queries</h3>
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="sortable" data-sort="execution_time">Name<i class="fas fa-sort"></i></th>
                            <th class="sortable" data-sort="execution_time">Execution Time (ms)<i class="fas fa-sort"></i></th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hive->where('type', 'hive') as $query)
                            <tr>
                                <td>{{ $query->name }}</td>
                                <td>{{ $query->exect_time }}</td>
                                <td>{{ $query->type }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('tableSelector').addEventListener('change', function() {
            var selectedValue = this.value;
            var contentsTable = document.getElementById('contentsTable');
            var heavyQueryTable = document.getElementById('heavyQueryTable');
            var searchForm = document.getElementById('searchForm');

            if (selectedValue === 'contents') {
                contentsTable.style.display = 'block';
                heavyQueryTable.style.display = 'none';
                searchForm.style.display = 'block';
            } else if (selectedValue === 'heavyQuery') {
                contentsTable.style.display = 'none';
                heavyQueryTable.style.display = 'block';
                searchForm.style.display = 'none';
            }
        });

        document.querySelectorAll('th.sortable').forEach(function(header) {
            header.addEventListener('click', function() {
                var table = header.closest('table');
                var tbody = table.querySelector('tbody');
                var rows = Array.from(tbody.querySelectorAll('tr'));
                var sortDirection = header.classList.contains('sorted-asc') ? 'desc' : 'asc';
                var sortKey = header.dataset.sort;

                rows.sort(function(rowA, rowB) {
                    var cellA = rowA.querySelector('td:nth-child(' + (header.cellIndex + 1) + ')').textContent.trim();
                    var cellB = rowB.querySelector('td:nth-child(' + (header.cellIndex + 1) + ')').textContent.trim();
                    
                    if (sortKey === 'execution_time') {
                        cellA = parseFloat(cellA);
                        cellB = parseFloat(cellB);
                    }

                    if (sortDirection === 'asc') {
                        return cellA > cellB ? 1 : -1;
                    } else {
                        return cellA < cellB ? 1 : -1;
                    }
                });

                rows.forEach(function(row) {
                    tbody.appendChild(row);
                });

                document.querySelectorAll('th.sortable').forEach(function(th) {
                    th.classList.remove('sorted-asc', 'sorted-desc');
                });
                header.classList.add('sorted-' + sortDirection);
            });
        });
    </script>
</body>
</html>
