<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DepartureSchedule;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TourSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_can_match_description_and_category_name(): void
    {
        $mountainCategory = Category::create([
            'name' => 'Tour Núi',
            'description' => 'Các tour khám phá núi rừng',
            'status' => 'active',
        ]);

        $matchingTour = Tour::create([
            'category_id' => $mountainCategory->category_id,
            'name' => 'Hành trình Tây Bắc',
            'description' => 'Trải nghiệm săn mây tại Tà Xùa',
            'supplier' => 'GoTour',
            'price' => 2500000,
            'duration' => 3,
            'status' => 'active',
        ]);

        $otherTour = Tour::create([
            'name' => 'Khám phá biển đảo',
            'description' => 'Nghỉ dưỡng tại Phú Quốc',
            'supplier' => 'Sea Travel',
            'price' => 3500000,
            'duration' => 4,
            'status' => 'active',
        ]);

        $this->createSchedule($matchingTour, Carbon::today()->addDays(7));
        $this->createSchedule($otherTour, Carbon::today()->addDays(8));

        $response = $this->get(route('tours.index', ['search' => 'Tà Xùa']));

        $response->assertOk();
        $response->assertSee($matchingTour->name);
        $response->assertDontSee($otherTour->name);

        $responseByCategory = $this->get(route('tours.index', ['search' => 'Tour Núi']));

        $responseByCategory->assertOk();
        $responseByCategory->assertSee($matchingTour->name);
        $responseByCategory->assertDontSee($otherTour->name);
    }

    public function test_tours_can_be_filtered_by_start_date_and_sorted_by_price_desc(): void
    {
        $category = Category::create([
            'name' => 'Tour Biển',
            'description' => 'Các tour biển',
            'status' => 'active',
        ]);

        $expensiveTour = Tour::create([
            'category_id' => $category->category_id,
            'name' => 'Resort Nha Trang cao cấp',
            'description' => 'Nghỉ dưỡng 5 sao',
            'supplier' => 'Luxury Travel',
            'price' => 7900000,
            'duration' => 4,
            'status' => 'active',
        ]);

        $budgetTour = Tour::create([
            'category_id' => $category->category_id,
            'name' => 'Du lịch Vũng Tàu tiết kiệm',
            'description' => 'Tour ngắn ngày',
            'supplier' => 'Budget Travel',
            'price' => 1900000,
            'duration' => 2,
            'status' => 'active',
        ]);

        $tooEarlyTour = Tour::create([
            'category_id' => $category->category_id,
            'name' => 'Phú Yên khởi hành sớm',
            'description' => 'Tour sẽ bị loại bởi bộ lọc ngày',
            'supplier' => 'Quick Travel',
            'price' => 4900000,
            'duration' => 3,
            'status' => 'active',
        ]);

        $this->createSchedule($expensiveTour, Carbon::today()->addDays(15));
        $this->createSchedule($budgetTour, Carbon::today()->addDays(18));
        $this->createSchedule($tooEarlyTour, Carbon::today()->addDays(5));

        $response = $this->get(route('tours.index', [
            'start_date' => Carbon::today()->addDays(10)->toDateString(),
            'sort' => 'price_desc',
        ]));

        $response->assertOk();
        $response->assertSee($expensiveTour->name);
        $response->assertSee($budgetTour->name);
        $response->assertDontSee($tooEarlyTour->name);

        $content = $response->getContent();
        $this->assertTrue(
            strpos($content, $expensiveTour->name) < strpos($content, $budgetTour->name),
            'Tour giá cao hơn phải xuất hiện trước khi sắp xếp giảm dần theo giá.'
        );
    }

    private function createSchedule(Tour $tour, Carbon $startDate): void
    {
        DepartureSchedule::create([
            'tour_id' => $tour->tour_id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $startDate->copy()->addDays(max(($tour->duration ?? 1) - 1, 1))->toDateString(),
            'max_people' => 20,
            'available_spots' => 10,
            'meeting_point' => 'Bến xe trung tâm',
            'status' => 'scheduled',
        ]);
    }
}
