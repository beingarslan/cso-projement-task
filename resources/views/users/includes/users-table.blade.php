<!-- Users Table -->
<div class="card shadow-sm">
    <div class="card-body">
        <!-- Loading indicator -->
        <div id="loadingIndicator" class="text-center py-4" style="display: none;">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2 text-muted">Loading users...</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="usersTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- Users will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Empty state -->
        <div id="emptyState" class="text-center py-4" style="display: none;">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <p class="text-muted">No users found.</p>
        </div>

        <!-- Pagination info and controls -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div id="paginationInfo" class="text-muted">
                    <!-- Pagination info will be displayed here -->
                </div>
            </div>
            <div class="col-md-6">
                <nav aria-label="Users pagination">
                    <ul class="pagination justify-content-end mb-0" id="paginationControls">
                        <!-- Pagination controls will be displayed here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
