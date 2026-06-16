<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'country' => $this->country,
            'residential_address' => $this->residential_address,
            'employee_id' => $this->employee_id,
            'campus_id' => $this->campus_id,
            'department_id' => $this->department_id,
            'employee_tier' => $this->employee_tier,
            'reporting_manager_id' => $this->reporting_manager_id,
            'joining_date' => $this->joining_date,
            'status' => $this->status,
            'two_factor_enabled' => $this->two_factor_enabled,
            'last_login_at' => $this->last_login_at,
            'total_logins' => $this->total_logins,
            'preferred_language' => $this->preferred_language,
            'dark_mode' => $this->dark_mode,
            'email_alerts' => $this->email_alerts,
            'sms_notifications' => $this->sms_notifications,
            'system_alerts' => $this->system_alerts,
            'oauth_provider' => $this->oauth_provider,
            'oauth_id' => $this->oauth_id,
            'email_verified_at' => $this->email_verified_at,
            'campus' => $this->whenLoaded('campus'),
            'department' => $this->whenLoaded('department'),
            'reportingManager' => $this->whenLoaded('reportingManager'),
            'roles' => $this->whenLoaded('roles', fn () => $this->roles->pluck('name')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
