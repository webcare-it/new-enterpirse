<!-- Modern Premium Delete Modal -->
<div id="delete-modal" class="modal fade">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .custom-close {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(220, 53, 69, 0.1);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }

        .custom-close i {
            color: #dc3545;
            font-size: 18px;
        }

        .custom-close:hover {
            background: #dc3545;
        }

        .custom-close:hover i {
            color: #fff;
        }
    </style>

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- Header -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold text-dark">
                    Delete Item
                </h5>
                <button type="button" class="custom-close" data-dismiss="modal">
                    <i class="las la-times"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body text-center px-4 pb-4">

                <!-- Icon with soft background -->
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                    style="width: 80px; height: 80px; border-radius: 50%; background: rgba(220, 53, 69, 0.1);">
                    <i class="las la-trash text-danger" style="font-size: 36px;"></i>
                </div>

                <!-- Title -->
                <h4 class="fw-bold mb-2">Are you sure?</h4>

                <!-- Description -->
                <p class="text-muted mb-4" style="font-size: 14px;">
                    This action cannot be undone. All related data will be permanently removed.
                </p>

                <!-- Buttons -->
                <div class="d-flex justify-content-center gap-3">

                    <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-medium" data-dismiss="modal">
                        Cancel
                    </button>

                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold shadow-sm">
                            <i class="las la-trash me-1"></i>
                            Delete
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
