@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 h6">{{ translate('Import Orders') }}</h5>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('orders.import.template') }}" class="btn btn-info btn-sm">
                    <i class="las la-download"></i> {{ translate('Download Template') }}
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-primary btn-sm">
                    <i class="las la-arrow-left"></i> {{ translate('Back') }}
                </a>
            </div>
        </div>
    </div>

    @if (session('import_summary'))
        <div class="alert alert-success">
            <h5>{{ translate('Import Summary') }}</h5>
            <ul>
                <li>{{ translate('Total Rows') }}: {{ session('import_summary')['total'] }}</li>
                <li>{{ translate('Successfully Imported') }}: {{ session('import_summary')['success'] }}</li>
                <li>{{ translate('Errors') }}: {{ session('import_summary')['errors'] }}</li>
            </ul>
            @if (!empty(session('import_summary')['error_list']))
                <button class="btn btn-sm btn-danger mt-2" onclick="$('#errorTable').toggle()">
                    {{ translate('View Errors') }}
                </button>
                <div id="errorTable" style="display: none; margin-top: 10px;">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>{{ translate('Row') }}</th>
                                <th>{{ translate('Error') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (session('import_summary')['error_list'] as $error)
                                <tr>
                                    <td>{{ $error['row'] }}</td>
                                    <td>{{ $error['message'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Upload CSV/Excel File') }}</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h5>{{ translate('Instructions:') }}</h5>
                <ul class="mb-0">
                    <li>{{ translate('Download the template CSV file and fill it with your order data') }}</li>
                    <li>{{ translate('Required columns: order_code, customer_name, shipping_address, product_sku, quantity, price') }}
                    </li>
                    <li>{{ translate('Product SKU must exist in your database') }}</li>
                    <li>{{ translate('Customer will be created automatically if not exists') }}</li>
                    <li>{{ translate('Delivery Status: pending, confirmed, processing, shipped, delivered, cancelled') }}
                    </li>
                    <li>{{ translate('Payment Status: unpaid, paid, refunded') }}</li>
                    <li>{{ translate('Payment Type: cash_on_delivery, manual, online') }}</li>
                    <li>{{ translate('Maximum file size: 5MB') }}</li>
                    <li>{{ translate('Supported file types: CSV, XLS, XLSX') }}</li>
                </ul>
            </div>

            <form action="{{ route('orders.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('CSV/Excel File') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="csv_file" id="csv_file"
                                accept=".csv,.xls,.xlsx" required>
                            <label class="custom-file-label" for="csv_file">{{ translate('Choose file') }}</label>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="las la-info-circle"></i>
                            {{ translate('Select a CSV or Excel file to preview data before import') }}
                        </small>
                    </div>
                </div>

                <!-- File Preview Section -->
                <div id="filePreview" style="display: none;" class="mb-4">
                    <div class="card border border-primary">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-white">
                                    <i class="las la-file-alt"></i> {{ translate('File Preview') }}
                                </h6>
                                <span id="rowCount" class="badge bg-light text-dark"></span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-bordered table-striped table-hover mb-0" id="previewTable">
                                    <thead class="thead-light" id="previewHeader">
                                    </thead>
                                    <tbody id="previewBody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 bg-light border-top">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <span class="text-muted">{{ translate('Total Rows') }}:</span>
                                        <strong id="totalRows">0</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted">{{ translate('Total Columns') }}:</span>
                                        <strong id="totalColumns">0</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted">{{ translate('File Size') }}:</span>
                                        <strong id="fileSize">0 KB</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted">{{ translate('File Name') }}:</span>
                                        <strong id="fileName">-</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Skip First Row') }}</label>
                    <div class="col-md-8">
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input type="checkbox" name="skip_first_row" value="1" checked>
                            <span></span>
                        </label>
                        <small>{{ translate('Skip the header row if your CSV has column headers') }}</small>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-10 offset-md-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-upload"></i> {{ translate('Import Orders') }}
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            {{ translate('Cancel') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#csv_file').on('change', function(e) {
                var file = e.target.files[0];
                if (!file) {
                    $('#filePreview').hide();
                    return;
                }

                // Update file name
                $(this).next('.custom-file-label').html(file.name);

                // Show preview section
                $('#filePreview').show();
                $('#fileName').text(file.name);
                $('#fileSize').text((file.size / 1024).toFixed(2) + ' KB');

                var extension = file.name.split('.').pop().toLowerCase();

                // For Excel files (.xls, .xlsx)
                if (extension === 'xlsx' || extension === 'xls') {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        try {
                            var data = new Uint8Array(e.target.result);
                            var workbook = XLSX.read(data, {
                                type: 'array'
                            });
                            var firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                            var jsonData = XLSX.utils.sheet_to_json(firstSheet, {
                                header: 1
                            });

                            console.log('Excel Data:', jsonData);

                            if (jsonData.length === 0) {
                                $('#previewHeader').html('');
                                $('#previewBody').html(
                                    '<tr><td colspan="1" class="text-center text-muted py-4"><i class="las la-inbox fs-40"></i><br>{{ translate('No data found') }}</td></tr>'
                                );
                                $('#totalRows').text(0);
                                $('#totalColumns').text(0);
                                $('#rowCount').html('<span class="text-danger">No data</span>');
                                return;
                            }

                            // First row is headers
                            var headers = jsonData[0] || [];
                            var rows = jsonData.slice(1);

                            // Filter out empty rows
                            rows = rows.filter(function(row) {
                                return row.some(function(cell) {
                                    return cell !== '' && cell !== null && cell !==
                                        undefined;
                                });
                            });

                            console.log('Headers:', headers);
                            console.log('Rows:', rows);

                            // Update counts
                            $('#totalRows').text(rows.length);
                            $('#totalColumns').text(headers.length);
                            $('#rowCount').text(rows.length + ' ' + '{{ translate('rows') }}');

                            // Build header
                            var headerHtml = '<tr>';
                            headers.forEach(function(header) {
                                var headerText = String(header || '');
                                var isRequired = ['order_code', 'customer_name',
                                    'shipping_address', 'product_sku', 'quantity', 'price'
                                ].includes(headerText);
                                var requiredBadge = isRequired ?
                                    ' <span class="">*</span>' : '';
                                headerHtml +=
                                    '<th class="text-nowrap" style="min-width: 100px; background: #f8f9fa; position: sticky; top: 0; z-index: 10;">' +
                                    escapeHtml(headerText) + requiredBadge + '</th>';
                            });
                            headerHtml += '</tr>';
                            $('#previewHeader').html(headerHtml);

                            // Build body
                            var bodyHtml = '';
                            var displayRows = rows.slice(0, 50);
                            displayRows.forEach(function(row, rowIndex) {
                                var rowClass = (rowIndex % 2 === 0) ? '' : 'table-light';
                                bodyHtml += '<tr class="' + rowClass + '">';
                                headers.forEach(function(header, index) {
                                    var cellValue = row[index] !== undefined ? String(
                                        row[index]) : '';
                                    var displayValue = cellValue;

                                    if (cellValue.length > 150) {
                                        displayValue = cellValue.substring(0, 150) +
                                            '...';
                                    }

                                    // Check for JSON
                                    if (cellValue.trim().startsWith('{') || cellValue
                                        .trim().startsWith('[')) {
                                        try {
                                            var json = JSON.parse(cellValue);
                                            displayValue = JSON.stringify(json);
                                            if (displayValue.length > 100) {
                                                displayValue = displayValue.substring(0,
                                                    100) + '...';
                                            }
                                        } catch (e) {}
                                    }

                                    var cellContent = escapeHtml(displayValue || '');

                                    // Add status badges
                                    if (header === 'delivery_status') {
                                        var statusColor = getStatusColor(cellValue);
                                        cellContent = '<span class="badge badge-' +
                                            statusColor + '">' + escapeHtml(cellValue ||
                                                'N/A') + '</span>';
                                    } else if (header === 'payment_status') {
                                        var paymentColor = getPaymentStatusColor(
                                            cellValue);
                                        cellContent = '<span class="badge badge-' +
                                            paymentColor + '">' + escapeHtml(
                                                cellValue || 'N/A') + '</span>';
                                    } else if (header === 'price' || header ===
                                        'quantity') {
                                        if (cellValue && !isNaN(cellValue)) {
                                            cellContent = '<strong>' + escapeHtml(
                                                cellValue) + '</strong>';
                                        }
                                    }

                                    bodyHtml +=
                                        '<td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding: 8px 12px;" title="' +
                                        escapeHtml(cellValue) + '">' + cellContent +
                                        '</td>';
                                });
                                bodyHtml += '</tr>';
                            });

                            if (rows.length > 50) {
                                bodyHtml += '<tr><td colspan="' + headers.length +
                                    '" class="text-center text-muted py-3">';
                                bodyHtml += '{{ translate('Showing first 50 rows of') }} ' + rows
                                    .length + ' {{ translate('rows') }}';
                                bodyHtml += '</td></tr>';
                            }

                            if (rows.length === 0) {
                                bodyHtml = '<tr><td colspan="' + headers.length +
                                    '" class="text-center text-muted py-4"><i class="las la-inbox fs-40"></i><br>{{ translate('No data rows found') }}</td></tr>';
                            }

                            $('#previewBody').html(bodyHtml);

                        } catch (error) {
                            console.error('Error parsing Excel:', error);
                            $('#previewBody').html(
                                '<tr><td colspan="1" class="text-center text-danger py-4"><i class="las la-exclamation-circle fs-40"></i><br>{{ translate('Error parsing file:') }} ' +
                                error.message + '</td></tr>');
                        }
                    };
                    reader.readAsArrayBuffer(file);
                }
                // For CSV files
                else {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var content = e.target.result;
                        var result = parseCSV(content);

                        console.log('Detected Delimiter:', result.delimiter);
                        console.log('Parsed Headers:', result.headers);
                        console.log('Parsed Rows:', result.rows);

                        $('#totalRows').text(result.rows.length);
                        $('#totalColumns').text(result.headers.length);
                        $('#rowCount').text(result.rows.length + ' ' + '{{ translate('rows') }}');

                        var headerHtml = '<tr>';
                        result.headers.forEach(function(header) {
                            var isRequired = ['order_code', 'customer_name', 'shipping_address',
                                'product_sku', 'quantity', 'price'
                            ].includes(header);
                            var requiredBadge = isRequired ?
                                ' <span class="">*</span>' : '';
                            headerHtml +=
                                '<th class="text-nowrap" style="min-width: 100px; background: #f8f9fa; position: sticky; top: 0; z-index: 10;">' +
                                escapeHtml(header) + requiredBadge + '</th>';
                        });
                        headerHtml += '</tr>';
                        $('#previewHeader').html(headerHtml);

                        var bodyHtml = '';
                        if (result.rows.length > 0) {
                            var displayRows = result.rows.slice(0, 50);
                            displayRows.forEach(function(row, rowIndex) {
                                var rowClass = (rowIndex % 2 === 0) ? '' : 'table-light';
                                bodyHtml += '<tr class="' + rowClass + '">';
                                result.headers.forEach(function(header, index) {
                                    var cellValue = row[index] || '';
                                    var displayValue = cellValue;

                                    if (cellValue.length > 150) {
                                        displayValue = cellValue.substring(0, 150) +
                                            '...';
                                    }

                                    if (cellValue.trim().startsWith('{') || cellValue
                                        .trim().startsWith('[')) {
                                        try {
                                            var json = JSON.parse(cellValue);
                                            displayValue = JSON.stringify(json);
                                            if (displayValue.length > 100) {
                                                displayValue = displayValue.substring(0,
                                                    100) + '...';
                                            }
                                        } catch (e) {}
                                    }

                                    var cellContent = escapeHtml(displayValue);
                                    if (header === 'delivery_status') {
                                        var statusColor = getStatusColor(cellValue);
                                        cellContent = '<span class="badge badge-' +
                                            statusColor + '">' + escapeHtml(cellValue ||
                                                'N/A') + '</span>';
                                    } else if (header === 'payment_status') {
                                        var paymentColor = getPaymentStatusColor(
                                            cellValue);
                                        cellContent = '<span class="badge badge-' +
                                            paymentColor + '">' + escapeHtml(
                                                cellValue || 'N/A') + '</span>';
                                    } else if (header === 'price' || header ===
                                        'quantity') {
                                        if (cellValue && !isNaN(cellValue)) {
                                            cellContent = '<strong>' + escapeHtml(
                                                cellValue) + '</strong>';
                                        }
                                    }

                                    bodyHtml +=
                                        '<td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding: 8px 12px;" title="' +
                                        escapeHtml(cellValue) + '">' + cellContent +
                                        '</td>';
                                });
                                bodyHtml += '</tr>';
                            });

                            if (result.rows.length > 50) {
                                bodyHtml += '<tr><td colspan="' + result.headers.length +
                                    '" class="text-center text-muted py-3">';
                                bodyHtml += '{{ translate('Showing first 50 rows of') }} ' + result
                                    .rows.length + ' {{ translate('rows') }}';
                                bodyHtml += '</td></tr>';
                            }
                        } else {
                            bodyHtml = '<tr><td colspan="' + result.headers.length +
                                '" class="text-center text-muted py-4"><i class="las la-inbox fs-40"></i><br>{{ translate('No data rows found') }}</td></tr>';
                        }

                        $('#previewBody').html(bodyHtml);

                        if (result.rows.length === 0) {
                            $('#rowCount').html('<span class="text-danger">No data rows</span>');
                        }
                    };
                    reader.readAsText(file);
                }
            });

            // Status color functions
            function getStatusColor(status) {
                var statusMap = {
                    'pending': 'warning',
                    'confirmed': 'info',
                    'processing': 'primary',
                    'shipped': 'info',
                    'delivered': 'success',
                    'cancelled': 'danger'
                };
                return statusMap[String(status).toLowerCase()] || 'secondary';
            }

            function getPaymentStatusColor(status) {
                var statusMap = {
                    'unpaid': 'danger',
                    'paid': 'success',
                    'refunded': 'warning'
                };
                return statusMap[String(status).toLowerCase()] || 'secondary';
            }

            // CSV Parser
            function parseCSV(text) {
                if (text.charCodeAt(0) === 0xFEFF) {
                    text = text.substring(1);
                }
                text = text.replace(/\r\n/g, '\n').replace(/\r/g, '\n');

                var firstLine = text.split('\n')[0] || '';
                var delimiter = detectDelimiter(firstLine);

                var lines = [];
                var currentLine = '';
                var inQuotes = false;
                var i = 0;

                if (text.length > 0 && text[text.length - 1] !== '\n') {
                    text += '\n';
                }

                while (i < text.length) {
                    var char = text[i];

                    if (char === '"') {
                        if (inQuotes && text[i + 1] === '"') {
                            currentLine += '"';
                            i += 2;
                            continue;
                        }
                        inQuotes = !inQuotes;
                        i++;
                        continue;
                    }

                    if (char === '\n' && !inQuotes) {
                        if (currentLine.trim() !== '') {
                            lines.push(currentLine);
                        }
                        currentLine = '';
                        i++;
                        continue;
                    }

                    if (char === delimiter && !inQuotes) {
                        currentLine += '\t';
                        i++;
                        continue;
                    }

                    currentLine += char;
                    i++;
                }

                if (currentLine.trim() !== '') {
                    lines.push(currentLine);
                }

                if (lines.length === 0) {
                    return {
                        headers: [],
                        rows: [],
                        delimiter: delimiter
                    };
                }

                var headerLine = lines[0].split('\t');
                var headers = headerLine.map(function(h) {
                    return h.trim();
                });

                var rows = [];
                for (var j = 1; j < lines.length; j++) {
                    var rowData = lines[j].split('\t');
                    rowData = rowData.map(function(cell) {
                        return cell.trim();
                    });
                    var hasData = rowData.some(function(cell) {
                        return cell !== '';
                    });
                    if (hasData) {
                        while (rowData.length < headers.length) {
                            rowData.push('');
                        }
                        rows.push(rowData);
                    }
                }

                return {
                    headers: headers,
                    rows: rows,
                    delimiter: delimiter
                };
            }

            function detectDelimiter(line) {
                var delimiters = [',', '\t', ';', '|'];
                var counts = {};

                delimiters.forEach(function(delim) {
                    var count = 0;
                    var inQuotes = false;
                    for (var i = 0; i < line.length; i++) {
                        var char = line[i];
                        if (char === '"') {
                            inQuotes = !inQuotes;
                        } else if (char === delim && !inQuotes) {
                            count++;
                        }
                    }
                    counts[delim] = count;
                });

                var maxCount = 0;
                var detectedDelimiter = ',';
                for (var d in counts) {
                    if (counts[d] > maxCount) {
                        maxCount = counts[d];
                        detectedDelimiter = d;
                    }
                }

                if (maxCount === 0) {
                    detectedDelimiter = ',';
                }

                return detectedDelimiter;
            }

            function escapeHtml(text) {
                if (!text) return '';
                var div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            $('#importForm').on('submit', function() {
                var btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true);
                btn.html('<i class="las la-spinner la-spin"></i> {{ translate('Importing...') }}');
            });
        });
    </script>

    <style>
        #previewTable {
            font-size: 13px;
        }

        #previewTable th {
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 2px solid #dee2e6;
            background: #f8f9fa;
        }

        #previewTable td {
            vertical-align: middle;
            padding: 6px 12px;
            font-size: 12px;
        }

        #previewTable tbody tr:hover {
            background-color: #e8f4ff !important;
            cursor: default;
        }

        .badge {
            font-size: 10px;
            padding: 3px 8px;
        }

        .table-responsive::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .table-responsive {
            border-radius: 8px;
        }

        .card-header.bg-primary {
            background: #0066cc !important;
        }

        .table-light {
            background-color: #f8f9fa !important;
        }
    </style>
@endsection
