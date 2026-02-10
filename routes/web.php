<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\PrintTransactionController;
use App\Http\Controllers\SacramentPrintController;
// Livewire Components
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Users;
use App\Livewire\Families;
use App\Livewire\Members;
use App\Livewire\Officers;
use App\Livewire\Transactions;
use App\Livewire\Budgets;
use App\Livewire\Finance;
use App\Livewire\Reports;
use App\Livewire\Settings;
use App\Livewire\Schedules;
use App\Livewire\Auctions;
use App\Livewire\Clerical\SacramentManager;
use App\Livewire\Letters;
use App\Livewire\Settings\Accounts;

// --- 1. HALAMAN LOGIN (Publik) ---
Route::get('/', Login::class)->name('login');

// --- 2. AREA LOGIN (Wajib Auth) ---
Route::middleware('auth')->group(function () {

    // AKSES UMUM (Semua User Login Bisa Masuk)
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/my-schedules', Schedules\MySchedules::class)->name('schedules.my'); // Jadwal Pribadi
    // LOGOUT
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');


    // --- HAK AKSES KHUSUS (ROLE & PERMISSION) ---

    // A. SUPER ADMIN (Manajemen User & Sistem)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', Users\Index::class)->name('users.index');
        Route::get('/users/create', Users\Create::class)->name('users.create');
        Route::get('/users/{user}/edit', Users\Edit::class)->name('users.edit');

        // Master Data & Settings
        Route::get('/settings/positions', Settings\Positions::class)->name('settings.positions');
        Route::get('/settings/activity-types', Settings\ActivityTypes::class)->name('settings.activity-types');
        Route::get('/budgets/budget-posts', Settings\BudgetPosts::class)->name('settings.budget-posts');
        Route::get('/settings/accounts', Settings\Accounts::class)->name('settings.accounts'); // Disesuaikan nama rutenya
        Route::get('/settings/accounts/dompet', Accounts::class)->name('settings.accounts.dompet');
        Route::get('/settings/roles', \App\Livewire\Settings\RoleManager::class)->name('settings.roles');
        Route::get('/settings/assets', \App\Livewire\Settings\AssetManager::class)->middleware(['auth'])->name('settings.assets');
        Route::get('/settings/{type}', Settings\MasterData::class)->name('settings.master');
    });

    // B. SEKRETARIAT (Sekretaris & Admin) -> Database Jemaat & Jadwal
    Route::middleware(['permission:manage_database|manage_schedules'])->group(function () {
        // Keluarga
        Route::get('/families', Families\Index::class)->name('families.index');
        Route::get('/families/create', Families\Create::class)->name('families.create');
        Route::get('/families/{family}', \App\Livewire\Families\Show::class)->name('families.show');
        Route::get('/families/{family}/edit', Families\Edit::class)->name('families.edit');
        Route::get('/families/{family}/members/create', Members\Create::class)->name('members.create');

        // Jemaat
        Route::get('/members', Members\Index::class)->name('members.index');
        Route::get('/members/{member}/edit', Members\Edit::class)->name('members.edit');
        Route::get('/members/{member}', Members\Show::class)->name('members.show');

        // Pejabat (HR)
        Route::get('/officers', Officers\Index::class)->name('officers.index');
        Route::get('/officers/create', Officers\Create::class)->name('officers.create');
        Route::get('/officers/{officer}/edit', Officers\Edit::class)->name('officers.edit');
        Route::get('/officers/{officer}/show', Officers\Show::class)->name('officers.show');

        Route::get('/pastoral/visits', \App\Livewire\Pastoral\PastoralManager::class)->name('pastoral.visits');
        Route::get('/clerical/sacraments', SacramentManager::class)->name('clerical.sacraments');
        Route::get('/clerical/sacraments/{record}/print', [SacramentPrintController::class, 'show'])->name('clerical.sacraments.print');
        // Surat & Jadwal Umum
        Route::get('/letters', Letters\LetterManager::class)->name('letters.index');
        Route::get('/schedules', Schedules\ScheduleManager::class)->name('schedules.index');
        Route::get('/schedules/{schedule}/servants', Schedules\ServantManager::class)->name('schedules.servants');
        Route::get('/schedules/groups', Schedules\GroupManager::class)->name('schedules.groups');
        Route::get('/schedules/pks/print', [App\Http\Controllers\PrintScheduleController::class, 'pks'])->name('schedules.pks.print');
    });

    // C. KEUANGAN (Bendahara & Admin) -> Transaksi, Lelang, Gaji
    Route::middleware(['permission:manage_finance'])->group(function () {
        // Jurnal Kas
        Route::get('/transactions', Transactions\Index::class)->name('transactions.index');
        Route::get('/transactions/create', Transactions\Create::class)->name('transactions.create');
        Route::get('/transactions/{transaction}/edit', Transactions\Edit::class)->name('transactions.edit');
        Route::get('/transactions/{transaction}/print', [PrintTransactionController::class, 'show'])->name('transactions.print');

        // Payroll
        Route::get('/finance/payroll', Finance\PayrollManager::class)->name('finance.payroll');
        Route::get('/finance/payroll/slip/{uuid}', Finance\PayrollSlip::class)->name('payroll.slip');

        // Lelang
        Route::get('/auctions', Auctions\EventIndex::class)->name('auctions.index');
        Route::get('/auctions/receivables', \App\Livewire\Auctions\Receivables::class)->name('auctions.receivables');
        Route::get('/finance/diakonia', \App\Livewire\Finance\DiakoniaManager::class)->name('finance.diakonia');
        Route::get('/auctions/{event}', Auctions\ItemManager::class)->name('auctions.items');
        Route::get('/finance/flexible-dues', \App\Livewire\Finance\FlexibleDuesManager::class)->name('finance.flexible-dues');
    });

    // D. KONTROL ANGGARAN (Bendahara) -> RAPB & Saldo Awal
    Route::middleware(['permission:manage_budget'])->group(function () {
        Route::get('/budgets/manage', Budgets\Manage::class)->name('budgets.manage');
        Route::get('/finance/opening-balances', Finance\OpeningBalances::class)->name('finance.opening-balances');

        // Verifikasi PKS (Hanya Bendahara yang boleh terima uang)
        Route::get('/schedules/pks/verify', Schedules\PksVerification::class)->name('schedules.pks.verify');
    });

    // E. INPUT LAPANGAN (Majelis Wilayah / Operator) -> Input PKS
    Route::middleware(['permission:input_pks'])->group(function () {
        Route::get('/schedules/pks', Schedules\PksScheduler::class)->name('schedules.pks');
    });

    // F. LAPORAN (Semua Pejabat Inti)
    Route::middleware(['permission:view_reports'])->group(function () {
        Route::get('/reports/weekly', Reports\Weekly::class)->name('reports.weekly');
        Route::get('/reports/budget-realization', Reports\BudgetRealization::class)->name('reports.budget-realization');
        Route::get('/reports/general-ledger', Reports\GeneralLedger::class)->name('reports.general-ledger');
        Route::get('/reports/finance', \App\Livewire\Reports\FinanceMonthly::class)->name('reports.monthly');
        Route::get('/reports/census', \App\Livewire\Reports\MemberCensus::class)->name('reports.census');
    });
    Route::get('/finance/due-types', \App\Livewire\Settings\DueTypeManager::class)->middleware(['auth', 'can:manage_settings'])->name('settings.due-types');
});
