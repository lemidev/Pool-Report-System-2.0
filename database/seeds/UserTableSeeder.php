<?php

use Illuminate\Database\Seeder;
use App\UserRoleCompany;
use App\Service;
use App\User;
use App\Image;
use App\PRS\Helpers\SeederHelpers;

class UserTableSeeder extends Seeder
{

    private $seederHelper;

    public function __construct(SeederHelpers $seederHelper)
    {
        $this->seederHelper = $seederHelper;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numOfClients = rand(100, 200);
        $numOfSupervisors = rand(10, 20);
        $numOfTechnicians = rand(20, 40);
        $faker = Faker\Factory::create();

        UserRoleCompany::flushEventListeners();
        User::flushEventListeners();
        Image::flushEventListeners();

        for ($i=0; $i < $numOfClients; $i++)
        {
            // woman or men
			$gender = (rand(0,1)) ? 'male':'female';

        	// generate and save image and tn_image
			$img = $this->seederHelper->get_random_image('client/'.$gender, 20);

            // get the service id for the pivot table
            $serviceId = $this->seederHelper->getRandomObject('services');

            // find admin_id congruent with the service
            $company = Service::findOrFail($serviceId)->company;

    		$user = factory(User::class)->create([
                'name' => $faker->firstName($gender),
    		]);

            $userRoleCompany = factory(UserRoleCompany::class)->create([
                'chat_id' => Uuid::generate()->string,
                'chat_nickname' => str_slug($user->fullName.' '.str_random(5), '.'),
                'user_id' => $user->id,
                'role_id' => 2,
                'company_id' => $company->id,
            ]);

            DB::table('urc_notify_setting')->insert([
                [ 'notify_setting_id' => 1, 'urc_id' => $userRoleCompany->id ],// Notification when Report is Created
                [ 'notify_setting_id' => 2, 'urc_id' => $userRoleCompany->id ],// Notification when Work Order is Created
                [ 'notify_setting_id' => 3, 'urc_id' => $userRoleCompany->id ],// Notification when Service is Created
                [ 'notify_setting_id' => 7, 'urc_id' => $userRoleCompany->id ],// Notification when Invoice is Created
                [ 'notify_setting_id' => 8, 'urc_id' => $userRoleCompany->id ],// Notification when Payment is Created
                [ 'notify_setting_id' => 9, 'urc_id' => $userRoleCompany->id ],// Notification when Work is added to Work Order
                [ 'notify_setting_id' => 10, 'urc_id' => $userRoleCompany->id ],// Notification when Chemical is added to Service
                [ 'notify_setting_id' => 11, 'urc_id' => $userRoleCompany->id ],// Notification when Equipment is added to Service
                [ 'notify_setting_id' => 12, 'urc_id' => $userRoleCompany->id ],// Notification when Contract is added to Service
                [ 'notify_setting_id' => 13, 'urc_id' => $userRoleCompany->id ],// Email when Report is Created
                [ 'notify_setting_id' => 14, 'urc_id' => $userRoleCompany->id ],// Email when Work Order is Created
                [ 'notify_setting_id' => 15, 'urc_id' => $userRoleCompany->id ],// Email when Service is Created
                [ 'notify_setting_id' => 19, 'urc_id' => $userRoleCompany->id ],// Email when Invoice is Created
                [ 'notify_setting_id' => 20, 'urc_id' => $userRoleCompany->id ],// Email when Payment is Created
            ]);

            // need to attach this user to some services
            $userRoleCompany->services()->attach($serviceId);

            // create images link it to client
            $userRoleCompany->images()->create([
                'big' => $img->big,
    			'medium' => $img->medium,
                'thumbnail' => $img->thumbnail,
                'icon' => $img->icon,
                'processing' => 0,
            ]);
        }

        for ($i=0; $i < $numOfSupervisors; $i++)
        {
            // generate and save image and tn_image
    		$img = $this->seederHelper->get_random_image('supervisor', 5);

            // get a random company_id that exists in database
            $company_id = rand(1,2);

    		$user = factory(User::class)->create();

            $userRoleCompany = factory(UserRoleCompany::class)->create([

                'chat_id' => Uuid::generate()->string,
                'chat_nickname' => str_slug($user->fullName.' '.str_random(5), '.'),
                'user_id' => $user->id,
                'role_id' => 3,
                'company_id' => $company_id,
            ]);

            DB::table('urc_notify_setting')->insert([
                [ 'notify_setting_id' => 1, 'urc_id' => $userRoleCompany->id ],// Notification when Report is Created
                [ 'notify_setting_id' => 2, 'urc_id' => $userRoleCompany->id ],// Notification when Work Order is Created
                [ 'notify_setting_id' => 3, 'urc_id' => $userRoleCompany->id ],// Notification when Service is Created
                [ 'notify_setting_id' => 4, 'urc_id' => $userRoleCompany->id ],// Notification when Client is Created
                [ 'notify_setting_id' => 5, 'urc_id' => $userRoleCompany->id ],// Notification when Supervisor is Created
                [ 'notify_setting_id' => 6, 'urc_id' => $userRoleCompany->id ],// Notification when Technician is Created
                [ 'notify_setting_id' => 7, 'urc_id' => $userRoleCompany->id ],// Notification when Invoice is Created
                [ 'notify_setting_id' => 8, 'urc_id' => $userRoleCompany->id ],// Notification when Payment is Created
                [ 'notify_setting_id' => 9, 'urc_id' => $userRoleCompany->id ],// Notification when Work is added to Work Order
                [ 'notify_setting_id' => 10, 'urc_id' => $userRoleCompany->id ],// Notification when Chemical is added to Service
                [ 'notify_setting_id' => 11, 'urc_id' => $userRoleCompany->id ],// Notification when Equipment is added to Service
                [ 'notify_setting_id' => 12, 'urc_id' => $userRoleCompany->id ],// Notification when Contract is added to Service
                [ 'notify_setting_id' => 13, 'urc_id' => $userRoleCompany->id ],// Email when Report is Created
                [ 'notify_setting_id' => 14, 'urc_id' => $userRoleCompany->id ],// Email when Work Order is Created
                [ 'notify_setting_id' => 15, 'urc_id' => $userRoleCompany->id ],// Email when Service is Created
                [ 'notify_setting_id' => 16, 'urc_id' => $userRoleCompany->id ],// Email when Client is Created
                [ 'notify_setting_id' => 17, 'urc_id' => $userRoleCompany->id ],// Email when Supervisor is Created
                [ 'notify_setting_id' => 18, 'urc_id' => $userRoleCompany->id ],// Email when Technician is Created
                [ 'notify_setting_id' => 19, 'urc_id' => $userRoleCompany->id ],// Email when Invoice is Created
                [ 'notify_setting_id' => 20, 'urc_id' => $userRoleCompany->id ],// Email when Payment is Created
            ]);

            // create images link it to supervisors
            $userRoleCompany->images()->create([
                'big' => $img->big,
    			'medium' => $img->medium,
                'thumbnail' => $img->thumbnail,
                'icon' => $img->icon,
                'processing' => 0,
            ]);

        }

        for ($i=0; $i < $numOfTechnicians; $i++)
        {
            // generate and save image and tn_image
			$img = $this->seederHelper->get_random_image('technician', 20);

            // get a random company_id that exists in database
            $company_id = rand(1,2);

    		$user = factory(User::class)->create();

            $userRoleCompany = factory(UserRoleCompany::class)->create([
                'chat_id' => Uuid::generate()->string,
                'chat_nickname' => str_slug($user->fullName.' '.str_random(5), '.'),
                'user_id' => $user->id,
                'role_id' => 4,
                'company_id' => $company_id,
            ]);

            DB::table('urc_notify_setting')->insert([
                [ 'notify_setting_id' => 1, 'urc_id' => $userRoleCompany->id ],// Notification when Report is Created
                [ 'notify_setting_id' => 2, 'urc_id' => $userRoleCompany->id ],// Notification when Work Order is Created
                [ 'notify_setting_id' => 3, 'urc_id' => $userRoleCompany->id ],// Notification when Service is Created
                [ 'notify_setting_id' => 9, 'urc_id' => $userRoleCompany->id ],// Notification when Work is added to Work Order
                [ 'notify_setting_id' => 10, 'urc_id' => $userRoleCompany->id ],// Notification when Chemical is added to Service
                [ 'notify_setting_id' => 11, 'urc_id' => $userRoleCompany->id ],// Notification when Equipment is added to Service
                [ 'notify_setting_id' => 12, 'urc_id' => $userRoleCompany->id ],// Notification when Contract is added to Service
                [ 'notify_setting_id' => 13, 'urc_id' => $userRoleCompany->id ],// Email when Report is Created
                [ 'notify_setting_id' => 14, 'urc_id' => $userRoleCompany->id ],// Email when Work Order is Created
                [ 'notify_setting_id' => 15, 'urc_id' => $userRoleCompany->id ],// Email when Service is Created
            ]);

    		// create images link it to technician
            $userRoleCompany->images()->create([
                'big' => $img->big,
    			'medium' => $img->medium,
                'thumbnail' => $img->thumbnail,
                'icon' => $img->icon,
                'processing' => 0,
            ]);
        }
    }
}
