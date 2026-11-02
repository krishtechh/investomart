<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Listing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $provider = Account::firstOrCreate(
            ['phone' => '9823011223'],
            [
                'country_code' => '+91',
                'name' => 'Oberoi Green Homes & Realty',
                'email' => 'oberoi@greenhomes.in',
                'role' => 'builder',
                'provider_type' => 'builder',
                'company_name' => 'Oberoi Green Homes & Realty',
                'phone_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $sampleProperties = [
            [
                'reference' => 'PROP-MV1',
                'title' => 'Modern Villa',
                'property_type' => 'Villa',
                'listing_type' => 'Sale',
                'bedrooms' => '4',
                'bathrooms' => 4,
                'price' => 12500000,
                'deposit' => 2500000,
                'area' => 3200,
                'area_unit' => 'sqft',
                'furnishing' => 'Fully Furnished',
                'city' => 'Noida',
                'location' => 'Sector 150, Noida',
                'address' => 'Sector 150, Noida Express Highway',
                'description' => 'Ultra-luxurious contemporary villa with double-height ceilings, private lawn, swimming pool, and Italian marble finishes.',
                'amenities' => ['Covered Parking', 'Private Garden', 'Swimming Pool', '24/7 Security', 'Power Backup'],
                'primary_image_url' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=600&q=80',
                'approval_status' => 'approved',
                'provider_status' => 'active',
                'capacity_status' => 'eligible',
                'submitted_at' => now(),
                'approved_at' => now(),
            ],
            [
                'reference' => 'PROP-L3A',
                'title' => 'Luxury 3 BHK Apartment',
                'property_type' => 'Apartment',
                'listing_type' => 'Rent',
                'bedrooms' => '3',
                'bathrooms' => 2,
                'price' => 32000,
                'price_period' => 'month',
                'deposit' => 90000,
                'area' => 1450,
                'area_unit' => 'sqft',
                'furnishing' => 'Fully Furnished',
                'city' => 'Noida',
                'location' => 'Sector 62, Noida',
                'address' => 'Tower B, Express Zenith, Sector 62, Noida',
                'description' => 'Designer 3 BHK apartment with modular kitchen, wooden flooring in master bedroom, wrap-around balcony, and EV charging station.',
                'amenities' => ['Covered Parking', 'Clubhouse & Gym', '24/7 Security', 'Power Backup', 'EV Charging Station'],
                'primary_image_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=600&q=80',
                'approval_status' => 'approved',
                'provider_status' => 'active',
                'capacity_status' => 'eligible',
                'submitted_at' => now(),
                'approved_at' => now(),
            ],
            [
                'reference' => 'PROP-PA1',
                'title' => 'Premium Apartment',
                'property_type' => 'Apartment',
                'listing_type' => 'Rent',
                'bedrooms' => '2',
                'bathrooms' => 2,
                'price' => 28000,
                'price_period' => 'month',
                'deposit' => 75000,
                'area' => 1250,
                'area_unit' => 'sqft',
                'furnishing' => 'Semi-Furnished',
                'city' => 'Gurugram',
                'location' => 'Sector 76, Gurugram',
                'address' => 'DLF Primus, Sector 76, Southern Peripheral Road, Gurugram',
                'description' => 'High-rise premium apartment facing the Aravali greens, featuring spacious living room and modular wardrobes.',
                'amenities' => ['Covered Parking', 'Swimming Pool', '24/7 Security', 'Power Backup'],
                'primary_image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=600&q=80',
                'approval_status' => 'approved',
                'provider_status' => 'active',
                'capacity_status' => 'eligible',
                'submitted_at' => now(),
                'approved_at' => now(),
            ],
        ];

        foreach ($sampleProperties as $prop) {
            Listing::firstOrCreate(
                ['reference' => $prop['reference']],
                array_merge($prop, [
                    'id' => (string) Str::uuid(),
                    'provider_account_id' => $provider->id,
                    'contact_name' => $provider->name,
                    'contact_phone' => $provider->phone,
                ])
            );
        }
    }
}
