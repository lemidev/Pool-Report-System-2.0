<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Administrator;

class WorkAuthorizationTest extends ApiTester
{

    protected $admin;
    protected $sup;
    protected $tech;

    public function setUp()
    {
        parent::setUp();

        $this->withoutMiddleware();

        $this->admin = factory(Administrator::class)->create();
        $user = factory(User::class)->create([
            'userable_id' => $this->admin->id,
            'userable_type' => 'App\Administrator',
        ]);

        $service = $this->createService($this->admin->id);

        $this->createClient($this->admin->id, [$service->id]);

        $this->sup = $this->createSupervisor($this->admin->id);

        $workOrder = $this->createWorkOrder($service, $this->sup);

        $this->tech = $this->createTechnician($this->sup->id);

        $this->createWork($workOrder, $this->tech);
    }


    //****************************************
    //               LIST
    //****************************************

    /** @test */
    public function it_authorizes_supervisor_to_list_work()
    {
        // Given
        // When
        $this->admin->sup_work_view = 1;
        $this->admin->save();
        // Then
        $this->actingAs($this->sup->user, 'api')
            ->json('GET', 'api/v1/workorders/10001/work');
        $this->assertResponseStatus(200);

    }

    /** @test */
    public function it_unauthorizes_supervisor_to_list_work()
    {
        // Given
        // When
        $this->admin->sup_work_view = 0;
        $this->admin->save();
        // Then
        $this->actingAs($this->sup->user, 'api')
            ->json('GET', 'api/v1/workorders/10001/work');
        $this->assertResponseStatus(403);

    }

    /** @test */
    public function it_authorizes_technician_to_list_work()
    {
        // Given
        // When
        $this->admin->tech_work_view = 1;
        $this->admin->save();
        // Then
        $this->actingAs($this->tech->user, 'api')
            ->json('GET', 'api/v1/workorders/10001/work');
        $this->assertResponseStatus(200);

    }

    /** @test */
    public function it_unauthorizes_technician_to_list_work()
    {
        // Given
        // When
        $this->admin->tech_work_view = 0;
        $this->admin->save();
        // Then
        $this->actingAs($this->tech->user, 'api')
            ->json('GET', 'api/v1/workorders/10001/work');
        $this->assertResponseStatus(403);
    }


    //****************************************
    //               VIEW
    //****************************************

    /** @test */
    public function it_authorizes_supervisor_to_view_work()
    {
        // Given
        // When
        $this->admin->sup_work_view = 1;
        $this->admin->save();
        // Then
        $this->actingAs($this->sup->user, 'api')
            ->json('GET', 'api/v1/work/1');
        $this->assertResponseStatus(500);

    }

    /** @test */
    public function it_unauthorizes_supervisor_to_view_work()
    {
        // Given
        // When
        $this->admin->sup_work_view = 0;
        $this->admin->save();
        // Then
        $this->actingAs($this->sup->user, 'api')
            ->json('GET', 'api/v1/work/1');
        $this->assertResponseStatus(403);

    }

    /** @test */
    public function it_authorizes_technician_to_view_work()
    {
        // Given
        // When
        $this->admin->tech_work_view = 1;
        $this->admin->save();
        // Then
        $this->actingAs($this->tech->user, 'api')
            ->json('GET', 'api/v1/work/1');
        $this->assertResponseStatus(500);

    }

    /** @test */
    public function it_unauthorizes_technician_to_view_work()
    {
        // Given
        // When
        $this->admin->tech_work_view = 0;
        $this->admin->save();
        // Then
        $this->actingAs($this->tech->user, 'api')
            ->json('GET', 'api/v1/work/1');
        $this->assertResponseStatus(403);
    }


    //****************************************
    //               CREATE
    //****************************************

    /** @test */
    public function it_authorizes_supervisor_to_create_work()
    {
        // Given
        // When
        $this->admin->sup_work_create = 1;
        $this->admin->save();
        // Then
        $this->actingAs($this->sup->user, 'api')
            ->json('POST', 'api/v1/workorders/10001/work');
        $this->assertResponseStatus(422);

    }

    /** @test */
    public function it_unauthorizes_supervisor_to_create_work()
    {
        // Given
        // When
        $this->admin->sup_work_create = 0;
        $this->admin->save();
        // Then
        $this->actingAs($this->sup->user, 'api')
            ->json('POST', 'api/v1/workorders/10001/work');
        $this->assertResponseStatus(403);

    }

    /** @test */
    public function it_authorizes_technician_to_create_work()
    {
        // Given
        // When
        $this->admin->tech_work_create = 1;
        $this->admin->save();
        // Then
        $this->actingAs($this->tech->user, 'api')
            ->json('POST', 'api/v1/workorders/10001/work');
        $this->assertResponseStatus(422);

    }

    /** @test */
    public function it_unauthorizes_technician_to_create_work()
    {
        // Given
        // When
        $this->admin->tech_work_create = 0;
        $this->admin->save();
        // Then
        $this->actingAs($this->tech->user, 'api')
            ->json('POST', 'api/v1/workorders/10001/work');
        $this->assertResponseStatus(403);
    }


    //****************************************
    //               UPDATE
    //****************************************

    /** @test */
    public function it_authorizes_supervisor_to_update_work()
    {
        // Given
        // When
        $this->admin->sup_work_update = 1;
        $this->admin->save();
        // Then
        $this->actingAs($this->sup->user, 'api')
            ->json('POST', 'api/v1/work/1');
        $this->assertResponseStatus(500);

    }

    /** @test */
    public function it_unauthorizes_supervisor_to_update_work()
    {
        // Given
        // When
        $this->admin->sup_work_update = 0;
        $this->admin->save();
        // Then
        $this->actingAs($this->sup->user, 'api')
            ->json('POST', 'api/v1/work/1');
        $this->assertResponseStatus(403);

    }

    /** @test */
    public function it_authorizes_technician_to_update_work()
    {
        // Given
        // When
        $this->admin->tech_work_update = 1;
        $this->admin->save();
        // Then
        $this->actingAs($this->tech->user, 'api')
            ->json('POST', 'api/v1/work/1');
        $this->assertResponseStatus(500);

    }

    /** @test */
    public function it_unauthorizes_technician_to_update_work()
    {
        // Given
        // When
        $this->admin->tech_work_update = 0;
        $this->admin->save();
        // Then
        $this->actingAs($this->tech->user, 'api')
            ->json('POST', 'api/v1/work/1');
        $this->assertResponseStatus(403);
    }


    //****************************************
    //               ADD PHOTO
    //****************************************

    // missing tests

    //****************************************
    //               REMOVE PHOTO
    //****************************************

    // missing tests


    //****************************************
    //               DELETE
    //****************************************

    /** @test */
    public function it_authorizes_supervisor_to_delete_work()
    {
        // Given
        // When
        $this->admin->sup_work_delete = 1;
        $this->admin->save();
        // Then
        $this->actingAs($this->sup->user, 'api')
            ->json('DELETE', 'api/v1/work/1');
        $this->assertResponseStatus(500); // because we removed the middleware

    }

    /** @test */
    public function it_unauthorizes_supervisor_to_delete_work()
    {
        // Given
        // When
        $this->admin->sup_work_delete = 0;
        $this->admin->save();
        // Then
        $this->actingAs($this->sup->user, 'api')
            ->json('DELETE', 'api/v1/work/1');
        $this->assertResponseStatus(403);

    }

    /** @test */
    public function it_authorizes_technician_to_delete_work()
    {
        // Given
        // When
        $this->admin->tech_work_delete = 1;
        $this->admin->save();
        // Then
        $this->actingAs($this->tech->user, 'api')
            ->json('DELETE', 'api/v1/work/1');
        $this->assertResponseStatus(500); // because we removed the middleware

    }

    /** @test */
    public function it_unauthorizes_technician_to_delete_work()
    {
        // Given
        // When
        $this->admin->tech_work_delete = 0;
        $this->admin->save();
        // Then
        $this->actingAs($this->tech->user, 'api')
            ->json('DELETE', 'api/v1/work/1');
        $this->assertResponseStatus(403);
    }

}
