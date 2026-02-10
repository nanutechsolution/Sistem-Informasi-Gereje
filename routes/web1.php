<?php

use App\Http\Controllers\PrintTransactionController;
use App\Livewire\Auctions\EventIndex;
use App\Livewire\Auctions\ItemManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\Login;
use App\Livewire\Budgets\Manage;
use App\Livewire\Dashboard;
use App\Livewire\Families\Create as FamiliesCreate;
use App\Livewire\Families\Edit as FamiliesEdit;
use App\Livewire\Families\Index as FamiliesIndex;
use App\Livewire\Finance\OpeningBalances;
use App\Livewire\Finance\PayrollManager;
use App\Livewire\Members\Create as MembersCreate;
use App\Livewire\Members\Edit as MembersEdit;
use App\Livewire\Members\Index as MembersIndex;
use App\Livewire\Members\Show;
use App\Livewire\Officers\Create as OfficersCreate;
use App\Livewire\Officers\Edit as OfficersEdit;
use App\Livewire\Officers\Index as OfficersIndex;
use App\Livewire\Officers\Show as OfficersShow;
use App\Livewire\Reports\BudgetRealization;
use App\Livewire\Reports\GeneralLedger;
use App\Livewire\Reports\Weekly;
use App\Livewire\Schedules\GroupManager;
use App\Livewire\Schedules\MySchedules;
use App\Livewire\Schedules\PksScheduler;
use App\Livewire\Schedules\ScheduleManager;
use App\Livewire\Settings\Accounts as AccountsGereja;
use App\Livewire\Settings\ActivityTypes;
use App\Livewire\Settings\BudgetPosts;
use App\Livewire\Settings\MasterData;
use App\Livewire\Transactions\Create as TransactionsCreate;
use App\Livewire\Transactions\Edit as TransactionsEdit;
use App\Livewire\Transactions\Index as TransactionsIndex;
use App\Livewire\Users\Index;
use App\Livewire\Users\Create;
use App\Livewire\Users\Edit;

// Halaman Login (Utama)
Route::get('/', Login::class)->name('login');

// Group Middleware Auth (Hanya bisa diakses jika login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    // Manajemen Users
    Route::get('/users', Index::class)->name('users.index');
    Route::get('/users/create', Create::class)->name('users.create');
    Route::get('/users/{user}/edit', Edit::class)->name('users.edit');
    Route::get('/families', FamiliesIndex::class)->name('families.index');
    Route::get('/families/create', FamiliesCreate::class)->name('families.create');
    Route::get('/families/{family}/edit', FamiliesEdit::class)->name('families.edit');
    Route::get('/families/{family}/members/create', MembersCreate::class)->name('members.create');
    Route::get('/members/{member}/edit', MembersEdit::class)->name('members.edit');
    Route::get('/members', MembersIndex::class)->name('members.index');
    Route::get('/members/{member}', Show::class)->name('members.show');
    Route::get('/settings/positions', \App\Livewire\Settings\Positions::class)->name('settings.positions');
    Route::get('/settings/activity-types', App\Livewire\Settings\ActivityTypes::class)->name('settings.activity-types');
    Route::get('/settings/{type}', MasterData::class)->name('settings.master');

    // keuangan
    Route::get('/transactions', TransactionsIndex::class)->name('transactions.index');
    Route::get('/transactions/create', TransactionsCreate::class)->name('transactions.create');
    Route::get('/transactions/{transaction}/edit', TransactionsEdit::class)->name('transactions.edit');
    Route::get('/reports/budget-realization', BudgetRealization::class)->name('reports.budget-realization');
    Route::get('/reports/general-ledger', GeneralLedger::class)->name('reports.general-ledger');

    // print
    Route::get('/transactions/{transaction}/print', [PrintTransactionController::class, 'show'])->name('transactions.print');
    Route::get('/budgets/manage', Manage::class)->name('budgets.manage');
    Route::get('/budgets/budget-posts', BudgetPosts::class)->name('settings.budget-posts');
    Route::get('/finance/opening-balances', OpeningBalances::class)->name('finance.opening-balances');
    Route::get('/reports/weekly', Weekly::class)->name('reports.weekly');
    Route::get('/settings/accounts/dompet', AccountsGereja::class)->name('settings.accounts.dompet');
    Route::get('/auctions', EventIndex::class)->name('auctions.index');
    Route::get('/auctions/{event}', ItemManager::class)->name('auctions.items');

    Route::get('/officers', OfficersIndex::class)->name('officers.index');
    Route::get('/officers/create', OfficersCreate::class)->name('officers.create');
    Route::get('/officers/{officer}/edit', OfficersEdit::class)->name('officers.edit');
    Route::get('/officers/{officer}/show', OfficersShow::class)->name('officers.show');
    Route::get('/finance/payroll', PayrollManager::class)->name('finance.payroll');
    Route::get('/finance/payroll/slip/{uuid}', App\Livewire\Finance\PayrollSlip::class)->name('payroll.slip');
    Route::get('/schedules', ScheduleManager::class)->name('schedules.index');
    Route::get('/schedules/pks', PksScheduler::class)->name('schedules.pks');
    Route::get('/schedules/{schedule}/servants', App\Livewire\Schedules\ServantManager::class)->name('schedules.servants');
    Route::get('/schedules/pks/verify', \App\Livewire\Schedules\PksVerification::class)->name('schedules.pks.verify');
    Route::get('/my-schedules', MySchedules::class)->name('schedules.my');
    Route::get('/schedules/groups', GroupManager::class)->name('schedules.groups');
    Route::get('/letters', \App\Livewire\Letters\LetterManager::class)->name('letters.index');
});
Route::post('/logout', function (Request $request) {
    Auth::logout();

    // Invalidate session
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');
