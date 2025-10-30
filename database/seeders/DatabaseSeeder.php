<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Criar/obter Roles (idempotente)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $gerenteRole = Role::firstOrCreate(['name' => 'gerente']);
        $vendedorRole = Role::firstOrCreate(['name' => 'vendedor']);
        $clienteRole = Role::firstOrCreate(['name' => 'cliente']);

        // Criar/obter Permissões (idempotente)
        $manageProducts = Permission::firstOrCreate(['name' => 'manage products']);
        $manageOrders = Permission::firstOrCreate(['name' => 'manage orders']);
        $manageUsers = Permission::firstOrCreate(['name' => 'manage users']);
        $stockView = Permission::firstOrCreate(['name' => 'stock.view']);
        $stockCreate = Permission::firstOrCreate(['name' => 'stock.create']);
        $stockAdjust = Permission::firstOrCreate(['name' => 'stock.adjust']);

        // Atribuir permissões às roles (idempotente)
        $adminRole->syncPermissions(collect($adminRole->permissions)->pluck('name')->merge([
            'manage products','manage orders','manage users','stock.view','stock.create','stock.adjust'
        ])->unique());
        $gerenteRole->syncPermissions(collect($gerenteRole->permissions)->pluck('name')->merge([
            'manage products','manage orders','stock.view','stock.create','stock.adjust'
        ])->unique());
        $vendedorRole->syncPermissions(collect($vendedorRole->permissions)->pluck('name')->merge([
            'manage orders'
        ])->unique());

        // Criar/obter usuário admin e garantir papel (idempotente)
        $admin = User::firstOrCreate(
            ['email' => 'admin@novarosamt.com'],
            ['name' => 'Administrador', 'password' => Hash::make('password')]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Criar/obter categorias (idempotente por slug)
        $category1 = Category::firstOrCreate(
            ['slug' => 'detergentes'],
            ['name' => 'Detergentes', 'description' => 'Detergentes para limpeza geral', 'is_active' => true]
        );

        $category2 = Category::firstOrCreate(
            ['slug' => 'desinfetantes'],
            ['name' => 'Desinfetantes', 'description' => 'Produtos desinfetantes', 'is_active' => true]
        );

        $category3 = Category::firstOrCreate(
            ['slug' => 'especializados'],
            ['name' => 'Especializados', 'description' => 'Produtos de limpeza especializados', 'is_active' => true]
        );

        // Criar produtos
        Product::firstOrCreate(
            ['slug' => 'detergente-liquido-500ml'],
            [
            'category_id' => $category1->id,
            'name' => 'Detergente Líquido 500ml',
            'description' => 'Detergente líquido para louças',
            'price' => 5.90,
            'stock' => 0,
            'is_active' => true,
        ]);

        Product::firstOrCreate(
            ['slug' => 'detergente-em-po-1kg'],
            [
            'category_id' => $category1->id,
            'name' => 'Detergente em Pó 1kg',
            'description' => 'Detergente em pó para máquina de lavar',
            'price' => 12.90,
            'stock' => 0,
            'is_active' => true,
        ]);

        Product::firstOrCreate(
            ['slug' => 'desinfetante-1l'],
            [
            'category_id' => $category2->id,
            'name' => 'Desinfetante 1L',
            'description' => 'Desinfetante para uso geral',
            'price' => 8.50,
            'stock' => 0,
            'is_active' => true,
        ]);

        Product::firstOrCreate(
            ['slug' => 'limpa-vidros-500ml'],
            [
            'category_id' => $category3->id,
            'name' => 'Limpa Vidros 500ml',
            'description' => 'Produto especializado para limpeza de vidros',
            'price' => 9.90,
            'stock' => 0,
            'is_active' => true,
        ]);
    }
}
