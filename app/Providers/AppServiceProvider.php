<?php

namespace App\Providers;

use App\Models\GoodsReceipt;
use App\Models\PurchaseReturn;
use App\Models\SalesOrder;
use App\Models\SalesReturn;
use App\Models\Shipment;
use App\Models\StockOpname;
use App\Models\Transfer;
use App\Observers\GoodsReceiptObserver;
use App\Observers\PurchaseReturnObserver;
use App\Observers\SalesOrderObserver;
use App\Observers\SalesReturnObserver;
use App\Observers\ShipmentObserver;
use App\Observers\StockOpnameObserver;
use App\Observers\TransferObserver;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerObservers();
    }

    /**
     * Register model observers for stock management.
     */
    protected function registerObservers(): void
    {
        GoodsReceipt::observe(GoodsReceiptObserver::class);
        Shipment::observe(ShipmentObserver::class);
        Transfer::observe(TransferObserver::class);
        SalesOrder::observe(SalesOrderObserver::class);
        PurchaseReturn::observe(PurchaseReturnObserver::class);
        SalesReturn::observe(SalesReturnObserver::class);
        StockOpname::observe(StockOpnameObserver::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
