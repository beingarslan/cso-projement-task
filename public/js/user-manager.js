/**
 * User Management JavaScript
 * Handles all CRUD operations for users with AJAX
 */

class UserManager {
    constructor() {
        this.currentUserId = null;
        this.isEditMode = false;
        this.currentPage = 1;
        this.perPage = 10;
        this.searchQuery = '';

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadUsers();
    }

    setupEventListeners() {
        // Search functionality with debounce
        let searchTimeout;
        $('#searchInput').on('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.searchQuery = $('#searchInput').val();
                this.currentPage = 1;
                this.loadUsers();
            }, 500);
        });

        // Per page change
        $('#perPageSelect').on('change', () => {
            this.perPage = $('#perPageSelect').val();
            this.currentPage = 1;
            this.loadUsers();
        });

        // Form submission
        $('#userForm').on('submit', (e) => this.handleFormSubmission(e));

        // Delete confirmation
        $('#confirmDeleteBtn').on('click', () => this.confirmDelete());

        // Reset form when modal is hidden
        $('#userModal').on('hidden.bs.modal', () => this.resetForm());
    }

    // Load users with pagination
    loadUsers(page = this.currentPage) {
        this.currentPage = page;

        $('#loadingIndicator').show();
        $('#usersTable').hide();
        $('#emptyState').hide();
        $('#paginationInfo').empty();
        $('#paginationControls').empty();

        $.ajax({
            url: '/users/data',
            type: 'GET',
            data: {
                page: this.currentPage,
                per_page: this.perPage,
                search: this.searchQuery
            },
            success: (response) => {
                if (response.success) {
                    this.displayUsers(response.data);
                    this.displayPagination(response.pagination);
                }
            },
            error: () => {
                window.showToast('Failed to load users', 'danger');
            },
            complete: () => {
                $('#loadingIndicator').hide();
            }
        });
    }

    // Display users in table
    displayUsers(users) {
        const tbody = $('#usersTableBody');
        tbody.empty();

        if (users.length === 0) {
            $('#usersTable').hide();
            $('#emptyState').show();
            return;
        }

        $('#usersTable').show();
        $('#emptyState').hide();

        users.forEach((user) => {
            const row = `
                <tr id="user-row-${user.id}">
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${new Date(user.created_at).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'})}</td>
                    <td class="table-actions">
                        <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                onclick="userManager.editUser(${user.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                                onclick="userManager.deleteUser(${user.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Display pagination
    displayPagination(pagination) {
        // Pagination info
        const info = `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total} users`;
        $('#paginationInfo').text(info);

        // Pagination controls
        const controls = $('#paginationControls');
        controls.empty();

        if (pagination.last_page > 1) {
            // Previous button
            if (pagination.current_page > 1) {
                controls.append(`
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="userManager.loadUsers(${pagination.current_page - 1})">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                `);
            }

            // Page numbers
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

            if (startPage > 1) {
                controls.append(`
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="userManager.loadUsers(1)">1</a>
                    </li>
                `);
                if (startPage > 2) {
                    controls.append('<li class="page-item disabled"><span class="page-link">...</span></li>');
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const activeClass = i === pagination.current_page ? 'active' : '';
                controls.append(`
                    <li class="page-item ${activeClass}">
                        <a class="page-link" href="#" onclick="userManager.loadUsers(${i})">${i}</a>
                    </li>
                `);
            }

            if (endPage < pagination.last_page) {
                if (endPage < pagination.last_page - 1) {
                    controls.append('<li class="page-item disabled"><span class="page-link">...</span></li>');
                }
                controls.append(`
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="userManager.loadUsers(${pagination.last_page})">${pagination.last_page}</a>
                    </li>
                `);
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                controls.append(`
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="userManager.loadUsers(${pagination.current_page + 1})">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                `);
            }
        }
    }

    // Clear search
    clearSearch() {
        $('#searchInput').val('');
        this.searchQuery = '';
        this.currentPage = 1;
        this.loadUsers();
    }

    // Open create modal
    openCreateModal() {
        this.resetForm();
        this.isEditMode = false;
        $('#userModalLabel').text('Add New User');
        $('#formMethod').val('POST');
        $('#password').prop('required', true);
        $('#password-required').show();
        $('#password-help').text('Minimum 6 characters');
        $('#saveBtn').html('<i class="fas fa-save me-2"></i>Save User');
    }

    // Edit user
    editUser(userId) {
        this.isEditMode = true;
        this.currentUserId = userId;

        // Fetch user data
        $.get(`/users/${userId}`)
            .done((response) => {
                if (response.success) {
                    const user = response.data;

                    // Populate form
                    $('#userId').val(user.id);
                    $('#name').val(user.name);
                    $('#email').val(user.email);
                    $('#password').val('').prop('required', false);

                    // Update modal
                    $('#userModalLabel').text('Edit User');
                    $('#formMethod').val('PUT');
                    $('#password-required').hide();
                    $('#password-help').text('Leave blank to keep current password');
                    $('#saveBtn').html('<i class="fas fa-save me-2"></i>Update User');

                    // Show modal
                    $('#userModal').modal('show');
                }
            });
    }

    // Delete user
    deleteUser(userId) {
        this.currentUserId = userId;
        $('#deleteModal').modal('show');
    }

    // Confirm delete
    confirmDelete() {
        if (this.currentUserId) {
            $.ajax({
                url: `/users/${this.currentUserId}`,
                type: 'DELETE',
                success: (response) => {
                    if (response.success) {
                        window.showToast(response.message, 'success');
                        $('#deleteModal').modal('hide');
                        this.loadUsers(); // Reload current page
                    }
                }
            });
        }
    }

    // Handle form submission
    handleFormSubmission(e) {
        e.preventDefault();

        // Clear previous errors
        this.clearFormErrors();

        // Prepare form data
        let formData;
        let contentType;
        let processData;

        if (this.isEditMode) {
            // For PUT requests, use JSON data
            const data = {
                name: $('#name').val(),
                email: $('#email').val(),
                user_id: $('#userId').val(), // Include user ID for unique email validation
                _method: 'PUT'
            };

            // Only include password if it's not empty
            const password = $('#password').val();
            if (password && password.trim() !== '') {
                data.password = password;
            }

            formData = JSON.stringify(data);
            contentType = 'application/json';
            processData = false;
        } else {
            // For POST requests, use FormData
            formData = new FormData(e.target);
            contentType = false;
            processData = false;
        }

        const method = this.isEditMode ? 'PUT' : 'POST';
        const url = this.isEditMode ? `/users/${this.currentUserId}` : '/users';

        // Show loading state
        const saveBtn = $('#saveBtn');
        const originalText = saveBtn.html();
        saveBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: processData,
            contentType: contentType,
            success: (response) => {
                if (response.success) {
                    window.showToast(response.message, 'success');
                    $('#userModal').modal('hide');
                    this.loadUsers(); // Reload current page
                }
            },
            error: (xhr) => {
                if (xhr.status === 422) {
                    this.displayFormErrors(xhr.responseJSON.errors);
                }
            },
            complete: () => {
                saveBtn.html(originalText).prop('disabled', false);
            }
        });
    }

    // Reset form
    resetForm() {
        $('#userForm')[0].reset();
        $('#userId').val('');
        this.currentUserId = null;
        this.clearFormErrors();
    }

    // Clear form errors
    clearFormErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').empty();
    }

    // Display form errors
    displayFormErrors(errors) {
        for (let field in errors) {
            const input = $(`#${field}`);
            const errorDiv = $(`#${field}-error`);

            input.addClass('is-invalid');
            errorDiv.text(errors[field][0]);
        }
    }
}

// Initialize when document is ready
$(document).ready(function() {
    window.userManager = new UserManager();
});

// Global functions for onclick handlers
function openCreateModal() {
    window.userManager.openCreateModal();
}

function clearSearch() {
    window.userManager.clearSearch();
}
