<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateOrdersCustomerId extends Command
{
    protected $signature = 'orders:update-customer-id {--dry-run : Apenas mostra o que seria alterado}';

    protected $description = 'Atualiza pedidos antigos que não têm customer_id, vinculando-os aos clientes correspondentes';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        $this->info('Buscando pedidos sem customer_id...');

        $orders = Order::whereNull('customer_id')->get();
        
        if ($orders->isEmpty()) {
            $this->info('Nenhum pedido sem customer_id encontrado.');
            return self::SUCCESS;
        }

        $this->info("Encontrados {$orders->count()} pedidos sem customer_id.");
        
        if ($dry) {
            $this->warn('Modo dry-run ativado. Nenhuma alteração será feita.');
        }

        $bar = $this->output->createProgressBar($orders->count());
        $bar->start();

        $updated = 0;
        $notFound = 0;

        foreach ($orders as $order) {
            $customer = null;

            // Tentar encontrar por CPF/CNPJ
            if (!empty($order->customer_cpf)) {
                $cpfCnpj = preg_replace('/\D/', '', $order->customer_cpf);
                
                if (strlen($cpfCnpj) === 11) {
                    // CPF
                    $customer = Customer::where('cpf', $cpfCnpj)->first();
                } elseif (strlen($cpfCnpj) === 14) {
                    // CNPJ
                    $customer = Customer::where('cnpj', $cpfCnpj)->first();
                }
            }

            // Se não encontrou por CPF/CNPJ, tentar por email
            if (!$customer && !empty($order->customer_email)) {
                $customer = Customer::where('email', $order->customer_email)->first();
            }

            if ($customer) {
                if ($dry) {
                    $this->line("\nPedido #{$order->id} -> Cliente: {$customer->name} (ID: {$customer->id})");
                } else {
                    $order->customer_id = $customer->id;
                    $order->save();
                }
                $updated++;
            } else {
                if ($dry) {
                    $this->line("\nPedido #{$order->id} -> Cliente não encontrado (CPF: {$order->customer_cpf}, Email: {$order->customer_email})");
                }
                $notFound++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        if ($dry) {
            $this->info("Dry-run concluído:");
            $this->line("  - Pedidos que seriam atualizados: {$updated}");
            $this->line("  - Pedidos sem cliente correspondente: {$notFound}");
        } else {
            $this->info("Atualização concluída:");
            $this->line("  - Pedidos atualizados: {$updated}");
            $this->line("  - Pedidos sem cliente correspondente: {$notFound}");
        }

        return self::SUCCESS;
    }
}

