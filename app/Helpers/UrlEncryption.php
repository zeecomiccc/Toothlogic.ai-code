<?php

namespace App\Helpers;

class UrlEncryption
{
    /**
     * Generate a short hash for customer ID
     */
    public static function generateShortHash($customerId)
    {
        // Use a simple base64 encoding with a salt
        $salt = config('app.key', 'default-salt');
        $data = $customerId . '|' . $salt;
        
        // Create a hash and take first 12 characters
        $hash = hash('sha256', $data);
        return substr($hash, 0, 12);
    }

    /**
     * Decode customer ID from short hash
     */
    public static function decodeShortHash($hash)
    {
        // Check cache first (optional optimization)
        $cacheKey = "feedback_hash_{$hash}";
        $cachedCustomerId = cache()->get($cacheKey);
        
        if ($cachedCustomerId) {
            return $cachedCustomerId;
        }
        
        // Get all customer IDs and check which one matches
        $customers = \App\Models\User::where('user_type', 'user')
            ->where('status', 1)
            ->pluck('id');
            
        foreach ($customers as $customerId) {
            $testHash = self::generateShortHash($customerId);
            if ($testHash === $hash) {
                // Cache the result for 1 hour
                cache()->put($cacheKey, $customerId, 3600);
                return $customerId;
            }
        }
        
        return null;
    }

    /**
     * Generate clean feedback URL with short hash
     */
    public static function generateFeedbackUrl($customerId)
    {
        $shortHash = self::generateShortHash($customerId);
        return route('patient.feedback.show', ['id' => $shortHash]);
    }
}
