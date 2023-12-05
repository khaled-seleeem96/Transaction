<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;

class UserService
{
    public function getUsersWithFilters($statusCode, $currency, $amountRange, $dateRange)
    {
        try {
            $query = User::query();
            $query->with('transactions');

            if ($statusCode) {
                $query->with(['transactions' => function ($transactionQuery) use ($statusCode) {
                    $transactionQuery->where('statusCode', $statusCode);
                }])->whereHas('transactions', function ($transactionQuery) use ($statusCode) {
                    $transactionQuery->where('statusCode', $statusCode);
                });
            }

            if ($currency) {
                $query->where('currency', $currency);
            }

            if ($amountRange) {
                $query->with(['transactions' => function ($transactionQuery) use ($amountRange) {
                    $transactionQuery->whereBetween('paidAmount', $amountRange);
                }])->whereHas('transactions', function ($transactionQuery) use ($amountRange) {
                    $transactionQuery->whereBetween('paidAmount', $amountRange);
                });
            }

            if ($dateRange) {
                $startDate = $dateRange[0];
                $endDate = $dateRange[1] ?? $dateRange[0];
                $query->with(['transactions' => function ($transactionQuery) use ($startDate, $endDate) {
                    $transactionQuery->whereBetween('paymentDate', [$startDate, $endDate]);
                }])->whereHas('transactions', function ($transactionQuery) use ($startDate, $endDate) {
                    $transactionQuery->whereBetween('paymentDate', [$startDate, $endDate]);
                });
            }

            return $query->get();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
