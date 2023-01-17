<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Tests\BrowserKitTest as TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageVehicleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_vehicle_list_in_vehicle_index_page()
    {
        $vehicle = Vehicle::factory()->create();

        $this->loginAsUser();
        $this->visitRoute('vehicles.index');
        $this->see($vehicle->title);
    }

    /** @test */
    public function user_can_create_a_vehicle()
    {
        $this->loginAsUser();
        $this->visitRoute('vehicles.index');

        $this->click(__('vehicle.create'));
        $this->seeRouteIs('vehicles.index', ['action' => 'create']);

        $this->submitForm(__('app.create'), [
            'title'       => 'Vehicle 1 title',
            'description' => 'Vehicle 1 description',
        ]);

        $this->seeRouteIs('vehicles.index');

        $this->seeInDatabase('vehicles', [
            'title'       => 'Vehicle 1 title',
            'description' => 'Vehicle 1 description',
        ]);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'title'       => 'Vehicle 1 title',
            'description' => 'Vehicle 1 description',
        ], $overrides);
    }

    /** @test */
    public function validate_vehicle_title_is_required()
    {
        $this->loginAsUser();

        // title empty
        $this->post(route('vehicles.store'), $this->getCreateFields(['title' => '']));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_vehicle_title_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // title 70 characters
        $this->post(route('vehicles.store'), $this->getCreateFields([
            'title' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_vehicle_description_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // description 256 characters
        $this->post(route('vehicles.store'), $this->getCreateFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_edit_a_vehicle_within_search_query()
    {
        $this->loginAsUser();
        $vehicle = Vehicle::factory()->create(['title' => 'Testing 123']);

        $this->visitRoute('vehicles.index', ['q' => '123']);
        $this->click('edit-vehicle-'.$vehicle->id);
        $this->seeRouteIs('vehicles.index', ['action' => 'edit', 'id' => $vehicle->id, 'q' => '123']);

        $this->submitForm(__('vehicle.update'), [
            'title'       => 'Vehicle 1 title',
            'description' => 'Vehicle 1 description',
        ]);

        $this->seeRouteIs('vehicles.index', ['q' => '123']);

        $this->seeInDatabase('vehicles', [
            'title'       => 'Vehicle 1 title',
            'description' => 'Vehicle 1 description',
        ]);
    }

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'title'       => 'Vehicle 1 title',
            'description' => 'Vehicle 1 description',
        ], $overrides);
    }

    /** @test */
    public function validate_vehicle_title_update_is_required()
    {
        $this->loginAsUser();
        $vehicle = Vehicle::factory()->create(['title' => 'Testing 123']);

        // title empty
        $this->patch(route('vehicles.update', $vehicle), $this->getEditFields(['title' => '']));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_vehicle_title_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $vehicle = Vehicle::factory()->create(['title' => 'Testing 123']);

        // title 70 characters
        $this->patch(route('vehicles.update', $vehicle), $this->getEditFields([
            'title' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_vehicle_description_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $vehicle = Vehicle::factory()->create(['title' => 'Testing 123']);

        // description 256 characters
        $this->patch(route('vehicles.update', $vehicle), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_vehicle()
    {
        $this->loginAsUser();
        $vehicle = Vehicle::factory()->create();
        Vehicle::factory()->create();

        $this->visitRoute('vehicles.index', ['action' => 'edit', 'id' => $vehicle->id]);
        $this->click('del-vehicle-'.$vehicle->id);
        $this->seeRouteIs('vehicles.index', ['action' => 'delete', 'id' => $vehicle->id]);

        $this->seeInDatabase('vehicles', [
            'id' => $vehicle->id,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('vehicles', [
            'id' => $vehicle->id,
        ]);
    }
}
