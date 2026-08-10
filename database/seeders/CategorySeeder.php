<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'code' => 'CAT-CPU',
                'name' => 'CPUs & Processors',
                'slug' => 'cpus-processors',
                'description' => 'High-performance desktop, workstation, and server processors (Intel Core, Xeon, AMD Ryzen, EPYC).',
                'icon' => 'fas fa-microchip',
                'type' => 'Computer Hardware',
                'status' => 'active',
                'order' => 1,
            ],
            [
                'code' => 'CAT-RAM',
                'name' => 'RAM & Memory Modules',
                'slug' => 'ram-memory-modules',
                'description' => 'Desktop DDR4/DDR5 DIMM RAM, Laptop SODIMM, and ECC Registered Server Memory.',
                'icon' => 'fas fa-memory',
                'type' => 'Computer Hardware',
                'status' => 'active',
                'order' => 2,
            ],
            [
                'code' => 'CAT-SSD',
                'name' => 'SSDs & Storage Drives',
                'slug' => 'ssds-storage-drives',
                'description' => 'M.2 NVMe PCIe Gen4/Gen5 SSDs, 2.5-inch SATA SSDs, Enterprise HDDs, and External Storage.',
                'icon' => 'fas fa-hard-drive',
                'type' => 'Computer Hardware',
                'status' => 'active',
                'order' => 3,
            ],
            [
                'code' => 'CAT-GPU',
                'name' => 'Graphics Cards & GPUs',
                'slug' => 'graphics-cards-gpus',
                'description' => 'Discrete graphics accelerators (NVIDIA GeForce RTX, Quadro/Ada Lovelace, AMD Radeon RX, Instinct).',
                'icon' => 'fas fa-display',
                'type' => 'Computer Hardware',
                'status' => 'active',
                'order' => 4,
            ],
            [
                'code' => 'CAT-MB',
                'name' => 'Motherboards & Mainboards',
                'slug' => 'motherboards-mainboards',
                'description' => 'Intel Socket LGA1700/1851 and AMD AM5 ATX, Micro-ATX, Mini-ITX, and Dual-Socket Server Mainboards.',
                'icon' => 'fas fa-circuit-board',
                'type' => 'Computer Hardware',
                'status' => 'active',
                'order' => 5,
            ],
            [
                'code' => 'CAT-PSU',
                'name' => 'Power Supplies & PSUs',
                'slug' => 'power-supplies-psus',
                'description' => 'Fully modular 80+ Gold, Platinum & Titanium ATX 3.0 Power Supply Units for PC building.',
                'icon' => 'fas fa-bolt',
                'type' => 'Computer Hardware',
                'status' => 'active',
                'order' => 6,
            ],
            [
                'code' => 'CAT-LAP',
                'name' => 'Laptop Parts & Components',
                'slug' => 'laptop-parts-components',
                'description' => 'Replacement IPS/OLED screens, lithium batteries, cooling fans, keyboards, and hinges for major laptop brands.',
                'icon' => 'fas fa-laptop',
                'type' => 'Laptop Material',
                'status' => 'active',
                'order' => 7,
            ],
            [
                'code' => 'CAT-COOL',
                'name' => 'Cooling Systems & Fans',
                'slug' => 'cooling-systems-fans',
                'description' => 'AIO Liquid Cpu Coolers, Air Heatsinks, PWM Case Fans, Thermal Paste, and Custom Loop Waterblock Parts.',
                'icon' => 'fas fa-fan',
                'type' => 'Thermal Solution',
                'status' => 'active',
                'order' => 8,
            ],
            [
                'code' => 'CAT-NET',
                'name' => 'Networking & Server Gear',
                'slug' => 'networking-server-gear',
                'description' => '10GbE Network Interface Cards (NICs), Managed Switches, Wi-Fi 7 Routers, and Patch Panels.',
                'icon' => 'fas fa-network-wired',
                'type' => 'Networking',
                'status' => 'active',
                'order' => 9,
            ],
            [
                'code' => 'CAT-ACC',
                'name' => 'Peripherals & Accessories',
                'slug' => 'peripherals-accessories',
                'description' => 'Mechanical Gaming Keyboards, Ergonomic Mice, USB-C Docking Stations, DisplayPort 2.1 Cables.',
                'icon' => 'fas fa-keyboard',
                'type' => 'Accessories',
                'status' => 'active',
                'order' => 10,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['code' => $cat['code']],
                $cat
            );
        }
    }
}
