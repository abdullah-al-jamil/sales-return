<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Returns</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2>Sales Returns</h2>
                <table id="sales-returns-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Invoice ID</th>
                            <th>Customer Name</th>
                            <th>Return Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="salesReturnModal" tabindex="-1" aria-labelledby="salesReturnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salesReturnModalLabel">Sales Return Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Return ID:</strong> <span id="modal-return-id"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Invoice ID:</strong> <span id="modal-invoice-id"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Customer Name:</strong> <span id="modal-customer-name"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Return Date:</strong> <span id="modal-return-date"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Reason:</strong> <span id="modal-reason"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <select class="form-select form-select-sm d-inline-block w-auto ms-2" id="status-select">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-primary ms-2" id="update-status-btn">Update</button>
                        </div>
                    </div>
                    <hr>
                    <h6>Items</h6>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>SKU</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>Tax Rate</th>
                                <th>VAT</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="modal-items-body"></tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>Taxable Amount:</strong></div>
                        <div class="col-md-4 text-end" id="modal-taxable-amount"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>Discount:</strong></div>
                        <div class="col-md-4 text-end" id="modal-discount-amount"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>VAT:</strong></div>
                        <div class="col-md-4 text-end" id="modal-vat-amount"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>Total Amount:</strong></div>
                        <div class="col-md-4 text-end" id="modal-total-amount"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="invoiceModal" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">Invoice Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Invoice ID:</strong> <span id="modal-invoice-sec-id"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Invoice Date:</strong> <span id="modal-invoice-date"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Customer Name:</strong> <span id="modal-customer-sec-name"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong> <span id="modal-status"></span>
                        </div>
                    </div>
                    <hr>
                    <h6>Items</h6>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>SKU</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>Tax Rate</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="modal-items-invoice-body"></tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>Taxable Amount:</strong></div>
                        <div class="col-md-4 text-end" id="modal-invoice-taxable-amount"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>Discount:</strong></div>
                        <div class="col-md-4 text-end" id="modal-invoice-discount-amount"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>VAT:</strong></div>
                        <div class="col-md-4 text-end" id="modal-invoice-vat-amount"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>Total Amount:</strong></div>
                        <div class="col-md-4 text-end" id="modal-invoice-total-amount"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        var currentReturnId = null;

        $(document).ready(function() {
            var table = $('#sales-returns-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("sales-returns.index") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'invoice_id', name: 'invoice_id' },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'return_date', name: 'return_date' },
                    { data: 'total_amount', name: 'total_amount' },
                    { 
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            var statusClass = {
                                'pending': 'warning',
                                'approved': 'success',
                                'rejected': 'danger'
                            };
                            return '<span class="badge bg-' + (statusClass[data] || 'secondary') + '">' + data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
                        }
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<button type="button" class="btn btn-sm btn-primary view-return" data-id="' + data + '">View</button>' + ' <button type="button" class="btn btn-sm btn-secondary view-invoice me-1" data-invoice-id="' + row.invoice_id + '">Invoice</button>';
                        }
                    }
                ]
            });

            $('#sales-returns-table').on('click', '.view-return', function() {
                var returnId = $(this).data('id');
                currentReturnId = returnId;
                
                $.ajax({
                    url: '/sales-returns/' + returnId,
                    method: 'GET',
                    success: function(response) {
                        $('#modal-return-id').text(response.sales_return.id);
                        $('#modal-invoice-id').text(response.sales_return.invoice_id);
                        $('#modal-customer-name').text(response.sales_return.customer_name);
                        $('#modal-return-date').text(response.sales_return.return_date);
                        $('#modal-reason').text(response.sales_return.reason);
                        $('#modal-taxable-amount').text(response.sales_return.taxable_amount);
                        $('#modal-discount-amount').text(response.sales_return.discount_amount);
                        $('#modal-vat-amount').text(response.sales_return.vat_amount);
                        $('#modal-total-amount').text(response.sales_return.total_amount);
                        
                        $('#status-select').val(response.sales_return.status);
                        
                        var itemsHtml = '';
                        response.items.forEach(function(item) {
                            itemsHtml += '<tr>';
                            itemsHtml += '<td>' + item.item_name + '</td>';
                            itemsHtml += '<td>' + item.sku + '</td>';
                            itemsHtml += '<td>' + item.quantity + '</td>';
                            itemsHtml += '<td>' + item.unit_price + '</td>';
                            itemsHtml += '<td>' + item.discount + '</td>';
                            itemsHtml += '<td>' + item.tax_rate + '%</td>';
                            itemsHtml += '<td>' + item.vat_amount + '</td>';
                            itemsHtml += '<td>' + item.total + '</td>';
                            itemsHtml += '</tr>';
                        });
                        $('#modal-items-body').html(itemsHtml);
                        
                        var modal = new bootstrap.Modal(document.getElementById('salesReturnModal'));
                        modal.show();
                    }
                });
            });
             $('#sales-returns-table').on('click', '.view-invoice', function() {
                var invoiceId = $(this).data('invoice-id');
                $.ajax({
                    url: '/invoices/' + invoiceId,
                    method: 'GET',
                    success: function(response) {
                        $('#modal-invoice-sec-id').text(response.invoice.id);
                        $('#modal-customer-sec-name').text(response.invoice.customer_name);
                        $('#modal-invoice-date').text(response.invoice.invoice_date);
                        
                        var statusClass = {
                            'paid': 'success',
                            'unpaid': 'warning',
                            'cancelled': 'danger'
                        };
                        $('#modal-status').html('<span class="badge bg-' + (statusClass[response.invoice.status] || 'secondary') + '">' + response.invoice.status.charAt(0).toUpperCase() + response.invoice.status.slice(1) + '</span>');
                        
                        $('#modal-invoice-taxable-amount').text(response.invoice.taxable_amount);
                        $('#modal-invoice-discount-amount').text(response.invoice.discount_amount);
                        $('#modal-invoice-vat-amount').text(response.invoice.vat_amount);
                        $('#modal-invoice-total-amount').text(response.invoice.total_amount);
                        
                        var itemsHtml = '';
                        response.items.forEach(function(item) {
                            itemsHtml += '<tr>';
                            itemsHtml += '<td>' + item.item_name + '</td>';
                            itemsHtml += '<td>' + item.sku + '</td>';
                            itemsHtml += '<td>' + item.quantity + '</td>';
                            itemsHtml += '<td>' + item.unit_price + '</td>';
                            itemsHtml += '<td>' + item.discount + '</td>';
                            itemsHtml += '<td>' + item.tax_rate + '</td>';
                            itemsHtml += '<td>' + item.total + '</td>';
                            itemsHtml += '</tr>';
                        });
                        $('#modal-items-invoice-body').html(itemsHtml);
                        
                        var modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                        modal.show();
                    }
                });
            });

            $('#update-status-btn').click(function() {
                var newStatus = $('#status-select').val();
                
                $.ajax({
                    url: '/sales-returns/' + currentReturnId + '/status',
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        status: newStatus
                    },
                    success: function(response) {
                        alert(response.message);
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Error updating status: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
</body>
</html>
