<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class CustomerSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        factory(App\Customer::class, 50)->create()->each(function ($customer) use ($faker){
            $max_trans = $faker->numberBetween($min = 0, $max = 10);
            if($max_trans > 0){
                $customer->transactions()->saveMany(factory(App\Transaction::class, $max_trans)->make());
            }
        });
    }
}
