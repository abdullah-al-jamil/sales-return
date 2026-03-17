<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-12">
                <h2>Stocks</h2>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" class="form-control form-control-sm" id="start_date">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" class="form-control form-control-sm" id="end_date">
            </div>
            <div class="col-md-3">
                <label class="form-label">Item</label>
                <input type="text" class="form-control form-control-sm" id="item" placeholder="Item name">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-primary me-1" id="filter-btn">Filter</button>
                <button type="button" class="btn btn-sm btn-secondary" id="reset-btn">Reset</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="stocks-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Opening Stock</th>
                            <th>Stock IN</th>
                            <th>Stock Out</th>
                            <th>Available Stock</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#stocks-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{{ route("stocks.index") }}',
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.item = $('#item').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'item', name: 'item' },
                    { data: 'opening_stock', name: 'opening_stock' },
                    { data: 'stock_in', name: 'stock_in' },
                    { data: 'stock_out', name: 'stock_out' },
                    { data: 'available_stock', name: 'available_stock' }
                ]
            });

            $('#filter-btn').click(function() {
                table.ajax.reload();
            });

            $('#reset-btn').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#item').val('');
                table.ajax.reload();
            });
        });
    </script>
</body>
</html>
