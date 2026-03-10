<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Plan;

class PlanService
{
    public function getPlan(Business $business): ?Plan
    {
        try {
            $sub = $business->activeSubscription;
            return $sub?->plan;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function canAddUser(Business $business): bool
    {
        $plan = $this->getPlan($business);
        if (!$plan) return false;
        if ($plan->hasUnlimitedUsers()) return true;

        $currentUsers = $business->users()->count();
        return $currentUsers < $plan->user_limit;
    }

    public function canAddProduct(Business $business): bool
    {
        $plan = $this->getPlan($business);
        if (!$plan) return false;
        if ($plan->hasUnlimitedProducts()) return true;

        $currentProducts = $business->products()->count();
        return $currentProducts < $plan->product_limit;
    }

    public function hasFeature(Business $business, string $feature): bool
    {
        $plan = $this->getPlan($business);
        if (!$plan) return false;
        return $plan->hasFeature($feature);
    }

    public function getLimitsInfo(Business $business): array
    {
        $plan = $this->getPlan($business);
        if (!$plan) {
            return [
                'plan_name' => 'Sin plan',
                'users' => ['current' => 0, 'limit' => 0, 'unlimited' => false],
                'products' => ['current' => 0, 'limit' => 0, 'unlimited' => false],
                'features' => [],
            ];
        }

        return [
            'plan_name' => $plan->name,
            'users' => [
                'current' => $business->users()->count(),
                'limit' => $plan->user_limit,
                'unlimited' => $plan->hasUnlimitedUsers(),
            ],
            'products' => [
                'current' => $business->products()->count(),
                'limit' => $plan->product_limit,
                'unlimited' => $plan->hasUnlimitedProducts(),
            ],
            'features' => $plan->features ?? [],
        ];
    }

    public function calculatePoints(float $saleTotal): int
    {
        // $1 = 1 point
        return (int) floor($saleTotal);
    }
}
