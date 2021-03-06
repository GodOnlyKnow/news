<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Admin;
use App\ContentType;
use App\Content;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

	  //$this->call('AdminTableSeeder');
		//$this->call('ContentTypeSeeder');
		$this->call('ContentSeeder');
	}

}

class AdminTableSeeder extends Seeder {

	public function run()
	{
			DB::table('admins')->delete();
			Admin::create(array(
				'username' => 'admin',
				'password' => Hash::make('admin')
			));
	}
}

class ContentTypeSeeder extends Seeder {

	public function run()
	{
		DB::table('content_types')->delete();
		ContentType::create(array('name' => '两性'));
		ContentType::create(array('name' => '热门'));
		ContentType::create(array('name' => '创意'));
	}
}

class ContentSeeder extends Seeder {

	public function run()
	{
		DB::table('contents')->delete();
		Content::create(array(
			'title' => '棒球',
			'body' => '棒球运动是一种以棒打球为主要特点，集体性、对抗性很强的球类运动项目，在美国、日本尤为盛行。',
			'img' => 'imgs/1.jpg',
			'type_id' => '1'
		));
		Content::create(array(
			'title' => '棒球',
			'body' => '棒球运动是一种以棒打球为主要特点，集体性、对抗性很强的球类运动项目，在美国、日本尤为盛行。',
			'img' => 'imgs/1.jpg',
			'type_id' => '2'
		));
		Content::create(array(
			'title' => '棒球',
			'body' => '棒球运动是一种以棒打球为主要特点，集体性、对抗性很强的球类运动项目，在美国、日本尤为盛行。',
			'img' => 'imgs/1.jpg',
			'type_id' => '3'
		));
		Content::create(array(
			'title' => '冲浪',
			'body' => '冲浪是以海浪为动力，利用自身的高超技巧和平衡能力，搏击海浪的一项运动。运动员站立在冲浪板上，或利用腹板、跪板、充气的橡皮垫、划艇、皮艇等驾驭海浪的一项水上运动。',
			'img' => 'imgs/2.jpg',
			'type_id' => '1'
		));
		Content::create(array(
			'title' => '冲浪',
			'body' => '冲浪是以海浪为动力，利用自身的高超技巧和平衡能力，搏击海浪的一项运动。运动员站立在冲浪板上，或利用腹板、跪板、充气的橡皮垫、划艇、皮艇等驾驭海浪的一项水上运动。',
			'img' => 'imgs/2.jpg',
			'type_id' => '2'
		));
		Content::create(array(
			'title' => '自行车',
			'body' => '以自行车为工具比赛骑行速度的体育运动。1896年第一届奥林匹克运动会上被列为正式比赛项目。环法赛为最著名的世界自行车锦标赛。',
			'img' => 'imgs/3.jpg',
			'type_id' => '3'
		));

	}
}
