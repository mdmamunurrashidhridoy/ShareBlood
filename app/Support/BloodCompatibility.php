<?php

namespace App\Support;

class BloodCompatibility
{
    /**
     * Recipient blood group => compatible donor blood groups
     */
    public const RECIPIENT_TO_DONORS = [
        'A+' => ['A+', 'A-', 'O+', 'O-'],
        'A-' => ['A-', 'O-'],
        'B+' => ['B+', 'B-', 'O+', 'O-'],
        'B-' => ['B-', 'O-'],
        'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        'AB-' => ['A-', 'B-', 'AB-', 'O-'],
        'O+' => ['O+', 'O-'],
        'O-' => ['O-'],
    ];

    /**
     * Donor blood group => recipient blood groups
     */
    public const DONOR_TO_RECIPIENTS = [
        'A+' => ['A+', 'AB+'],
        'A-' => ['A+', 'A-', 'AB+', 'AB-'],
        'B+' => ['B+', 'AB+'],
        'B-' => ['B+', 'B-', 'AB+', 'AB-'],
        'AB+' => ['AB+'],
        'AB-' => ['AB+', 'AB-'],
        'O+' => ['O+', 'A+', 'B+', 'AB+'],
        'O-' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
    ];

    public static function compatibleDonorsFor(?string $recipientBloodGroup): array
    {
        if (!$recipientBloodGroup) {
            return [];
        }

        return self::RECIPIENT_TO_DONORS[$recipientBloodGroup] ?? [];
    }

    public static function compatibleRecipientsFor(?string $donorBloodGroup): array
    {
        if (!$donorBloodGroup) {
            return [];
        }

        return self::DONOR_TO_RECIPIENTS[$donorBloodGroup] ?? [];
    }

    public static function canDonateTo(?string $donorBloodGroup, ?string $recipientBloodGroup): bool
    {
        if (!$donorBloodGroup || !$recipientBloodGroup) {
            return false;
        }

        return in_array(
            $donorBloodGroup,
            self::compatibleDonorsFor($recipientBloodGroup),
            true
        );
    }

    public static function isExactMatch(?string $donorBloodGroup, ?string $recipientBloodGroup): bool
    {
        return $donorBloodGroup && $recipientBloodGroup && $donorBloodGroup === $recipientBloodGroup;
    }
}
