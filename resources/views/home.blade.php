@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Dashboard</h4>
            <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-bell"></i>
            </button>
            <button class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-server text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">3</h3>
                            <small class="text-muted">Running Containers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-database text-success fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">12</h3>
                            <small class="text-muted">Database Tables</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                            <i class="fab fa-aws text-info fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">5</h3>
                            <small class="text-muted">AWS Services</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-bolt text-warning fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">8</h3>
                            <small class="text-muted">API Endpoints</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Services Status -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-server text-primary me-2"></i>
                        Services Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Port</th>
                                    <th>Version</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-success me-3">●</span>
                                            <div>
                                                <strong>Laravel Application</strong>
                                                <div class="text-muted small">Web Server</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Running</span></td>
                                    <td><code>8080</code></td>
                                    <td>PHP 8.2</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-success me-3">●</span>
                                            <div>
                                                <strong>MySQL Database</strong>
                                                <div class="text-muted small">Database Server</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Running</span></td>
                                    <td><code>3306</code></td>
                                    <td>8.0</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-success me-3">●</span>
                                            <div>
                                                <strong>phpMyAdmin</strong>
                                                <div class="text-muted small">Database Management</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Running</span></td>
                                    <td><code>8081</code></td>
                                    <td>5.2</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg py-2">
                            <i class="fas fa-cloud-upload-alt me-2"></i>
                            Deploy to Production
                        </button>
                        <button class="btn btn-outline-secondary py-2">
                            <i class="fas fa-code me-2"></i>
                            View API Documentation
                        </button>
                        <button class="btn btn-outline-secondary py-2">
                            <i class="fas fa-database me-2"></i>
                            Manage Database
                        </button>
                        <button class="btn btn-outline-secondary py-2">
                            <i class="fas fa-chart-bar me-2"></i>
                            View Analytics
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-history text-info me-2"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-sign-in-alt text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">User Login</div>
                                    <small class="text-muted">Just now</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-server text-success"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Containers Started</div>
                                    <small class="text-muted">5 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-database text-info"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Database Connected</div>
                                    <small class="text-muted">10 minutes ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    border-radius: 8px;
    transition: all 0.2s ease;
}

.table th {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
    text-transform: uppercase;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.5rem;
}

.badge {
    font-size: 0.75em;
}

code {
    background: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.875em;
    color: #e83e8c;
}
</style>
@endsection
