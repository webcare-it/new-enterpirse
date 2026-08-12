@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 h6">{{ translate('Import Products') }}</h5>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('products.import.template') }}" class="btn btn-info btn-sm">
                    <i class="las la-download"></i> {{ translate('Download Template') }}
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
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
                <li>{{ translate('Inserted') }}: {{ session('import_summary')['inserted'] }}</li>
                <li>{{ translate('Updated') }}: {{ session('import_summary')['updated'] }}</li>
            </ul>
            @if (!empty(session('import_summary')['error_list']))
                <button class="btn btn-sm btn-danger mt-2"
                    onclick="$('#errorTable').toggle()">{{ translate('View Errors') }}</button>
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
            <h5 class="mb-0 h6">{{ translate('Upload CSV File') }}</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h5>{{ translate('Instructions:') }}</h5>
                <ul class="mb-0">
                    <li>{{ translate('Download the template CSV file and fill it with your product data') }}</li>
                    <li>{{ translate('Required columns: name, category_id, regular_price, stock, thumbnail_img') }}</li>
                    <li>{{ translate('Category IDs must exist in your database') }}</li>
                    <li>{{ translate('For multiple images/photos, separate with commas') }}</li>
                    <li>{{ translate('For boolean fields (status, is_featured, etc.), use 1 for Yes/0 for No') }}</li>
                    <li>{{ translate('Maximum file size: 5MB') }}</li>
                    <li>{{ translate('Supported file types: CSV, XLS, XLSX') }}</li>
                </ul>
            </div>

            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('CSV File') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="csv_file" id="csv_file"
                                accept=".csv,.xls,.xlsx" required>
                            <label class="custom-file-label" for="csv_file">{{ translate('Choose file') }}</label>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="las la-info-circle"></i>
                            {{ translate('Select a CSV file to preview data before import') }}
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
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto; overflow-x: auto;">
                                <table class="table table-bordered table-striped table-hover mb-0" id="previewTable">
                                    <thead class="thead-light" id="previewHeader"
                                        style="position: sticky; top: 0; z-index: 100;">
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
                            <div class="p-2 bg-danger text-white" id="imageErrorSummary" style="display: none;">
                                <i class="las la-exclamation-triangle"></i>
                                <span id="imageErrorCount">0</span> {{ translate('rows have image loading issues') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Import Mode') }}</label>
                    <div class="col-md-8">
                        <div class="radio">
                            <label class="mr-3">
                                <input type="radio" name="import_mode" value="insert" checked>
                                {{ translate('Insert Only (Skip existing)') }}
                            </label>
                            <label>
                                <input type="radio" name="import_mode" value="update">
                                {{ translate('Update Existing Products') }}
                            </label>
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
                    <label class="col-md-2 col-from-label">{{ translate('Send Email Notification') }}</label>
                    <div class="col-md-8">
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input type="checkbox" name="send_notification" value="1">
                            <span></span>
                        </label>
                        <small>{{ translate('Receive email notification when import is complete') }}</small>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-10 offset-md-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-upload"></i> {{ translate('Import Products') }}
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            {{ translate('Cancel') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Columns to hide from preview
            var hiddenColumns = ['description', 'short_description', 'meta_description', 'notes', 'photos',
                'variant_attributes'
            ];

            // Store image error rows
            var imageErrorRows = [];

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

                var reader = new FileReader();
                reader.onload = function(e) {
                    var content = e.target.result;

                    // Parse CSV
                    var result = parseCSV(content);

                    // Find thumbnail_img column index
                    var thumbnailIndex = -1;
                    result.headers.forEach(function(header, index) {
                        if (header.toLowerCase().trim() === 'thumbnail_img') {
                            thumbnailIndex = index;
                        }
                    });

                    // Filter out hidden columns
                    var visibleHeaders = [];
                    var visibleIndices = [];

                    result.headers.forEach(function(header, index) {
                        var headerLower = header.toLowerCase().trim();
                        var isHidden = false;
                        hiddenColumns.forEach(function(hidden) {
                            if (headerLower === hidden || headerLower.includes(
                                    hidden)) {
                                isHidden = true;
                            }
                        });
                        if (!isHidden) {
                            visibleHeaders.push(header);
                            visibleIndices.push(index);
                        }
                    });

                    // Update row count
                    $('#totalRows').text(result.rows.length);
                    $('#totalColumns').text(visibleHeaders.length);
                    $('#rowCount').text(result.rows.length + ' ' + '{{ translate('rows') }}');

                    // Reset image error rows
                    imageErrorRows = [];

                    // Build table header with row number column
                    var headerHtml = '<tr>';
                    headerHtml +=
                        '<th class="text-nowrap" style="min-width: 50px; background: #e9ecef; position: sticky; left: 0; z-index: 101;">#</th>';
                    // Add status column
                    headerHtml +=
                        '<th class="text-nowrap" style="min-width: 60px; background: #e9ecef; position: sticky; left: 50px; z-index: 101;">Status</th>';
                    visibleHeaders.forEach(function(header) {
                        var headerLower = header.toLowerCase().trim();
                        var isImageColumn = (headerLower === 'thumbnail_img');
                        var displayHeader = isImageColumn ? 'Thumbnail' : header;
                        headerHtml += '<th class="text-nowrap" style="min-width: 120px;">' +
                            escapeHtml(displayHeader) + '</th>';
                    });
                    headerHtml += '</tr>';
                    $('#previewHeader').html(headerHtml);

                    // Build table body (show ALL rows with scroll)
                    var bodyHtml = '';
                    var displayRows = result.rows;
                    displayRows.forEach(function(row, rowIndex) {
                        var hasImageError = false;
                        var rowClass = (rowIndex % 2 === 0) ? '' : 'table-light';

                        bodyHtml += '<tr class="' + rowClass + '" id="row_' + rowIndex + '">';

                        // Row number column (sticky)
                        bodyHtml += '<td style="position: sticky; left: 0; background: ' + (
                                rowIndex % 2 === 0 ? '#ffffff' : '#f8f9fa') +
                            '; z-index: 50; font-weight: bold; text-align: center;">' + (
                                rowIndex + 1) + '</td>';

                        // Status column (sticky) - will be updated after image load check
                        bodyHtml += '<td style="position: sticky; left: 50px; background: ' + (
                                rowIndex % 2 === 0 ? '#ffffff' : '#f8f9fa') +
                            '; z-index: 50; text-align: center;" id="status_' + rowIndex +
                            '">' +
                            '<span class="badge badge-secondary">Checking...</span>' +
                            '</td>';

                        visibleIndices.forEach(function(index) {
                            var cellValue = row[index] || '';
                            var headerName = visibleHeaders[visibleIndices.indexOf(
                                index)];

                            // Check if this is the thumbnail column
                            var isThumbnail = (headerName && headerName.toLowerCase()
                                .trim() ===
                                'thumbnail_img');

                            // Handle image display
                            if (isThumbnail && cellValue) {
                                // Check if it's a URL or numeric ID
                                var imageSrc = '';
                                var isUrl = false;

                                if (cellValue.match(/^https?:\/\//)) {
                                    imageSrc = cellValue;
                                    isUrl = true;
                                } else if (cellValue.match(/^[0-9]+$/)) {
                                    // It's an ID, try to construct image URL
                                    imageSrc = '{{ asset('uploads/all') }}/' +
                                        cellValue;
                                }

                                if (imageSrc) {
                                    bodyHtml +=
                                        '<td style="max-width: 150px; text-align: center;">' +
                                        '<img src="' + imageSrc +
                                        '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;" ' +
                                        'onerror="handleImageError(' + rowIndex +
                                        ', this)" ' +
                                        'onload="handleImageLoad(' + rowIndex +
                                        ', this)" ' +
                                        'data-row="' + rowIndex + '" ' +
                                        'data-url="' + escapeHtml(cellValue) + '" ' +
                                        '/>' +
                                        '<br><small style="font-size: 10px; color: #999; word-break: break-all;">' +
                                        escapeHtml(cellValue.substring(0, 30)) + (
                                            cellValue
                                            .length > 30 ? '...' : '') + '</small>' +
                                        '</td>';
                                } else {
                                    hasImageError = true;
                                    bodyHtml +=
                                        '<td style="max-width: 150px; text-align: center; background-color: #f8d7da;">' +
                                        '<span class="text-danger">Invalid image</span>' +
                                        '<br><small style="font-size: 10px; color: #999;">' +
                                        escapeHtml(cellValue.substring(0, 20)) +
                                        '</small>' +
                                        '</td>';
                                }
                            } else {
                                // ======== FIX: Handle HTML tags in description ========
                                var plainText = cellValue;

                                if (cellValue.indexOf('<') !== -1 && cellValue.indexOf(
                                        '>') !== -
                                    1) {
                                    var tempDiv = document.createElement('div');
                                    tempDiv.innerHTML = cellValue;
                                    plainText = tempDiv.textContent || tempDiv
                                        .innerText || '';
                                    if (plainText.trim() === '') {
                                        plainText = '[HTML Content]';
                                    }
                                }

                                var displayValue = plainText;
                                if (cellValue.trim().startsWith('{') || cellValue.trim()
                                    .startsWith(
                                        '[')) {
                                    try {
                                        var json = JSON.parse(cellValue);
                                        displayValue = JSON.stringify(json);
                                        if (displayValue.length > 150) {
                                            displayValue = displayValue.substring(0,
                                                    150) +
                                                '...';
                                        }
                                    } catch (e) {}
                                }

                                if (displayValue.length > 200) {
                                    displayValue = displayValue.substring(0, 200) +
                                        '...';
                                }

                                var escapedDisplay = escapeHtml(displayValue);
                                var escapedFull = escapeHtml(cellValue);

                                bodyHtml +=
                                    '<td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' +
                                    escapedFull + '">' + escapedDisplay + '</td>';
                            }
                        });
                        bodyHtml += '</tr>';
                    });

                    if (result.rows.length === 0) {
                        bodyHtml = '<tr><td colspan="' + (visibleHeaders.length + 2) +
                            '" class="text-center text-muted py-4">' +
                            '<i class="las la-inbox fs-40"></i><br>{{ translate('No data rows found') }}</td></tr>';
                    }

                    $('#previewBody').html(bodyHtml);

                    // Auto scroll to top of table
                    $('.table-responsive').scrollTop(0);

                    // Check for any image errors after a delay
                    setTimeout(checkImageErrors, 2000);
                };

                reader.readAsText(file);
            });

            // Handle image load success
            function handleImageLoad(rowIndex, img) {
                var statusCell = $('#status_' + rowIndex);
                if (statusCell.length) {
                    statusCell.html(
                        '<span class="badge badge-success"><i class="las la-check-circle"></i> OK</span>');
                }
                // Remove row highlight if no errors
                $('#row_' + rowIndex).removeClass('table-danger');
                $('#row_' + rowIndex).css('border-left', '');

                // Remove from error rows
                var index = imageErrorRows.indexOf(rowIndex);
                if (index > -1) {
                    imageErrorRows.splice(index, 1);
                }
                updateImageErrorSummary();
            }

            // Handle image load error
            function handleImageError(rowIndex, img) {
                var statusCell = $('#status_' + rowIndex);
                if (statusCell.length) {
                    statusCell.html(
                        '<span class="badge badge-danger"><i class="las la-times-circle"></i> Error</span>');
                }

                // Add red background to the row
                $('#row_' + rowIndex).addClass('table-danger');
                $('#row_' + rowIndex).css('border-left', '4px solid #dc3545');

                // Add to error rows if not already
                if (imageErrorRows.indexOf(rowIndex) === -1) {
                    imageErrorRows.push(rowIndex);
                }
                updateImageErrorSummary();

                // Show error on image
                if (img) {
                    $(img).hide();
                    $(img).after('<span class="text-danger" style="font-size: 11px;">Image failed to load</span>');
                }
            }

            // Check for image errors after page load
            function checkImageErrors() {
                // Check all images that might have failed silently
                $('#previewTable img').each(function() {
                    var img = this;
                    var rowIndex = parseInt($(img).data('row'));

                    // If image is broken
                    if (!img.complete || img.naturalWidth === 0) {
                        handleImageError(rowIndex, img);
                    }
                });

                // Update summary
                updateImageErrorSummary();
            }

            // Update image error summary
            function updateImageErrorSummary() {
                if (imageErrorRows.length > 0) {
                    $('#imageErrorSummary').show();
                    $('#imageErrorCount').text(imageErrorRows.length);
                } else {
                    $('#imageErrorSummary').hide();
                }
            }

            // Improved CSV Parser with better delimiter detection
            function parseCSV(text) {
                var lines = [];
                var currentLine = '';
                var inQuotes = false;
                var i = 0;

                if (text.charCodeAt(0) === 0xFEFF) {
                    text = text.substring(1);
                }

                text = text.replace(/\r\n/g, '\n').replace(/\r/g, '\n');

                var firstLine = text.split('\n')[0] || '';
                var delimiter = detectDelimiter(firstLine);

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
                        rows: []
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
                    while (rowData.length < headers.length) {
                        rowData.push('');
                    }
                    rows.push(rowData);
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

                return detectedDelimiter;
            }

            function escapeHtml(text) {
                if (!text) return '';
                var div = document.createElement('div');
                div.textContent = text;
                var escaped = div.innerHTML;
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return escaped.replace(/[&<>"']/g, function(m) {
                    return map[m];
                });
            }

            $('#importForm').on('submit', function() {
                var btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true);
                btn.html('<i class="las la-spinner la-spin"></i> {{ translate('Importing...') }}');
            });
        });
    </script>

    <style>
        #previewTable thead th {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        #previewTable tbody td:first-child {
            position: sticky;
            left: 0;
            z-index: 50;
            font-weight: bold;
            text-align: center;
            min-width: 50px;
            background: inherit;
        }

        #previewTable tbody td:nth-child(2) {
            position: sticky;
            left: 50px;
            z-index: 50;
            background: inherit;
            min-width: 60px;
        }

        #previewTable tbody tr:hover td:first-child,
        #previewTable tbody tr:hover td:nth-child(2) {
            background: #e8f4ff !important;
        }

        #previewTable tbody tr.table-danger:hover td:first-child,
        #previewTable tbody tr.table-danger:hover td:nth-child(2) {
            background: #f5c6cb !important;
        }

        #previewTable tbody tr:hover {
            background-color: #e8f4ff !important;
            cursor: default;
        }

        #previewTable tbody tr.table-danger:hover {
            background-color: #f5c6cb !important;
        }

        .table-responsive {
            border-radius: 8px;
            max-height: 500px;
            overflow-y: auto;
            overflow-x: auto;
        }

        .table-responsive::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        #previewTable {
            font-size: 13px;
            margin-bottom: 0;
        }

        #previewTable th {
            padding: 8px 12px;
            white-space: nowrap;
        }

        #previewTable td {
            padding: 6px 12px;
            vertical-align: middle;
            font-size: 12px;
        }

        .table-light {
            background-color: #f8f9fa !important;
        }

        .table-danger {
            background-color: #f8d7da !important;
        }

        .card-header.bg-primary {
            background: #0066cc !important;
            border-radius: 8px 8px 0 0;
        }

        .badge.bg-light {
            background-color: #fff !important;
            color: #333 !important;
            padding: 5px 12px;
            font-size: 12px;
        }

        .badge-success {
            background-color: #28a745 !important;
            color: #fff !important;
        }

        .badge-danger {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        .badge-secondary {
            background-color: #6c757d !important;
            color: #fff !important;
        }

        #imageErrorSummary {
            border-radius: 0 0 8px 8px;
            padding: 8px 15px;
            font-size: 14px;
        }

        #previewTable td img {
            transition: transform 0.2s;
        }

        #previewTable td img:hover {
            transform: scale(3);
            z-index: 999;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
        }

        #previewTable td img[style*="display: none"] {
            display: none !important;
        }
    </style>
@endsection
