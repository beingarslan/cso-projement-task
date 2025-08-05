<!-- Search and Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search by name or email...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="perPageSelect">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()">
                    <i class="fas fa-times me-1"></i>Clear
                </button>
            </div>
        </div>
    </div>
</div>
