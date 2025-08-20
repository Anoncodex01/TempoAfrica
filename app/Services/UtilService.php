<?php

namespace App\Services;

class UtilService
{
    /**
     * Format phone number (reject numbers starting with 0, expect format like 756xxxxxxxx)
     */
    public static function formatPhone($phone)
    {
        // Remove any spaces or special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Check if phone starts with 0 and reject it
        if (preg_match("~^0\d+$~", $phone)) {
            throw new \Exception('Phone number should not start with 0. Please enter number starting with country code (e.g., 756xxxxxxxx)');
        }
        
        // If phone doesn't start with 255, add it (assuming Tanzania)
        if (!preg_match("~^255\d+$~", $phone)) {
            $phone = '255' . $phone;
        }

        return $phone;
    }

    /**
     * Check if a string contains special characters
     */
    public static function hasSpecialChar($string)
    {
        return preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string);
    }

    /**
    * Generate a 6-digit OTP
    */
    public static function getOtp()
    {
    return self::generateRandomCode('1923084765', 6);
    }

    /**
    * Generate an 8-digit reset code
    */
    public static function resetCode()
    {
    return self::generateRandomCode('1923084765', 8);
    }

    /**
    * Generate a random password of 10 characters
    */
    public static function getPassword()
    {
    return self::generateRandomCode('A1B2C3D4E5F6G7H8I9J0KLMNPQRSTUVWXYZ', 10);
    }

    /**
    * Generate a batch number (YYYY-MM-RANDOM)
    */
    public static function getBatch()
    {
    return Carbon::now()->format('Y-m').'-'.self::generateRandomCode('W0AB1CD2EF3GH4IXJ5KLY6MN7PQR8ST9UVZ', 8);
    }

    /**
    * Generate a random string based on a character set and length
    */
    private static function generateRandomCode($characters, $length)
    {
    $code = '';
    for ($i = 0; $i < $length; $i++) { $code .=$characters[rand(0, strlen($characters) - 1)]; } return $code; } }