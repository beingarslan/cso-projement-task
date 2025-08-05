<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel CRUD')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .table-actions {
            white-space: nowrap;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .modal-header {
            background-color: #f8f9fa;
        }
        .alert {
            border-radius: 0.375rem;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-users me-2"></i>Laravel CRUD
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toast-body">
                    <!-- Toast message will be inserted here -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <script>
        // Setup CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = $('#toast');
            const toastBody = $('#toast-body');

            // Set toast content and style
            toastBody.text(message);
            toast.removeClass('bg-success bg-danger bg-warning').addClass(`bg-${type}`);

            // Show toast
            const bsToast = new bootstrap.Toast(toast[0]);
            bsToast.show();
        }

        // Handle AJAX errors globally
        $(document).ajaxError(function(event, xhr, settings) {
            if (xhr.status === 422) {
                // Validation errors
                const errors = xhr.responseJSON.errors;
                let errorMessages = [];
                for (let field in errors) {
                    errorMessages.push(errors[field][0]);
                }
                showToast(errorMessages.join(', '), 'danger');
            } else {
                showToast('An error occurred. Please try again.', 'danger');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
