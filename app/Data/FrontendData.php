<?php

namespace App\Data;

class FrontendData
{
    /**
     * Get all categories.
     */
    public static function categories()
    {
        $products = static::products();
        $counts = array_count_values(array_column($products, 'category_slug'));

        $items = [
            ['name' => 'Laptop', 'slug' => 'laptop', 'icon' => 'fas fa-laptop', 'description' => 'Business, Enterprise, and Ultrabook Laptops'],
            ['name' => 'Desktop', 'slug' => 'desktop', 'icon' => 'fas fa-desktop', 'description' => 'Commercial Desktop Towers and Small Form Factor PCs'],
            ['name' => 'Gaming PC', 'slug' => 'gaming-pc', 'icon' => 'fas fa-gamepad', 'description' => 'High-Performance Rig & Gaming Desktop Systems'],
            ['name' => 'Workstation', 'slug' => 'workstation', 'icon' => 'fas fa-server', 'description' => 'Heavy-Duty Workstations for CAD, AI, & Render Labs'],
            ['name' => 'CPU', 'slug' => 'cpu', 'icon' => 'fas fa-microchip', 'description' => 'Intel Xeon, Core i7/i9 & AMD Ryzen / EPYC Processors'],
            ['name' => 'GPU', 'slug' => 'gpu', 'icon' => 'fas fa-vr-cardboard', 'description' => 'NVIDIA RTX Enterprise & Consumer Graphics Cards'],
            ['name' => 'RAM', 'slug' => 'ram', 'icon' => 'fas fa-memory', 'description' => 'DDR4 / DDR5 ECC & Non-ECC Server & Desktop Memory'],
            ['name' => 'SSD', 'slug' => 'ssd', 'icon' => 'fas fa-hdd', 'description' => 'NVMe M.2 & Enterprise SATA Solid State Drives'],
            ['name' => 'HDD', 'slug' => 'hdd', 'icon' => 'fas fa-database', 'description' => 'High-Capacity Enterprise NAS & Server Hard Drives'],
            ['name' => 'Motherboard', 'slug' => 'motherboard', 'icon' => 'fas fa-chess-board', 'description' => 'Server, Workstation, and Commercial Mainboards'],
            ['name' => 'Monitor', 'slug' => 'monitor', 'icon' => 'fas fa-tv', 'description' => '4K UltraHD, Curved, and Color-Calibrated Displays'],
            ['name' => 'Keyboard', 'slug' => 'keyboard', 'icon' => 'fas fa-keyboard', 'description' => 'Ergonomic, Mechanical, and Office Keyboards'],
            ['name' => 'Mouse', 'slug' => 'mouse', 'icon' => 'fas fa-mouse', 'description' => 'Precision Optical, Wireless, and Ergonomic Mice'],
            ['name' => 'Router', 'slug' => 'router', 'icon' => 'fas fa-wifi', 'description' => 'Enterprise Wi-Fi 6/6E Routers & Access Points'],
            ['name' => 'Switch', 'slug' => 'switch', 'icon' => 'fas fa-network-wired', 'description' => 'Managed & Unmanaged Gigabit PoE Switches'],
            ['name' => 'Printer', 'slug' => 'printer', 'icon' => 'fas fa-print', 'description' => 'High-Volume Enterprise Multifunction Laser Printers'],
            ['name' => 'UPS', 'slug' => 'ups', 'icon' => 'fas fa-battery-full', 'description' => 'Uninterruptible Power Supply Units & Battery Backup'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'icon' => 'fas fa-plug', 'description' => 'Docking Stations, Cables, Adapters & Mounts'],
        ];

        return array_map(function ($item) use ($counts) {
            $item['count'] = $counts[$item['slug']] ?? 0;
            return $item;
        }, $items);
    }

    /**
     * Get all brands.
     */
    public static function brands()
    {
        $products = static::products();
        $counts = array_count_values(array_column($products, 'brand_slug'));

        $items = [
            ['name' => 'Dell', 'slug' => 'dell', 'logo' => 'DELL', 'icon' => 'fas fa-laptop', 'tagline' => 'Enterprise Laptops & Servers', 'accent' => '#007db8'],
            ['name' => 'HP', 'slug' => 'hp', 'logo' => 'HP', 'icon' => 'fas fa-desktop', 'tagline' => 'EliteBook & ProLiant', 'accent' => '#0096d6'],
            ['name' => 'Lenovo', 'slug' => 'lenovo', 'logo' => 'LENOVO', 'icon' => 'fas fa-laptop-code', 'tagline' => 'ThinkPad & Workstations', 'accent' => '#e2231a'],
            ['name' => 'ASUS', 'slug' => 'asus', 'logo' => 'ASUS', 'icon' => 'fas fa-microchip', 'tagline' => 'ExpertBook & Systems', 'accent' => '#00539b'],
            ['name' => 'Acer', 'slug' => 'acer', 'logo' => 'ACER', 'icon' => 'fas fa-laptop-house', 'tagline' => 'TravelMate Series', 'accent' => '#83b81a'],
            ['name' => 'MSI', 'slug' => 'msi', 'logo' => 'MSI', 'icon' => 'fas fa-gamepad', 'tagline' => 'Pro Desktops & GPUs', 'accent' => '#ff0000'],
            ['name' => 'Intel', 'slug' => 'intel', 'logo' => 'INTEL', 'icon' => 'fas fa-microchip', 'tagline' => 'Xeon & Core CPUs', 'accent' => '#0068b5'],
            ['name' => 'AMD', 'slug' => 'amd', 'logo' => 'AMD', 'icon' => 'fas fa-server', 'tagline' => 'EPYC & Ryzen CPUs', 'accent' => '#ed1c24'],
            ['name' => 'NVIDIA', 'slug' => 'nvidia', 'logo' => 'NVIDIA', 'icon' => 'fas fa-memory', 'tagline' => 'RTX Workstation GPUs', 'accent' => '#76b900'],
            ['name' => 'Samsung', 'slug' => 'samsung', 'logo' => 'SAMSUNG', 'icon' => 'fas fa-hdd', 'tagline' => 'NVMe SSDs & RAM', 'accent' => '#1428a0'],
            ['name' => 'Logitech', 'slug' => 'logitech', 'logo' => 'LOGITECH', 'icon' => 'fas fa-keyboard', 'tagline' => 'Business Peripherals', 'accent' => '#00b8fc'],
            ['name' => 'TP-Link', 'slug' => 'tp-link', 'logo' => 'TP-LINK', 'icon' => 'fas fa-wifi', 'tagline' => 'Omada Networking', 'accent' => '#4ac4cf'],
            ['name' => 'Canon', 'slug' => 'canon', 'logo' => 'CANON', 'icon' => 'fas fa-print', 'tagline' => 'Commercial Printers', 'accent' => '#cc0000'],
            ['name' => 'Epson', 'slug' => 'epson', 'logo' => 'EPSON', 'icon' => 'fas fa-copy', 'tagline' => 'WorkForce Scanners', 'accent' => '#003399'],
        ];

        return array_map(function ($item) use ($counts) {
            $item['count'] = $counts[$item['slug']] ?? 0;
            return $item;
        }, $items);
    }

    /**
     * Extract brand name from product name or details.
     */
    protected static function extractBrandName(string $name): string
    {
        $knownBrands = ['Dell', 'HP', 'Lenovo', 'ASUS', 'Acer', 'MSI', 'Intel', 'AMD', 'NVIDIA', 'Samsung', 'Logitech', 'TP-Link', 'Canon', 'Epson', 'APC', 'Seagate', 'Kingston'];
        foreach ($knownBrands as $brand) {
            if (stripos($name, $brand) !== false) {
                return $brand;
            }
        }
        $words = explode(' ', trim($name));
        return $words[0] ?? 'General';
    }

    /**
     * Get all products (dynamically fetched from Stock database table with static fallback).
     */
    public static function products()
    {
        try {
            if (class_exists(\App\Models\Stock::class) && \App\Models\Stock::count() > 0) {
                $stocks = \App\Models\Stock::orderBy('id', 'asc')->get();
                $dbProducts = [];

                foreach ($stocks as $stock) {
                    $brandName = $stock->details['brand'] ?? static::extractBrandName($stock->product_name);
                    
                    $wholesalePrices = [];
                    if (!empty($stock->tier_prices) && is_array($stock->tier_prices)) {
                        foreach ($stock->tier_prices as $tier) {
                            $wholesalePrices[] = [
                                'minQty' => (int) ($tier['min_qty'] ?? $tier['minQty'] ?? 1),
                                'maxQty' => isset($tier['max_qty']) && $tier['max_qty'] !== null && $tier['max_qty'] !== '' ? (int) $tier['max_qty'] : (isset($tier['maxQty']) && $tier['maxQty'] !== null ? (int) $tier['maxQty'] : null),
                                'price' => (float) ($tier['price'] ?? $stock->retail_price),
                            ];
                        }
                    }

                    if (empty($wholesalePrices) && $stock->retail_price > 0) {
                        $price = (float) $stock->retail_price;
                        $wholesalePrices = [
                            ['minQty' => 1, 'maxQty' => 4, 'price' => $price],
                            ['minQty' => 5, 'maxQty' => 19, 'price' => round($price * 0.95, 2)],
                            ['minQty' => 20, 'maxQty' => 49, 'price' => round($price * 0.90, 2)],
                            ['minQty' => 50, 'maxQty' => null, 'price' => round($price * 0.85, 2)],
                        ];
                    }

                    $dbProducts[] = [
                        'id' => $stock->id,
                        'sku' => $stock->sku,
                        'name' => $stock->product_name,
                        'brand' => $brandName,
                        'brand_slug' => \Illuminate\Support\Str::slug($brandName),
                        'category' => $stock->category ?? 'General',
                        'category_slug' => \Illuminate\Support\Str::slug($stock->category ?? 'General'),
                        'description' => $stock->short_description ?? $stock->description ?? 'High quality commercial wholesale product.',
                        'price' => (float) ($stock->retail_price > 0 ? $stock->retail_price : $stock->unit_cost),
                        'stock' => (int) $stock->quantity,
                        'moq' => (int) ($stock->min_reorder_level ?? 5),
                        'rating' => (float) ($stock->details['rating'] ?? 4.8),
                        'reviews' => (int) ($stock->details['reviews'] ?? 24),
                        'warranty' => $stock->details['warranty'] ?? '3 Years Standard Warranty',
                        'featured' => (bool) ($stock->details['featured'] ?? ($stock->id <= 10)),
                        'best_seller' => (bool) ($stock->details['best_seller'] ?? ($stock->quantity > 80)),
                        'new_arrival' => (bool) ($stock->details['new_arrival'] ?? false),
                        'image' => $stock->image_url ?? $stock->image ?? 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80',
                        'specifications' => is_array($stock->details) ? $stock->details : [],
                        'wholesalePrices' => $wholesalePrices,
                    ];
                }

                if (!empty($dbProducts)) {
                    return $dbProducts;
                }
            }
        } catch (\Throwable $e) {
            // DB fallback
        }

        return static::fallbackProducts();
    }

    /**
     * Fallback static products.
     */
    public static function fallbackProducts()
    {
        return [
            [
                'id' => 1,
                'sku' => 'DELL-L5440-I5',
                'name' => 'Dell Latitude 5440 14" Business Laptop',
                'brand' => 'Dell',
                'brand_slug' => 'dell',
                'category' => 'Laptop',
                'category_slug' => 'laptop',
                'description' => 'Enterprise 14-inch laptop featuring 13th Gen Intel Core i5 processor, lightweight body, long battery life, and security features designed for corporate deployments.',
                'price' => 680,
                'stock' => 120,
                'moq' => 5,
                'rating' => 4.8,
                'reviews' => 42,
                'warranty' => '3 Years Onsite',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'CPU' => 'Intel Core i5-1335U (10 Cores, up to 4.60 GHz)',
                    'RAM' => '16GB DDR4 3200MHz',
                    'Storage' => '512GB PCIe NVMe SSD',
                    'Display' => '14.0" FHD (1920x1080) Anti-Glare',
                    'GPU' => 'Intel Iris Xe Graphics',
                    'OS' => 'Windows 11 Pro 64-bit',
                    'Ports' => '2x Thunderbolt 4, 2x USB 3.2, HDMI 2.0, RJ-45',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 4, 'price' => 680],
                    ['minQty' => 5, 'maxQty' => 19, 'price' => 660],
                    ['minQty' => 20, 'maxQty' => 49, 'price' => 630],
                    ['minQty' => 50, 'maxQty' => null, 'price' => 600],
                ],
            ],
            [
                'id' => 2,
                'sku' => 'LEN-T14-G4',
                'name' => 'Lenovo ThinkPad T14 Gen 4 Ultrabook',
                'brand' => 'Lenovo',
                'brand_slug' => 'lenovo',
                'category' => 'Laptop',
                'category_slug' => 'laptop',
                'description' => 'Iconic business laptop with legendary keyboard, durable magnesium body, Intel Core i7 processor, and enterprise security management.',
                'price' => 890,
                'stock' => 95,
                'moq' => 3,
                'rating' => 4.9,
                'reviews' => 56,
                'warranty' => '3 Years Premier Support',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'CPU' => 'Intel Core i7-1355U (12 Cores, up to 5.00 GHz)',
                    'RAM' => '32GB LPDDR5x 4800MHz',
                    'Storage' => '1TB M.2 PCIe 4.0 SSD',
                    'Display' => '14.0" WUXGA (1920x1200) IPS 400 nits',
                    'GPU' => 'Intel Iris Xe Graphics',
                    'OS' => 'Windows 11 Pro 64-bit',
                    'Weight' => '1.36 kg (3.0 lbs)',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 2, 'price' => 890],
                    ['minQty' => 3, 'maxQty' => 9, 'price' => 860],
                    ['minQty' => 10, 'maxQty' => 29, 'price' => 820],
                    ['minQty' => 30, 'maxQty' => null, 'price' => 780],
                ],
            ],
            [
                'id' => 3,
                'sku' => 'HP-EB840-G10',
                'name' => 'HP EliteBook 840 G10 Business Laptop',
                'brand' => 'HP',
                'brand_slug' => 'hp',
                'category' => 'Laptop',
                'category_slug' => 'laptop',
                'description' => 'Premium aluminum business laptop optimized for hybrid work with 5MP camera, AI noise cancellation, and high-performance Intel CPU.',
                'price' => 820,
                'stock' => 80,
                'moq' => 4,
                'rating' => 4.7,
                'reviews' => 29,
                'warranty' => '3 Years Onsite Warranty',
                'featured' => true,
                'best_seller' => false,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'CPU' => 'Intel Core i5-1340P (12 Cores, up to 4.60 GHz)',
                    'RAM' => '16GB DDR5 4800MHz',
                    'Storage' => '512GB PCIe Gen4 NVMe SSD',
                    'Display' => '14.0" WUXGA Anti-Glare IPS 250 nits',
                    'GPU' => 'Intel Iris Xe Graphics',
                    'OS' => 'Windows 11 Pro',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 3, 'price' => 820],
                    ['minQty' => 4, 'maxQty' => 14, 'price' => 790],
                    ['minQty' => 15, 'maxQty' => 39, 'price' => 750],
                    ['minQty' => 40, 'maxQty' => null, 'price' => 720],
                ],
            ],
            [
                'id' => 4,
                'sku' => 'ASUS-EXP-B5',
                'name' => 'ASUS ExpertBook B5 Flip Convertible',
                'brand' => 'ASUS',
                'brand_slug' => 'asus',
                'category' => 'Laptop',
                'category_slug' => 'laptop',
                'description' => '360-degree convertible laptop designed for business versatility, featuring stylus support, OLED touchscreen option, and dual SSD support.',
                'price' => 750,
                'stock' => 60,
                'moq' => 5,
                'rating' => 4.6,
                'reviews' => 18,
                'warranty' => '2 Years Global Warranty',
                'featured' => false,
                'best_seller' => false,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'CPU' => 'Intel Core i5-1335U',
                    'RAM' => '16GB DDR5',
                    'Storage' => '512GB PCIe M.2 SSD',
                    'Display' => '14.0" FHD Touchscreen 360-degree',
                    'GPU' => 'Intel Iris Xe Graphics',
                    'OS' => 'Windows 11 Pro',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 4, 'price' => 750],
                    ['minQty' => 5, 'maxQty' => 19, 'price' => 710],
                    ['minQty' => 20, 'maxQty' => null, 'price' => 670],
                ],
            ],
            [
                'id' => 5,
                'sku' => 'DELL-OPT-7010-SFF',
                'name' => 'Dell OptiPlex 7010 SFF Desktop PC',
                'brand' => 'Dell',
                'brand_slug' => 'dell',
                'category' => 'Desktop',
                'category_slug' => 'desktop',
                'description' => 'Space-saving small form factor commercial desktop computer built for high performance, business reliability, and easy maintenance.',
                'price' => 520,
                'stock' => 150,
                'moq' => 5,
                'rating' => 4.8,
                'reviews' => 64,
                'warranty' => '3 Years Basic Onsite',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'CPU' => 'Intel Core i5-13500 (14 Cores, up to 4.80 GHz)',
                    'RAM' => '16GB DDR4 3200MHz',
                    'Storage' => '512GB M.2 PCIe NVMe SSD',
                    'GPU' => 'Intel UHD Graphics 770',
                    'Form Factor' => 'Small Form Factor (SFF)',
                    'Power Supply' => '180W 85% Efficient PSU',
                    'OS' => 'Windows 11 Pro 64-bit',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 4, 'price' => 520],
                    ['minQty' => 5, 'maxQty' => 19, 'price' => 495],
                    ['minQty' => 20, 'maxQty' => 49, 'price' => 470],
                    ['minQty' => 50, 'maxQty' => null, 'price' => 440],
                ],
            ],
            [
                'id' => 6,
                'sku' => 'HP-PRO-400-G9',
                'name' => 'HP ProTower 400 G9 Commercial PC',
                'brand' => 'HP',
                'brand_slug' => 'hp',
                'category' => 'Desktop',
                'category_slug' => 'desktop',
                'description' => 'Expandable microtower desktop delivering enterprise performance, tool-less chassis design, and robust security management.',
                'price' => 490,
                'stock' => 110,
                'moq' => 5,
                'rating' => 4.7,
                'reviews' => 38,
                'warranty' => '3 Years Parts & Labor',
                'featured' => false,
                'best_seller' => false,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1587831990711-23ca6441447b?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'CPU' => 'Intel Core i5-13400 (10 Cores, up to 4.60 GHz)',
                    'RAM' => '16GB DDR4 3200MHz',
                    'Storage' => '512GB PCIe NVMe SSD',
                    'GPU' => 'Intel UHD Graphics 730',
                    'Form Factor' => 'Microtower',
                    'OS' => 'Windows 11 Pro',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 4, 'price' => 490],
                    ['minQty' => 5, 'maxQty' => 19, 'price' => 465],
                    ['minQty' => 20, 'maxQty' => 49, 'price' => 440],
                    ['minQty' => 50, 'maxQty' => null, 'price' => 415],
                ],
            ],
            [
                'id' => 7,
                'sku' => 'LEN-P3-TOWER',
                'name' => 'Lenovo ThinkStation P3 Tower Workstation',
                'brand' => 'Lenovo',
                'brand_slug' => 'lenovo',
                'category' => 'Workstation',
                'category_slug' => 'workstation',
                'description' => 'ISV-certified workstation engineered for CAD design, engineering simulations, AI compute, and content creation.',
                'price' => 1450,
                'stock' => 45,
                'moq' => 2,
                'rating' => 4.9,
                'reviews' => 22,
                'warranty' => '3 Years Premier Support',
                'featured' => true,
                'best_seller' => false,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'CPU' => 'Intel Core i9-13900 (24 Cores, up to 5.60 GHz)',
                    'RAM' => '64GB DDR5 4800MHz ECC',
                    'Storage' => '1TB M.2 NVMe PCIe 4.0 SSD',
                    'GPU' => 'NVIDIA RTX A2000 12GB GDDR6',
                    'Power Supply' => '750W 92% Platinum PSU',
                    'OS' => 'Windows 11 Pro for Workstations',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 1, 'price' => 1450],
                    ['minQty' => 2, 'maxQty' => 4, 'price' => 1390],
                    ['minQty' => 5, 'maxQty' => 14, 'price' => 1320],
                    ['minQty' => 15, 'maxQty' => null, 'price' => 1250],
                ],
            ],
            [
                'id' => 8,
                'sku' => 'MSI-MAG-INFINITE-13',
                'name' => 'MSI MAG Infinite S3 Gaming Desktop',
                'brand' => 'MSI',
                'brand_slug' => 'msi',
                'category' => 'Gaming PC',
                'category_slug' => 'gaming-pc',
                'description' => 'Pre-built high-end gaming PC equipped with NVIDIA GeForce RTX 4070, liquid cooling, RGB chassis, and high air flow.',
                'price' => 1350,
                'stock' => 35,
                'moq' => 2,
                'rating' => 4.8,
                'reviews' => 31,
                'warranty' => '2 Years Limited Warranty',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'CPU' => 'Intel Core i7-13700F (16 Cores, up to 5.20 GHz)',
                    'RAM' => '32GB DDR5 5600MHz RGB',
                    'Storage' => '1TB PCIe Gen4 NVMe SSD',
                    'GPU' => 'NVIDIA GeForce RTX 4070 12GB GDDR6X',
                    'Power Supply' => '650W 80 Plus Gold',
                    'OS' => 'Windows 11 Home',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 1, 'price' => 1350],
                    ['minQty' => 2, 'maxQty' => 4, 'price' => 1290],
                    ['minQty' => 5, 'maxQty' => 9, 'price' => 1230],
                    ['minQty' => 10, 'maxQty' => null, 'price' => 1180],
                ],
            ],
            [
                'id' => 9,
                'sku' => 'INTEL-I9-14900K',
                'name' => 'Intel Core i9-14900K Desktop Processor',
                'brand' => 'Intel',
                'brand_slug' => 'intel',
                'category' => 'CPU',
                'category_slug' => 'cpu',
                'description' => '24 Cores (8 Performance + 16 Efficient Cores) flagship desktop CPU reaching up to 6.0 GHz Turbo frequency for extreme workload computing.',
                'price' => 560,
                'stock' => 200,
                'moq' => 10,
                'rating' => 4.9,
                'reviews' => 88,
                'warranty' => '3 Years Boxed CPU Warranty',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Cores / Threads' => '24 Cores / 32 Threads',
                    'Max Turbo Frequency' => '6.00 GHz',
                    'Cache' => '36MB Intel Smart Cache',
                    'Socket' => 'LGA 1700',
                    'Base Power' => '125W (Max Turbo 253W)',
                    'Memory Support' => 'DDR5 5600 / DDR4 3200',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 9, 'price' => 560],
                    ['minQty' => 10, 'maxQty' => 24, 'price' => 535],
                    ['minQty' => 25, 'maxQty' => 49, 'price' => 510],
                    ['minQty' => 50, 'maxQty' => null, 'price' => 485],
                ],
            ],
            [
                'id' => 10,
                'sku' => 'AMD-RYZEN9-7950X',
                'name' => 'AMD Ryzen 9 7950X 16-Core Processor',
                'brand' => 'AMD',
                'brand_slug' => 'amd',
                'category' => 'CPU',
                'category_slug' => 'cpu',
                'description' => 'Zen 4 architecture 16-Core, 32-Thread unlocked desktop processor delivering unbelievable performance for software development, 3D rendering, and encoding.',
                'price' => 540,
                'stock' => 180,
                'moq' => 10,
                'rating' => 4.8,
                'reviews' => 74,
                'warranty' => '3 Years Manufacturer Warranty',
                'featured' => false,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Cores / Threads' => '16 Cores / 32 Threads',
                    'Max Boost Clock' => 'up to 5.7 GHz',
                    'L3 Cache' => '64MB',
                    'Socket' => 'AM5',
                    'Default TDP' => '170W',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 9, 'price' => 540],
                    ['minQty' => 10, 'maxQty' => 24, 'price' => 515],
                    ['minQty' => 25, 'maxQty' => 49, 'price' => 490],
                    ['minQty' => 50, 'maxQty' => null, 'price' => 465],
                ],
            ],
            [
                'id' => 11,
                'sku' => 'NV-RTX4090-24G',
                'name' => 'NVIDIA GeForce RTX 4090 24GB GDDR6X',
                'brand' => 'NVIDIA',
                'brand_slug' => 'nvidia',
                'category' => 'GPU',
                'category_slug' => 'gpu',
                'description' => 'The ultimate GPU for gaming, AI training, deep learning, and 3D rendering powered by Ada Lovelace architecture and 24GB VRAM.',
                'price' => 1850,
                'stock' => 30,
                'moq' => 2,
                'rating' => 5.0,
                'reviews' => 96,
                'warranty' => '3 Years Manufacturer Warranty',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'CUDA Cores' => '16384',
                    'Memory Size' => '24GB GDDR6X',
                    'Memory Bus' => '384-bit',
                    'Display Outputs' => '3x DisplayPort 1.4a, 1x HDMI 2.1a',
                    'Recommended PSU' => '850W',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 1, 'price' => 1850],
                    ['minQty' => 2, 'maxQty' => 4, 'price' => 1790],
                    ['minQty' => 5, 'maxQty' => 9, 'price' => 1730],
                    ['minQty' => 10, 'maxQty' => null, 'price' => 1670],
                ],
            ],
            [
                'id' => 12,
                'sku' => 'NV-RTX4070TI-S',
                'name' => 'NVIDIA GeForce RTX 4070 Ti Super 16GB',
                'brand' => 'NVIDIA',
                'brand_slug' => 'nvidia',
                'category' => 'GPU',
                'category_slug' => 'gpu',
                'description' => 'High-efficiency graphics card built for 1440p and 4K workstation visualization with DLSS 3 frame generation.',
                'price' => 790,
                'stock' => 75,
                'moq' => 3,
                'rating' => 4.8,
                'reviews' => 41,
                'warranty' => '3 Years Limited Warranty',
                'featured' => false,
                'best_seller' => false,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'CUDA Cores' => '8448',
                    'Memory Size' => '16GB GDDR6X',
                    'Memory Bus' => '256-bit',
                    'Boost Clock' => '2610 MHz',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 2, 'price' => 790],
                    ['minQty' => 3, 'maxQty' => 9, 'price' => 755],
                    ['minQty' => 10, 'maxQty' => null, 'price' => 720],
                ],
            ],
            [
                'id' => 13,
                'sku' => 'SAM-990PRO-2TB',
                'name' => 'Samsung 990 PRO 2TB PCIe 4.0 NVMe M.2 SSD',
                'brand' => 'Samsung',
                'brand_slug' => 'samsung',
                'category' => 'SSD',
                'category_slug' => 'ssd',
                'description' => 'Unmatched sequential read speeds up to 7,450 MB/s for data-intensive enterprise servers, workstations, and high-performance laptops.',
                'price' => 165,
                'stock' => 350,
                'moq' => 10,
                'rating' => 4.9,
                'reviews' => 112,
                'warranty' => '5 Years Limited Warranty',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Capacity' => '2TB',
                    'Form Factor' => 'M.2 2280',
                    'Interface' => 'PCIe Gen 4.0 x4, NVMe 2.0',
                    'Sequential Read' => 'Up to 7,450 MB/s',
                    'Sequential Write' => 'Up to 6,900 MB/s',
                    'TBW' => '1,200 TBW',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 9, 'price' => 165],
                    ['minQty' => 10, 'maxQty' => 24, 'price' => 152],
                    ['minQty' => 25, 'maxQty' => 49, 'price' => 142],
                    ['minQty' => 50, 'maxQty' => null, 'price' => 132],
                ],
            ],
            [
                'id' => 14,
                'sku' => 'SAM-980EVO-1TB',
                'name' => 'Samsung 980 1TB NVMe M.2 SSD',
                'brand' => 'Samsung',
                'brand_slug' => 'samsung',
                'category' => 'SSD',
                'category_slug' => 'ssd',
                'description' => 'DRAM-less high-speed M.2 NVMe SSD for commercial desktop upgrades and IT deployments offering up to 3,500 MB/s read speeds.',
                'price' => 85,
                'stock' => 500,
                'moq' => 10,
                'rating' => 4.7,
                'reviews' => 85,
                'warranty' => '5 Years Limited Warranty',
                'featured' => false,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Capacity' => '1TB',
                    'Form Factor' => 'M.2 2280',
                    'Sequential Read' => 'Up to 3,500 MB/s',
                    'Sequential Write' => 'Up to 3,000 MB/s',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 9, 'price' => 85],
                    ['minQty' => 10, 'maxQty' => 29, 'price' => 77],
                    ['minQty' => 30, 'maxQty' => 99, 'price' => 70],
                    ['minQty' => 100, 'maxQty' => null, 'price' => 64],
                ],
            ],
            [
                'id' => 15,
                'sku' => 'SAM-DDR5-32GB',
                'name' => 'Samsung 32GB DDR5 5600MHz UDIMM Desktop RAM',
                'brand' => 'Samsung',
                'brand_slug' => 'samsung',
                'category' => 'RAM',
                'category_slug' => 'ram',
                'description' => 'High-reliability enterprise grade DDR5 memory module featuring built-in On-Die ECC for enhanced stability and high-bandwidth processing.',
                'price' => 95,
                'stock' => 400,
                'moq' => 10,
                'rating' => 4.8,
                'reviews' => 54,
                'warranty' => 'Lifetime Warranty',
                'featured' => false,
                'best_seller' => true,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1562976540-1502c2145186?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Memory Type' => 'DDR5 UDIMM',
                    'Capacity' => '32GB (1x32GB)',
                    'Speed' => '5600 MHz (PC5-44800)',
                    'Voltage' => '1.1V',
                    'Pin Count' => '288-Pin',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 9, 'price' => 95],
                    ['minQty' => 10, 'maxQty' => 29, 'price' => 88],
                    ['minQty' => 30, 'maxQty' => 99, 'price' => 81],
                    ['minQty' => 100, 'maxQty' => null, 'price' => 74],
                ],
            ],
            [
                'id' => 16,
                'sku' => 'LEN-DDR4-16GB-ECC',
                'name' => 'Lenovo 16GB DDR4 3200MHz ECC SODIMM RAM',
                'brand' => 'Lenovo',
                'brand_slug' => 'lenovo',
                'category' => 'RAM',
                'category_slug' => 'ram',
                'description' => 'Error-Correcting Code (ECC) SODIMM memory module for mobile workstations and compact servers.',
                'price' => 55,
                'stock' => 300,
                'moq' => 10,
                'rating' => 4.6,
                'reviews' => 27,
                'warranty' => 'Lifetime Warranty',
                'featured' => false,
                'best_seller' => false,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1562976540-1502c2145186?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Memory Type' => 'DDR4 ECC SODIMM',
                    'Capacity' => '16GB',
                    'Speed' => '3200 MHz',
                    'Voltage' => '1.2V',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 9, 'price' => 55],
                    ['minQty' => 10, 'maxQty' => 29, 'price' => 49],
                    ['minQty' => 30, 'maxQty' => null, 'price' => 43],
                ],
            ],
            [
                'id' => 17,
                'sku' => 'DELL-U2723QE',
                'name' => 'Dell UltraSharp U2723QE 27" 4K USB-C Hub Monitor',
                'brand' => 'Dell',
                'brand_slug' => 'dell',
                'category' => 'Monitor',
                'category_slug' => 'monitor',
                'description' => 'World-class 27-inch 4K UHD monitor with IPS Black technology, 98% DCI-P3 color gamut, integrated RJ45 network port, and 90W USB-C power delivery.',
                'price' => 520,
                'stock' => 90,
                'moq' => 3,
                'rating' => 4.9,
                'reviews' => 68,
                'warranty' => '3 Years Advanced Exchange Service',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Screen Size' => '27 inch IPS Black',
                    'Resolution' => '4K UHD (3840 x 2160) at 60 Hz',
                    'Contrast Ratio' => '2000:1',
                    'Inputs' => 'HDMI, DisplayPort 1.4, USB-C (90W PD), RJ45 Ethernet',
                    'Ergonomics' => 'Height, Tilt, Swivel, Pivot Adjustable',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 2, 'price' => 520],
                    ['minQty' => 3, 'maxQty' => 9, 'price' => 495],
                    ['minQty' => 10, 'maxQty' => 24, 'price' => 470],
                    ['minQty' => 25, 'maxQty' => null, 'price' => 445],
                ],
            ],
            [
                'id' => 18,
                'sku' => 'ASUS-PA329CV',
                'name' => 'ASUS ProArt PA329CV 32" 4K Color Monitor',
                'brand' => 'ASUS',
                'brand_slug' => 'asus',
                'category' => 'Monitor',
                'category_slug' => 'monitor',
                'description' => 'Factory pre-calibrated Calman Verified 32-inch 4K UHD monitor engineered for video editors, graphic designers, and photographers.',
                'price' => 690,
                'stock' => 40,
                'moq' => 2,
                'rating' => 4.8,
                'reviews' => 23,
                'warranty' => '3 Years Onsite Warranty',
                'featured' => false,
                'best_seller' => false,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Screen Size' => '32 inch IPS',
                    'Resolution' => '4K UHD (3840 x 2160)',
                    'Color Accuracy' => '100% sRGB / 100% Rec. 709, Delta E < 2',
                    'Connectivity' => 'USB-C 90W, DisplayPort, HDMI, USB Hub',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 1, 'price' => 690],
                    ['minQty' => 2, 'maxQty' => 4, 'price' => 655],
                    ['minQty' => 5, 'maxQty' => null, 'price' => 620],
                ],
            ],
            [
                'id' => 19,
                'sku' => 'TPL-ARCHER-AXE75',
                'name' => 'TP-Link Archer AXE75 Tri-Band Wi-Fi 6E Router',
                'brand' => 'TP-Link',
                'brand_slug' => 'tp-link',
                'category' => 'Router',
                'category_slug' => 'router',
                'description' => 'Tri-Band Gigabit Wi-Fi 6E router unlocking the ultra-fast 6 GHz band for interference-free high-speed corporate branch connectivity.',
                'price' => 175,
                'stock' => 140,
                'moq' => 5,
                'rating' => 4.7,
                'reviews' => 45,
                'warranty' => '2 Years Replacement Warranty',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Wi-Fi Standard' => 'Wi-Fi 6E (802.11axe)',
                    'Speed' => '5400 Mbps (6GHz: 2402 Mbps, 5GHz: 2402 Mbps, 2.4GHz: 574 Mbps)',
                    'Processor' => '1.7 GHz Quad-Core CPU',
                    'Ports' => '1x Gigabit WAN, 4x Gigabit LAN, 1x USB 3.0',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 4, 'price' => 175],
                    ['minQty' => 5, 'maxQty' => 14, 'price' => 160],
                    ['minQty' => 15, 'maxQty' => 29, 'price' => 148],
                    ['minQty' => 30, 'maxQty' => null, 'price' => 135],
                ],
            ],
            [
                'id' => 20,
                'sku' => 'TPL-TL-SG1024D',
                'name' => 'TP-Link 24-Port Gigabit Rackmount Switch',
                'brand' => 'TP-Link',
                'brand_slug' => 'tp-link',
                'category' => 'Switch',
                'category_slug' => 'switch',
                'description' => '24-Port 10/100/1000Mbps unmanaged rackmount steel switch with energy-efficient technology for expanding business network infrastructure.',
                'price' => 95,
                'stock' => 160,
                'moq' => 5,
                'rating' => 4.8,
                'reviews' => 33,
                'warranty' => '3 Years Limited Warranty',
                'featured' => false,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Ports' => '24x 10/100/1000Mbps RJ45 Ports',
                    'Switching Capacity' => '48 Gbps',
                    'Form Factor' => '13-inch or 19-inch Rack-mountable Steel Case',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 4, 'price' => 95],
                    ['minQty' => 5, 'maxQty' => 14, 'price' => 86],
                    ['minQty' => 15, 'maxQty' => null, 'price' => 78],
                ],
            ],
            [
                'id' => 21,
                'sku' => 'LOG-MX-MASTER-3S',
                'name' => 'Logitech MX Master 3S Performance Wireless Mouse',
                'brand' => 'Logitech',
                'brand_slug' => 'logitech',
                'category' => 'Mouse',
                'category_slug' => 'mouse',
                'description' => 'Industry-standard ergonomic wireless business mouse featuring 8000 DPI track-on-glass sensor, quiet clicks, and MagSpeed electromagnetic scrolling.',
                'price' => 90,
                'stock' => 250,
                'moq' => 10,
                'rating' => 4.9,
                'reviews' => 140,
                'warranty' => '1 Year Hardware Warranty',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Sensor' => '8000 DPI Darkfield High Precision',
                    'Connectivity' => 'Bluetooth Low Energy & Logi Bolt USB Receiver',
                    'Battery Life' => 'Up to 70 days on full charge',
                    'Weight' => '141 g',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 9, 'price' => 90],
                    ['minQty' => 10, 'maxQty' => 29, 'price' => 82],
                    ['minQty' => 30, 'maxQty' => 49, 'price' => 75],
                    ['minQty' => 50, 'maxQty' => null, 'price' => 68],
                ],
            ],
            [
                'id' => 22,
                'sku' => 'LOG-MX-KEYS-S',
                'name' => 'Logitech MX Keys S Wireless Business Keyboard',
                'brand' => 'Logitech',
                'brand_slug' => 'logitech',
                'category' => 'Keyboard',
                'category_slug' => 'keyboard',
                'description' => 'Advanced wireless illuminated keyboard designed for fluent, precise typing with smart backlighting and customizable Smart Actions.',
                'price' => 105,
                'stock' => 220,
                'moq' => 5,
                'rating' => 4.8,
                'reviews' => 92,
                'warranty' => '1 Year Hardware Warranty',
                'featured' => false,
                'best_seller' => true,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Key Switches' => 'Perfect Stroke Spherically-Dished Keys',
                    'Backlighting' => 'Smart Proximity Sensors',
                    'Multi-Device' => 'Pair up to 3 devices with Easy-Switch',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 4, 'price' => 105],
                    ['minQty' => 5, 'maxQty' => 19, 'price' => 96],
                    ['minQty' => 20, 'maxQty' => null, 'price' => 88],
                ],
            ],
            [
                'id' => 23,
                'sku' => 'CAN-LBP236DW',
                'name' => 'Canon imageCLASS LBP236dw Wireless Laser Printer',
                'brand' => 'Canon',
                'brand_slug' => 'canon',
                'category' => 'Printer',
                'category_slug' => 'printer',
                'description' => 'Compact high-speed black & white laser printer producing up to 40 ppm with duplex auto-printing for small-to-medium offices.',
                'price' => 240,
                'stock' => 85,
                'moq' => 3,
                'rating' => 4.7,
                'reviews' => 36,
                'warranty' => '1 Year Onsite Warranty',
                'featured' => false,
                'best_seller' => false,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1612815150548-99483ceb649d?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Print Speed' => 'Up to 40 ppm (Letter)',
                    'Resolution' => '600 x 600 dpi',
                    'Paper Capacity' => '250-sheet Cassette + 100-sheet Multipurpose Tray',
                    'Connectivity' => 'Wi-Fi, Ethernet, USB 2.0 Direct',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 2, 'price' => 240],
                    ['minQty' => 3, 'maxQty' => 9, 'price' => 220],
                    ['minQty' => 10, 'maxQty' => null, 'price' => 200],
                ],
            ],
            [
                'id' => 24,
                'sku' => 'EPS-L6270-ECO',
                'name' => 'Epson EcoTank L6270 Wi-Fi Duplex All-in-One InkTank',
                'brand' => 'Epson',
                'brand_slug' => 'epson',
                'category' => 'Printer',
                'category_slug' => 'printer',
                'description' => 'Ultra-low cost high-volume color multifunction printer with ADF, Wi-Fi Direct, auto-duplexing, and bottle ink system.',
                'price' => 310,
                'stock' => 70,
                'moq' => 3,
                'rating' => 4.8,
                'reviews' => 49,
                'warranty' => '2 Years or 50,000 Prints Warranty',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1612815150548-99483ceb649d?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Functions' => 'Print, Scan, Copy with ADF',
                    'Yield' => 'Up to 7,500 Black / 6,000 Color pages per ink set',
                    'Print Speed' => '15.5 ipm (Black), 8.5 ipm (Color)',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 2, 'price' => 310],
                    ['minQty' => 3, 'maxQty' => 9, 'price' => 285],
                    ['minQty' => 10, 'maxQty' => null, 'price' => 260],
                ],
            ],
            [
                'id' => 25,
                'sku' => 'DELL-APC-SMT1500',
                'name' => 'Dell APC Smart-UPS 1500VA LCD 230V with SmartConnect',
                'brand' => 'Dell',
                'brand_slug' => 'dell',
                'category' => 'UPS',
                'category_slug' => 'ups',
                'description' => 'Intelligent and efficient network power protection from entry level to scaleable runtime. Ideal for servers, POS, routers, switches and hubs.',
                'price' => 480,
                'stock' => 50,
                'moq' => 2,
                'rating' => 4.9,
                'reviews' => 31,
                'warranty' => '3 Years Repair or Replace',
                'featured' => false,
                'best_seller' => false,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Output Power Capacity' => '1000 Watts / 1500 VA',
                    'Output Connections' => '(8) IEC 320 C13',
                    'Nominal Output Voltage' => '230V',
                    'Topology' => 'Line Interactive Pure Sine Wave',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 1, 'price' => 480],
                    ['minQty' => 2, 'maxQty' => 4, 'price' => 450],
                    ['minQty' => 5, 'maxQty' => null, 'price' => 420],
                ],
            ],
            [
                'id' => 26,
                'sku' => 'ASUS-ROG-STRIX-Z790',
                'name' => 'ASUS ROG Strix Z790-E Gaming WiFi Motherboard',
                'brand' => 'ASUS',
                'brand_slug' => 'asus',
                'category' => 'Motherboard',
                'category_slug' => 'motherboard',
                'description' => 'Intel LGA 1700 ATX motherboard with 18+1 power stages, PCIe 5.0, DDR5 support, five M.2 slots, and Wi-Fi 6E.',
                'price' => 420,
                'stock' => 65,
                'moq' => 3,
                'rating' => 4.8,
                'reviews' => 40,
                'warranty' => '3 Years Manufacturer Warranty',
                'featured' => false,
                'best_seller' => false,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Form Factor' => 'ATX',
                    'Socket' => 'LGA 1700 (13th & 14th Gen Intel)',
                    'Memory' => '4x DDR5 DIMM up to 192GB (7800+ MHz OC)',
                    'Slots' => '1x PCIe 5.0 x16, 5x M.2 Slots',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 2, 'price' => 420],
                    ['minQty' => 3, 'maxQty' => 9, 'price' => 395],
                    ['minQty' => 10, 'maxQty' => null, 'price' => 370],
                ],
            ],
            [
                'id' => 27,
                'sku' => 'MSI-MAG-B650-TOMAHAWK',
                'name' => 'MSI MAG B650 Tomahawk WiFi AM5 Motherboard',
                'brand' => 'MSI',
                'brand_slug' => 'msi',
                'category' => 'Motherboard',
                'category_slug' => 'motherboard',
                'description' => 'Robust military-grade AM5 ATX motherboard designed for AMD Ryzen 7000/8000 series CPUs with DDR5, PCIe 4.0, and 2.5G LAN.',
                'price' => 210,
                'stock' => 110,
                'moq' => 5,
                'rating' => 4.7,
                'reviews' => 58,
                'warranty' => '3 Years Manufacturer Warranty',
                'featured' => false,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Form Factor' => 'ATX',
                    'Socket' => 'AMD AM5',
                    'Power Phase' => '14+2+1 Duet Rail Power System',
                    'Networking' => '2.5G LAN + Wi-Fi 6E',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 4, 'price' => 210],
                    ['minQty' => 5, 'maxQty' => 14, 'price' => 195],
                    ['minQty' => 15, 'maxQty' => null, 'price' => 180],
                ],
            ],
            [
                'id' => 28,
                'sku' => 'ACER-NITRO-KG271U',
                'name' => 'Acer Nitro 27" QHD 170Hz Gaming Monitor',
                'brand' => 'Acer',
                'brand_slug' => 'acer',
                'category' => 'Monitor',
                'category_slug' => 'monitor',
                'description' => '27-inch 2560x1440 QHD IPS display featuring 170Hz refresh rate, 0.5ms response time, and AMD FreeSync Premium.',
                'price' => 210,
                'stock' => 130,
                'moq' => 4,
                'rating' => 4.6,
                'reviews' => 71,
                'warranty' => '3 Years Limited Warranty',
                'featured' => false,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Resolution' => '2K QHD (2560 x 1440)',
                    'Refresh Rate' => '170Hz',
                    'Panel Type' => 'IPS ZeroFrame',
                    'Response Time' => '0.5ms (G to G)',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 3, 'price' => 210],
                    ['minQty' => 4, 'maxQty' => 11, 'price' => 192],
                    ['minQty' => 12, 'maxQty' => null, 'price' => 175],
                ],
            ],
            [
                'id' => 29,
                'sku' => 'DELL-WD19S-130W',
                'name' => 'Dell Performance Docking Station WD19S 130W',
                'brand' => 'Dell',
                'brand_slug' => 'dell',
                'category' => 'Accessories',
                'category_slug' => 'accessories',
                'description' => 'Enterprise USB-C docking station delivering power up to 90W for non-Dell systems and 130W for Dell laptops with triple monitor output support.',
                'price' => 185,
                'stock' => 160,
                'moq' => 5,
                'rating' => 4.8,
                'reviews' => 63,
                'warranty' => '3 Years Advanced Exchange',
                'featured' => true,
                'best_seller' => true,
                'new_arrival' => false,
                'image' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Ports' => '2x DisplayPort 1.4, 1x HDMI 2.0b, 1x USB-C Multifunction, 3x USB-A 3.1, 1x Gigabit Ethernet',
                    'Power Delivery' => 'Up to 130W Power Delivery',
                    'Compatibility' => 'Windows 10/11, macOS, Linux, ChromeOS',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 4, 'price' => 185],
                    ['minQty' => 5, 'maxQty' => 14, 'price' => 168],
                    ['minQty' => 15, 'maxQty' => 29, 'price' => 155],
                    ['minQty' => 30, 'maxQty' => null, 'price' => 140],
                ],
            ],
            [
                'id' => 30,
                'sku' => 'HP-EXOS-16TB',
                'name' => 'HP Enterprise Exos 16TB 7200 RPM Hard Drive',
                'brand' => 'HP',
                'brand_slug' => 'hp',
                'category' => 'HDD',
                'category_slug' => 'hdd',
                'description' => 'Hyperscale SATA 6Gb/s enterprise internal hard drive built for 24/7 rackmount server arrays, cloud datacenters, and heavy data storage.',
                'price' => 260,
                'stock' => 110,
                'moq' => 4,
                'rating' => 4.9,
                'reviews' => 44,
                'warranty' => '5 Years Limited Warranty',
                'featured' => false,
                'best_seller' => false,
                'new_arrival' => true,
                'image' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=600&auto=format&fit=crop&q=80',
                'specifications' => [
                    'Capacity' => '16TB',
                    'RPM' => '7200 RPM',
                    'Cache' => '256MB',
                    'Interface' => 'SATA 6Gb/s',
                    'Workload Rating' => '550TB / year',
                ],
                'wholesalePrices' => [
                    ['minQty' => 1, 'maxQty' => 3, 'price' => 260],
                    ['minQty' => 4, 'maxQty' => 11, 'price' => 240],
                    ['minQty' => 12, 'maxQty' => null, 'price' => 220],
                ],
            ],
        ];
    }

    /**
     * Get wholesale price tier based on product and quantity.
     */
    public static function getWholesalePrice($product, $quantity)
    {
        $quantity = max(1, (int) $quantity);
        $tiers = $product['wholesalePrices'] ?? [];
        $appliedPrice = $product['price'];
        $appliedTier = null;

        foreach ($tiers as $tier) {
            $min = $tier['minQty'];
            $max = $tier['maxQty'];

            if ($quantity >= $min && ($max === null || $quantity <= $max)) {
                $appliedPrice = $tier['price'];
                $appliedTier = $tier;
                break;
            }
        }

        // If quantity exceeds max of highest tier, pick highest tier
        if (!$appliedTier && count($tiers) > 0) {
            $lastTier = end($tiers);
            if ($quantity >= $lastTier['minQty']) {
                $appliedPrice = $lastTier['price'];
                $appliedTier = $lastTier;
            }
        }

        return [
            'unitPrice' => $appliedPrice,
            'subtotal' => $appliedPrice * $quantity,
            'tier' => $appliedTier,
            'savings' => max(0, ($product['price'] - $appliedPrice) * $quantity),
        ];
    }

    /**
     * Get single product by ID.
     */
    public static function getProductById($id)
    {
        $products = static::products();
        foreach ($products as $p) {
            if ($p['id'] == $id) {
                return $p;
            }
        }
        return null;
    }

    /**
     * Initial sample customer.
     */
    public static function sampleCustomer()
    {
        return [
            'id' => 1,
            'company' => 'ABC Technology Solutions Co., Ltd.',
            'tax_number' => 'VAT-987654321',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1 (555) 234-5678',
            'address' => '742 Enterprise Boulevard, Suite 400',
            'city' => 'San Jose',
            'province' => 'California',
            'zip' => '95131',
            'country' => 'United States',
        ];
    }

    /**
     * Initial sample orders.
     */
    public static function sampleOrders()
    {
        return [
            [
                'id' => 'ORD-20260810-001',
                'date' => '2026-08-10',
                'status' => 'Shipped',
                'payment_method' => 'Bank Transfer',
                'subtotal' => 12600,
                'shipping' => 150,
                'tax' => 1260,
                'total' => 14010,
                'items' => [
                    [
                        'product_id' => 1,
                        'sku' => 'DELL-L5440-I5',
                        'name' => 'Dell Latitude 5440 14" Business Laptop',
                        'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80',
                        'quantity' => 20,
                        'price' => 630,
                        'subtotal' => 12600,
                    ],
                ],
                'shipping_address' => '742 Enterprise Boulevard, Suite 400, San Jose, CA 95131',
            ],
            [
                'id' => 'ORD-20260802-004',
                'date' => '2026-08-02',
                'status' => 'Delivered',
                'payment_method' => 'Credit Terms (Net 30)',
                'subtotal' => 8250,
                'shipping' => 0,
                'tax' => 825,
                'total' => 9075,
                'items' => [
                    [
                        'product_id' => 13,
                        'sku' => 'SAM-990PRO-2TB',
                        'name' => 'Samsung 990 PRO 2TB PCIe 4.0 NVMe M.2 SSD',
                        'image' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=600&auto=format&fit=crop&q=80',
                        'quantity' => 50,
                        'price' => 132,
                        'subtotal' => 6600,
                    ],
                    [
                        'product_id' => 14,
                        'sku' => 'SAM-980EVO-1TB',
                        'name' => 'Samsung 980 1TB NVMe M.2 SSD',
                        'image' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=600&auto=format&fit=crop&q=80',
                        'quantity' => 25,
                        'price' => 66,
                        'subtotal' => 1650,
                    ],
                ],
                'shipping_address' => '742 Enterprise Boulevard, Suite 400, San Jose, CA 95131',
            ],
        ];
    }

    /**
     * Initial sample quotes.
     */
    public static function sampleQuotes()
    {
        return [
            [
                'id' => 'QT-20260810-001',
                'date' => '2026-08-10',
                'product_name' => 'Dell Latitude 5440 14" Business Laptop',
                'quantity' => 50,
                'target_price' => 580,
                'required_date' => '2026-08-25',
                'status' => 'Under Review',
                'message' => 'Need 50 units for our corporate tech refresh initiative.',
            ],
        ];
    }
}
