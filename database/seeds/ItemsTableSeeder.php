<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new App\Item(['name' => 'Bananen']);
        $model->save();
        $model = new App\Item(['name' => 'Erdbeeren']);
        $model->save();
        $model = new App\Item(['name' => 'Yogurt']);
        $model->save();
        $model = new App\Item(['name' => 'Bier']);
        $model->save();
        $model = new App\Item(['name' => 'Gin']);
        $model->save();
        $model = new App\Item(['name' => 'Abwaschmittel']);
        $model->save();
        $model = new App\Item(['name' => 'Gurken']);
        $model->save();
        $model = new App\Item(['name' => 'Chips']);
        $model->save();
        $model = new App\Item(['name' => 'Fleisch']);
        $model->save();
    }
}
