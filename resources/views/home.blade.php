@extends('layouts.app')

@section('title', 'Tableau de bord')

@push('styles')
    @vite('resources/css/home.css')
@endpush

@section('content')
<!-- Dashboard Header -->
<div class="dashboard-header">
    <h1 class="dashboard-title">Tableau de bord</h1>
    <p class="dashboard-subtitle">Bienvenue, {{ Auth::user()->name }}</p>
</div>

<!-- Welcome Banner -->
@if($stats['total_wallets'] === 0)
<div class="welcome-banner">
    <h2>🎉 Commencez votre aventure crypto !</h2>
    <p>Vous n'avez pas encore de wallet. Créez-en un maintenant pour commencer.</p>
</div>
@endif

<!-- Stats Grid -->
<div class="stats-grid">
    <!-- Total Wallets -->
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Total de Wallets</span>
            <div class="stat-card-icon">
                💼
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['total_wallets'] }}</div>
        <div class="stat-card-change">
            <span>{{ $stats['active_wallets'] }} actifs</span>
        </div>
    </div>

    <!-- Total Balance ETH -->
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Balance totale (ETH)</span>
            <div class="stat-card-icon">
                ⟠
            </div>
        </div>
        <div class="stat-card-value">{{ number_format($stats['total_balance_eth'], 4) }}</div>
        <div class="stat-card-change">
            <span>ETH</span>
        </div>
    </div>

    <!-- Total Balance USD -->
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Valeur totale (USD)</span>
            <div class="stat-card-icon">
                💵
            </div>
        </div>
        <div class="stat-card-value">${{ number_format($stats['total_balance_usd'], 2) }}</div>
        <div class="stat-card-change">
            <span>USD</span>
        </div>
    </div>

    <!-- Networks -->
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Réseaux actifs</span>
            <div class="stat-card-icon">
                🌐
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['networks']->count() }}</div>
        <div class="stat-card-change">
            @if($stats['networks']->count() > 0)
                <span>{{ $stats['networks']->implode(', ') }}</span>
            @else
                <span>Aucun réseau</span>
            @endif
        </div>
    </div>
</div>

<!-- Actions Section -->
<div class="actions-section">
    <h2 class="section-title">Actions rapides</h2>
    <div class="actions-grid">
        @if(Route::has('wallets.create'))
        <a href="{{ route('wallets.create') }}" class="action-card">
            <div class="action-card-icon">➕</div>
            <h3 class="action-card-title">Créer un Wallet</h3>
            <p class="action-card-description">Créez un nouveau wallet pour gérer vos crypto-monnaies</p>
        </a>
        @endif

        @if(Route::has('wallets.import'))
        <a href="{{ route('wallets.import') }}" class="action-card">
            <div class="action-card-icon">📥</div>
            <h3 class="action-card-title">Importer un Wallet</h3>
            <p class="action-card-description">Importez un wallet existant avec votre clé privée</p>
        </a>
        @endif

        @if(Route::has('wallets.index'))
        <a href="{{ route('wallets.index') }}" class="action-card">
            <div class="action-card-icon">📋</div>
            <h3 class="action-card-title">Voir tous les Wallets</h3>
            <p class="action-card-description">Consultez et gérez tous vos wallets</p>
        </a>
        @endif
    </div>
</div>

<!-- Recent Wallets Section -->
@if($recentWallets->count() > 0)
<div class="actions-section">
    <h2 class="section-title">Wallets récents</h2>
    <div class="wallets-list">
        @foreach($recentWallets->take(5) as $wallet)
        <div class="wallet-card">
            <div class="wallet-card-header">
                <div class="wallet-info">
                    <h3 class="wallet-name">{{ $wallet->name }}</h3>
                    <p class="wallet-address">{{ Str::limit($wallet->address, 20) }}</p>
                </div>
                <div class="wallet-network">
                    <span class="network-badge">{{ strtoupper($wallet->network) }}</span>
                </div>
            </div>
            <div class="wallet-card-body">
                <div class="wallet-balance">
                    <span class="balance-label">Balance:</span>
                    <span class="balance-value">{{ number_format($wallet->balance, 4) }} ETH</span>
                </div>
                <div class="wallet-balance-usd">
                    <span class="balance-usd">${{ number_format($wallet->balance_usd, 2) }} USD</span>
                </div>
            </div>
            <div class="wallet-card-footer">
                @if(Route::has('wallets.show'))
                <a href="{{ route('wallets.show', $wallet) }}" class="btn-view">
                    Voir les détails →
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
/* Wallets list styling */
.wallets-list {
    display: grid;
    gap: 1.5rem;
}

.wallet-card {
    background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-secondary) 100%);
    border: 1px solid var(--border-primary);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.wallet-card:hover {
    border-color: var(--gold);
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.15);
    transform: translateX(4px);
}

.wallet-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.wallet-info {
    flex: 1;
}

.wallet-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.wallet-address {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0;
}

.wallet-network {
    margin-left: 1rem;
}

.network-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: rgba(212, 175, 55, 0.1);
    color: var(--gold);
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.wallet-card-body {
    margin-bottom: 1rem;
}

.wallet-balance {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.balance-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.balance-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

.wallet-balance-usd {
    text-align: right;
}

.balance-usd {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.wallet-card-footer {
    border-top: 1px solid var(--border-primary);
    padding-top: 1rem;
    display: flex;
    justify-content: flex-end;
}

.btn-view {
    color: var(--gold);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.btn-view:hover {
    color: var(--gold-light);
    transform: translateX(4px);
}

/* Alerts */
.alert {
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    margin-bottom: 1.5rem;
    border: 1px solid;
}

.alert-success {
    background-color: rgba(34, 197, 94, 0.1);
    border-color: rgba(34, 197, 94, 0.3);
    color: #22c55e;
}

.alert-error {
    background-color: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

@media (max-width: 768px) {
    .wallet-card-header {
        flex-direction: column;
    }
    
    .wallet-network {
        margin-left: 0;
        margin-top: 0.5rem;
    }
    
    .wallet-balance {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .wallet-balance-usd {
        text-align: left;
    }
}
</style>
@endpush
