<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2>Invoice List</h2>
                <table id="invoices-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Invoice Date</th>
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
                            <strong>Invoice ID:</strong> <span id="modal-invoice-id"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Invoice Date:</strong> <span id="modal-invoice-date"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Customer Name:</strong> <span id="modal-customer-name"></span>
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

    <div class="modal fade" id="returnModal" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Create Sales Return</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Invoice ID:</strong> <span id="return-invoice-id"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Customer Name:</strong> <span id="return-customer-name"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Invoice Total:</strong> <span id="return-invoice-total"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label"><strong>Return Type:</strong></label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="returnType" id="returnTypeFull" value="full" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="returnTypeFull">Full Return</label>
                            <input type="radio" class="btn-check" name="returnType" id="returnTypePartial" value="partial" autocomplete="off">
                            <label class="btn btn-outline-primary" for="returnTypePartial">Partial Return</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="returnDate" class="form-label">Return Date</label>
                        <input type="datetime-local" class="form-control" id="returnDate">
                    </div>
                    <div class="mb-3">
                        <label for="returnReason" class="form-label">Reason</label>
                        <textarea class="form-control" id="returnReason" rows="2" placeholder="Enter return reason..."></textarea>
                    </div>
                    <hr>
                    <h6>Select Items to Return</h6>
                    <table class="table table-bordered table-sm" id="return-items-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">
                                    <input type="checkbox" id="selectAllItems" checked>
                                </th>
                                <th>Item</th>
                                <th>SKU</th>
                                <th>Available Qty</th>
                                <th>Return Qty</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>Tax Rate</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="return-items-body"></tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>Return Taxable Amount:</strong></div>
                        <div class="col-md-4 text-end" id="return-taxable-amount">0.00</div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>Return Discount:</strong></div>
                        <div class="col-md-4 text-end" id="return-discount-amount">0.00</div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>Return VAT:</strong></div>
                        <div class="col-md-4 text-end" id="return-vat-amount">0.00</div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 text-end"><strong>Return Total Amount:</strong></div>
                        <div class="col-md-4 text-end" id="return-total-amount">0.00</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="submitReturn">Submit Return</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        var returnItemsData = [];
        var currentInvoiceId = null;

        $(document).ready(function() {
            var table = $('#invoices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("invoices.index") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'invoice_date', name: 'invoice_date' },
                    { data: 'total_amount', name: 'total_amount' },
                    { 
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            var statusClass = {
                                'paid': 'success',
                                'unpaid': 'warning',
                                'cancelled': 'danger'
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
                            var actionHtml = '<button type="button" class="btn btn-sm btn-primary view-invoice me-1" data-id="' + data + '">View</button>';
                            if (row.sales_returns_count > 0) {
                                actionHtml += '<span class="badge bg-success">Returned</span>';
                            } else {
                                actionHtml += '<button type="button" class="btn btn-sm btn-success create-return" data-id="' + data + '">Return</button>';
                            }
                            return actionHtml;
                        }
                    }
                ]
            });

            $('#invoices-table').on('click', '.view-invoice', function() {
                var invoiceId = $(this).data('id');
                $.ajax({
                    url: '/invoices/' + invoiceId,
                    method: 'GET',
                    success: function(response) {
                        $('#modal-invoice-id').text(response.invoice.id);
                        $('#modal-customer-name').text(response.invoice.customer_name);
                        $('#modal-invoice-date').text(response.invoice.invoice_date);
                        
                        var statusClass = {
                            'paid': 'success',
                            'unpaid': 'warning',
                            'cancelled': 'danger'
                        };
                        $('#modal-status').html('<span class="badge bg-' + (statusClass[response.invoice.status] || 'secondary') + '">' + response.invoice.status.charAt(0).toUpperCase() + response.invoice.status.slice(1) + '</span>');
                        
                        $('#modal-taxable-amount').text(response.invoice.taxable_amount);
                        $('#modal-discount-amount').text(response.invoice.discount_amount);
                        $('#modal-vat-amount').text(response.invoice.vat_amount);
                        $('#modal-total-amount').text(response.invoice.total_amount);
                        
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
                        $('#modal-items-body').html(itemsHtml);
                        
                        var modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                        modal.show();
                    }
                });
            });

            $('#invoices-table').on('click', '.create-return', function() {
                var invoiceId = $(this).data('id');
                currentInvoiceId = invoiceId;
                $('#returnDate').val(new Date().toISOString().slice(0, 16));
                
                $.ajax({
                    url: '/invoices/' + invoiceId + '/return',
                    method: 'GET',
                    success: function(response) {
                        $('#return-invoice-id').text(response.invoice.id);
                        $('#return-customer-name').text(response.invoice.customer_name);
                        $('#return-invoice-total').text(response.invoice.total_amount);
                        
                        returnItemsData = response.items;
                        renderReturnItems(response.items, 'full');
                        
                        calculateReturnTotals();
                        
                        var modal = new bootstrap.Modal(document.getElementById('returnModal'));
                        modal.show();
                    }
                });
            });

            $('input[name="returnType"]').change(function() {
                var returnType = $(this).val();
                renderReturnItems(returnItemsData, returnType);
            });

            $('#selectAllItems').change(function() {
                var isChecked = $(this).is(':checked');
                $('.item-checkbox').prop('checked', isChecked);
                $('.item-quantity').prop('disabled', !isChecked);
                if (!isChecked) {
                    $('.item-quantity').val(0);
                } else {
                    $('.item-quantity').each(function() {
                        var maxQty = parseInt($(this).data('max'));
                        $(this).val(maxQty);
                    });
                }
                calculateReturnTotals();
            });

            $('#return-items-body').on('change', '.item-checkbox', function() {
                var isChecked = $(this).is(':checked');
                var row = $(this).closest('tr');
                row.find('.item-quantity').prop('disabled', !isChecked);
                if (!isChecked) {
                    row.find('.item-quantity').val(0);
                } else {
                    var maxQty = parseInt(row.find('.item-quantity').data('max'));
                    row.find('.item-quantity').val(maxQty);
                }
                updateSelectAllCheckbox();
                calculateReturnTotals();
            });

            $('#return-items-body').on('input', '.item-quantity', function() {
                var maxQty = parseInt($(this).data('max'));
                var val = parseInt($(this).val());
                if (val > maxQty) {
                    $(this).val(maxQty);
                }
                if (val < 0 || isNaN(val)) {
                    $(this).val(0);
                }
                calculateReturnTotals();
            });

            $('#submitReturn').click(function() {
                var returnDate = $('#returnDate').val();
                var reason = $('#returnReason').val();
                
                if (!returnDate) {
                    alert('Please select return date');
                    return;
                }
                if (!reason) {
                    alert('Please enter return reason');
                    return;
                }
                
                var selectedItems = [];
                var hasSelected = false;
                
                $('.item-checkbox:checked').each(function() {
                    var row = $(this).closest('tr');
                    var quantity = parseInt(row.find('.item-quantity').val());
                    if (quantity > 0) {
                        hasSelected = true;
                        selectedItems.push({
                            selected: true,
                            invoice_item_id: $(this).val(),
                            item_id: row.data('item-id'),
                            quantity: quantity
                        });
                    }
                });
                
                if (!hasSelected) {
                    alert('Please select at least one item to return');
                    return;
                }
                
                $.ajax({
                    url: '/invoices/' + currentInvoiceId + '/return',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        return_date: returnDate,
                        reason: reason,
                        items: selectedItems
                    },
                    success: function(response) {
                        alert(response.message);
                        var modal = bootstrap.Modal.getInstance(document.getElementById('returnModal'));
                        modal.hide();
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Error creating return: ' + xhr.responseJSON.message);
                    }
                });
            });

            function renderReturnItems(items, returnType) {
                var html = '';
                items.forEach(function(item) {
                    var qty = returnType === 'full' ? item.quantity : 0;
                    html += '<tr data-item-id="' + item.item_id + '">';
                    html += '<td><input type="checkbox" class="item-checkbox" value="' + item.id + '" ' + (returnType === 'full' ? 'checked' : '') + '></td>';
                    html += '<td>' + item.item_name + '</td>';
                    html += '<td>' + item.sku + '</td>';
                    html += '<td>' + item.quantity + '</td>';
                    html += '<td><input type="number" class="form-control form-control-sm item-quantity" value="' + qty + '" data-max="' + item.quantity + '" min="0" max="' + item.quantity + '" ' + (returnType === 'partial' ? 'disabled' : '') + '></td>';
                    html += '<td>' + item.unit_price + '</td>';
                    html += '<td>' + item.discount + '</td>';
                    html += '<td>' + item.tax_rate + '%</td>';
                    html += '<td class="item-total">' + (returnType === 'full' ? item.total : '0.00') + '</td>';
                    html += '</tr>';
                });
                $('#return-items-body').html(html);
                $('#selectAllItems').prop('checked', returnType === 'full');
            }

            function calculateReturnTotals() {
                var taxableAmount = 0;
                var discountAmount = 0;
                var vatAmount = 0;
                var totalAmount = 0;
                
                $('.item-checkbox:checked').each(function() {
                    var row = $(this).closest('tr');
                    var quantity = parseInt(row.find('.item-quantity').val()) || 0;
                    var itemData = returnItemsData.find(i => i.id == $(this).val());
                    if (itemData && quantity > 0) {
                        var unitPrice = itemData.unit_price_raw;
                        var discount = itemData.discount_raw;
                        var taxRate = itemData.tax_rate_raw;
                        
                        var taxable = (unitPrice - discount) * quantity;
                        var vat = taxable * taxRate / 100;
                        var total = taxable + vat;
                        
                        taxableAmount += taxable;
                        discountAmount += discount * quantity;
                        vatAmount += vat;
                        totalAmount += total;
                        
                        row.find('.item-total').text(total.toFixed(2));
                    } else {
                        row.find('.item-total').text('0.00');
                    }
                });
                
                $('#return-taxable-amount').text(taxableAmount.toFixed(2));
                $('#return-discount-amount').text(discountAmount.toFixed(2));
                $('#return-vat-amount').text(vatAmount.toFixed(2));
                $('#return-total-amount').text(totalAmount.toFixed(2));
            }

            function updateSelectAllCheckbox() {
                var totalCheckboxes = $('.item-checkbox').length;
                var checkedCheckboxes = $('.item-checkbox:checked').length;
                $('#selectAllItems').prop('checked', totalCheckboxes === checkedCheckboxes);
            }
        });
    </script>
</body>
</html>
