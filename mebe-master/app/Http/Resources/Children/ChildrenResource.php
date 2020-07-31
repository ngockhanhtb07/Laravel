<?php

namespace App\Http\Resources\Children;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ChildrenResource extends JsonResource
{

    use FormatResponse;

    public function toArray($request)
    {
        $dateOfBirth = new \DateTime(date('Y-m-d', strtotime($this->date_of_birth)));
        $now = new \DateTime(now());
        $age = $now->diff($dateOfBirth);
        if ($age->invert == 0) {
            $year = $month = $ageRange = 0;
        } else {
            $year = $age->y;
            $month = $age->m;
            if ($year == 0 && $month == 0 && $age->days > 0) {
                $ageRange = 1;
            } else {
                $ageRange = $this->ageRange($year, $month);
            }
        }
        return [
            'id' => $this->format($this->children_id, 'integer'),
            'birthday' => $this->format($dateOfBirth->getTimestamp(), 'integer'),
            'nickname' => $this->format($this->nickname),
            'gender' => $this->format($this->gender, 'integer'),
            'year' => $year,
            'month' => $month,
            'age_range' => $ageRange
        ];
    }

    private function ageRange($year, $month)
    {
        if ($year == 0) {
            if ($month <= 0) {
                return 0;
            } else {
                return 1;
            }
        } else {
            if ($year < 2) {
                return 2;
            }
            if ($year < 3) {
                return 3;
            }
            if ($year < 5) {
                return 4;
            } else {
                return 5;
            }
        }

    }
}
