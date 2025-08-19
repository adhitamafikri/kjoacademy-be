<?php

namespace App\Constants;

/**
 * List of OTP Token Purposes
 * Right now, it is only for login
 */
enum TokenPurpose: string
{
    case STUDENT_LOGIN = 'student-login';
    case ADMIN_LOGIN = 'admin-login';
}
