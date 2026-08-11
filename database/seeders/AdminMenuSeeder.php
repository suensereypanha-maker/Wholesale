<?php

namespace Database\Seeders;

use App\Models\AdminMenu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing menu items for clean re-seeding
        Schema::disableForeignKeyConstraints();
        AdminMenu::truncate();
        Schema::enableForeignKeyConstraints();

        $menus = [
            // Overview Section
            [
                'section'    => 'Overview',
                'title'      => 'Dashboard',
                'icon'       => 'fas fa-chart-line',
                'route'      => 'admin.dashboard',
                'permission' => 'view_dashboard',
                'order'      => 1,
            ],
            [
                'section'    => 'Overview',
                'title'      => 'Analytics & Forecast',
                'icon'       => 'fas fa-chart-pie',
                'url'        => '#',
                'permission' => 'view_reports',
                'order'      => 2,
            ],

            // B2B Commerce & Sales Section
            [
                'section'     => 'B2B Commerce & Sales',
                'title'       => 'Bulk Orders',
                'icon'        => 'fas fa-file-invoice-dollar',
                'url'         => '#',
                'permission'  => 'manage_orders',
                'order'       => 3,
            ],
            [
                'section'     => 'B2B Commerce & Sales',
                'title'       => 'Order & Sale',
                'icon'        => 'fas fa-cart-shopping',
                'permission'  => 'manage_orders',
                'order'       => 4,
                'children'    => [
                    [
                        'title'      => 'Customer Order',
                        'icon'       => 'fas fa-cart-shopping',
                        'route'      => 'admin.orders.index',
                        'permission' => 'manage_orders',
                        'order'      => 1,
                    ],
                ],
            ],
            [
                'section'     => 'B2B Commerce & Sales',
                'title'       => 'Quotes & Inquiries',
                'icon'        => 'fas fa-handshake-angle',
                'url'         => '#',
                'permission'  => 'manage_orders',
                'order'       => 5,
            ],
            [
                'section'    => 'B2B Commerce & Sales',
                'title'      => 'Statements & Invoices',
                'icon'       => 'fas fa-receipt',
                'url'        => '#',
                'permission' => 'manage_orders',
                'order'      => 6,
            ],

            // Wholesale Catalog Section
            [
                'section'    => 'Wholesale Catalog',
                'title'      => 'Products Catalog',
                'icon'       => 'fas fa-box-archive',
                'route'      => 'admin.stocks.index',
                'permission' => 'manage_products',
                'order'      => 7,
            ],
            [
                'section'    => 'Wholesale Catalog',
                'title'      => 'Product Categories',
                'icon'       => 'fas fa-layer-group',
                'route'      => 'admin.categories.index',
                'permission' => '',
                'order'      => 8,
            ],
            [
                'section'    => 'Wholesale Catalog',
                'title'      => 'Tiered Pricing Rules',
                'icon'       => 'fas fa-tags',
                'url'        => '#',
                'permission' => 'manage_products',
                'order'      => 9,
            ],

            // Client Accounts Section
            [
                'section'    => 'Client Accounts',
                'title'      => 'B2B Clients',
                'icon'       => 'fas fa-users-gear',
                'url'        => '#',
                'permission' => 'manage_users',
                'order'      => 10,
            ],
            [
                'section'     => 'Client Accounts',
                'title'       => 'Buyer Approvals',
                'icon'        => 'fas fa-user-check',
                'url'         => '#',
                'permission'  => 'manage_users',
                'order'       => 11,
            ],
            [
                'section'    => 'Client Accounts',
                'title'      => 'Credit Terms & Limits',
                'icon'       => 'fas fa-credit-card',
                'url'        => '#',
                'permission' => 'manage_users',
                'order'      => 12,
            ],

            // Inventory & Logistics Section (with Children Submenu)
            [
                'section'     => 'Inventory & Logistics',
                'title'       => 'Inventory',
                'icon'        => 'fas fa-boxes-stacked',
                'permission'  => 'manage_products',
                'order'       => 13,
                'children'    => [
                    [
                        'title' => 'Stock In',
                        'icon'  => 'fas fa-arrow-down-to-bracket',
                        'route' => 'admin.stocks.in',
                        'order' => 1,
                    ],
                    [
                        'title' => 'Stock Out',
                        'icon'  => 'fas fa-arrow-up-from-bracket',
                        'route' => 'admin.stocks.out',
                        'order' => 2,
                    ],
                    [
                        'title' => 'Stock & Warehouses',
                        'icon'  => 'fas fa-warehouse',
                        'route' => 'admin.warehouses.index',
                        'order' => 3,
                    ],
                    [
                        'title' => 'Stock Adjustments',
                        'icon'  => 'fas fa-sliders',
                        'route' => 'admin.stocks.index',
                        'order' => 4,
                    ],
                ]
            ],
            [
                'section' => 'People',
                'title'   => 'People',
                'icon'    => 'fas fa-users',
                'order'   => 14,
                'children' => [
                    [
                        'title'      => 'Suppliers',
                        'icon'       => 'fas fa-truck-field',
                        'route'      => 'admin.suppliers.index',
                        'permission' => '',
                        'order'      => 1,
                    ],
                    [
                        'title'      => 'Customers',
                        'icon'       => 'fas fa-user',
                        'route'      => 'admin.customers.index',
                        'permission' => '',
                        'order'      => 2,
                    ],
                ],
            ],

            // Settings Section (with Children Submenu)
            [
                'section'     => 'Settings',
                'title'       => 'Settings',
                'icon'        => 'fas fa-gear',
                'order'       => 15,
                'children'    => [
                    [
                        'title'      => 'Users',
                        'icon'       => 'fas fa-user',
                        'route'      => 'admin.users.index',
                        'permission' => 'manage_users',
                        'order'      => 1,
                    ],
                    [
                        'title'      => 'Roles',
                        'icon'       => 'fas fa-user-gear',
                        'route'      => 'admin.roles.index',
                        'permission' => 'manage_roles',
                        'order'      => 2,
                    ],
                    [
                        'title'      => 'Permissions',
                        'icon'       => 'fas fa-sliders',
                        'route'      => 'admin.permissions.index',
                        'permission' => 'manage_roles',
                        'order'      => 3,
                    ],
                ]
            ],
        ];

        foreach ($menus as $menuData) {
            $children = $menuData['children'] ?? [];
            unset($menuData['children']);

            $menu = AdminMenu::create(array_merge([
                'is_active' => true,
                'parent_id' => null,
            ], $menuData));

            foreach ($children as $childData) {
                AdminMenu::create(array_merge([
                    'is_active' => true,
                    'parent_id' => $menu->id,
                    'route'     => null,
                    'url'       => null,
                ], $childData));
            }
        }
    }
}
