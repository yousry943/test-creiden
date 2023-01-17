<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest as TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_vehicle_has_title_link_attribute()
    {
        $vehicle = Vehicle::factory()->create();

        $title = __('app.show_detail_title', [
            'title' => $vehicle->title, 'type' => __('vehicle.vehicle'),
        ]);
        $link = '<a href="'.route('vehicles.show', $vehicle).'"';
        $link .= ' title="'.$title.'">';
        $link .= $vehicle->title;
        $link .= '</a>';

        $this->assertEquals($link, $vehicle->title_link);
    }

    /** @test */
    public function a_vehicle_has_belongs_to_creator_relation()
    {
        $vehicle = Vehicle::factory()->make();

        $this->assertInstanceOf(User::class, $vehicle->creator);
        $this->assertEquals($vehicle->creator_id, $vehicle->creator->id);
    }
}
