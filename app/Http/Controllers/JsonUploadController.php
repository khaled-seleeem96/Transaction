<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JsonUploadController extends Controller
{
    public function importDataFromJson(Request $request)
    {
        try {
            $usersData = $this->parseJson($request->users);
            $transactionsData = $this->parseJson($request->transactions);

            foreach ($usersData['users'] as $userData) {
                $user = $this->createOrUpdateUser($userData);
                $this->saveUserTransactions($user, $transactionsData);
            }

            return response()->json(['success' => 'All data imported successfully']);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function parseJson($filePath)
    {
        return json_decode(file_get_contents($filePath), true);
    }

    private function createOrUpdateUser(array $userData)
    {
        return User::updateOrCreate(
            ['email' => $userData['email']],
            [
                'balance' => $userData['balance'],
                'currency' => $userData['currency'],
                'serialNumber' => $userData['id'],
                'created_at' => $this->convertDateFormat($userData['created_at']),
            ]
        );
    }

    private function convertDateFormat($date)
    {
        return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d H:i:s');
    }

    private function saveUserTransactions(User $user, $transactionsData)
    {
        collect($transactionsData['transactions'])
            ->where('parentEmail', $user->email)
            ->each(function ($transactionData) use ($user) {
                Transaction::updateOrCreate([
                    'paidAmount' => $transactionData['paidAmount'],
                    'Currency' => $transactionData['Currency'],
                    'parentEmail' => $transactionData['parentEmail'],
                    'statusCode' => $transactionData['statusCode'],
                    'paymentDate' => $transactionData['paymentDate'],
                    'parentIdentification' => $transactionData['parentIdentification'],
                ]);
            });
    }

}
